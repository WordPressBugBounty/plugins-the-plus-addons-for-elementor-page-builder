<?php
/**
 * Widget Name: Form
 * Description: Third party plugin Plus Form  style.
 * Author: Theplus
 * Author URI: https://posimyth.com
 *
 * @package ThePlus
 */

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\controls\change;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ThePlus_Form_widget.
 */
class L_ThePlus_Form_Widget extends Widget_Base {

	/**
	 * Document Link For Need help.
	 *
	 * @var tp_doc of the class.
	 */
	public $tp_doc = L_THEPLUS_TPDOC;

	/**
	 * Helpdesk Link For Need help.
	 *
	 * @var tp_help of the class.
	 */
	public $tp_help = L_THEPLUS_HELP;

	/**
	 * Get Widget Name.
	 *
	 * @since   1.0.0
	 * @version 5.4.2
	 */
	public function get_name() {
		return 'tp-plus-form';
	}

	/**
	 * Get Widget Title.
	 *
	 * @since   1.0.0
	 * @version 5.4.2
	 */
	public function get_title() {
		return esc_html__( 'Form', 'tpebl' );
	}

	/**
	 * Get Widget Icon.
	 *
	 * @since   1.0.0
	 * @version 5.4.2
	 */
	public function get_icon() {
		return 'fa fa-envelope-o theplus_backend_icon';
	}

	/**
	 * Get Widget categories.
	 *
	 * @since   1.0.0
	 * @version 5.4.2
	 */
	public function get_categories() {
		return array( 'plus-listing' );
	}

	/**
	 * Get Widget keywords.
	 *
	 * @since   1.0.0
	 * @version 5.4.2
	 */
	public function get_keywords() {
		return array( 'Forms' );
	}

	/**
	 * Get Widget categories.
	 *
	 * @since   1.0.0
	 * @version 5.6.5
	 */
	public function get_custom_help_url() {
		$help_url = $this->tp_help;
	}

