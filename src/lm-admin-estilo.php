<?php
/**
 * Plugin Name: WP Admin estilo
 * Description: Minimalist shared stylesheet (white background, thin cards, brand-colour primary button) only for the settings screens of your own plugins.
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
 * Which screens: those whose page= URL parameter starts with a prefix returned by the filter
 *   'lm_admin_estilo_prefijos' (default: empty = styles nothing) or contains a fragment returned by
 *   'lm_admin_estilo_contiene' (default: empty). Slugs matching the exclusion list are never styled
 *   (filter 'lm_admin_estilo_excluir', default: payment/order-like words).
 * Colours: filters 'lm_admin_estilo_color_primario' (default #2271b1), 'lm_admin_estilo_color_primario_hover'
 *   (default #135e96) and 'lm_admin_estilo_color_texto' (default #1f2937), or the constants
 *   LM_ADMIN_ESTILO_PRIMARIO / _HOVER / _TEXTO.
 * Switch off: wp option update lm_admin_estilo_off 1   Undo: wp option delete lm_admin_estilo_off
 */

defined( 'ABSPATH' ) || exit;

/** Valid CSS colour (hex only), else the fallback. */
function lm_admin_estilo_color( $valor, $defecto ) {
	return ( is_string( $valor ) && preg_match( '/^#[0-9a-fA-F]{3,8}$/', $valor ) ) ? $valor : $defecto;
}

/** Is the current screen one of your own plugins? Looks at page= and that the screen hook contains it. */
function lm_admin_estilo_es_propia() {
	global $hook_suffix;
	if ( ! is_admin() || get_option( 'lm_admin_estilo_off' ) ) {
		return false;
	}
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( '' === $page || empty( $hook_suffix ) || false === strpos( (string) $hook_suffix, $page ) ) {
		return false;
	}
	$excluir = (array) apply_filters( 'lm_admin_estilo_excluir', array( 'bacs', 'transferencia', 'pedido', 'cobro', 'checkout', 'onhold', 'order', 'payment' ) );
	foreach ( $excluir as $e ) {
		if ( '' !== (string) $e && false !== strpos( $page, (string) $e ) ) {
			return false;
		}
	}
	$prefijos = (array) apply_filters( 'lm_admin_estilo_prefijos', array() );
	foreach ( $prefijos as $p ) {
		if ( '' !== (string) $p && 0 === strpos( $page, sanitize_key( (string) $p ) ) ) {
			return true;
		}
	}
	$contiene = (array) apply_filters( 'lm_admin_estilo_contiene', array() );
	foreach ( $contiene as $c ) {
		if ( '' !== (string) $c && false !== strpos( $page, sanitize_key( (string) $c ) ) ) {
			return true;
		}
	}
	return false;
}

add_filter( 'admin_body_class', function ( $clases ) {
	return lm_admin_estilo_es_propia() ? $clases . ' lm-admin' : $clases;
} );

