<?php
/**
 * Exit if accessed directly.
 *
 * @link       https://posimyth.com/
 * @since      5.3.3
 *
 * @package    Theplus
 * @subpackage ThePlus/Notices
 * */

namespace Tp\Notices\WidgetNotice;

/**
 * Exit if accessed directly.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tp_Widget_Notice' ) ) {

	/**
	 * This class used for only load widget notice
	 *
	 * @since 5.3.3
	 */
	class Tp_Widget_Notice {

		/**
		 * Instance
		 *
		 * @since 5.3.3
		 * @access private
		 * @static
		 * @var instance of the class.
		 */
		private static $instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 5.3.3
		 * @access public
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
		 * @since 5.2.3
		 * @version 6.6.0
		 */
		public function __construct() {
			if ( defined( 'THEPLUS_VERSION' ) && defined( 'L_THEPLUS_MINIMUM_PRO_VERSION' ) && version_compare( THEPLUS_VERSION, L_THEPLUS_MINIMUM_PRO_VERSION, '<' ) ) {
				add_action( 'admin_notices', array( $this, 'tpae_pro_outdated_notice' ) );
			}
		}

		/**
		 * Pro is older than L_THEPLUS_MINIMUM_PRO_VERSION: one "Update Pro" notice.
		 *
		 * Replaces the three notices for Pro below 5.5.3, 6.0.0 and 6.1.0, which all showed
		 * at once on an old enough Pro.
		 *
		 * @since 6.6.0
		 */
		public function tpae_pro_outdated_notice() {

			if ( ! current_user_can( 'update_plugins' ) ) {
				return;
			}

			echo '<div class="notice notice-error tpae-notice-show" style="border-left-color: #6660EF;">
				<div class="tp-notice-wrap" style="display: flex; column-gap: 12px; align-items: flex-start; padding: 15px 10px; position: relative; margin-left: 0;">
					<div style="margin: 0; color: #000;">
						<h3 style="margin: 10px 0 7px;">' . esc_html__( 'Update The Plus Addons for Elementor Pro', 'tpebl' ) . '</h3>
						<p>'
							/* translators: 1: installed Pro version, 2: minimum Pro version. */
							. sprintf( esc_html__( 'You have Pro %1$s. This version of The Plus Addons for Elementor needs Pro %2$s or newer to work correctly.', 'tpebl' ), esc_html( THEPLUS_VERSION ), esc_html( L_THEPLUS_MINIMUM_PRO_VERSION ) )
						. '</p>
						<div class="tp-tpae-button" style="margin-top: 10px;">
							<a href="' . esc_url( self_admin_url( 'plugins.php' ) ) . '" class="button button-primary">' . esc_html__( 'Update Pro', 'tpebl' ) . '</a>
						</div>
					</div>
				</div>
			</div>';
		}
	}

	Tp_Widget_Notice::instance();
}
