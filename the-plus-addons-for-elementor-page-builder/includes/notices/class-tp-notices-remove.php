<?php
/**
 * This is use for Remove Databsh Entry
 *
 * @link       https://posimyth.com/
 * @since      5.3.3
 *
 * @package    Theplus
 * @subpackage ThePlus/Notices
 * */

namespace Tp\Notices\Remove;

/**
 * Exit if accessed directly.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tp_Notices_Remove' ) ) {

	/**
	 * This class used for only load All Notice Files
	 *
	 * @since 5.3.3
	 */
	class Tp_Notices_Remove {

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
		 * @since 5.3.3
		 * @access public
		 */
		public function __construct() {
			$this->tp_delete_removed_notice_keys();
		}

		/**
		 * Delete notice keys which notice removed from plugin
		 *
		 * @since 6.5.7
		 */
		public function tp_delete_removed_notice_keys() {
			delete_option( 'tpae_bfsale_notice_dismissed' );
			delete_option( 'tpae_cmsale_notice_dismissed' );
			delete_option( 'tpae_wintersale_notice_dismissed' );
			delete_option( 'tpae_pluginfeatures_notice_dismissed' );

			/* 6.6.0: the Nexter Blocks, Nexter Extension and Join Community notices were removed. */
			delete_option( 'tpae_nexter_block_notice' );
			delete_option( 'tpae_nexter_extension_notice' );
			delete_option( 'tpae_nxt_ext_pnotice' );
			delete_option( 'tpae_join_community_notice' );

			/* 6.6.0: the 6.5.0 Widget Changes (removed/deprecated widgets) notice was removed. */
			delete_option( 'tpae_removed_widgets_notice' );
		}
	}

	Tp_Notices_Remove::instance();
}