	/**
	 * Register controls.
	 *
	 * @since   1.0.0
	 * @version 5.4.2
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'General', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->start_controls_tabs( 'tabs_form_button_style' );

		$repeater->start_controls_tab(
			'field_content',
			array(
				'label' => esc_html__( 'Content', 'tpebl' ),
			)
		);

		$repeater->add_control(
			'form_fields',
			array(
				'label'       => esc_html__( 'Type', 'tpebl' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => esc_html__( 'text', 'tpebl' ),
				'options'     => array(
					'text'      => esc_html__( 'Text', 'tpebl' ),
					'textarea'  => esc_html__( 'Textarea', 'tpebl' ),
					'email'     => esc_html__( 'Email', 'tpebl' ),
					'number'    => esc_html__( 'Number', 'tpebl' ),
					'hidden'    => esc_html__( 'Hidden', 'tpebl' ),
					'recaptcha' => esc_html__( 'reCAPTCHA v3', 'tpebl' ),
					'honeypot'  => esc_html__( 'HoneyPot', 'tpebl' ),
				),
				'label_block' => false,
			)
		);
		$repeater->add_control(
			'field_label',
			array(
				'label'       => esc_html__( 'Label', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Field Label', 'tpebl' ),
				'ai'          => array(
					'active' => false,
				),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number' ),
				),
			)
		);
		$repeater->add_control(
			'place_holder',
			array(
				'label'       => esc_html__( 'Placeholder', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Placeholder Text', 'tpebl' ),
				'ai'          => array(
					'active' => false,
				),
				'condition'   => array(
					'form_fields' => array( 'text', 'textarea', 'email', 'number' ),
				),
			)
		);

		$repeater->add_control(
			'required',
			array(
				'label'     => esc_html__( 'Required', 'tpebl' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Yes', 'tpebl' ),
				'label_off' => esc_html__( 'No', 'tpebl' ),
				'separator' => 'after',
			)
		);

		$repeater->add_responsive_control(
			'column_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Column Width', 'tpebl' ),
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
			)
		);
		$repeater->add_control(
			'textarea_rows',
			array(
				'label'     => esc_html__( 'Rows', 'tpebl' ),
				'type'      => Controls_Manager::NUMBER,
				'dynamic'   => array(
					'active' => false,
				),
				'ai'        => array(
					'active' => false,
				),
				'condition' => array(
					'form_fields' => array( 'textarea' ),
				),
				'default'   => '4',
			)
		);
		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'field_advance',
			array(
				'label' => esc_html__( 'Advance', 'tpebl' ),
			)
		);

		$repeater->add_control(
			'field_default_value',
			array(
				'label'       => esc_html__( 'Default Value', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'Default Value', 'tpebl' ),
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$repeater->add_control(
			'field_id',
			array(
				'label'       => esc_html__( 'ID', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'ID', 'tpebl' ),
				'classes'     => 'tp-form-field-id',
				'ai'          => array(
					'active' => false,
				),
			)
		);
		$repeater->add_control(
			'field_id_notice',
			array(
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<p class="tp-controller-notice"><i>Note : Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows `A-z 0-9` & underscore chars without spaces.</i></p>',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'field_shortcode',
			array(
				'label'       => esc_html__( 'Shortcode', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'classes'     => 'tp-form-field-shortcode',
				'placeholder' => esc_html__( 'Shortcode', 'tpebl' ),
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'tabs',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'field_label'         => esc_html__( 'Name', 'tpebl' ),
						'place_holder'        => esc_html__( 'Name', 'tpebl' ),
						'form_fields'         => 'text',
						'column_width'        => '45',
						'required'            => 'no',
						'field_default_value' => '',
						'field_id'            => 'name',
						'field_shortcode'     => '[field_id="name"]',
					),
					array(
						'field_label'         => esc_html__( 'Email', 'tpebl' ),
						'place_holder'        => esc_html__( 'Email', 'tpebl' ),
						'form_fields'         => 'email',
						'column_width'        => '45',
						'required'            => 'yes',
						'field_default_value' => '',
						'field_id'            => 'email',
						'field_shortcode'     => '[field_id="email"]',
					),
					array(
						'field_label'         => esc_html__( 'Message', 'tpebl' ),
						'place_holder'        => esc_html__( 'Message', 'tpebl' ),
						'form_fields'         => 'textarea',
						'column_width'        => '100',
						'required'            => 'no',
						'field_default_value' => '',
						'field_id'            => 'name',
						'field_shortcode'     => '[field_id="email"]',
					),
				),
				'title_field' => '{{{ field_label }}}',
			)
		);

		$this->add_control(
			'label_display',
			array(
				'label'     => esc_html__( 'Show Label', 'tpebl' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Yes', 'tpebl' ),
				'label_off' => esc_html__( 'No', 'tpebl' ),

			)
		);
		$this->add_control(
			'required_mask',
			array(
				'label'     => esc_html__( 'Required Mark', 'tpebl' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Yes', 'tpebl' ),
				'label_off' => esc_html__( 'No', 'tpebl' ),
				'condition' => array(
					'label_display' => 'yes',
				),

			)
		);

		$this->add_control(
			'form_title',
			array(
				'label'       => wp_kses_post( 'Unique Field Label', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'New Plus Form', 'tpebl' ),
				'placeholder' => esc_html__( 'Enter Form Name', 'tpebl' ),
				'dynamic'     => array(
					'active' => false,
				),
				'ai'          => array(
					'active' => false,
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'button_section',
			array(
				'label' => esc_html__( 'Button', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_column_width',
			array(
				'label'       => esc_html__( 'Column Width', 'tpebl' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array(
					'%' => array(
						'min'  => 10,
						'max'  => 100,
						'step' => 2,
					),
				),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'button_submit',
			array(
				'label'   => esc_html__( 'Submit', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'default' => esc_html__( 'Send', 'tpebl' ),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'button_id',
			array(
				'label'       => esc_html__( 'Button ID', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'ai'          => array(
					'active' => false,
				),
				'placeholder' => esc_html__( 'button-id', 'tpebl' ),
			)
		);
		$this->add_control(
			'display_icon',
			array(
				'label'     => esc_html__( 'Show Icon', 'tpebl' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
			)
		);

		$this->add_control(
			'button_icon_style',
			array(
				'label'     => esc_html__( 'Icon Font', 'tpebl' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'font_awesome_5' => esc_html__( 'Font Awesome 5', 'tpebl' ),
					'none'           => esc_html__( 'None', 'tpebl' ),
				),
				'condition' => array(
					'display_icon' => 'yes',
				),
			)
		);
		$this->add_control(
			'icon_fontawesome_5',
			array(
				'label'       => esc_html__( 'Icon Library', 'tpebl' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-plus',
					'library' => 'solid',
				),
				'condition'   => array(
					'display_icon'      => 'yes',
					'button_icon_style' => 'font_awesome_5',
				),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'submit_actions',
			array(
				'label' => esc_html__( 'Actions After Submit', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'add_action',
			array(
				'label'       => esc_html__( 'Add Action', 'tpebl' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => esc_html__( 'email', 'tpebl' ),
				'multiple'    => true,
				'options'     => array(
					'email'    => esc_html__( 'Email', 'tpebl' ),
					'Redirect' => esc_html__( 'Redirect', 'tpebl' ),
				),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'email_settings',
			array(
				'label'     => esc_html__( 'Email Settings', 'tpebl' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'add_action' => 'email',
				),
				'dynamic'   => array(
					'active' => false,
				),
				'ai'        => array(
					'active' => false,
				),
			)
		);

		$this->start_controls_tabs( 'tabs_email' );

		$this->start_controls_tab(
			'email_to_tab',
			array(
				'label' => esc_html__( 'To', 'tpebl' ),
			)
		);

		$this->add_control(
			'email_to',
			array(
				'label'   => esc_html__( 'Email Address', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'email_cc_tab',
			array(
				'label' => esc_html__( 'CC', 'tpebl' ),
			)
		);

		$this->add_control(
			'email_cc',
			array(
				'label'   => esc_html__( 'Email Address', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'email_bcc_tab',
			array(
				'label' => esc_html__( 'BCC', 'tpebl' ),
			)
		);

		$this->add_control(
			'email_bcc',
			array(
				'label'   => esc_html__( 'Email Address', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'email_subject',
			array(
				'label'     => esc_html__( 'Subject', 'tpebl' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => false,
				),
				'ai'        => array(
					'active' => false,
				),
				'separator' => 'before',
				'default'   => 'New Form Submission',
			)
		);

		$this->add_control(
			'email_message',
			array(
				'label'   => esc_html__( 'Message', 'tpebl' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array(
					'active' => false,
				),
				'default' => '[all-fields]',
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'field_message_notice',
			array(
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<p class="tp-controller-notice"><i>Note : By default all forms are sent via [all-fields] shortcode to customize sent fields, copy the shortcode that appears inside each field and paste it above.</i></p>',
				'label_block' => true,
			)
		);

		$this->add_control(
			'email_from',
			array(
				'label'   => esc_html__( 'From Email', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'email_from_name',
			array(
				'label'   => esc_html__( 'From Name', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'email_reply_to',
			array(
				'label'   => esc_html__( 'Reply-To', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'redirect_settings',
			array(
				'label'     => esc_html__( 'Redirect Settings', 'tpebl' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'add_action' => 'Redirect',
				),
			)
		);

		$this->add_control(
			'redirect_to',
			array(
				'label'       => esc_html__( 'Redirect To', 'tpebl' ),
				'type'        => Controls_Manager::URL,
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'default'     => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => true,
				),
				'label_block' => true,
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'message_content',
			array(
				'label' => esc_html__( 'Message Content', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'custom_message',
			array(
				'label'     => esc_html__( 'Custom Message', 'tpebl' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Yes', 'tpebl' ),
				'label_off' => esc_html__( 'No', 'tpebl' ),
			)
		);

		$this->add_control(
			'success_message',
			array(
				'label'       => esc_html__( 'Success Message', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'condition'   => array(
					'custom_message' => 'yes',
				),
				'label_block' => true,
				'default'     => 'Form Submitted Successfully',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'form_error',
			array(
				'label'       => esc_html__( 'Form Error', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'condition'   => array(
					'custom_message' => 'yes',
				),
				'label_block' => true,
				'default'     => 'There is an Error in Form Submission',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'server_error',
			array(
				'label'       => esc_html__( 'Server Error', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'condition'   => array(
					'custom_message' => 'yes',
				),
				'label_block' => true,
				'default'     => 'There is a Server Error',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'invalid_form',
			array(
				'label'       => esc_html__( 'Invalid Form', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'condition'   => array(
					'custom_message' => 'yes',
				),
				'label_block' => true,
				'default'     => 'Invalid Form ! Please Check form Again',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'required_fields',
			array(
				'label'       => esc_html__( 'Required Fields', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => false,
				),
				'condition'   => array(
					'custom_message' => 'yes',
				),
				'label_block' => true,
				'default'     => 'Field Required',
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'Additiona_Options',
			array(
				'label' => esc_html__( 'Extra Options', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'reCaptchaV3Keys',
			array(
				'label'        => __( 'ReCaptcha v3', 'tpebl' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'tpebl' ),
				'label_on'     => __( 'Custom', 'tpebl' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->start_popover();
		$this->add_control(
			'reCaptchaSiteKey',
			array(
				'label'       => __( 'Site Key', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter ReCaptcha v3 Site Key', 'tpebl' ),
				'condition'   => array(
					'reCaptchaV3Keys' => 'yes',
				),
				'label_block' => true,
				'ai'          => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'reCaptchaSecretKey',
			array(
				'label'       => __( 'Secret Key', 'tpebl' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter ReCaptcha v3 Secret Key', 'tpebl' ),
				'condition'   => array(
					'reCaptchaV3Keys' => 'yes',
				),
				'label_block' => true,
				'ai'          => array(
					'active' => false,
				),
			)
		);
		$this->end_popover();

		$this->add_control(
			'form_id',
			array(
				'label'   => esc_html__( 'Form ID', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
				'ai'      => array(
					'active' => false,
				),
			)
		);

		$this->add_control(
			'field_id_notice',
			array(
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<p class="tp-controller-notice"><i>Note : Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows `A-z 0-9` & underscore chars without spaces.</i></p>',
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_style',
			array(
				'label' => esc_html__( 'Form', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_label_heading',
			array(
				'label'     => esc_html__( 'Label', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_label_typography',
				'label'    => esc_html__( 'Typography', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-form-label',
			)
		);

		$this->add_responsive_control(
			'form_label_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Spacing', 'tpebl' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 10,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tp-form-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_label_position',
			array(
				'label'       => esc_html__( 'Label Text Align', 'tpebl' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tp-form-label' => 'text-align: {{VALUE}};',
				),
				'default'     => 'left',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_form_label_colors' );

		$this->start_controls_tab(
			'form_label_normal',
			array(
				'label' => esc_html__( 'Normal', 'tpebl' ),
			)
		);

		$this->add_control(
			'form_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tp-form-label, {{WRAPPER}} .tp-form input, {{WRAPPER}} .tp-form textarea' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'form_label_hover',
			array(
				'label' => esc_html__( 'Hover', 'tpebl' ),
			)
		);

		$this->add_control(
			'form_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tp-form-label:hover, {{WRAPPER}} .tp-form input, {{WRAPPER}} .tp-form textarea' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'form_html_heading',
			array(
				'label'     => esc_html__( 'HTML Field', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'form_html_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Spacing', 'tpebl' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 10,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tp-form' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_html_color',
			array(
				'label'     => esc_html__( 'HTML Text Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tp-form' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_html_typography',
				'label'    => esc_html__( 'Typography', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-form input, {{WRAPPER}} .tp-form textarea',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_field_style',
			array(
				'label' => esc_html__( 'Field', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_text_field_typography',
				'label'    => esc_html__( 'Typography', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-form input::placeholder, {{WRAPPER}} .tp-form textarea::placeholder',
			)
		);

		$this->add_responsive_control(
			'form_placeholder_position',
			array(
				'label'       => esc_html__( 'Input Text Align', 'tpebl' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tp-form-field input::placeholder' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tp-form-field textarea::placeholder' => 'text-align: {{VALUE}};',
				),
				'default'     => 'left',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_form_placeholder_colors' );

		$this->start_controls_tab(
			'form_placeholder_normal',
			array(
				'label' => esc_html__( 'Normal', 'tpebl' ),
			)
		);

		$this->add_control(
			'form_placeholder_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#888888',
				'selectors' => array(
					'{{WRAPPER}} .tp-form-field input::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tp-form-field textarea::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'form_placeholder_hover',
			array(
				'label' => esc_html__( 'Hover', 'tpebl' ),
			)
		);

		$this->add_control(
			'form_placeholder_text_color_hover',
			array(
				'label'     => esc_html__( 'Hover Text Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .tp-form input:hover::placeholder, {{WRAPPER}} .tp-form textarea:hover::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'form_field_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .tp-form input, {{WRAPPER}} .tp-form textarea' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_field_border',
				'label'    => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-form input, {{WRAPPER}} .tp-form textarea',
			)
		);

		$this->add_responsive_control(
			'form_field_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-form input, {{WRAPPER}} .tp-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_button_style',
			array(
				'label' => esc_html__( 'Button', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_button_typography',
				'label'    => esc_html__( 'Typography', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-form .tp-form-button',
			)
		);

		$this->add_responsive_control(
			'form_button_position',
			array(
				'label'       => esc_html__( 'Position', 'tpebl' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tp-form-submit-container' => 'display: flex; justify-content: {{VALUE}};',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->add_responsive_control(
			'form_button_alignment',
			array(
				'label'       => esc_html__( 'Alignment', 'tpebl' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon'  => 'eicon-text-align-right',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'tpebl' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tp-form .tp-form-button' => 'text-align: {{VALUE}};',
				),
				'default'     => 'center',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->add_responsive_control(
			'form_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tp-form .tp-form-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_button_border',
				'label'    => esc_html__( 'Button Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-form .tp-form-button',
			)
		);

		$this->add_responsive_control(
			'form_button_icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'tpebl' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tp-form .tp-form-button i' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_button_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'tpebl' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tp-form .tp-form-button i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'submit_btn_style' );

		$this->start_controls_tab(
			'submit_btn_style_n',
			array(
				'label' => esc_html__( 'Normal', 'tpebl' ),
			)
		);

			$this->add_control(
				'form_button_background_color',
				array(
					'label'     => esc_html__( 'Background Color', 'tpebl' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => array(
						'{{WRAPPER}} .tp-form .tp-form-button' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_text_color',
				array(
					'label'     => esc_html__( 'Text Color', 'tpebl' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tp-form .tp-form-button' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_icon_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'tpebl' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tp-form .tp-form-button i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'form_button_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'tpebl' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-form .tp-form-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'  => 'before',
				)
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'submit_btn_style_h',
			array(
				'label' => esc_html__( 'Hover', 'tpebl' ),
			)
		);

			$this->add_control(
				'form_button_hover_background_color',
				array(
					'label'     => esc_html__( 'Hover Background Color', 'tpebl' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => array(
						'{{WRAPPER}} .tp-form .tp-form-button:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_hover_text_color',
				array(
					'label'     => esc_html__( 'Hover Text Color', 'tpebl' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tp-form .tp-form-button:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'form_button_icon_hover_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'tpebl' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => array(
						'{{WRAPPER}} .tp-form .tp-form-button:hover i' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'form_button_hover_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'tpebl' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .tp-form .tp-form-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'  => 'before',
				)
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'form_message_style',
			array(
				'label' => esc_html__( 'Message', 'tpebl' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_message_typography',
				'label'    => esc_html__( 'Typography', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-form .tp-form-message',
			)
		);

		$this->add_responsive_control(
			'form_msg_align',
			array(
				'label'       => esc_html__( 'Message Text Align', 'tpebl' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tp-form-message' => 'text-align: {{VALUE}};',
				),
				'default'     => 'left',
				'toggle'      => true,
				'label_block' => false,
			)
		);

		$this->add_control(
			'form_success_message_color',
			array(
				'label'     => esc_html__( 'Success Message Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#28a745',
				'selectors' => array(
					'{{WRAPPER}} .tp-form .tp-form-message.success' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_error_message_color',
			array(
				'label'     => esc_html__( 'Error Message Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dc3545',
				'selectors' => array(
					'{{WRAPPER}} .tp-form .tp-form-message.error' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_success_msg_bg_clr',
			array(
				'label'     => esc_html__( 'Success Message Background Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dc3545',
				'selectors' => array(
					'{{WRAPPER}} .tp-form .tp-form-message' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_error_msg_bg_clr',
			array(
				'label'     => esc_html__( 'Error Message Background Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dc3545',
				'selectors' => array(
					'{{WRAPPER}} .tp-form .tp-form-message.error' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_inline_message_color',
			array(
				'label'     => esc_html__( 'Inline Message Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .tp-form .tp-form-message.tp-form-inline' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'form_gaps',
			array(
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => esc_html__( 'Spacing', 'tpebl' ),
			)
		);

		$this->add_responsive_control(
			'form_column_gap',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Columns Gap', 'tpebl' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 10,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tp-form-container .tp-form' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_row_gap',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Rows Gap', 'tpebl' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 10,
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .tp-form-container .tp-form' => 'row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$tabs     = $settings['tabs'];

		$submit_button        = ! empty( $settings['button_submit'] ) ? $settings['button_submit'] : 'Submit';
		$label_display        = ! empty( $settings['label_display'] ) ? $settings['label_display'] : '';
		$button_input_size    = ! empty( $settings['input_size'] ) ? $settings['input_size'] : 'medium';
		$button_column        = ! empty( $settings['button_column_width']['size'] ) ? $settings['button_column_width']['size'] : '100';
		$recaptcha_site_key   = ! empty( $settings['reCaptchaSiteKey'] ) ? $settings['reCaptchaSiteKey'] : '';
		$recaptcha_secret_key = ! empty( $settings['reCaptchaSecretKey'] ) ? $settings['reCaptchaSecretKey'] : '';

		$display_icon      = ! empty( $settings['display_icon'] ) && 'yes' === $settings['display_icon'];
		$button_icon_style = ! empty( $settings['button_icon_style'] ) ? $settings['button_icon_style'] : 'font_awesome';
		$button_icon       = '';

		if ( $display_icon && 'font_awesome_5' === $button_icon_style && ! empty( $settings['icon_fontawesome_5']['value'] ) ) {
			$button_icon = '<i class="' . esc_attr( $settings['icon_fontawesome_5']['value'] ) . '" aria-hidden="true"></i> ';
		} elseif ( $display_icon && 'font_awesome' === $button_icon_style && ! empty( $settings['icon_fontawesome'] ) ) {
			$button_icon = '<i class="' . esc_attr( $settings['icon_fontawesome'] ) . '" aria-hidden="true"></i> ';
		}

		if ( $recaptcha_site_key ) {
			wp_enqueue_script( 'google-recaptcha-v3', 'https://www.google.com/recaptcha/api.js?render=' . $recaptcha_site_key, array(), null, true );
		}

		$form_id = ! empty( $settings['form_id'] ) ? esc_attr( $settings['form_id'] ) : 'tp-form-main';

		$acf_data = array(
			'form_id'         => $form_id,
			'Required_mask'   => 'yes' === $settings['required_mask'] ? 'show-asterisks' : 'hide-asterisks',
			'invalid_form'    => ! empty( $settings['invalid_form'] ) ? $settings['invalid_form'] : '',
			'required_fields' => ! empty( $settings['required_fields'] ) ? $settings['required_fields'] : '',
			'form_error'      => ! empty( $settings['form_error'] ) ? $settings['form_error'] : '',
			'success_message' => ! empty( $settings['success_message'] ) ? $settings['success_message'] : '',
			'server_error'    => ! empty( $settings['server_error'] ) ? $settings['server_error'] : '',
		);

		$email_data = array(
			'email_to'        => ! empty( $settings['email_to'] ) ? $settings['email_to'] : '',
			'email_subject'   => ! empty( $settings['email_subject'] ) ? $settings['email_subject'] : '',
			'email_message'   => ! empty( $settings['email_message'] ) ? $settings['email_message'] : '',
			'email_from'      => ! empty( $settings['email_from'] ) ? $settings['email_from'] : '',
			'email_from_name' => ! empty( $settings['email_from_name'] ) ? $settings['email_from_name'] : '',
			'email_reply_to'  => ! empty( $settings['email_reply_to'] ) ? $settings['email_reply_to'] : '',
			'email_cc'        => ! empty( $settings['email_cc'] ) ? $settings['email_cc'] : null,
			'email_bcc'       => ! empty( $settings['email_bcc'] ) ? $settings['email_bcc'] : null,
			'redirection'     => ! empty( $settings['redirect_to'] ) && ! empty( $settings['redirect_to']['url'] ) ? array(
				'url'         => esc_url( $settings['redirect_to']['url'] ),
				'is_external' => ! empty( $settings['redirect_to']['is_external'] ) ? true : false,
				'nofollow'    => ! empty( $settings['redirect_to']['nofollow'] ) ? true : false,
			) : '',
		);

		$basic = array(
			'nonce'                => wp_create_nonce( 'tp-form-nonce' ),
			'recaptcha_present'    => ! empty( $recaptcha_site_key ),
			'recaptcha_site_key'   => $recaptcha_site_key,
			'recaptcha_secret_key' => $recaptcha_secret_key,
		);

		$required_fields = array();
		foreach ( $tabs as $tab ) {
			if ( ! empty( $tab['required'] ) && 'yes' === $tab['required'] ) {
				$required_fields[] = ! empty( $tab['field_id'] ) ? $tab['field_id'] : '';
			}
		}

		$acf_data    = 'data-formdata="' . htmlspecialchars( wp_json_encode( $acf_data, true ), ENT_QUOTES, 'UTF-8' ) . '"';
		$email_data  = 'data-emaildata="' . htmlspecialchars( wp_json_encode( $email_data, true ), ENT_QUOTES, 'UTF-8' ) . '"';
		$req_fields2 = 'data-required-fields="' . htmlspecialchars( wp_json_encode( $required_fields, true ), ENT_QUOTES, 'UTF-8' ) . '"';
		$basic_data  = 'data-basic="' . htmlspecialchars( wp_json_encode( $basic, true ), ENT_QUOTES, 'UTF-8' ) . '"';

		$form_markup  = '<div class="tp-form-container" ' . $acf_data . ' ' . $email_data . ' ' . $req_fields2 . ' ' . $basic_data . ' >';
		$form_markup .= '<div class="tp-form-messages"></div>';
		$form_markup .= '<form id="' . esc_attr( $form_id ) . '" class="tp-form" method="post" action="#">';

		foreach ( $tabs as $tab ) {
			$tab_column        = ! empty( $tab['column_width']['size'] ) ? $tab['column_width']['size'] : '100';
			$tab_id            = ! empty( $tab['field_id'] ) ? $tab['field_id'] : 'tab_' . uniqid();
			$tab_label         = ! empty( $tab['field_label'] ) ? $tab['field_label'] : 'Label';
			$tab_placeholder   = ! empty( $tab['place_holder'] ) ? $tab['place_holder'] : '';
			$tab_default       = ! empty( $tab['field_default_value'] ) ? $tab['field_default_value'] : '';
			$tab_required      = ( ! empty( $tab['required'] ) && 'yes' === $tab['required'] ) ? 'required' : '';
			$tab_input_size    = ! empty( $settings['input_size'] ) ? $settings['input_size'] : 'medium';
			$tab_field_type    = ! empty( $tab['form_fields'] ) ? $tab['form_fields'] : 'text';
			$tab_textarea_rows = ! empty( $tab['textarea_rows'] ) ? $tab['textarea_rows'] : 3;

			$form_markup .= '<div class="tp-form-field" style="width: ' . esc_attr( $tab_column ) . '%;">';

			if ( 'yes' === $label_display && ! in_array( $tab_field_type, array( 'recaptcha', 'honeypot', 'hidden' ) ) ) {
				$form_markup .= '<label for="form_fields[' . esc_attr( $tab_id ) . ']" class="tp-form-label">';
				$form_markup .= esc_html( $tab_label );

				if ( ! empty( $tab_required ) ) {
					$form_markup .= ' <span class="tp-required-asterisk">*</span>';
				}
				$form_markup .= '</label>';
			}

			if ( $tab_required ) {
				$required_fields[] = esc_attr( $tab_id );
			}

			if ( in_array( $tab_field_type, array( 'text', 'email', 'number' ) ) ) {
				$form_markup .= '<input type="' . esc_attr( $tab_field_type ) . '" name="form_fields[' . esc_attr( $tab_id ) . ']" id="form-field-' . esc_attr( $tab_id ) . '" placeholder="' . esc_attr( $tab_placeholder ) . '" ' . $tab_required . ' class="' . esc_attr( $tab_input_size ) . '" value="' . esc_attr( $tab_default ) . '"/>';
			} elseif ( 'textarea' === $tab_field_type ) {
				$form_markup .= '<textarea name="form_fields[' . esc_attr( $tab_id ) . ']" rows="' . esc_attr( $tab_textarea_rows ) . '" id="form-field-' . esc_attr( $tab_id ) . '" placeholder="' . esc_attr( $tab_placeholder ) . '" ' . $tab_required . ' class="' . esc_attr( $tab_input_size ) . '">' . esc_textarea( $tab_default ) . '</textarea>';
			} elseif ( 'recaptcha' === $tab_field_type ) {
				$form_markup .= '<input type="hidden" name="recaptcha_response" class="g-recaptcha-response" />';
			} elseif ( 'hidden' === $tab_field_type ) {
				$form_markup .= '<input type="hidden" name="hidden" />';
			} elseif ( 'honeypot' === $tab_field_type ) {
				$form_markup .= '<input type="text" name="honeypot" style="display:none;" />';
			}

			$form_markup .= '</div>';
		}
		$form_markup .= '<div class="tp-form-submit-container" style="width:100%;">';
		$form_markup .= '<button type="submit" class="tp-form-submit tp-form-button ' . esc_attr( $tab_input_size ) . '" style="width: ' . esc_attr( $button_column ) . '%;">' . esc_html( $submit_button ) . $button_icon . '</button>';
		$form_markup .= '</div>';
		$form_markup .= '</form></div>';
		echo $form_markup;
	}
}