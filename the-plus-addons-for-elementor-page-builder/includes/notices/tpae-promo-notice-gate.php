<?php
/**
 * Promotional admin notices take turns.
 *
 * Cross-promotions (UiChemy, Pro, Widget Recipes) and the review ask used to stack with
 * others, four deep at the top of the WordPress Dashboard on a client's site. Each one
 * still decides for itself whether it is due; this only decides whether it may print:
 * at most one per screen, and none on the WordPress Dashboard, the first screen site
 * owners and their clients see. Sites running Pro - paid, and often an agency's client
 * sites - get no cross-promotion at all; only the review ask remains there. Release,
 * licence and compatibility notices are not gated.
 *
 * @link       https://posimyth.com/
 * @since      6.6.0
 *
 * @package    the-plus-addons-for-elementor-page-builder
 */

/**Exit if accessed directly.*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'tpae_promo_notice_allowed' ) ) {

	/**
	 * Whether the promotion $id may show on this site at all. With Pro active only the
	 * review ask is left; UiChemy and Widget Recipes are not.
	 *
	 * @since 6.6.0
	 *
	 * @param string $id Promotion id.
	 * @return bool
	 */
	function tpae_promo_notice_allowed( $id ) {
		return ! defined( 'THEPLUS_VERSION' ) || 'ask-review' === $id;
	}
}

if ( ! function_exists( 'tpae_promo_notice_slot' ) ) {

	/**
	 * Whether the promotion $id may print on this screen. The first one to ask holds
	 * the slot for the rest of the request.
	 *
	 * @since 6.6.0
	 *
	 * @param string $id Promotion id.
	 * @return bool
	 */
	function tpae_promo_notice_slot( $id ) {
		static $owner = null;

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( $screen && 'dashboard' === $screen->id ) {
			return false;
		}

		if ( null === $owner ) {
			$owner = $id;
		}

		return $owner === $id;
	}
}

if ( ! function_exists( 'tpae_promo_notice' ) ) {

	/**
	 * Wrap a promotion's render callback so it prints only when it holds the slot. The
	 * callback runs as before; its output is kept or dropped as a whole, so a notice that
	 * is not due prints nothing and does not take the slot.
	 *
	 * @since 6.6.0
	 *
	 * @param string   $id       Promotion id.
	 * @param callable $callback The notice's render callback.
	 * @return Closure
	 */
	function tpae_promo_notice( $id, $callback ) {
		return function () use ( $id, $callback ) {
			if ( ! tpae_promo_notice_allowed( $id ) ) {
				return;
			}

			ob_start();
			call_user_func( $callback );
			$html = ob_get_clean();

			if ( '' !== trim( (string) $html ) && tpae_promo_notice_slot( $id ) ) {
				echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the notice's own markup, escaped where it is built.
			}
		};
	}
}
