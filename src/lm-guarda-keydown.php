<?php
/**
 * Plugin Name: WP Guarda keydown
 * Description: Works around the "e.target.matches is not a function" error of the Save button in Pinterest for WooCommerce: stops only keydown events that arrive without an element target.
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
 * Seen with Pinterest for WooCommerce 1.5.1; check whether your version already fixed it and delete this file if so.
 * Real key presses are untouched (their target is an Element). Adds one <script id="lm-guarda-keydown"> at the top of <head>.
 * Switch off: wp option update lm_guarda_keydown_off 1   Undo: wp option delete lm_guarda_keydown_off (or delete the file).
 * After changing it, purge your page cache.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'lm_guarda_keydown_script', 0 );
function lm_guarda_keydown_script() {
	if ( '1' === (string) get_option( 'lm_guarda_keydown_off', '0' ) ) {
		return;
	}
	echo '<script id="lm-guarda-keydown">window.addEventListener("keydown",function(e){if(!(e.target instanceof Element))e.stopImmediatePropagation();},true);</script>';
}
