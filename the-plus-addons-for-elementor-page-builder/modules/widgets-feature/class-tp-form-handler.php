<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://posimyth.com/
 * @since      5.4.2
 *
 * @package    ThePlus
 */

if ( ! class_exists( 'Tp_Form_Handler' ) ) {

    /**
	 * Handles form submission and email sending functionality.
	 */
	class Tp_Form_Handler {

        /**
		 * Instance of the class.
		 *
		 * @var Tp_Form_Handler
		 */
		private static $instance;

        /**
		 * Get the singleton instance of the class.
		 *
		 * @return Tp_Form_Handler
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

        /**
		 * Constructor to add necessary actions.
		 */
		public function __construct() {
			add_action( 'wp_ajax_tpae_form_submission', array( $this, 'tpae_form_submission' ) );
			add_action( 'wp_ajax_nopriv_tpae_form_submission', array( $this, 'tpae_form_submission' ) );
		}

        /**
		 * Handle form submission and process email sending.
		 */
		public function tpae_form_submission() {
			$result['success'] = 0;

			$nonce = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'tp-form-nonce' ) ) {
				$result['message'] = 'Nonce verification failed.';
				wp_send_json( $result );
			}

			$form_data_json = isset( $_POST['form_data'] ) ? sanitize_text_field( wp_unslash( $_POST['form_data'] ) ) : '';
			$form_data      = json_decode( $form_data_json, true );

			$form_fields_json = isset( $_POST['form_fields'] ) ? sanitize_text_field( wp_unslash( $_POST['form_fields'] ) ) : '';
			$form_fields      = json_decode( $form_fields_json, true );

			if ( ! isset( $form_fields ) || ! is_array( $form_fields ) ) {
				$result['message'] = 'form_fields data is missing or not an array.';
				wp_send_json( $result );
			}

			foreach ( $form_data as $key => $value ) {
				$form_fields[ $key ] = is_array( $value ) ? implode( ', ', $value ) : sanitize_text_field( $value );
			}

			foreach ( $form_data as $key => $value ) {
				if ( strpos( $key, 'required' ) !== false && 'yes' === $value && empty( $form_data[ str_replace( '_required', '', $key ) ] ) ) {
					$result['message'] = ucfirst( str_replace( '_required', '', $key ) ) . ' is required.';
					wp_send_json( $result );
				}
			}

			$recaptcha_response = isset( $form_data['recaptcha_response'] ) ? sanitize_text_field( $form_data['recaptcha_response'] ) : '';
			if ( ! empty( $recaptcha_response ) ) {
				$recaptcha_secret_key = isset( $_POST['secretKey'] ) ? sanitize_text_field( wp_unslash( $_POST['secretKey'] ) ) : '';
				$response             = wp_remote_post(
					'https://www.google.com/recaptcha/api/siteverify',
					array(
						'body' => array(
							'secret'   => $recaptcha_secret_key,
							'response' => $recaptcha_response,
						),
					)
				);

				if ( is_wp_error( $response ) || ! json_decode( wp_remote_retrieve_body( $response ), true )['success'] ) {
					$result['message'] = 'reCAPTCHA verification failed.';
					wp_send_json( $result );
				}
			}

			$email_data_json = isset( $_POST['email_data'] ) ? wp_unslash( $_POST['email_data'] ) : '';
			$email_data      = json_decode( $email_data_json, true );

			$email_sent = false;

			$email_subject = ! empty( $email_data['email_subject'] ) ? sanitize_text_field( $email_data['email_subject'] ) : '';

			if ( ! empty( $email_data ) && ! empty( $email_subject ) ) {
				$email_settings = $this->prepare_email_settings( $email_data, $form_data, $form_fields );
				$email_sent     = $this->send_email( $email_settings );
			}

			$result = array(
				'success' => 1,
				'data'    => array(
					'email_sent' => $email_sent,
				),
				'message' => 'Email sent successfully.',
			);
			wp_send_json( $result );
		}

        /**
		 * Send email using wp_mail.
		 *
		 * @param array $email_data Array of email data.
		 * @param array $form_data Array of form data.
		 * @param array $form_fields Array of form fields.
		 * @return bool Whether the email was sent successfully.
		 */
		private function prepare_email_settings( $email_data, $form_data, $form_fields ) {
			$email_to        = ! empty( $email_data['email_to'] ) ? sanitize_email( $email_data['email_to'] ) : get_option( 'admin_email' );
			$email_subject   = ! empty( $email_data['email_subject'] ) ? sanitize_text_field( $email_data['email_subject'] ) : 'New Form Submission';
			$email_from      = ! empty( $email_data['email_from'] ) ? sanitize_email( $email_data['email_from'] ) : 'no-reply@example.com';
			$email_from_name = ! empty( $email_data['email_from_name'] ) ? sanitize_text_field( $email_data['email_from_name'] ) : '';
			$email_reply_to  = ! empty( $email_data['email_reply_to'] ) ? sanitize_email( $email_data['email_reply_to'] ) : '';
			$email_cc        = ! empty( $email_data['email_cc'] ) ? sanitize_email( $email_data['email_cc'] ) : '';
			$email_bcc       = ! empty( $email_data['email_bcc'] ) ? sanitize_email( $email_data['email_bcc'] ) : '';

			$email_message = isset( $email_data['email_message'] ) && ! empty( $email_data['email_message'] )
				? sanitize_text_field( $email_data['email_message'] )
				: 'all-fields';

			$email_message_content = $this->build_email_message( $form_data, $form_fields, $email_message );

			return array(
				'email_to'        => $email_to,
				'email_subject'   => $email_subject,
				'email_message'   => $email_message_content,
				'email_from'      => $email_from,
				'email_from_name' => $email_from_name,
				'email_reply_to'  => $email_reply_to,
				'email_cc'        => $email_cc,
				'email_bcc'       => $email_bcc,
			);
		}

		private function build_email_message( $form_data, $form_fields, $shortcode = 'all-fields' ) {
			if ( empty( $shortcode ) ) {
				$shortcode = 'all-fields';
			}

			$message = '<h2>New Form Submission</h2>';

			if ( strtolower( $shortcode ) === 'all-fields' ) {
				foreach ( $form_fields as $field ) {
					if ( isset( $field['field_value'] ) && ! empty( $field['field_value'] ) ) {
						$message .= '<p>' . wp_kses_post( $field['field_value'] ) . '</p>';
					}
				}
			} else {
				preg_match( '/field_id="([^"]+)"/', $shortcode, $matches );
				$field_id_from_shortcode = isset( $matches[1] ) ? $matches[1] : '';

				if ( empty( $field_id_from_shortcode ) ) {
					$message .= '<p><strong>Error:</strong> Invalid shortcode format. No field ID found.</p>';
				} else {
					$matched_field_value = '';
					foreach ( $form_fields as $field ) {

						if ( isset( $field['field_id'] ) && strpos( $field['field_id'], $field_id_from_shortcode ) !== false ) {
							$matched_field_value = wp_kses_post( $field['field_value'] );
							$message            .= "<p><strong>{$field['field_id']}:</strong> $matched_field_value</p>";
							break;
						} elseif ( isset( $field['field_name'] ) && strpos( $field['field_name'], $field_id_from_shortcode ) !== false ) {
							$matched_field_value = wp_kses_post( $field['field_value'] );
							$message            .= "<p><strong>{$field['field_name']}:</strong> $matched_field_value</p>";
							break;
						}
					}

					if ( empty( $matched_field_value ) ) {
						$message .= "<p><strong>Error:</strong> No valid data found for field ID '$field_id_from_shortcode'.</p>";
					}
				}
			}

			if ( empty( $message ) ) {
				$message = '<p><strong>Error:</strong> No valid form data available.</p>';
			}

			return $message;
		}

		private function send_email( $mail_conf ) {
			$to      = $mail_conf['email_to'];
			$subject = $mail_conf['email_subject'];
			$message = $mail_conf['email_message'];
			$headers = array(
				'From: ' . $mail_conf['email_from_name'] . ' <' . $mail_conf['email_from'] . '>',
				'Reply-To: ' . $mail_conf['email_reply_to'],
				'Content-Type: text/html; charset=UTF-8',
			);

			if ( ! empty( $mail_conf['email_cc'] ) ) {
				$headers[] = 'Cc: ' . $mail_conf['email_cc'];
			}
			if ( ! empty( $mail_conf['email_bcc'] ) ) {
				$headers[] = 'Bcc: ' . $mail_conf['email_bcc'];
			}

			return wp_mail( $to, $subject, $message, $headers );
		}
	}

	Tp_Form_Handler::get_instance();
}