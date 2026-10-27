<?php
/**
 * "What's new in this release" admin notice.
 *
 * Shown once per release line. The dismissal stores the release version rather
 * than a boolean, so the next release shows its own notice without needing a new
 * option key -- bump nothing, just ship.
 *
 * @link       https://posimyth.com/
 * @since      6.5.0
 *
 * @package    Theplus
 * @subpackage ThePlus/Notices
 * */

namespace Tp\Notices\WhatsNew;

/**
 * Exit if accessed directly.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tp_Whats_New_Notice' ) ) {

	/**
	 * Release highlights notice.
	 *
	 * @since 6.5.0
	 */
	class Tp_Whats_New_Notice {

		/**
		 * Instance
		 *
		 * @since 6.5.0
		 * @static
		 * @var instance of the class.
		 */
		private static $instance = null;

		/**
		 * Option holding the release whose notice was last dismissed.
		 *
		 * @since 6.5.0
		 * @var string
		 */
		const OPTION = 'tpae_whats_new_dismissed';

		/**
		 * Option holding the release we first saw, when, and whether the site was
		 * updated to it, as "release|timestamp|updated" (updated is 1 or 0). Stamps
		 * written before 6.6.0 have only the first two parts.
		 *
		 * @since 6.5.0
		 * @var string
		 */
		const OPTION_SEEN = 'tpae_whats_new_seen';

		/**
		 * Hours to hold the notice back after a release first appears.
		 *
		 * The notice waits two days after a release first appears. That keeps it off
		 * the screen right after the update and gives the admin a quiet first look.
		 *
		 * @since 6.5.0
		 * @var int
		 */
		const DELAY_HOURS = 48;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 6.5.0
		 *
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
		 * @since 6.5.0
		 */
		public function __construct() {
			add_action( 'wp_ajax_theplus_whats_new_notice_dismiss', array( $this, 'theplus_whats_new_notice_dismiss' ) );

			if ( $this->tp_release() !== get_option( self::OPTION ) && $this->tp_delay_passed() && $this->tp_is_update() ) {
				add_action( 'admin_notices', array( $this, 'theplus_whats_new_notice' ) );
			}
		}

		/**
		 * Whether the hold-back window has elapsed for the current release.
		 *
		 * Deliberately NOT keyed off tpae_install_time, which the review and
		 * community notices use: that option is stamped once on first install and
		 * never again, so on an existing site it is already months old and the
		 * window would count as elapsed the instant the update lands. This needs
		 * the moment THIS release first appeared, so it stamps its own.
		 *
		 * Stores a Unix timestamp rather than the mysql datetime the sibling
		 * notices store. current_time( 'mysql' ) is site-local while time() is UTC,
		 * so comparing the two is off by the site's UTC offset. That is harmless
		 * across a 14 or 30 day wait but not across 48 hours.
		 *
		 * @since 6.5.0
		 *
		 * @return bool True once the window has passed.
		 */
		public function tp_delay_passed() {
			$release = $this->tp_release();

			if ( '' === $release ) {
				return false;
			}

			$seen  = get_option( self::OPTION_SEEN );
			$parts = is_string( $seen ) ? explode( '|', $seen, 3 ) : array();

			/** First admin load on this release: stamp it and hold the notice back. */
			if ( count( $parts ) < 2 || $parts[0] !== $release || ! is_numeric( $parts[1] ) ) {
				// A stamp for an earlier release means the site ran that release, so this is an update.
				$updated = ( count( $parts ) >= 2 && '' !== $parts[0] ) || $this->tp_installed_before( time() );

				update_option( self::OPTION_SEEN, $release . '|' . time() . '|' . ( $updated ? '1' : '0' ), false );

				return false;
			}

			return ( time() - (int) $parts[1] ) >= ( self::DELAY_HOURS * HOUR_IN_SECONDS );
		}

		/**
		 * Whether the site was updated to this release rather than installed on it.
		 *
		 * The notice describes what changed, which means nothing on a fresh install.
		 * Decided once, when the release is first stamped; stamps written before this
		 * check existed fall back to the install time alone.
		 *
		 * @since 6.6.0
		 *
		 * @return bool
		 */
		public function tp_is_update() {
			$seen  = get_option( self::OPTION_SEEN );
			$parts = is_string( $seen ) ? explode( '|', $seen, 3 ) : array();

			if ( count( $parts ) < 2 || ! is_numeric( $parts[1] ) ) {
				return false;
			}

			if ( isset( $parts[2] ) ) {
				return '1' === $parts[2];
			}

			return $this->tp_installed_before( (int) $parts[1] );
		}

		/**
		 * Whether TPAE was installed more than a day before $timestamp.
		 *
		 * tpae_install_time is site-local mysql time (see class-tp-notices-main.php),
		 * so it is converted to UTC before comparing with a Unix timestamp. A site
		 * without it, which includes every fresh install, counts as new.
		 *
		 * @since 6.6.0
		 *
		 * @param int $timestamp Unix timestamp.
		 * @return bool
		 */
		public function tp_installed_before( $timestamp ) {
			$installed = get_option( 'tpae_install_time' );

			if ( empty( $installed ) ) {
				return false;
			}

			$installed = (int) get_gmt_from_date( $installed, 'U' );

			return $installed > 0 && $installed < ( $timestamp - DAY_IN_SECONDS );
		}

		/**
		 * Current release line, without any beta or RC suffix.
		 *
		 * 6.5.0-beta.8 and 6.5.0 are the same release as far as this notice is
		 * concerned, so testers do not get it twice.
		 *
		 * @since 6.5.0
		 *
		 * @return string
		 */
		public function tp_release() {
			$version = defined( 'L_THEPLUS_VERSION' ) ? L_THEPLUS_VERSION : '';

			return (string) preg_replace( '/[-+].*$/', '', $version );
		}

		/**
		 * Render the notice.
		 *
		 * @since 6.5.0
		 */
		public function theplus_whats_new_notice() {

			$screen = get_current_screen();

			$allowed_parents = array( 'elementor', 'themes', 'edit', 'plugins', 'theplus_welcome_page' );

			$parent_base = ! empty( $screen->parent_base ) && in_array( $screen->parent_base, $allowed_parents, true );

			if ( ! $parent_base ) {
				return;
			}

			$nonce = wp_create_nonce( 'tpae-whats-new' );
			/**
			 * UTM follows the convention the other notices already use: source is always
			 * wpbackend, medium names the surface (adminpanel for an admin notice) and
			 * campaign names the subject. utm_content carries the release, so visits from
			 * each release's notice can be told apart. Unlike the other roadmap links, the
			 * changelog carries it too, because this notice is the one place we know why
			 * they came.
			 */
			$log_url  = add_query_arg(
				array(
					'utm_source'   => 'wpbackend',
					'utm_medium'   => 'adminpanel',
					'utm_campaign' => 'whatsnew',
					'utm_content'  => 'v' . $this->tp_release(),
				),
				'https://roadmap.theplusaddons.com/en/changelogs'
			);
			$logo_url = defined( 'L_THEPLUS_ASSETS_URL' ) ? L_THEPLUS_ASSETS_URL . 'svg/navbox/tpae-logomark.svg' : '';

			// The headline is the redesigned dashboard, so the one button opens it.
			$dashboard_url = admin_url( 'admin.php?page=theplus_welcome_page' );

			$this->tp_styles();
			?>
			<div class="notice tpae-wn is-dismissible" role="region" aria-label="<?php echo esc_attr__( 'What is new in The Plus Addons for Elementor', 'tpebl' ); ?>">

				<?php if ( $logo_url ) { ?>
					<span class="tpae-wn__mark">
						<img class="tpae-wn__logo" src="<?php echo esc_url( $logo_url ); ?>" alt="" width="40" height="40">
					</span>
				<?php } ?>

				<div class="tpae-wn__txt">
					<p class="tpae-wn__title">
						<?php echo esc_html__( 'Meet the New Dashboard', 'tpebl' ); ?>
					</p>
					<p class="tpae-wn__desc">
						<?php echo esc_html__( 'Widgets, extensions, settings and the Theme Builder, rebuilt to be clearer and quicker to use.', 'tpebl' ); ?>
					</p>
					<p class="tpae-wn__links">
						<a class="tpae-wn__btn" href="<?php echo esc_url( $dashboard_url ); ?>">
							<?php echo esc_html__( 'Open Dashboard', 'tpebl' ); ?>
						</a>
						<a class="tpae-wn__link" href="<?php echo esc_url( $log_url ); ?>" target="_blank" rel="noopener noreferrer">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h4"/></svg>
							<?php echo esc_html__( 'What else changed', 'tpebl' ); ?>
							<span class="screen-reader-text"><?php echo esc_html__( '(opens in a new tab)', 'tpebl' ); ?></span>
						</a>
					</p>
				</div>
			</div>
			<script>
				// WordPress fades and removes the notice itself; this only saves the dismissal.
				jQuery( document ).on( 'click', '.tpae-wn .notice-dismiss', function () {
					var d = new FormData();
					d.append( 'action', 'theplus_whats_new_notice_dismiss' );
					d.append( 'security', "<?php echo esc_js( $nonce ); ?>" );
					// keepalive: an admin who dismisses and moves straight on must not
					// cancel the request, or the notice is back on the next screen.
					fetch( ajaxurl, { method: 'POST', credentials: 'same-origin', body: d, keepalive: true } );
				} );
			</script>
			<?php
		}

		/**
		 * Scoped styles for the notice.
		 *
		 * Emitted inline and namespaced under .tpae-wn so nothing here can reach
		 * the rest of wp-admin, and so the notice never depends on a stylesheet
		 * that is gated off this screen.
		 *
		 * @since 6.5.0
		 */
		public function tp_styles() {
			?>
			<style>
			/* One card laid out like the other TPAE notices: logo, then heading, text and
			   a row with the one button and two text links, in the brand violet they use
			   for their left edge and button.
			   Qualified with div.notice so the card's own border and padding outrank
			   core's .notice and .notice.is-dismissible rules without !important. */
			div.notice.tpae-wn{position:relative;display:flex;align-items:flex-start;gap:16px;margin:12px 0 10px;padding:20px 52px 20px 18px;background:#fff;border:1px solid #e2e0f0;border-left:4px solid #6660EF;border-radius:6px;box-shadow:0 1px 2px rgba(16,16,32,.05);animation:tpaeWnIn .35s cubic-bezier(.21,1,.31,1) both}

			/* The logo on its own, top-aligned with the heading. */
			.tpae-wn__mark{flex:0 0 auto;align-self:flex-start;display:flex}
			.tpae-wn__logo{display:block;width:40px;height:40px;border-radius:9px}
			.tpae-wn__txt{flex:1 1 auto;min-width:0}

			/* Core .notice p adds its own margin and padding; reset at (0,2,1). */
			/* Heading matches the other TPAE notices' h3 (1.3em of 13px, wp-admin ink). */
			.tpae-wn p.tpae-wn__title{margin:0;padding:0;font-size:17px;font-weight:600;line-height:1.35;color:#1d2327}
			.tpae-wn p.tpae-wn__desc{margin:4px 0 14px;padding:0;font-size:13px;line-height:1.5;color:#3c434a}
			.tpae-wn p.tpae-wn__links{display:flex;flex-wrap:wrap;align-items:center;gap:10px 22px;margin:0;padding:0;font-size:13.5px;line-height:1.4}

			/* Core common.css styles `div.notice a` at (0,1,2), stronger than a single
			   class, and would force the theme link colour, an underline, its own focus
			   ring and border-radius:2px. Every rule below is qualified with the tag name
			   so it outranks core without needing !important. Each link leads with a
			   small icon in the brand colour; the text stays quiet until hovered. */
			.tpae-wn a.tpae-wn__link{display:inline-flex;align-items:center;gap:6px;color:#3c434a;font-weight:500;text-decoration:none;transition:color .15s ease}
			.tpae-wn a.tpae-wn__link svg{flex:0 0 auto;width:16px;height:16px;color:#6660EF}
			.tpae-wn a.tpae-wn__link:hover{color:#3b35a6;text-decoration:underline;text-underline-offset:3px}
			.tpae-wn a.tpae-wn__link:focus{color:#3b35a6;border-radius:3px;box-shadow:0 0 0 2px rgba(102,96,239,.45);outline:none}

			.tpae-wn a.tpae-wn__btn{flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;height:38px;padding:0 20px;border:0;border-radius:8px;background:#6660EF;color:#fff;font-size:13px;font-weight:500;line-height:1;text-decoration:none;white-space:nowrap;transition:background .15s ease}
			.tpae-wn a.tpae-wn__btn:hover{color:#fff;background:#564ed2}
			.tpae-wn a.tpae-wn__btn:focus{color:#fff;box-shadow:0 0 0 3px rgba(102,96,239,.45);outline:none}

			/* Keep core's dismiss button, centred on the card's top-right corner. */
			.tpae-wn .notice-dismiss{top:6px;right:4px}

			@keyframes tpaeWnIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:none}}

			@media screen and (max-width:782px){
				div.notice.tpae-wn{gap:14px;padding:18px 46px 18px 18px}
				.tpae-wn__logo{width:34px;height:34px;border-radius:8px}
			}

			@media (prefers-reduced-motion:reduce){
				div.notice.tpae-wn{animation:none}
				.tpae-wn a.tpae-wn__btn,.tpae-wn a.tpae-wn__link{transition:none}
			}
			</style>
			<?php
		}

		/**
		 * Store the dismissed release so the notice does not return until the
		 * next one.
		 *
		 * @since 6.5.0
		 */
		public function theplus_whats_new_notice_dismiss() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

			if ( empty( $get_security ) || ! wp_verify_nonce( $get_security, 'tpae-whats-new' ) ) {
				wp_send_json_error( __( 'Security check failed', 'tpebl' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to do this action', 'tpebl' ) );
			}

			update_option( self::OPTION, $this->tp_release(), false );

			wp_send_json_success();
		}
	}

	Tp_Whats_New_Notice::instance();
}
