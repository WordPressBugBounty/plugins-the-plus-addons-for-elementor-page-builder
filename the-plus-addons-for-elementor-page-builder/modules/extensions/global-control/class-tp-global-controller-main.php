<?php
/**
 * The file that defines the widget plugin for the free version.
 *
 * @link       https://posimyth.com/
 * @since      v6.5.0
 *
 * @package    the-plus-addons-for-elementor-page-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define Tpae_Global_Controllers_Main class for the free version.
 * 
 * @since v6.5.0
 */
if ( ! class_exists( 'Tpae_Global_Controllers_Main' ) ) {

    /**
     * Define L_Tpaef_Extensions_Main class for the free version
     * 
     * @since v6.5.0
     */
    class Tpae_Global_Controllers_Main {

        /**
         * Call __construct.
         * 
         * @since v6.5.0
         */
        public function __construct() {

            $theplus_options = get_option( 'theplus_options' );

            $extras_elements = ! empty( $theplus_options['extras_elements'] ) ? $theplus_options['extras_elements'] : [];
            $get_widget = ! empty( $theplus_options['check_elements'] ) ? $theplus_options['check_elements'] : [];

            add_action( 'elementor/kit/register_tabs', array( $this, 'register_setting_tabs' ) );

            if ( in_array( 'plus_global_box_shadow', $extras_elements, true ) ) {
                add_action(
                    'elementor/controls/register',
                    function ( $controls_manager ) {
                        $global_path = L_THEPLUS_PATH . 'modules/extensions/global-control/class-tp-global-box-shadow-controller.php';
                        if ( ! class_exists( 'ThePlusAddons\\Elementor\\BoxShadow\\TP_Box_Shadow_Global' ) && file_exists( $global_path ) ) {
                            include_once $global_path;
                        }

                        $group_path = L_THEPLUS_PATH . 'modules/extensions/global-control/class-tp-group-box-shadow.php';
                        if ( file_exists( $group_path ) ) {
                            include_once $group_path;
                        }

                        if ( class_exists( 'ThePlusAddons\\Elementor\\BoxShadow\\TP_Group_Control_Box_Shadow' ) ) {
                            $controls_manager->add_group_control(
                                'box-shadow',
                                new ThePlusAddons\Elementor\BoxShadow\TP_Group_Control_Box_Shadow()
                            );
                        }
                    }
                );

                add_action(
                    'elementor/frontend/before_render',
                    function ( $element ) {
                        if ( ! class_exists( 'ThePlusAddons\\Elementor\\BoxShadow\\TP_Box_Shadow_Global' ) ) {
                            return;
                        }

                        $settings   = $element->get_settings();
                        $element_id = $element->get_id();

                        foreach ( $settings as $key => $value ) {
                            if ( empty( $value ) || substr( $key, -20 ) !== '_tp_bs_global_preset' ) {
                                continue;
                            }

                            $css = ThePlusAddons\Elementor\BoxShadow\TP_Box_Shadow_Global::get_preset_css( $value );
                            if ( ! $css ) {
                                break;
                            }

                            $base_name    = substr( $key, 0, -20 );
                            $selector_key = $base_name . '_' . $base_name . '_tp_bs_selector_store';

                            $selector_tmpl = ! empty( $settings[ $selector_key ] ) ? $settings[ $selector_key ] : '{{WRAPPER}}';

                            $wrapper_class = '.elementor-element-' . $element_id;
                            $selector      = str_replace( '{{WRAPPER}}', $wrapper_class, $selector_tmpl );

                            echo '<style>' . $selector . '{box-shadow:' . $css . ' !important;}</style>';
                            break;
                        }
                    },
                    10
                );
            }

            if ( in_array( 'plus_global_gradient_color', $extras_elements, true ) ) {
                add_action(
                    'elementor/controls/register',
                    function ( $controls_manager ) {
                        $global_path = L_THEPLUS_PATH . 'modules/extensions/global-control/class-tp-global-gradient-color-controller.php';
                        if ( ! class_exists( 'ThePlusAddons\\Elementor\\Gradient\\TP_Gradient_Global' ) && file_exists( $global_path ) ) {
                            include_once $global_path;
                        }
                        $group_path = L_THEPLUS_PATH . 'modules/extensions/global-control/class-tp-group-background.php';
                        if ( file_exists( $group_path ) ) {
                            include_once $group_path;
                        }
                        if ( class_exists( 'ThePlusAddons\\Elementor\\Gradient\\TP_Group_Control_Background' ) ) {
                            $controls_manager->add_group_control(
                                'background',
                                new ThePlusAddons\Elementor\Gradient\TP_Group_Control_Background()
                            );
                        }
                    }
                );

                add_action(
                    'elementor/frontend/before_render',
                    function ( $element ) {
                        if ( ! class_exists( 'ThePlusAddons\\Elementor\\Gradient\\TP_Gradient_Global' ) ) {
                            return;
                        }
                        $settings   = $element->get_settings();
                        $element_id = $element->get_id();

                        foreach ( $settings as $key => $value ) {
                            if ( empty( $value ) || substr( $key, -20 ) !== '_tp_gg_global_preset' ) {
                                continue;
                            }

                            $css = ThePlusAddons\Elementor\Gradient\TP_Gradient_Global::get_preset_css( $value );
                            if ( ! $css ) {
                                break;
                            }

                            $base_name    = substr( $key, 0, -20 );
                            $selector_key = $base_name . '_' . $base_name . '_tp_gg_selector_store';

                            $selector_tmpl = ! empty( $settings[ $selector_key ] ) ? $settings[ $selector_key ] : '{{WRAPPER}}';

                            $wrapper_class = '.elementor-element-' . $element_id;
                            $selector      = str_replace( '{{WRAPPER}}', $wrapper_class, $selector_tmpl );

                            echo '<style>' . $selector . '{background-image:' . $css . ' !important;}</style>';
                            break;
                        }
                    },
                    10
                );
            }

            if ( in_array( 'plus_global_dimensions', $extras_elements, true ) ) {
                add_action(
                    'elementor/controls/register',
                    function ( $controls_manager ) {
                        $global_path = L_THEPLUS_PATH . 'modules/extensions/global-control/class-tp-global-dimensions-controller.php';
                        if ( ! class_exists( 'ThePlusAddons\\Elementor\\Dimensions\\TP_Dimensions_Global' ) && file_exists( $global_path ) ) {
                            include_once $global_path;
                        }

                        $has_global_dimensions = false;
                        if ( class_exists( 'ThePlusAddons\\Elementor\\Dimensions\\TP_Dimensions_Global' ) ) {
                            $global_dimensions = \ThePlusAddons\Elementor\Dimensions\TP_Dimensions_Global::get_global_dimensions_list();
                            if ( ! empty( $global_dimensions ) ) {
                                $has_global_dimensions = true;
                            }
                        }

                        if ( $has_global_dimensions ) {
                            $control_path = L_THEPLUS_PATH . 'modules/extensions/global-control/class-tp-control-dimensions.php';
                            if ( file_exists( $control_path ) ) {
                                include_once $control_path;
                            }

                            if ( class_exists( 'ThePlusAddons\\Elementor\\Dimensions\\TP_Control_Dimensions' ) ) {
                                $controls_manager->unregister( 'dimensions' );
                                $controls_manager->register( new ThePlusAddons\Elementor\Dimensions\TP_Control_Dimensions(), 'dimensions' );
                            }
                        }
                    },
                    100
                );
            }
        }

        /**
         * Register setting tabs.
         * 
         * @since v6.5.0.0
         */
        public function register_setting_tabs( $tabs_manager ) {
            $theplus_options = get_option( 'theplus_options' );
			$extras_elements = ! empty( $theplus_options['extras_elements'] ) ? $theplus_options['extras_elements'] : array();

			$available_tabs = array(
				'plus_global_box_shadow'     => array(
					'file'  => 'modules/extensions/global-control/class-tp-global-box-shadow-controller.php',
					'key'   => 'tp-box-shadow-global',
					'class' => 'ThePlusAddons\Elementor\BoxShadow\TP_Box_Shadow_Global',
				),
				'plus_global_gradient_color' => array(
					'file'  => 'modules/extensions/global-control/class-tp-global-gradient-color-controller.php',
					'key'   => 'tp-global-gradient-color',
					'class' => 'ThePlusAddons\Elementor\Gradient\TP_Gradient_Global',
				),
				'plus_global_dimensions' => array(
					'file'  => 'modules/extensions/global-control/class-tp-global-dimensions-controller.php',
					'key'   => 'tp-global-dimensions',
					'class' => 'ThePlusAddons\Elementor\Dimensions\TP_Dimensions_Global',
				),
				'plus_global_button' => array(
					'file'  => 'modules/extensions/global-control/class-tp-global-button-style-controller.php',
					'key'   => 'tp-global-button-styles',
					'class' => 'ThePlusAddons\Elementor\ButtonStyle\TP_Button_Style_Global',
				),
			);

			$styles_tabs = array();

			foreach ( $available_tabs as $feature_key => $tab ) {
				if ( in_array( $feature_key, $extras_elements, true ) ) {
					$styles_tabs[] = $tab;
				}
			}

			foreach ( $styles_tabs as $tab ) {

                $path = L_THEPLUS_PATH . $tab['file'];

                if ( file_exists( $path ) ) {
                    include_once $path;
                }

                if ( class_exists( $tab['class'] ) ) {
                    $tabs_manager->register_tab( $tab['key'], $tab['class'] );
                }
			}
        }

        /**
         * Get the computed global box shadow rule for an element.
         *
         * @since v6.5.0
         *
         * @param array  $settings         Element settings.
         * @param string $wrapper_selector Wrapper selector replacement.
         * @return array
         */
        protected function get_global_box_shadow_rule( $settings, $wrapper_selector ) {
            if ( ! class_exists( 'ThePlusAddons\\Elementor\\BoxShadow\\TP_Box_Shadow_Global' ) ) {
                return array();
            }

            foreach ( $settings as $key => $value ) {
                if ( empty( $value ) || substr( $key, -20 ) !== '_tp_bs_global_preset' ) {
                    continue;
                }

                $css = ThePlusAddons\Elementor\BoxShadow\TP_Box_Shadow_Global::get_preset_css( $value );
                if ( ! $css ) {
                    break;
                }

                $base_name     = substr( $key, 0, -20 );
                $selector_key  = $base_name . '_' . $base_name . '_tp_bs_selector_store';
                $selector_tmpl = ! empty( $settings[ $selector_key ] ) ? $settings[ $selector_key ] : '{{WRAPPER}}';
                $selector      = str_replace( '{{WRAPPER}}', $wrapper_selector, $selector_tmpl );

                return array(
                    'selector' => $selector,
                    'css'      => $css,
                );
            }

            return array();
        }

        /**
         * Render global box shadow preset CSS on frontend output.
         *
         * @since v6.5.0
         *
         * @param object $element Elementor element instance.
         * @return void
         */
        public function render_global_box_shadow_css( $element ) {
            $settings = $element->get_settings();
            $rule     = $this->get_global_box_shadow_rule( $settings, '.elementor-element-' . $element->get_id() );

            if ( empty( $rule['selector'] ) || empty( $rule['css'] ) ) {
                return;
            }

            echo '<style>' . $rule['selector'] . '{box-shadow:' . $rule['css'] . ' !important;}</style>';
        }

        /**
         * Add global box shadow preset CSS to Elementor generated stylesheets.
         *
         * This keeps the preset visible inside the editor preview as well.
         *
         * @since v6.5.0
         *
         * @param object $post_css Elementor CSS file object.
         * @param object $element  Elementor element instance.
         * @return void
         */
        public function parse_global_box_shadow_css( $post_css, $element ) {
            if ( ! method_exists( $post_css, 'get_stylesheet' ) || ! method_exists( $post_css, 'get_element_unique_selector' ) ) {
                return;
            }

            $settings = $element->get_settings();
            $rule     = $this->get_global_box_shadow_rule( $settings, $post_css->get_element_unique_selector( $element ) );

            if ( empty( $rule['selector'] ) || empty( $rule['css'] ) ) {
                return;
            }

            $post_css->get_stylesheet()->add_raw_css( $rule['selector'] . '{box-shadow:' . $rule['css'] . ' !important;}' );
        }
    }
}

new Tpae_Global_Controllers_Main();
