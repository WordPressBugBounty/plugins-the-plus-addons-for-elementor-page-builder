<?php
/**
 * Black Friday 2026 sale admin notice.
 *
 * Same format as the 2025 Black Friday notice (6.4.2): an image, a title, a line
 * of text, four feature bullets and one button. Free sites get the Pro upgrade
 * offer; Pro sites without a lifetime licence get the Lifetime upgrade offer.
 *
 * The dismissal is stored under a new option. The 2025 option
 * (tpae_bfsale_notice_dismissed) is still cleared by class-tp-notices-remove.php,
 * so reusing it would bring the notice back after every cleanup run.
 *
 * @link       https://posimyth.com/
 * @since      6.6.1
 *
 * @package    Theplus
 * @subpackage ThePlus/Notices
 * */

namespace Tp\Notices\TPAEBlackfridayBanner;

/**
 * Exit if accessed directly.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tpae_Blackfriday_Banner' ) ) {

	/**
	 * Black Friday sale notice.
	 *
	 * @since 6.6.1
	 */
	class Tpae_Blackfriday_Banner {

		/**
		 * Instance
		 *
		 * @since 6.6.1
		 * @var instance of the class.
		 */
		private static $instance = null;

		/**
		 * Option holding whether this notice was dismissed.
		 *
		 * @since 6.6.1
		 * @var string
		 */
		const OPTION = 'tpae_bfsale_2026_notice_dismissed';

		/**
		 * Last day the notice is shown (site time). After it the notice stays
		 * hidden even on sites that never update the plugin.
		 *
		 * @since 6.6.1
		 * @var string
		 */
		const SALE_END = '2026-12-03';

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 6.6.1
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
		 * @since 6.6.1
		 */
		public function __construct() {
			add_action( 'wp_ajax_tpae_blackfriday_dismiss_notice', array( $this, 'tpae_blackfriday_dismiss_notice' ) );

			if ( ! get_option( self::OPTION ) && $this->tpae_sale_running() ) {
				add_action( 'in_admin_header', array( $this, 'tpae_claim_promo_slot' ), -1 );
				add_action( 'admin_notices', tpae_promo_notice( 'bf-sale', array( $this, 'tpae_bf_sale_banner' ) ) );
			}
		}

		/**
		 * Take the one-promo-per-screen slot before the other promos.
		 *
		 * The UiChemy promo renders on in_admin_header (priority 0), before admin_notices,
		 * so without this it would always win the slot and the sale would never show on
		 * Free sites. Claimed only on screens where the sale notice renders.
		 *
		 * @since 6.6.1
		 */
		public function tpae_claim_promo_slot() {
			if ( tpae_promo_notice_allowed( 'bf-sale' ) && $this->tpae_screen_allowed() ) {
				tpae_promo_notice_slot( 'bf-sale' );
			}
		}

		/**
		 * Whether the current admin screen is one the notice shows on.
		 *
		 * @since 6.6.1
		 *
		 * @return bool
		 */
		private function tpae_screen_allowed() {
			$screen = get_current_screen();
			if ( ! $screen ) {
				return false;
			}

			$allowed_parents = array( 'index', 'elementor', 'themes', 'edit', 'plugins' );

			$et_plugin_status = apply_filters( 'tpae_get_plugin_status', 'template-kit-import/template-kit-import.php' );

			if ( get_option( 'tpae_onbording_end' ) || 'not_installed' !== $et_plugin_status ) {
				$allowed_parents[] = 'theplus_welcome_page';
			}

			$parent_base = $screen->parent_base;

			/*
			 * On in_admin_header the screen has no parent yet: admin-header.php calls
			 * set_parentage() after that hook. Work it out from $parent_file the same way.
			 */
			if ( empty( $parent_base ) && ! empty( $GLOBALS['parent_file'] ) ) {
				$parent_base = str_replace( '.php', '', strtok( $GLOBALS['parent_file'], '?' ) );
			}

			return ! empty( $parent_base ) && in_array( $parent_base, $allowed_parents, true );
		}

		/**
		 * Whether the sale is still running.
		 *
		 * @since 6.6.1
		 *
		 * @return bool
		 */
		private function tpae_sale_running() {
			return current_time( 'Y-m-d' ) <= self::SALE_END;
		}

		/**
		 * Render the notice.
		 *
		 * @since 6.6.1
		 */
		public function tpae_bf_sale_banner() {

			if ( ! $this->tpae_screen_allowed() ) {
				return;
			}

			$nonce = wp_create_nonce( 'tpae-blackfriday-banner' );

			$notice_text = __( 'Skip Multiple Plugin Subscriptions. Upgrade To The Plus Addons For Elementor Pro At 40% OFF', 'tpebl' );
			$desc_text   = __( 'Our Black Friday sale is live! Upgrade this season and get up to 40% OFF on the Pro version.', 'tpebl' );
			$btn_text    = __( 'Get offer', 'tpebl' );
			$utm         = 'utm_source=wpbackend&utm_medium=adminpanel&utm_campaign=bfsale2026';
			$btn_link    = 'https://theplusaddons.com/pricing/?' . $utm;

			if ( defined( 'THEPLUS_VERSION' ) ) {
				$license_data = get_option( 'tpaep_licence_data' );
				$license_key  = ! empty( $license_data['license_key'] ) ? $license_data['license_key'] : '';

				$notice_text = __( 'Black Friday 40% OFF, Upgrade To The Lifetime Unlimited Sites Plan And Save $120 Today', 'tpebl' );
				$desc_text   = __( 'Upgrade now to the Lifetime plan for unlimited sites, continuous plugin updates, lifetime premium support and much more at an unbeatable price.', 'tpebl' );
				$btn_text    = __( 'Upgrade now', 'tpebl' );

				if ( ! empty( $license_key ) ) {
					$btn_link = sprintf(
						'https://store.posimyth.com/checkout/?edd_action=sl_license_upgrade&license_id=%s&upgrade_id=5&discount=TPAE40BF26&%s',
						rawurlencode( $license_key ),
						$utm
					);
				}
			}

			$features = array(
				__( '1,000+ Elementor Templates', 'tpebl' ),
				__( '120+ Elementor Widgets', 'tpebl' ),
				__( 'Theme Builder for Elementor & Code Snippets', 'tpebl' ),
				__( 'Trusted by 180K+ Users', 'tpebl' ),
			);
			?>
			<div class="notice tpae-notice-show tpae-bf-banner is-dismissible" style="border:1px solid #E2E0F0;border-left:4px solid #DF241B;border-radius:6px;box-shadow:0 1px 2px rgba(16,16,32,0.05);">
				<div class="inline" style="display:flex;column-gap:12px;align-items:center;padding:15px 10px;position:relative;margin-left:0;">
					<img style="max-width:136px;max-height:136px;" src="<?php echo esc_url( L_THEPLUS_ASSETS_URL . 'images/bf-banner.png' ); ?>" alt="" />
					<div style="margin:0 10px;color:#000;display:flex;flex-direction:column;gap:10px;">
						<div style="font-size:16px;font-weight:700;letter-spacing:0.1px;"><?php echo esc_html( $notice_text ); ?></div>
						<div style="font-size:12px;color:#5D5D5D;"><?php echo esc_html( $desc_text ); ?></div>
						<div style="display:flex;flex-wrap:wrap;column-gap:12px;row-gap:4px;">
							<?php foreach ( $features as $feature ) { ?>
								<span>&bull; <?php echo esc_html( $feature ); ?></span>
							<?php } ?>
						</div>
						<a href="<?php echo esc_url( $btn_link ); ?>" class="button tpae-bf-banner__btn" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $btn_text ); ?></a>
					</div>
				</div>
			</div>
			<style>
				.tpae-bf-banner .tpae-bf-banner__btn{width:max-content;color:#fff;border-color:#DF241B;background:#DF241B;padding:0 22px;min-height:36px;line-height:34px;font-size:14px;border-radius:5px;font-weight:500;margin-top:3px;}
				.tpae-bf-banner .tpae-bf-banner__btn:hover,.tpae-bf-banner .tpae-bf-banner__btn:focus{color:#fff;border-color:#B91D15;background:#B91D15;}
			</style>
			<script>
				jQuery( document ).on( 'click', '.tpae-bf-banner.tpae-notice-show .notice-dismiss', function( e ) {
					e.preventDefault();

					jQuery.ajax( {
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'tpae_blackfriday_dismiss_notice',
							security: '<?php echo esc_js( $nonce ); ?>',
						},
						success: function() {
							jQuery( '.tpae-bf-banner' ).hide();
						}
					} );
				} );
			</script>
			<?php
		}

		/**
		 * Save the dismissal of the Black Friday notice.
		 *
		 * @since 6.6.1
		 */
		public function tpae_blackfriday_dismiss_notice() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( ! $get_security || ! wp_verify_nonce( $get_security, 'tpae-blackfriday-banner' ) ) {
				wp_send_json_error( 'Security check failed!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'tpebl' ) );
			}

			update_option( self::OPTION, true, false );

			wp_send_json_success();
		}
	}

	Tpae_Blackfriday_Banner::instance();
}
