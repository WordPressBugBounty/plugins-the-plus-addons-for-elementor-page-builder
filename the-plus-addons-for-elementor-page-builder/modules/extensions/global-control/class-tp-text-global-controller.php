<?php
namespace ThePlusAddons\Elementor\Text;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global GSAP Animations Tab
 * Appears under: Site Settings → Theme Style
 *
 * @since v6.4.5
 */
class TP_GSAP_Text_Global extends Tab_Base {

	public function get_id() {
		return 'settings-tp-text-gsap-global';
	}

	public function get_title() {
		return esc_html__( 'Global Text Animations', 'tpebl' );
	}

	public function get_group() {
		return 'global';
	}

	public function get_icon() {
		return 'eicon-text-area';
	}

	protected function register_tab_controls() {

		$this->start_controls_section(
			'section_' . $this->get_id(),
			array(
				'label' => $this->get_title(),
				'tab'   => $this->get_id(),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Animation Name', 'tpebl' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'My Animation', 'tpebl' ),
				'ai'      => false,
			)
		);
		$repeater->add_control(
			'text_animation_type',
			array(
				'label'   => esc_html__( 'Animation Type', 'tpebl' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => array(
					'normal'       => esc_html__( 'Normal', 'tpebl' ),
					'explode'      => esc_html__( 'Explode / Scatter', 'tpebl' ),
					'scramble'     => esc_html__( 'Scramble Text', 'tpebl' ),
					'typing'       => esc_html__( 'Typing Effect', 'tpebl' ),
					'tp_text_swap' => esc_html__( 'Text Style Swap', 'tpebl' ),
				),
			)
		);
		$repeater->add_control(
			'swap_txt_color',
			array(
				'label'     => esc_html__( 'Text Color', 'tpebl' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0885f2ff',
				'condition' => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_font_family',
			array(
				'label'     => esc_html__( 'Font Family', 'tpebl' ),
				'type'      => Controls_Manager::FONT,
				'default'   => '',
				'condition' => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_font_size',
			array(
				'label'      => esc_html__( 'Size', 'tpebl' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
					'vw' => array(
						'min'  => 0.1,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'condition'  => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_font_weight',
			array(
				'label'     => esc_html__( 'Weight', 'tpebl' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					'100'     => '100',
					'200'     => '200',
					'300'     => '300',
					'400'     => '400',
					'500'     => '500',
					'600'     => '600',
					'700'     => '700',
					'800'     => '800',
					'900'     => '900',
					'normal'  => 'Normal',
					'bold'    => 'Bold',
					'bolder'  => 'Bolder',
					'lighter' => 'Lighter',
				),
				'condition' => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_text_transform',
			array(
				'label'     => esc_html__( 'Transform', 'tpebl' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					'uppercase'  => 'Uppercase',
					'lowercase'  => 'Lowercase',
					'capitalize' => 'Capitalize',
					'none'       => 'Normal',
				),
				'condition' => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_font_style',
			array(
				'label'     => esc_html__( 'Style', 'tpebl' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					'normal'  => 'Normal',
					'italic'  => 'Italic',
					'oblique' => 'Oblique',
				),
				'condition' => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_text_decoration',
			array(
				'label'     => esc_html__( 'Decoration', 'tpebl' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					'underline'    => 'Underline',
					'overline'     => 'Overline',
					'line-through' => 'Line Through',
					'none'         => 'None',
				),
				'condition' => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_line_height',
			array(
				'label'      => esc_html__( 'Line-Height', 'tpebl' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
					),
				),
				'default'    => array(
					'unit' => 'em',
				),
				'condition'  => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);

		$repeater->add_control(
			'swap_txt_typography_letter_spacing',
			array(
				'label'      => esc_html__( 'Letter Spacing', 'tpebl' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vw' ),
				'range'      => array(
					'px' => array(
						'min'  => -5,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'condition'  => array(
					'text_animation_type' => 'tp_text_swap',
				),
			)
		);
		$repeater->add_control(
			'transform_x',
			array(
				'label'      => esc_html__( 'X Position', 'tpebl' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -500,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'condition'  => array(
					'text_animation_type' => 'normal',
				),
			)
		);
		$repeater->add_control(
			'transform_y',
			array(
				'label'      => esc_html__( 'Y Position', 'tpebl' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -500,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'condition'  => array(
					'text_animation_type' => 'normal',
				),
			)
		);
		$repeater->add_control(
			'transform_skewx',
			array(
				'label'     => esc_html__( 'Skew X', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'min'  => -180,
					'max'  => 180,
					'step' => 1,
				),
				'default'   => array( 'size' => 0 ),
				'condition' => array(
					'text_animation_type' => 'normal',
				),
			)
		);
		$repeater->add_control(
			'transform_skewy',
			array(
				'label'     => esc_html__( 'Skew Y', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'min'  => -180,
					'max'  => 180,
					'step' => 1,
				),
				'default'   => array( 'size' => 0 ),
				'condition' => array(
					'text_animation_type' => 'normal',
				),

			)
		);
		$repeater->add_control(
			'transform_scale',
			array(
				'label'     => esc_html__( 'Scale', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'min'  => 0,
					'max'  => 5,
					'step' => 0.01,
				),
				'default'   => array( 'size' => 1 ),
				'condition' => array(
					'text_animation_type' => 'normal',
				),

			)
		);
		$repeater->add_control(
			'transform_rotation',
			array(
				'label'     => esc_html__( 'Rotation', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => array(
					'min'  => -360,
					'max'  => 360,
					'step' => 1,
				),
				'default'   => array( 'size' => 0 ),
				'condition' => array(
					'text_animation_type' => 'normal',
				),

			)
		);
		$repeater->add_control(
			'transform_origin',
			array(
				'label'     => esc_html__( 'Transform Origin', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '50% 50%',
				'options'   => array(
					'0% 0%'     => 'Top Left',
					'50% 0%'    => 'Top Center',
					'100% 0%'   => 'Top Right',
					'0% 50%'    => 'Center Left',
					'50% 50%'   => 'Center',
					'100% 50%'  => 'Center Right',
					'0% 100%'   => 'Bottom Left',
					'50% 100%'  => 'Bottom Center',
					'100% 100%' => 'Bottom Right',
				),
				'condition' => array(
					'text_animation_type' => 'normal',
				),

			)
		);
		$repeater->add_control(
			'split_type',
			array(
				'label'     => esc_html__( 'Split Type', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'chars',
				'options'   => array(
					'chars' => esc_html__( 'Characters', 'tpebl' ),
					'words' => esc_html__( 'Words', 'tpebl' ),
				),
				'condition' => array(
					'text_animation_type!' => array( 'typing', 'scramble' ),
				),
			)
		);
		$repeater->add_control(
			'text_trigger',
			array(
				'label'   => esc_html__( 'Animation Trigger', 'tpebl' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'onload',
				'options' => array(
					'onload'   => esc_html__( 'On Load', 'tpebl' ),
					'onscroll' => esc_html__( 'On Scroll', 'tpebl' ),
					'onhover'  => esc_html__( 'On Hover', 'tpebl' ),
				),
			)
		);
		$repeater->add_control(
			'tp_scrub',
			array(
				'label'        => __( 'Enable Scroll Scrub', 'tpebl' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'tpebl' ),
				'label_off'    => __( 'No', 'tpebl' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'text_trigger'         => 'onscroll',
					'text_animation_type!' => array( 'typing', 'scramble' ),
				),
			)
		);
		$repeater->add_control(
			'text_duration',
			array(
				'label'   => esc_html__( 'Duration', 'tpebl' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 1.2,
			)
		);
		$repeater->add_control(
			'text_delay',
			array(
				'label'   => esc_html__( 'Delay', 'tpebl' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 0.3,
			)
		);
		$repeater->add_control(
			'text_stagger',
			array(
				'label'     => esc_html__( 'Stagger', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 0.04,
				'condition' => array(
					'text_animation_type!' => array( 'typing', 'scramble' ),
				),
			)
		);
		$repeater->add_control(
			'text_ease',
			array(
				'label'     => esc_html__( 'Animation Effects', 'tpebl' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'power1.out',
				'options'   => array(
					'power1.out'  => 'Power 1 Out',
					'power2.out'  => 'Power 2 Out',
					'power3.out'  => 'Power 3 Out',
					'power4.out'  => 'Power 4 Out',
					'sine.out'    => 'Sine Out',
					'expo.out'    => 'Expo Out',
					'circ.out'    => 'Circular Out',
					'back.out'    => 'Back Out',
					'elastic.out' => 'Elastic Out',
					'bounce.out'  => 'Bounce Out',
				),
				'condition' => array(
					'text_animation_type!' => 'typing',
				),
			)
		);
		$repeater->add_control(
			'text_repeat',
			array(
				'label'        => esc_html__( 'Repeat', 'tpebl' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'tpebl' ),
				'label_off'    => esc_html__( 'No', 'tpebl' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'text_animation_type!' => 'typing',
				),
			)
		);
		$this->add_control(
			'tp_text_global_gsap_list',
			array(
				// 'label'       => esc_html__( 'Global GSAP Animations', 'tpebl' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'button_text' => esc_html__( 'Add Global Animation', 'tpebl' ),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Helper: Get all saved GSAP animations (for widgets)
	 *
	 * @since v6.4.5
	 */
	public static function get_text_global_gsap_list() {
		$kit = Plugin::$instance->kits_manager->get_active_kit();
		if ( ! $kit ) {
			return array();
		}

		$animations = $kit->get_settings( 'tp_text_global_gsap_list' );

		return ! empty( $animations ) ? $animations : array();
	}
}
