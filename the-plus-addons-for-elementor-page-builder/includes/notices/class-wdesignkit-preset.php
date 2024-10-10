<?php
/**
 * It is Main File to load all Notice, Upgrade Menu and all
 *
 * @link       https://posimyth.com/
 * @since      5.6.3
 *
 * @package    Theplus
 * @subpackage ThePlus/Notices
 * */

namespace Theplus\Notices;

/**
 * Exit if accessed directly.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tp_wdkit_preview_popup' ) ) {

	/**
	 * This class used for Wdesign-kit releted
	 *
	 * @since 5.6.3
	 */
	class Tp_wdkit_preview_popup {

		/**
		 * Instance
		 *
		 * @since 5.6.3
		 * @static
		 * @var instance of the class.
		 */
		private static $instance = null;

		/**
		 * White label Option
		 *
		 * @var string
		 */
		public $whitelabel = '';

		/**
		 * White label Option
		 *
		 * @var string
		 */
		public $hidden_label = '';

		/**
		 * Instance
		 *
		 * @since 5.5.6
		 * @var w_d_s_i_g_n_k_i_t_slug
		 */
		public $w_d_s_i_g_n_k_i_t_slug = 'wdesignkit/wdesignkit.php';

		/**
		 * It is store wp_options table with name tp_wdkit_preview_popup
		 *
		 * @since 5.5.6
		 * @var db_preview_popup_key
		 */
		public $db_preview_popup_key = 'tp_wdkit_preview_popup';

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 5.6.3
		 * @static
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
		 * @since 5.6.3
		 */
		public function __construct() {

			add_action( 'elementor/editor/footer', array( $this, 'tp_wdkit_button_html_popup' ) );

			if ( class_exists( '\Elementor\Plugin' ) ) {
				add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'wdkit_elementor_editor_style' ) );

				add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'wdkit_button_demo' ) );
			}
			
		}

		/**
		 * Loded Wdesignkit Template Js
		 *
		 * @since 5.6.3
		 */
		public function wdkit_button_demo() {
			wp_enqueue_script( 'tp-wdkit-button', L_THEPLUS_URL . 'assets/js/wdesignkit/tp-wdkit-button.js', array( 'jquery', 'wp-i18n' ), L_THEPLUS_VERSION, true );
		}

		/**
		 * Loded Wdesignkit Template CSS
		 *
		 * @since 5.6.3
		 */
		public function wdkit_elementor_editor_style() {
			wp_enqueue_style( 'tp-wdkit-elementor-popup', L_THEPLUS_URL . 'assets/css/wdesignkit/tp-preset-widget.css', array(), L_THEPLUS_VERSION );
		}

		public function tp_wdkit_button_html_popup() { ?>
			<div id="tp-wdkit-wrap-button" class="tp-main-container-button">
	
			</div>

			<?php
		}
	}

	Tp_wdkit_preview_popup::instance();
}