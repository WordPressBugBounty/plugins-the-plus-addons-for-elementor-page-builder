<?php
/**
 * Exit if accessed directly.
 *
 * @link       https://posimyth.com/
 * @since      6.0.0
 *
 * @package    Theplus
 * @subpackage ThePlus/Notices
 * */

namespace Tp\Notices\TPAEHaalloweenNotice;

/**
 * Exit if accessed directly.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TP_Halloween_Notice' ) ) {

	/**
	 * This class used for only elementor widget load
	 *
	 * @since 6.0.0
	 */
	class TP_Halloween_Notice {

		/**
		 * Instance
		 *
		 * @since 6.0.0
		 * @var instance of the class.
		 */
		private static $instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 6.0.0
		 * @return instance of the class.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Perform some compatibility checks to make sure basic requirements are meet.
		 *
		 * @since 6.0.0
		 * @access public
		 */
		public function __construct() {

			/** TPAE Halloween Notice*/
			add_action( 'admin_notices', array( $this, 'tpae_halloween_offer_notice' ) );

			/** TPAE Halloween Close Notice*/
			add_action( 'wp_ajax_tpae_halloween_dismiss_notice', array( $this, 'tpae_halloween_dismiss_notice' ) );
		}

		/**
		 * Halloween Offer notice
		 *
		 * @since 6.0.0
		 */
		public function tpae_halloween_offer_notice() {

			$nonce  = wp_create_nonce( 'tpae-halloween-notice' );
			$screen = get_current_screen();

			$parent_base = ! empty( $screen->parent_base ) && in_array( $screen->parent_base, array( 'index', 'elementor', 'themes', 'edit', 'plugins' ), true );

			if ( ! $parent_base ) {
				return;
			}

			if ( defined( 'L_THEPLUS_VERSION' ) && ! defined( 'THEPLUS_VERSION' ) ) {
				if ( ! get_option( 'tpae_halloween_notice_dismissed' ) ) {
					echo '<div class="notice tpae-plugin-halloween is-dismissible">
						<div class="inline tpae-plugin-halloween-halloween" style="display: flex;column-gap: 12px;align-items: center;padding: 10px;position: relative;    margin-left: 0px;">
							<img style="max-width: 110px;max-height: 110px;" src="' . esc_url( L_THEPLUS_URL . '/assets/images/halloween.png' ) . '" />
							<div style="margin: 0 10px">  
								<h3 style="margin-top:10px;margin-bottom:7px;">' . esc_html__( 'Best Time to Upgrade to The Plus Addons for Elementor PRO – Up to 30% OFF!', 'tpebl' ) . '</h3>
								<p> ' . esc_html__( 'Our Halloween Sale is live! Upgrade this season and get up to 30% OFF on the pro version.', 'tpebl' ) . ' </p>
								<p style="display: flex;column-gap: 12px;">  <span> • ' . esc_html__( '1,000+ Elementor Templates', 'tpebl' ) . '</span>  <span> • ' . esc_html__( '120+ Elementor Widgets', 'tpebl' ) . '</span>  <span> • ' . esc_html__( 'Trusted by 100K+ Users', 'tpebl' ) . '</span> </p>
								<a href="' . esc_url( 'https://theplusaddons.com/pricing?utm_source=wpbackend&utm_medium=dashboard&utm_campaign=plussettings' ) . '" class="button" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Claim Your Offer', 'tpebl' ) . '</a>
							</div>
						</div>
					</div>';
				}
			}
			?>
			<script>
                jQuery(document).ready(function($) {
                    jQuery('.tpae-plugin-halloween .notice-dismiss').on('click', function(e) {
                        jQuery.ajax({
                        	url: ajaxurl,
                        	type: 'POST',
                        	data: {
                        		action: 'tpae_halloween_dismiss_notice',
                        		security: "<?php echo esc_html( $nonce ); ?>",
                        		type: 'tpae_halloween_notice',
                        	},
                        	success: function(response) {
                        		jQuery('.tpae-plugin-halloween').hide();
                        	}
                        });
                    });
				});
			</script>
			<?php
		}

		/**
		 * It's is use for Save key in database for the TPAE halloween offer notice
		 *
		 * @since 6.0.0
		 */
		public function tpae_halloween_dismiss_notice() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! isset( $get_security ) || empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-halloween-notice' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			if ( 'tpae_halloween_notice' === $get_type ) {
				update_user_meta( '1', 'tpae_halloween_notice_dismissed', true );
			}

			wp_send_json_success();
		}
	}

	TP_Halloween_Notice::instance();
}