add_action( 'admin_head', function () {
	if ( ! lm_admin_estilo_es_propia() ) {
		return;
	}
	$prim  = lm_admin_estilo_color( apply_filters( 'lm_admin_estilo_color_primario', defined( 'LM_ADMIN_ESTILO_PRIMARIO' ) ? LM_ADMIN_ESTILO_PRIMARIO : '#2271b1' ), '#2271b1' );
	$hover = lm_admin_estilo_color( apply_filters( 'lm_admin_estilo_color_primario_hover', defined( 'LM_ADMIN_ESTILO_HOVER' ) ? LM_ADMIN_ESTILO_HOVER : '#135e96' ), '#135e96' );
	$texto = lm_admin_estilo_color( apply_filters( 'lm_admin_estilo_color_texto', defined( 'LM_ADMIN_ESTILO_TEXTO' ) ? LM_ADMIN_ESTILO_TEXTO : '#1f2937' ), '#1f2937' );
	?>
<style id="lm-admin-estilo">
body.lm-admin{--lm-primario:<?php echo $prim; ?>;--lm-hover:<?php echo $hover; ?>;--lm-texto:<?php echo $texto; ?>}
body.lm-admin,body.lm-admin #wpcontent,body.lm-admin #wpbody{background:#fff}
body.lm-admin #wpbody-content .wrap{font-family:system-ui,-apple-system,"Segoe UI",Manrope,Roboto,sans-serif;color:var(--lm-texto);max-width:1100px;line-height:1.5}
body.lm-admin .wrap h1,body.lm-admin .wrap h2,body.lm-admin .wrap h3,body.lm-admin .wrap h4{color:var(--lm-texto);font-weight:700}
body.lm-admin .wrap h1{font-size:1.6em;margin-bottom:12px}
body.lm-admin .wrap p.description,body.lm-admin .wrap .description,body.lm-admin .wrap .form-table td p,body.lm-admin .wrap small{color:#4b5563;font-size:13px;line-height:1.55}
body.lm-admin .wrap .card,body.lm-admin .wrap .postbox,body.lm-admin .wrap .stuffbox{background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:none;max-width:none}
body.lm-admin .wrap .card{padding:16px 20px}
body.lm-admin .wrap .postbox .hndle,body.lm-admin .wrap .postbox-header{border-bottom:1px solid #e5e7eb}
body.lm-admin .wrap .form-table th{color:var(--lm-texto);font-weight:600}
body.lm-admin .wrap .widefat{border:1px solid #e5e7eb;border-radius:10px;box-shadow:none}
body.lm-admin .wrap .widefat thead th,body.lm-admin .wrap .widefat tfoot th{color:var(--lm-texto);border-color:#e5e7eb}
body.lm-admin .wrap .widefat td,body.lm-admin .wrap .widefat th{border-color:#e5e7eb}
body.lm-admin .wrap .striped>tbody>:nth-child(odd){background:#f9fafb}
body.lm-admin .wrap input[type=text],body.lm-admin .wrap input[type=number],body.lm-admin .wrap input[type=url],
body.lm-admin .wrap input[type=email],body.lm-admin .wrap input[type=password],body.lm-admin .wrap input[type=search],
body.lm-admin .wrap select,body.lm-admin .wrap textarea{border:1px solid #d1d5db;border-radius:8px;box-shadow:none;color:var(--lm-texto)}
body.lm-admin .wrap input:focus,body.lm-admin .wrap select:focus,body.lm-admin .wrap textarea:focus{border-color:var(--lm-primario);box-shadow:0 0 0 1px var(--lm-primario);outline:0}
body.lm-admin .wrap .button,body.lm-admin .wrap .button-secondary{border:1px solid #d1d5db;border-radius:10px;color:var(--lm-texto);background:#fff;box-shadow:none}
body.lm-admin .wrap .button:hover{border-color:#9ca3af;background:#f9fafb;color:var(--lm-texto)}
body.lm-admin .wrap .button-primary,body.lm-admin .wrap .button.button-primary{background:var(--lm-primario);border-color:var(--lm-primario);color:#fff;border-radius:10px;font-weight:700;box-shadow:none;text-shadow:none}
body.lm-admin .wrap .button-primary:hover,body.lm-admin .wrap .button-primary:focus{background:var(--lm-hover);border-color:var(--lm-hover);color:#fff}
body.lm-admin .wrap .nav-tab-wrapper{border-bottom:1px solid #e5e7eb}
body.lm-admin .wrap .nav-tab{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px 10px 0 0;color:#4b5563}
body.lm-admin .wrap .nav-tab-active,body.lm-admin .wrap .nav-tab-active:hover{background:#fff;color:var(--lm-texto);border-bottom-color:#fff}
body.lm-admin .wrap .notice{border-radius:10px;box-shadow:none}
</style>
	<?php
} );
