<?php
/**
 * The file that defines the core plugin class
 *
 * @link    https://posimyth.com/
 * @since   6.2.7
 *
 * @package the-plus-addons-for-elementor-page-builder
 */

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

/*
 * Advanced Shadows Theplus.
 */
if ( ! class_exists( 'Tpae_Advanced_Shadow' ) ) {

	/**
	 * Define Tpae_Advanced_Shadow class
	 *
	 * @since 6.2.7
	 */
	class Tpae_Advanced_Shadow {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 6.2.7
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Returns a singleton instance of the class.
		 *
		 * This method ensures that only one instance of the class is created (singleton pattern).
		 * If an instance doesn't exist, it creates one using the provided shortcodes.
		 *
		 * @since 6.2.7
		 *
		 * @param array $shortcodes Optional. An array of shortcodes to initialize the instance with.
		 * @return self The single instance of the class.
		 */
		public static function get_instance( $shortcodes = array() ) {

			if ( null === self::$instance ) {
				self::$instance = new self( $shortcodes );
			}

			return self::$instance;
		}

		/**
		 * Get the widget name.
		 *
		 * @since 6.2.7
		 */
		public function get_name() {
			return 'plus-advanced-shadow';
		}

		/**
		 * Initalize integration hooks
		 *
		 * @since 6.2.7
		 * @return void
		 */
		public function __construct() {

			add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'plus_adv_shadow_controls' ), 10, 2 );
			add_action( 'elementor/element/column/_section_responsive/after_section_end', array( $this, 'plus_adv_shadow_controls' ), 10, 2 );
			add_action( 'elementor/element/common/section_custom_css_pro/after_section_end', array( $this, 'plus_adv_shadow_controls' ), 10, 2 );

			if ( \Elementor\Plugin::instance()->experiments->is_feature_active( 'container' ) ) {
				add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'plus_adv_shadow_controls' ), 10, 2 );
			}

			add_action( 'elementor/frontend/before_render', array( $this, 'tp_adv_shadow_before_render' ), 10, 1 );
		}

		/**
		 * Register controls for the Advanced Shadows feature
		 *
		 * @since 6.2.7
		 */
		public function plus_adv_shadow_controls( $element ) {
			$element->start_controls_section(
				'plus_adv_shadow_section',
				array(
					'label' => esc_html__( 'Advanced Shadows', 'tpebl' ),
					'tab'   => Controls_Manager::TAB_ADVANCED,
				)
			);

			/** Box Shadow*/
			$element->add_control(
				'adv_shadow_boxshadow',
				array(
					'label'        => esc_html__( 'Box Shadows', 'tpebl' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enable', 'tpebl' ),
					'label_off'    => esc_html__( 'Disable', 'tpebl' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$element->add_control(
				'adv_shadow_boxshadow_apply',
				array(
					'label'     => esc_html__( 'Apply to', 'tpebl' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'default'     => 'Default',
						'customclass' => 'Add Custom Class',
					),
					'default'   => 'default',
					'condition' => array(
						'adv_shadow_boxshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'adv_shadow_boxshadow_class',
				array(
					'label'       => esc_html__( 'Enter Custom Class Name', 'tpebl' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => '',
					'placeholder' => esc_html__( 'e.g. .class-name', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),
					'description' => '<a rel="noopener noreferrer" target="_blank" href="https://theplusaddons.com/help/advanced-shadows/">Read Documentation</a>',
					'condition'   => array(
						'adv_shadow_boxshadow'       => 'yes',
						'adv_shadow_boxshadow_apply' => 'customclass',
					),
				)
			);
			$element->start_controls_tabs( 'adv_shadow_boxshadow_tabs' );
			$element->start_controls_tab(
				'adv_shadow_boxshadow_n',
				array(
					'label'     => esc_html__( 'Normal', 'tpebl' ),
					'condition' => array(
						'adv_shadow_boxshadow' => 'yes',
					),
				)
			);
			$repeater = new \Elementor\Repeater();
			$repeater->add_control(
				'as_bs_label',
				array(
					'label'       => esc_html__( 'Label', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Box Shadow 1', 'tpebl' ),
					'placeholder' => esc_html__( 'Enter label', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),

				)
			);
			$repeater->add_control(
				'as_bs_type',
				array(
					'label'   => esc_html__( 'Type', 'tpebl' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'bst_inset'  => 'Inset',
						'bst_outset' => 'Outset',
					),
					'default' => 'bst_outset',
				)
			);
			$repeater->add_control(
				'as_bs_x',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'X', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater->add_control(
				'as_bs_y',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Y', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater->add_control(
				'as_bs_blur',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Blur', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater->add_control(
				'as_bs_spread',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Spread', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -50,
							'max'  => 50,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater->add_control(
				'as_bs_color',
				array(
					'label' => esc_html__( 'Color', 'tpebl' ),
					'type'  => Controls_Manager::COLOR,
				)
			);
			$element->add_control(
				'as_bs_lists',
				array(
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ as_bs_label }}}',
					'condition'   => array(
						'adv_shadow_boxshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'as_bs_transition',
				array(
					'label'       => esc_html__( 'Transition css', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'e.g. all .3s linear', 'tpebl' ),
					'condition'   => array(
						'adv_shadow_boxshadow' => 'yes',
					),
				)
			);
			$element->end_controls_tab();
			$element->start_controls_tab(
				'adv_shadow_boxshadow_h',
				array(
					'label'     => esc_html__( 'Hover', 'tpebl' ),
					'condition' => array(
						'adv_shadow_boxshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'adv_shadow_boxshadow_h_s',
				array(
					'label'        => esc_html__( 'Hover Box Shadows', 'tpebl' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enable', 'tpebl' ),
					'label_off'    => esc_html__( 'Disable', 'tpebl' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'condition'    => array(
						'adv_shadow_boxshadow' => 'yes',
					),
				)
			);
			$repeaterh = new \Elementor\Repeater();
			$repeaterh->add_control(
				'as_bs_label_h',
				array(
					'label'       => esc_html__( 'Label', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Box Shadow 1', 'tpebl' ),
					'placeholder' => esc_html__( 'Enter label', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),

				)
			);
			$repeaterh->add_control(
				'as_bs_type_h',
				array(
					'label'   => esc_html__( 'Type', 'tpebl' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'bst_inset'  => 'Inset',
						'bst_outset' => 'Outset',
					),
					'default' => 'bst_outset',
				)
			);
			$repeaterh->add_control(
				'as_bs_x_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'X', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeaterh->add_control(
				'as_bs_y_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Y', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeaterh->add_control(
				'as_bs_blur_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Blur', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeaterh->add_control(
				'as_bs_spread_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Spread', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -50,
							'max'  => 50,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeaterh->add_control(
				'as_bs_color_h',
				array(
					'label' => esc_html__( 'Color', 'tpebl' ),
					'type'  => Controls_Manager::COLOR,
				)
			);
			$element->add_control(
				'as_bs_lists_h',
				array(
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeaterh->get_controls(),
					'title_field' => '{{{ as_bs_label_h }}}',
					'condition'   => array(
						'adv_shadow_boxshadow'     => 'yes',
						'adv_shadow_boxshadow_h_s' => 'yes',
					),
				)
			);
			$element->add_control(
				'as_bs_transition_h',
				array(
					'label'       => esc_html__( 'Transition css', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'e.g. all .3s linear', 'tpebl' ),
					'condition'   => array(
						'adv_shadow_boxshadow'     => 'yes',
						'adv_shadow_boxshadow_h_s' => 'yes',
					),
				)
			);
			$element->end_controls_tab();
			$element->end_controls_tabs();

			/** Text Shadow*/
			$element->add_control(
				'adv_shadow_textshadow',
				array(
					'label'        => esc_html__( 'Text Shadows', 'tpebl' ),
					'separator'    => 'before',
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enable', 'tpebl' ),
					'label_off'    => esc_html__( 'Disable', 'tpebl' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$element->add_control(
				'adv_shadow_textshadow_apply',
				array(
					'label'     => esc_html__( 'Apply to', 'tpebl' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'default'     => 'Default',
						'customclass' => 'Add Custom Class',
					),
					'default'   => 'default',
					'condition' => array(
						'adv_shadow_textshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'adv_shadow_textshadow_class',
				array(
					'label'       => esc_html__( 'Enter Custom Class Name', 'tpebl' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => '',
					'placeholder' => esc_html__( 'e.g. .class-name', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),
					'description' => '<a rel="noopener noreferrer" target="_blank" href="https://theplusaddons.com/help/advanced-shadows/">Read Documentation</a>',
					'condition'   => array(
						'adv_shadow_textshadow'       => 'yes',
						'adv_shadow_textshadow_apply' => 'customclass',
					),
				)
			);
			$element->start_controls_tabs( 'adv_shadow_textshadow_tabs' );
			$element->start_controls_tab(
				'adv_shadow_textshadow_n',
				array(
					'label'     => esc_html__( 'Normal', 'tpebl' ),
					'condition' => array(
						'adv_shadow_textshadow' => 'yes',
					),
				)
			);
			$repeater1 = new \Elementor\Repeater();
			$repeater1->add_control(
				'as_ts_label',
				array(
					'label'       => esc_html__( 'Label', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Text Shadow 1', 'tpebl' ),
					'placeholder' => esc_html__( 'Enter label', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),

				)
			);
			$repeater1->add_control(
				'as_ts_x',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'X', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater1->add_control(
				'as_ts_y',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Y', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater1->add_control(
				'as_ts_blur',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Blur', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater1->add_control(
				'as_ts_color',
				array(
					'label' => esc_html__( 'Color', 'tpebl' ),
					'type'  => Controls_Manager::COLOR,
				)
			);
			$element->add_control(
				'as_ts_lists',
				array(
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater1->get_controls(),
					'title_field' => '{{{ as_ts_label }}}',
					'condition'   => array(
						'adv_shadow_textshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'as_ts_transition',
				array(
					'label'       => esc_html__( 'Transition css', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'e.g. all .3s linear', 'tpebl' ),
					'condition'   => array(
						'adv_shadow_textshadow' => 'yes',
					),
				)
			);
			$element->end_controls_tab();
			$element->start_controls_tab(
				'adv_shadow_textshadow_h',
				array(
					'label'     => esc_html__( 'Hover', 'tpebl' ),
					'condition' => array(
						'adv_shadow_textshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'adv_shadow_textshadow_h_s',
				array(
					'label'        => esc_html__( 'Hover Text Shadows', 'tpebl' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enable', 'tpebl' ),
					'label_off'    => esc_html__( 'Disable', 'tpebl' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$repeater1_h = new \Elementor\Repeater();
			$repeater1_h->add_control(
				'as_ts_label_h',
				array(
					'label'       => esc_html__( 'Label', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Text Shadow 1', 'tpebl' ),
					'placeholder' => esc_html__( 'Enter label', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),

				)
			);
			$repeater1_h->add_control(
				'as_ts_x_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'X', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater1_h->add_control(
				'as_ts_y_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Y', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater1_h->add_control(
				'as_ts_blur_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Blur', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater1_h->add_control(
				'as_ts_color_h',
				array(
					'label' => esc_html__( 'Color', 'tpebl' ),
					'type'  => Controls_Manager::COLOR,
				)
			);
			$element->add_control(
				'as_ts_lists_h',
				array(
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater1_h->get_controls(),
					'title_field' => '{{{ as_ts_label_h }}}',
					'condition'   => array(
						'adv_shadow_textshadow'     => 'yes',
						'adv_shadow_textshadow_h_s' => 'yes',
					),
				)
			);
			$element->add_control(
				'as_ts_transition_h',
				array(
					'label'       => esc_html__( 'Transition css', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'e.g. all .3s linear', 'tpebl' ),
					'condition'   => array(
						'adv_shadow_textshadow'     => 'yes',
						'adv_shadow_textshadow_h_s' => 'yes',
					),
				)
			);
			$element->end_controls_tab();
			$element->end_controls_tabs();

			/** Drop shadow*/
			$element->add_control(
				'adv_shadow_dropshadow',
				array(
					'label'        => esc_html__( 'Drop Shadows', 'tpebl' ),
					'separator'    => 'before',
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enable', 'tpebl' ),
					'label_off'    => esc_html__( 'Disable', 'tpebl' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$element->add_control(
				'adv_shadow_dropshadow_apply',
				array(
					'label'     => esc_html__( 'Apply to', 'tpebl' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'default'     => 'Default',
						'customclass' => 'Add Custom Class',
					),
					'default'   => 'default',
					'condition' => array(
						'adv_shadow_dropshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'adv_shadow_dropshadow_class',
				array(
					'label'       => esc_html__( 'Enter Custom Class Name', 'tpebl' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => '',
					'placeholder' => esc_html__( 'e.g. .class-name', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),
					'description' => '<a rel="noopener noreferrer" target="_blank" href="https://theplusaddons.com/help/advanced-shadows/">Read Documentation</a>',
					'condition'   => array(
						'adv_shadow_dropshadow'       => 'yes',
						'adv_shadow_dropshadow_apply' => 'customclass',
					),
				)
			);
			$element->start_controls_tabs( 'adv_shadow_dropshadow_tabs' );
			$element->start_controls_tab(
				'adv_shadow_dropshadow_n',
				array(
					'label'     => esc_html__( 'Normal', 'tpebl' ),
					'condition' => array(
						'adv_shadow_dropshadow' => 'yes',
					),
				)
			);
			$repeater2 = new \Elementor\Repeater();
			$repeater2->add_control(
				'as_ds_label',
				array(
					'label'       => esc_html__( 'Label', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Drop Shadow 1', 'tpebl' ),
					'placeholder' => esc_html__( 'Enter label', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),

				)
			);
			$repeater2->add_control(
				'as_ds_x',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'X', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater2->add_control(
				'as_ds_y',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Y', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater2->add_control(
				'as_ds_blur',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Blur', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater2->add_control(
				'as_ds_color',
				array(
					'label' => esc_html__( 'Color', 'tpebl' ),
					'type'  => Controls_Manager::COLOR,
				)
			);
			$element->add_control(
				'as_ds_lists',
				array(
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater2->get_controls(),
					'title_field' => '{{{ as_ds_label }}}',
					'condition'   => array(
						'adv_shadow_dropshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'as_ds_transition',
				array(
					'label'       => esc_html__( 'Transition css', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'e.g. all .3s linear', 'tpebl' ),
					'condition'   => array(
						'adv_shadow_dropshadow' => 'yes',
					),
				)
			);
			$element->end_controls_tab();
			$element->start_controls_tab(
				'adv_shadow_dropshadow_h',
				array(
					'label'     => esc_html__( 'Hover', 'tpebl' ),
					'condition' => array(
						'adv_shadow_dropshadow' => 'yes',
					),
				)
			);
			$element->add_control(
				'adv_shadow_dropshadow_h_s',
				array(
					'label'        => esc_html__( 'Hover Drop Shadows', 'tpebl' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enable', 'tpebl' ),
					'label_off'    => esc_html__( 'Disable', 'tpebl' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);
			$repeater2_h = new \Elementor\Repeater();
			$repeater2_h->add_control(
				'as_ds_label_h',
				array(
					'label'       => esc_html__( 'Label', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Drop Shadow 1', 'tpebl' ),
					'placeholder' => esc_html__( 'Enter label', 'tpebl' ),
					'dynamic'     => array( 'active' => true ),

				)
			);
			$repeater2_h->add_control(
				'as_ds_x_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'X', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater2_h->add_control(
				'as_ds_y_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Y', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => -250,
							'max'  => 250,
							'step' => 1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater2_h->add_control(
				'as_ds_blur_h',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Blur', 'tpebl' ),
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						),
					),
					'render_type' => 'ui',
				)
			);
			$repeater2_h->add_control(
				'as_ds_color_h',
				array(
					'label' => esc_html__( 'Color', 'tpebl' ),
					'type'  => Controls_Manager::COLOR,
				)
			);
			$element->add_control(
				'as_ds_lists_h',
				array(
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater2_h->get_controls(),
					'title_field' => '{{{ as_ds_label_h }}}',
					'condition'   => array(
						'adv_shadow_dropshadow'     => 'yes',
						'adv_shadow_dropshadow_h_s' => 'yes',
					),
				)
			);
			$element->add_control(
				'as_ds_transition_h',
				array(
					'label'       => esc_html__( 'Transition css', 'tpebl' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'e.g. all .3s linear', 'tpebl' ),
					'condition'   => array(
						'adv_shadow_dropshadow'     => 'yes',
						'adv_shadow_dropshadow_h_s' => 'yes',
					),
				)
			);
			$element->end_controls_tab();
			$element->end_controls_tabs();
			$element->end_controls_section();
		}

		/**
		 * Apply Advanced Shadowss settings before rendering the widget.
		 *
		 * @param string $element Element.
		 *
		 * @since 6.2.7
		 */
		public function tp_adv_shadow_before_render( $element ) {
			$settings = $element->get_settings();

			$id      = $element->get_id();
			$idhover = $element->get_id() . ':hover';

			$adv_shadow_boxshadow     = isset( $settings['adv_shadow_boxshadow'] ) ? $settings['adv_shadow_boxshadow'] : '';
			$adv_shadow_boxshadow_h_s = isset( $settings['adv_shadow_boxshadow_h_s'] ) ? $settings['adv_shadow_boxshadow_h_s'] : '';

			$as_bs_lists   = ! empty( $settings['as_bs_lists'] ) ? $settings['as_bs_lists'] : '';
			$as_bs_lists_h = ! empty( $settings['as_bs_lists_h'] ) ? $settings['as_bs_lists_h'] : '';

			$adv_shadow_boxshadow_apply = ! empty( $settings['adv_shadow_boxshadow_apply'] ) ? $settings['adv_shadow_boxshadow_apply'] : '';
			$as_bs_transition           = ! empty( $settings['as_bs_transition'] ) ? $settings['as_bs_transition'] : '';
			$as_bs_transition_h         = ! empty( $settings['as_bs_transition_h'] ) ? $settings['as_bs_transition_h'] : '';

			$adv_shadow_textshadow       = isset( $settings['adv_shadow_textshadow'] ) ? $settings['adv_shadow_textshadow'] : '';
			$adv_shadow_textshadow_h_s   = isset( $settings['adv_shadow_textshadow_h_s'] ) ? $settings['adv_shadow_textshadow_h_s'] : '';
			$as_ts_lists                 = ! empty( $settings['as_ts_lists'] ) ? $settings['as_ts_lists'] : '';
			$as_ts_lists_h               = ! empty( $settings['as_ts_lists_h'] ) ? $settings['as_ts_lists_h'] : '';
			$adv_shadow_textshadow_apply = ! empty( $settings['adv_shadow_textshadow_apply'] ) ? $settings['adv_shadow_textshadow_apply'] : '';
			$as_ts_transition            = ! empty( $settings['as_ts_transition'] ) ? $settings['as_ts_transition'] : '';
			$as_ts_transition_h          = ! empty( $settings['as_ts_transition_h'] ) ? $settings['as_ts_transition_h'] : '';

			$adv_shadow_dropshadow       = isset( $settings['adv_shadow_dropshadow'] ) ? $settings['adv_shadow_dropshadow'] : '';
			$adv_shadow_dropshadow_h_s   = isset( $settings['adv_shadow_dropshadow_h_s'] ) ? $settings['adv_shadow_dropshadow_h_s'] : '';
			$as_ds_lists                 = ! empty( $settings['as_ds_lists'] ) ? $settings['as_ds_lists'] : '';
			$as_ds_lists_h               = ! empty( $settings['as_ds_lists_h'] ) ? $settings['as_ds_lists_h'] : '';
			$adv_shadow_dropshadow_apply = ! empty( $settings['adv_shadow_dropshadow_apply'] ) ? $settings['adv_shadow_dropshadow_apply'] : '';
			$as_ds_transition            = ! empty( $settings['as_ds_transition'] ) ? $settings['as_ds_transition'] : '';
			$as_ds_transition_h          = ! empty( $settings['as_ds_transition_h'] ) ? $settings['as_ds_transition_h'] : '';

			/** Box Shadow*/
			$bs_normal = '';
			$bs_hover  = '';

			$bcss   = '';
			$bcss_h = '';
			if ( ! empty( $adv_shadow_boxshadow ) && 'yes' === $adv_shadow_boxshadow ) {
				$bs_class = '';
				if ( ! empty( $settings['adv_shadow_boxshadow_class'] ) && ! empty( $adv_shadow_boxshadow_apply ) && 'customclass' === $adv_shadow_boxshadow_apply ) {
					$bs_class = ' .' . $settings['adv_shadow_boxshadow_class'];
				}

				$bstrans = '';
				if ( ! empty( $as_bs_transition ) ) {
					$bstrans = '-webkit-transition: ' . $as_bs_transition . ';-moz-transition: ' . $as_bs_transition . ';-o-transition: ' . $as_bs_transition . ';-ms-transition: ' . $as_bs_transition . ';';
				}

				if ( ! empty( $as_bs_lists ) ) {
					$i = 1;
					foreach ( $as_bs_lists as $item ) {
						$total = count( $as_bs_lists );

						$as_bs_type = '';
						if ( ! empty( $item['as_bs_type'] ) && 'bst_inset' === $item['as_bs_type'] ) {
							$as_bs_type = 'inset';
						}
						$as_bs_x = ! empty( $item['as_bs_x']['size'] ) ? $item['as_bs_x']['size'] . $item['as_bs_x']['unit'] : 0;
						$as_bs_y = ! empty( $item['as_bs_y']['size'] ) ? $item['as_bs_y']['size'] . $item['as_bs_y']['unit'] : 0;

						$as_bs_blur   = ! empty( $item['as_bs_blur']['size'] ) ? $item['as_bs_blur']['size'] . $item['as_bs_blur']['unit'] : 0;
						$as_bs_spread = ! empty( $item['as_bs_spread']['size'] ) ? $item['as_bs_spread']['size'] . $item['as_bs_spread']['unit'] : 0;
						$as_bs_color  = ! empty( $item['as_bs_color'] ) ? $item['as_bs_color'] : '#00000033';

						$sep = '';
						if ( $i != $total ) {
							$sep .= ', ';
						}

						$bcss .= $as_bs_type . ' ' . $as_bs_x . ' ' . $as_bs_y . ' ' . $as_bs_blur . ' ' . $as_bs_spread . ' ' . $as_bs_color . $sep;
						++$i;
					}

					$bs_normal .= '.elementor-element.elementor-element-' . $id . $bs_class . ',.elementor-element.e-container.elementor-element-' . $id . $bs_class . ',.elementor-element.e-con.elementor-element-' . $id . $bs_class . ',.elementor-element.elementor-element-' . $id . ':not(.elementor-motion-effects-element-type-background) > .elementor-widget-wrap ' . $bs_class . ',.elementor-element.elementor-element-' . $id . ' > .elementor-widget-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer ' . $bs_class . ',.elementor-element.elementor-element-' . $id . ' ' . $bs_class . '{ ' . $bstrans . 'box-shadow : ' . $bcss . ' }';

				}

				if ( isset( $adv_shadow_boxshadow_h_s ) && 'yes' === $adv_shadow_boxshadow_h_s && ! empty( $as_bs_lists_h ) ) {

					$bstransh = '';
					if ( ! empty( $as_bs_transition_h ) ) {
						$bstransh = '-webkit-transition: ' . $as_bs_transition_h . ';-moz-transition: ' . $as_bs_transition_h . ';-o-transition: ' . $as_bs_transition_h . ';-ms-transition: ' . $as_bs_transition_h . ';';
					}
					$ih = 1;
					foreach ( $as_bs_lists_h as $itemh ) {
						$total_h = count( $as_bs_lists_h );

						$as_bs_type_h = '';
						if ( ! empty( $itemh['as_bs_type_h'] ) && 'bst_inset' === $itemh['as_bs_type_h'] ) {
							$as_bs_type_h = 'inset';
						}
						$as_bs_x_h = ! empty( $itemh['as_bs_x_h']['size'] ) ? $itemh['as_bs_x_h']['size'] . $itemh['as_bs_x_h']['unit'] : 0;
						$as_bs_y_h = ! empty( $itemh['as_bs_y_h']['size'] ) ? $itemh['as_bs_y_h']['size'] . $itemh['as_bs_y_h']['unit'] : 0;

						$as_bs_blur_h   = ! empty( $itemh['as_bs_blur_h']['size'] ) ? $itemh['as_bs_blur_h']['size'] . $itemh['as_bs_blur_h']['unit'] : 0;
						$as_bs_spread_h = ! empty( $itemh['as_bs_spread_h']['size'] ) ? $itemh['as_bs_spread_h']['size'] . $itemh['as_bs_spread_h']['unit'] : 0;
						$as_bs_color_h  = ! empty( $itemh['as_bs_color_h'] ) ? $itemh['as_bs_color_h'] : '#00000033';

						$sep_h = '';
						if ( $ih != $total_h ) {
							$sep_h .= ', ';
						}

						$bcss_h .= $as_bs_type_h . ' ' . $as_bs_x_h . ' ' . $as_bs_y_h . ' ' . $as_bs_blur_h . ' ' . $as_bs_spread_h . ' ' . $as_bs_color_h . $sep_h;
						++$ih;
					}

					$bs_hover .= '.elementor-element.elementor-element-' . $idhover . $bs_class . ',.elementor-element.e-container.elementor-element-' . $idhover . $bs_class . ',.elementor-element.e-con.elementor-element-' . $idhover . $bs_class . ',.elementor-element.e-container.elementor-element-' . $idhover . $bs_class . ',.elementor-element.e-con.elementor-element-' . $idhover . $bs_class . ',.elementor-element.elementor-element-' . $idhover . ':not(.elementor-motion-effects-element-type-background) > .elementor-widget-wrap ' . $bs_class . ',.elementor-element.elementor-element-' . $idhover . ' > .elementor-widget-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer ' . $bs_class . ',.elementor-element.elementor-element-' . $idhover . ' ' . $bs_class . '{ ' . $bstransh . 'box-shadow : ' . $bcss_h . ' }';

				}
				if ( ! empty( $bs_normal ) || ! empty( $bs_hover ) ) {
					echo '<style>' . $bs_normal . $bs_hover . '</style>';
				}
			}

			/** Text Shadow*/
			$ts_normal = '';
			$ts_hover  = '';

			$tcss   = '';
			$tcss_h = '';
			if ( ! empty( $adv_shadow_textshadow ) && 'yes' === $adv_shadow_textshadow ) {
				$ts_class = '';
				if ( ! empty( $settings['adv_shadow_textshadow_class'] ) && ! empty( $adv_shadow_textshadow_apply ) && 'customclass' === $adv_shadow_textshadow_apply ) {
					$ts_class = ' .' . $settings['adv_shadow_textshadow_class'];
				}

				$tstrans = '';
				if ( ! empty( $as_ts_transition ) ) {
					$tstrans = '-webkit-transition: ' . $as_ts_transition . ';-moz-transition: ' . $as_ts_transition . ';-o-transition: ' . $as_ts_transition . ';-ms-transition: ' . $as_ts_transition . ';';
				}

				if ( ! empty( $as_ts_lists ) ) {
					$j = 1;
					foreach ( $as_ts_lists as $item ) {
						$total = count( $as_ts_lists );

						$as_ts_x = ! empty( $item['as_ts_x']['size'] ) ? $item['as_ts_x']['size'] . $item['as_ts_x']['unit'] : 0;
						$as_ts_y = ! empty( $item['as_ts_y']['size'] ) ? $item['as_ts_y']['size'] . $item['as_ts_y']['unit'] : 0;

						$as_ts_blur  = ! empty( $item['as_ts_blur']['size'] ) ? $item['as_ts_blur']['size'] . $item['as_ts_blur']['unit'] : 0;
						$as_ts_color = ! empty( $item['as_ts_color'] ) ? $item['as_ts_color'] : '#00000033';

						$sept = '';
						if ( $j != $total ) {
							$sept .= ', ';
						}

						$tcss .= $as_ts_x . ' ' . $as_ts_y . ' ' . $as_ts_blur . ' ' . $as_ts_color . $sept;
						++$j;
					}

					$ts_normal .= '.elementor-element.elementor-element-' . $id . $ts_class . ',.elementor-element.e-container.elementor-element-' . $id . $ts_class . ',.elementor-element.e-con.elementor-element-' . $id . $ts_class . ',.elementor-element.elementor-element-' . $id . ':not(.elementor-motion-effects-element-type-background) > .elementor-widget-wrap ' . $ts_class . ',.elementor-element.elementor-element-' . $id . ' > .elementor-widget-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer ' . $ts_class . ',.elementor-element.elementor-element-' . $id . ' ' . $ts_class . '{ ' . $tstrans . 'text-shadow : ' . $tcss . ' }';
				}

				if ( isset( $adv_shadow_textshadow_h_s ) && 'yes' === $adv_shadow_textshadow_h_s && ! empty( $as_ts_lists_h ) ) {

					$tstransh = '';
					if ( ! empty( $as_ts_transition_h ) ) {
						$tstransh = '-webkit-transition: ' . $as_ts_transition_h . ';-moz-transition: ' . $as_ts_transition_h . ';-o-transition: ' . $as_ts_transition_h . ';-ms-transition: ' . $as_ts_transition_h . ';';
					}

					$jh = 1;
					foreach ( $as_ts_lists_h as $itemh ) {
						$total_h = count( $as_ts_lists_h );

						$as_ts_x_h = ! empty( $itemh['as_ts_x_h']['size'] ) ? $itemh['as_ts_x_h']['size'] . $itemh['as_ts_x_h']['unit'] : 0;
						$as_ts_y_h = ! empty( $itemh['as_ts_y_h']['size'] ) ? $itemh['as_ts_y_h']['size'] . $itemh['as_ts_y_h']['unit'] : 0;

						$as_ts_blur_h  = ! empty( $itemh['as_ts_blur_h']['size'] ) ? $itemh['as_ts_blur_h']['size'] . $itemh['as_ts_blur_h']['unit'] : 0;
						$as_ts_color_h = ! empty( $itemh['as_ts_color_h'] ) ? $itemh['as_ts_color_h'] : '#00000033';

						$sept_h = '';
						if ( $jh != $total_h ) {
							$sept_h .= ', ';
						}

						$tcss_h .= $as_ts_x_h . ' ' . $as_ts_y_h . ' ' . $as_ts_blur_h . ' ' . $as_ts_color_h . $sept_h;
						++$jh;
					}

					$ts_hover .= '.elementor-element.elementor-element-' . $idhover . $ts_class . ',.elementor-element.e-container.elementor-element-' . $idhover . $ts_class . ',.elementor-element.e-con.elementor-element-' . $idhover . $ts_class . ',.elementor-element.elementor-element-' . $idhover . ':not(.elementor-motion-effects-element-type-background) > .elementor-widget-wrap ' . $ts_class . ',.elementor-element.elementor-element-' . $idhover . ' > .elementor-widget-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer ' . $ts_class . ',.elementor-element.elementor-element-' . $idhover . ' ' . $ts_class . '{ ' . $tstransh . 'text-shadow : ' . $tcss_h . ' }';

				}
				if ( ! empty( $ts_normal ) || ! empty( $ts_hover ) ) {
					echo '<style>' . $ts_normal . $ts_hover . '</style>';
				}
			}

			/** Drop Shadow*/
			$ds_normal = '';
			$ds_hover  = '';

			$dcss   = '';
			$dcss_h = '';
			if ( ! empty( $adv_shadow_dropshadow ) && 'yes' === $adv_shadow_dropshadow ) {
				$ds_class = '';
				if ( ! empty( $settings['adv_shadow_dropshadow_class'] ) && ! empty( $adv_shadow_dropshadow_apply ) && 'customclass' === $adv_shadow_dropshadow_apply ) {
					$ds_class = ' .' . $settings['adv_shadow_dropshadow_class'];
				}

				$dstrans = '';
				if ( ! empty( $as_ds_transition ) ) {
					$dstrans = '-webkit-transition: ' . $as_ds_transition . ';-moz-transition: ' . $as_ds_transition . ';-o-transition: ' . $as_ds_transition . ';-ms-transition: ' . $as_ds_transition . ';';
				}

				if ( ! empty( $as_ds_lists ) ) {
					$k = 1;
					foreach ( $as_ds_lists as $item ) {
						$total = count( $as_ds_lists );

						$as_ds_x = ! empty( $item['as_ds_x']['size'] ) ? $item['as_ds_x']['size'] . $item['as_ds_x']['unit'] : 0;
						$as_ds_y = ! empty( $item['as_ds_y']['size'] ) ? $item['as_ds_y']['size'] . $item['as_ds_y']['unit'] : 0;

						$as_ds_blur  = ! empty( $item['as_ds_blur']['size'] ) ? $item['as_ds_blur']['size'] . $item['as_ds_blur']['unit'] : 0;
						$as_ds_color = ! empty( $item['as_ds_color'] ) ? $item['as_ds_color'] : '#00000033';

						$dcss .= 'drop-shadow(' . $as_ds_x . ' ' . $as_ds_y . ' ' . $as_ds_blur . ' ' . $as_ds_color . ') ';
						++$k;
					}

					$ds_normal .= '.elementor-element.elementor-element-' . $id . $ds_class . ',.elementor-element.e-container.elementor-element-' . $id . $ds_class . ',.elementor-element.e-con.elementor-element-' . $id . $ds_class . ',.elementor-element.elementor-element-' . $id . ':not(.elementor-motion-effects-element-type-background) > .elementor-widget-wrap ' . $ds_class . ',.elementor-element.elementor-element-' . $id . ' > .elementor-widget-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer ' . $ds_class . ',.elementor-element.elementor-element-' . $id . ' ' . $ds_class . '{ ' . $dstrans . 'filter : ' . $dcss . ' }';
				}

				if ( isset( $adv_shadow_dropshadow_h_s ) && 'yes' === $adv_shadow_dropshadow_h_s && ! empty( $as_ds_lists_h ) ) {

					$dstransh = '';
					if ( ! empty( $as_ds_transition_h ) ) {
						$dstransh = '-webkit-transition: ' . $as_ds_transition_h . ';-moz-transition: ' . $as_ds_transition_h . ';-o-transition: ' . $as_ds_transition_h . ';-ms-transition: ' . $as_ds_transition_h . ';';
					}

					$kh = 1;
					foreach ( $as_ds_lists_h as $itemh ) {
						$total_h = count( $as_ds_lists_h );

						$as_ds_x_h = ! empty( $itemh['as_ds_x_h']['size'] ) ? $itemh['as_ds_x_h']['size'] . $itemh['as_ds_x_h']['unit'] : 0;
						$as_ds_y_h = ! empty( $itemh['as_ds_y_h']['size'] ) ? $itemh['as_ds_y_h']['size'] . $itemh['as_ds_y_h']['unit'] : 0;

						$as_ds_blur_h  = ! empty( $itemh['as_ds_blur_h']['size'] ) ? $itemh['as_ds_blur_h']['size'] . $itemh['as_ds_blur_h']['unit'] : 0;
						$as_ds_color_h = ! empty( $itemh['as_ds_color_h'] ) ? $itemh['as_ds_color_h'] : '#00000033';

						$dcss_h .= 'drop-shadow(' . $as_ds_x_h . ' ' . $as_ds_y_h . ' ' . $as_ds_blur_h . ' ' . $as_ds_color_h . ') ';
						++$kh;
					}

					$ds_hover .= '.elementor-element.elementor-element-' . $idhover . $ds_class . ',.elementor-element.e-container.elementor-element-' . $idhover . $ds_class . ',.elementor-element.e-con.elementor-element-' . $idhover . $ds_class . ',.elementor-element.elementor-element-' . $idhover . ':not(.elementor-motion-effects-element-type-background) > .elementor-widget-wrap ' . $ds_class . ',.elementor-element.elementor-element-' . $idhover . ' > .elementor-widget-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer ' . $ds_class . ',.elementor-element.elementor-element-' . $idhover . ' ' . $ds_class . '{ ' . $dstransh . 'filter : ' . $dcss_h . ' }';

				}
				if ( ! empty( $ds_normal ) || ! empty( $ds_hover ) ) {
					echo '<style>' . $ds_normal . $ds_hover . '</style>';
				}
			}
		}
	}
}

Tpae_Advanced_Shadow::get_instance();
