# wp-admin-limpio

Three small, independent *mu-plugins* to keep WordPress tidier:

| File | What it does |
|---|---|
| `src/lm-admin-estilo.php` | A minimalist stylesheet (white background, thin cards, configurable primary-button colour) **only** for the settings screens of your own plugins |
| `src/lm-sin-invasivos.php` | Removes the Google Customer Reviews opt-in (the rating survey) from the "order received" page, if another plugin registers it |
| `src/lm-guarda-keydown.php` | Patch for the `e.target.matches is not a function` error of the *Save* button in Pinterest for WooCommerce |

## Who it is for
Anyone maintaining a WordPress site with several in-house plugins who wants consistent settings screens, no survey pop-ups, and no JavaScript console errors from the Pinterest button.

## Requirements
WordPress 6.x, PHP 7.4+. `lm-guarda-keydown.php` only matters with Pinterest for WooCommerce (seen with 1.5.1). `lm-sin-invasivos.php` only has an effect if another plugin registers `lm_gcr_render_optin` (or another function you name) on `woocommerce_thankyou`.

## Installation
Copy the files from `src/` (one or all three) to `wp-content/mu-plugins/`. Nothing to activate.

## Configuration
By default `lm-admin-estilo.php` **styles no screen at all**: you must tell it which ones are yours, e.g. in another mu-plugin or `functions.php`:

```php
add_filter( 'lm_admin_estilo_prefijos', fn() => array( 'myplugin-', 'my_other_' ) );
add_filter( 'lm_admin_estilo_contiene', fn() => array( 'finder' ) );
```

| Setting | Filter / constant / option | Default | What it does |
|---|---|---|---|
| Own `page=` prefixes | filter `lm_admin_estilo_prefijos` | empty list | Slugs starting with any of these get the style |
| `page=` fragments | filter `lm_admin_estilo_contiene` | empty list | Slugs containing any of these get the style |
| Exclusions | filter `lm_admin_estilo_excluir` | `bacs, transferencia, pedido, cobro, checkout, onhold, order, payment` | Slugs containing any of these **never** get the style |
| Primary colour | filter `lm_admin_estilo_color_primario` or constant `LM_ADMIN_ESTILO_PRIMARIO` | `#2271b1` | Primary button and field focus (hex only) |
| Primary hover colour | filter `lm_admin_estilo_color_primario_hover` or constant `LM_ADMIN_ESTILO_HOVER` | `#135e96` | Primary button hover |
| Text colour | filter `lm_admin_estilo_color_texto` or constant `LM_ADMIN_ESTILO_TEXTO` | `#1f2937` | Text and headings |
| Callbacks to remove | filter `lm_sin_invasivos_callbacks` | `array( 'lm_gcr_render_optin' )` | Functions removed from `woocommerce_thankyou` |

## Switches
| Option | Effect |
|---|---|
| `lm_admin_estilo_off` | Any truthy value (`1`) disables the style |
| `lm_sin_invasivos_off` | Value `1` stops removing the opt-in |
| `lm_guarda_keydown_off` | Value `1` stops injecting the keyboard patch (purge your page cache afterwards) |

Usage: `wp option update <name> 1`; undo: `wp option delete <name>`.

## How to undo
Delete the file. Nothing is left in the database (except switch options, if you set them).

## FAQ
**Does it change the WordPress dashboard or WooCommerce screens?** No: only screens whose `page=` matches and whose hook suffix contains that same `page`.
**Does the Pinterest patch block real key presses?** No. It only stops, in the capture phase from `wp_head`, `keydown` events whose `target` is not an element (`document`/`window`, typical of extensions or autofill). Real key presses arrive with an element as target.
**Does `lm-sin-invasivos` remove pop-ups in general?** No. Only the callbacks you name (by default the Google Customer Reviews opt-in).
**Does the style work on the public front end?** No, admin only.

## Limitations
- `lm-sin-invasivos.php` does nothing if no plugin registers the callback; it also serves as an example of a late `remove_action`.
- `lm-guarda-keydown.php` runs on the front end, on every page, until Pinterest for WooCommerce fixes it; check whether your version already did and delete the patch.

## License
GPL-2.0-or-later. See `LICENSE`.

## Author and support
Andrés Valencia Tobón · [GitHub](https://github.com/quijotevitruvio) · [LinkedIn](https://www.linkedin.com/in/andr%C3%A9s-valencia-tob%C3%B3n/)
Support: https://paypal.me/bibliotecologo
Developed for librosmedellin.com.
