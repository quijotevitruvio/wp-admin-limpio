# wp-admin-limpio

Tres *mu-plugins* pequeños e independientes para dejar WordPress más limpio:

| Archivo | Qué hace |
|---|---|
| `src/lm-admin-estilo.php` | Hoja de estilo minimalista (fondo blanco, tarjetas finas, botón primario de color configurable) **solo** para las pantallas de ajustes de tus propios plugins |
| `src/lm-sin-invasivos.php` | Quita el opt-in de Google Customer Reviews (la encuesta de calificación) de la página «pedido recibido», si otro plugin lo registra |
| `src/lm-guarda-keydown.php` | Parche del error `e.target.matches is not a function` del botón *Guardar* de Pinterest for WooCommerce |

## Para quién
Quien mantiene un WordPress con varios plugins propios y quiere pantallas de ajustes coherentes, sin ventanas de encuesta, y sin errores de JavaScript en consola por el botón de Pinterest.

## Requisitos
WordPress 6.x, PHP 7.4+. `lm-guarda-keydown.php` solo sirve con Pinterest for WooCommerce (visto con 1.5.1). `lm-sin-invasivos.php` solo tiene efecto si otro plugin registra `lm_gcr_render_optin` (u otra función que indiques) en `woocommerce_thankyou`.

## Instalación
Copia los archivos de `src/` (uno o los tres) a `wp-content/mu-plugins/`. No hay que activar nada.

## Configuración
Por defecto `lm-admin-estilo.php` **no estiliza ninguna pantalla**: tienes que decirle cuáles son las tuyas, por ejemplo en otro mu-plugin o en `functions.php`:

```php
add_filter( 'lm_admin_estilo_prefijos', fn() => array( 'miplugin-', 'mi_otro_' ) );
add_filter( 'lm_admin_estilo_contiene', fn() => array( 'finder' ) );
```

| Ajuste | Filtro / constante / opción | Por defecto | Qué hace |
|---|---|---|---|
| Prefijos de `page=` propios | filtro `lm_admin_estilo_prefijos` | lista vacía | Slugs que empiezan por alguno de estos valores reciben el estilo |
| Fragmentos de `page=` | filtro `lm_admin_estilo_contiene` | lista vacía | Slugs que contienen alguno de estos valores reciben el estilo |
| Exclusiones | filtro `lm_admin_estilo_excluir` | `bacs, transferencia, pedido, cobro, checkout, onhold, order, payment` | Slugs que contienen alguno de ellos **nunca** reciben el estilo |
| Color primario | filtro `lm_admin_estilo_color_primario` o constante `LM_ADMIN_ESTILO_PRIMARIO` | `#2271b1` | Botón primario y foco de campos (solo hex) |
| Color primario al pasar el ratón | filtro `lm_admin_estilo_color_primario_hover` o constante `LM_ADMIN_ESTILO_HOVER` | `#135e96` | Hover del botón primario |
| Color de texto | filtro `lm_admin_estilo_color_texto` o constante `LM_ADMIN_ESTILO_TEXTO` | `#1f2937` | Texto y títulos |
| Callbacks a quitar | filtro `lm_sin_invasivos_callbacks` | `array( 'lm_gcr_render_optin' )` | Funciones que se quitan de `woocommerce_thankyou` |

## Interruptores
| Opción | Efecto |
|---|---|
| `lm_admin_estilo_off` | Con cualquier valor «verdadero» (`1`), no aplica el estilo |
| `lm_sin_invasivos_off` | Con valor `1`, deja de quitar el opt-in |
| `lm_guarda_keydown_off` | Con valor `1`, no inyecta el parche de teclado (purga la caché de páginas después) |

Forma: `wp option update <nombre> 1`; deshacer: `wp option delete <nombre>`.

## Cómo deshacer
Borra el archivo. No dejan nada en la base de datos (solo las opciones de interruptor, si las pusiste).

## FAQ
**¿Cambia el escritorio de WordPress o WooCommerce?** No: solo las pantallas cuyo `page=` coincide y cuyo hook contiene ese mismo `page`.
**¿El parche de Pinterest bloquea teclas reales?** No: solo detiene, en fase de captura y desde `wp_head`, los `keydown` cuyo `target` no es un elemento (`document`/`window`, típicos de extensiones o autorrelleno). Las teclas reales llegan con un elemento como destino.
**¿`lm-sin-invasivos` quita ventanas emergentes en general?** No. Solo los callbacks que indiques (por defecto el opt-in de Google Customer Reviews).
**¿Funciona en el front-end público el estilo?** No, es solo administrador.

## Limitaciones
- `lm-sin-invasivos.php` no hace nada si ningún plugin registra el callback; sirve también de ejemplo de un `remove_action` tardío.
- `lm-guarda-keydown.php` actúa en el front-end, en todas las páginas, mientras no se arregle Pinterest for WooCommerce; revisa si su versión ya corrigió el fallo y borra el parche.

## Licencia
GPL-2.0-or-later. Ver `LICENSE`.

## Autor y apoyo
Andrés Valencia Tobón · [GitHub](https://github.com/quijotevitruvio) · [LinkedIn](https://www.linkedin.com/in/andr%C3%A9s-valencia-tob%C3%B3n/)
Apoya / Support: https://paypal.me/bibliotecologo
Desarrollado para librosmedellin.com.
