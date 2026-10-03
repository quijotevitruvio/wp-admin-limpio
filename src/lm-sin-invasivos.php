<?php
/**
 * Plugin Name: WP Sin invasivos
 * Description: Removes the Google Customer Reviews opt-in (rating survey) from the WooCommerce "order received" page, if another plugin registers it. Example of a late remove_action.
 * Version: 1.0.0
 * Author: Andrés Valencia Tobón
 * Author URI: https://github.com/quijotevitruvio
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * LinkedIn: https://www.linkedin.com/in/andr%C3%A9s-valencia-tob%C3%B3n/
 * Donate link: https://paypal.me/bibliotecologo
 * Text Domain: wp-admin-limpio
 *
 * Developed for librosmedellin.com.
 *
 * What it removes: the callback named by the filter 'lm_sin_invasivos_callbacks' (default: lm_gcr_render_optin)
 *   from the 'woocommerce_thankyou' action. Without a plugin registering it, it does nothing.
 * Switch off: wp option update lm_sin_invasivos_off 1   Undo: wp option delete lm_sin_invasivos_off
 */

defined( 'ABSPATH' ) || exit;

function lm_sin_invasivos_activo() {
	return '1' !== (string) get_option( 'lm_sin_invasivos_off', '0' );
}

// Removed at several moments: the other plugin registers on load, but in case something
// re-adds it later this repeats on init and template_redirect.
foreach ( array( 'init', 'template_redirect' ) as $lm_si_hook ) {
	add_action( $lm_si_hook, function () {
		if ( ! lm_sin_invasivos_activo() ) {
			return;
		}
		$callbacks = (array) apply_filters( 'lm_sin_invasivos_callbacks', array( 'lm_gcr_render_optin' ) );
		foreach ( $callbacks as $cb ) {
			if ( ! is_string( $cb ) || '' === $cb ) {
				continue;
			}
			remove_action( 'woocommerce_thankyou', $cb, 5 );
			remove_action( 'woocommerce_thankyou', $cb );
		}
	}, 99 );
}
