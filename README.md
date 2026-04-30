# VertiGO! WordPress Thinking Mark Development

Pictau WordPress theme with [tailwindcss](https://tailwindcss.com/docs/installation), based on [www.underscoretw.com](https://underscoretw.com).
Any question and contributions are welcome: [@xenolito](mailto:orey@pictau.com)

## Quickstart

### TODO - FIX package.json script:
```
"watch:browser-sync": "browser-sync start --proxy $npm_package_config_domain --files \"theme\" --no-notify --no-inject-changes",
```

### 1) Before installation, prepare your WordPress

1. Add to your `wp-config.php` the following var definitons:

```php

/* MEMORY LIMITS*/
define('WP_MEMORY_LIMIT', '3000M');
define( 'WP_MAX_MEMORY_LIMIT', '256M' );
set_time_limit(300);
/* Cambiamos directorio Uploads  */
define( 'UPLOADS', ''.'xen_media' );
ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL );

define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', false);

define('DISALLOW_FILE_EDIT', true);
define('WP_POST_REVISIONS', 10);

define('AUTOMATIC_UPDATER_DISABLED', true);

```

I also encourage you to change your database prefix for security reasons, you can use a plugin like [Brozzme DB Prefix & Tools Addons](https://wordpress.org/plugins/brozzme-db-prefix-change/)

2. Add and activate the following free plugins (Optional):

-   [Attributes for blocks](https://es.wordpress.org/plugins/attributes-for-blocks/): Used for theme block animations.
-   [Contact form 7](https://es.wordpress.org/plugins/contact-form-7/): The theme handles these forms with nice error validation animations and styles.
-   [LightStart - Maintenance Mode](https://es.wordpress.org/plugins/wp-maintenance-mode/): The theme provides a default page template for this. Create an empty maintenance page and change the `page template` to `MAINTENANCE MODE`.
-   [WP Mail SMTP](https://es.wordpress.org/plugins/wp-mail-smtp/)
-   [WPS Hide Login](https://es.wordpress.org/plugins/wps-hide-login/)
-   [GDPR Cookie Compliance](https://wordpress.org/plugins/gdpr-cookie-compliance/)

### 2) Installation

1. Move this folder to `wp-content/themes` in your local development environment
2. Edit `package.json` and update `"config": { "domain": [your-local-domain]}` for browsersync to setup.
3. Run `npm install && npm run dev` in this folder
4. In Theme folder, setup the customer email `company name` and `email from address` at `[your-theme]/inc/template-functions.php`.
5. In Wordpress, add at least a Desktop (Primary) and Mobile menu
6. Activate this theme in WordPress

### Development

4. Run `npm run watch`
5. Add [Tailwind utility classes](https://tailwindcss.com/docs/utility-first) with abandon

### Anchor navigation with Lenis + GSAP

When using hash links (`a#name`) and dot navigation, smooth scrolling is handled with a hybrid approach to avoid animation lag after Lenis upgrades.

-   File: `javascript/modules/scrollToAName.js`
-   GSAP controls the tween curve and timing (`expo.inOut`, `0.7s`)
-   Lenis applies the animated scroll position on each GSAP frame with `immediate: true`
-   This avoids double smoothing (Lenis easing on top of GSAP easing), which caused a perceptible pause before scrolling started
-   If Lenis is not available, the module falls back to GSAP ScrollTo on `window`

Implementation notes:

1. Convert anchor target to absolute `y` position (with header offset support)
2. Animate an internal numeric state with GSAP
3. On each `onUpdate`, call `lenis.scrollTo(stateY, { immediate: true, force: true })`
4. Prevent overlap by killing the previous active tween before starting a new one

If you update `lenis` or `gsap`, validate anchor clicks and dot navigation behavior to ensure scroll starts immediately and easing remains consistent.

### Video time trigger (`video_time_trigger.js`)

Dispara lógica JS cuando un `<video>` alcanza un segundo concreto de reproducción. Compatible con vídeos en autoplay, sin controles y en bucle.

**Archivo:** `javascript/modules/video_time_trigger.js`

#### Data-attributes

| Atributo | Tipo | Default | Descripción |
|---|---|---|---|
| `data-videotrigger` | — | — | Activa el módulo en el elemento |
| `data-videotrigger_time` | Number | `0` | Segundo en el que se dispara el evento |
| `data-videotrigger_callback` | String | — | Nombre de función global (`window[name]`) a llamar |
| `data-videotrigger_once` | Boolean | `false` | Si `true`, se dispara solo la primera vez (no en cada bucle) |

#### Mecanismo de disparo (doble)

1. **CustomEvent `videotrigger`** — siempre emitido en el elemento, con `{ bubbles: true, detail: { time, video } }`.
2. **Función global** — si `callback` está definido, llama a `window[callback](video, el)`.

#### Uso

```html
<video
  data-videotrigger
  data-videotrigger_time="5"
  data-videotrigger_callback="miCallback"
  autoplay loop muted playsinline
  src="video.mp4">
</video>

<script>
// Opción A: función global
function miCallback(video, el) { ... }

// Opción B: CustomEvent (sin necesidad de callback)
document.querySelector('[data-videotrigger]').addEventListener('videotrigger', e => {
  console.log('segundo alcanzado:', e.detail.time)
})
</script>
```

Para disparar solo una vez (no re-disparar en cada vuelta del loop):
```html
data-videotrigger_once="true"
```

La instancia queda accesible en `el.videoTimeTrigger`. Expone `reset()` para reiniciar el estado manualmente.

### Inline SVG shortcode behavior

The `[svg]` shortcode now ignores internal control attributes when generating the final `<svg>` tag.

- `filename` and `figure` are used only by the shortcode logic and are not injected as SVG attributes.
- Empty attribute values are ignored to avoid invalid output like `width=""` or `height=""`.
- This prevents browser console errors caused by invalid SVG length attributes.

### Deployment

6. Run `npm run production` to build the production.
7. Run `npm run deploy` to git add and commit, and push to the remote repo.
8. Or `npm run bundle` for .zip the theme (OPTIONAL)
9. Upload the resulting zip file to your site using the “Upload Theme” button on the “Add Themes” administration page (OPTIONAL)

Or [deploy with the tool of your choice](https://underscoretw.com/docs/deployment/#h-other-deployment-options)!

## Full Documentation

### Fundamentals

-   [Installation](https://underscoretw.com/docs/installation/)
    Generate your custom theme, install it in WordPress and run your first Tailwind builds
-   [Development](https://underscoretw.com/docs/development/)
    Watch for changes, build for production and learn more about how \_tw, WordPress and Tailwind work together
-   [Deployment](https://underscoretw.com/docs/deployment/)
    Share your new WordPress theme with the world
-   [Troubleshooting](https://underscoretw.com/docs/troubleshooting/)
    Find solutions to potential issues and answers to frequently asked questions

### In Depth

-   [Using Tailwind Typography](https://underscoretw.com/docs/tailwind-typography/)
    Customize front-end and back-end typographic styles
-   [JavaScript Bundling with esbuild](https://underscoretw.com/docs/esbuild/)
    Install and bundle JavaScript libraries (very quickly)
-   [Linting and Code Formatting](https://underscoretw.com/docs/linting-code-formatting/)
    Catch bugs and stop thinking about formatting

### Extras

-   [On Tailwind and WordPress](https://underscoretw.com/docs/wordpress-tailwind/)
    Understand how WordPress and Tailwind work together
-   [Managing Styles for Custom Blocks](https://underscoretw.com/docs/custom-blocks/)
    Learn strategies for using Tailwind in theme-specific custom blocks
-   [Setting Up Browsersync](https://underscoretw.com/docs/browsersync/)
    Add live reloads and synchronized cross-device testing to your workflow
