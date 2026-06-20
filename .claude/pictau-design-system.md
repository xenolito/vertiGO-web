# Pictau Design System — Referencia completa

> Leer este archivo antes de crear cualquier contenido en Gutenberg o cualquier CSS nuevo.
> Fuente: análisis de `/tailwind/custom/components/` y `/theme/inc/`.

---

## Variables CSS del tema

Nunca usar valores hardcodeados. Usar siempre estas variables:

### Colores
```css
var(--main-color)           /* Color principal (#ff6700 aprox, configurable) */
var(--secondary-color)      /* Color secundario */
var(--cta-color)            /* Color de CTA */
var(--link-color)           /* Color de enlaces */
var(--link-color-dark)      /* Color de enlaces en dark mode */
var(--bg-light)             /* Fondo claro */
var(--bg-dark)              /* Fondo oscuro */
var(--bg-card-light)        /* Fondo de tarjeta claro */
var(--bg-card-dark)         /* Fondo de tarjeta oscuro */
var(--heading-color)        /* Color de headings */
var(--txt-color)            /* Color de texto body */
var(--txt-color-dark)       /* Color de texto dark mode */
var(--line-color)           /* Color de líneas/bordes */
```

### Espaciado y layout
```css
var(--layout-max-width)                 /* Max-width del contenedor (1280px) */
var(--def-layout-x-padding)             /* Padding horizontal por defecto */
var(--card-padding)                     /* Padding interno de tarjetas */
var(--default-section-vert-padding)     /* Padding vertical de secciones */
var(--whole-header-height)              /* Altura total del header */
var(--page-header-min-height)           /* Altura mínima de header de página */
var(--section-overlap-y)                /* Valor de solapamiento entre secciones */
var(--form-max-width)                   /* Max-width de formularios */
```

### Bordes y forma
```css
var(--def-border-radius)        /* Border-radius por defecto (1.5rem) */
var(--card-border-radius)       /* Border-radius de tarjetas */
var(--button-border-radius)     /* Border-radius de botones */
```

### Sombras
```css
var(--box-shadow)       /* Sombra por defecto */
var(--box-shadow-md)    /* Sombra media */
var(--box-shadow-lg)    /* Sombra grande */
```

### Tipografía
```css
var(--def-font-family)          /* Fuente body (Quicksand) */
var(--def-heading-font-family)  /* Fuente headings (Montserrat) */
```

### Gradientes y fondos especiales
```css
var(--footer-gradient)              /* Gradiente de footer */
var(--card-gradient)                /* Gradiente de tarjeta */
var(--heading-default-grad-bg)      /* Gradiente de fondo de page header */
var(--heading-highlight-grad)       /* Gradiente highlight en headings */
var(--heading-highlight-grad-dark)  /* Idem dark mode */
```

### Easing CSS
```css
var(--ease-1)           /* cubic-bezier(0.25, 0, 0.5, 1) */
var(--ease-3)           /* cubic-bezier(0.25, 0, 0.3, 1) */
var(--ease-out-3)       /* cubic-bezier(0, 0, 0.3, 1) */
var(--ease-in-out-3)    /* cubic-bezier(0.5, 0, 0.5, 1) */
```

---

## Colores del tema (theme.json / Gutenberg palette)

| Slug | Valor | Clase Tailwind |
|---|---|---|
| `background` | #ffffff | `has-background-color` |
| `foreground` | #404040 | — |
| `primary` | #b91c1c | `has-primary-color` |
| `secondary` | #15803d | `has-secondary-color` |
| `tertiary` | #0369a1 | `has-tertiary-color` |

---

## CSS: Secciones de página

### `.pct-section`
Sección estándar con padding vertical (`--default-section-vert-padding`). Siempre aplicar en bloque `<section>`.
```html
<section class="pct-section">...</section>
```

### `.full-width` / `alignfull` en sección
Ancho completo (100vw), saliendo del contenedor.
Los hijos internos mantienen el max-width del sitio automáticamente.
```html
<section class="pct-section full-width">...</section>
```

### `.has-bg`
Sección con fondo de color/imagen. Activa `overflow: hidden` y `color: white`.
Se combina con `.is-bg` directamente en el bloque Imagen.
```html
<section class="pct-section has-bg full-width">
  <figure class="wp-block-image is-bg">   <!-- is-bg va en la <figure>, sin grupo contenedor -->
    <img src="...">
  </figure>
  <!-- contenido encima -->
</section>
```

En Gutenberg: añadir `is-bg` como clase extra en el bloque **Imagen** directamente.  
**No crear un bloque Grupo intermedio** con `is-bg`.

### `.is-bg`
Posicionamiento absoluto como fondo (position: absolute; inset: 0).  
Aplicar siempre en el bloque **Imagen** (`<figure>`), nunca en un Grupo contenedor.  
Las imágenes dentro tienen `object-fit: cover; width: 100%; height: 100%` automáticamente.

Variante: `.is-bg.bg-top-right` — anclado arriba a la derecha.

### `.center-y-after-header`
Centrado vertical con margin-top igual a la altura del header. Para páginas hero.

### `.section-heading`
Encabezado de sección con línea decorativa inferior.
```html
<div class="section-heading">
  <h2>Título</h2>
  <span class="heading-endline"></span>
</div>
```

### Secciones con solapamiento
```html
<section class="pct-section">...</section>
<!-- La sección anterior tendrá padding-bottom: var(--section-overlap-y) automático -->
<section class="pct-section has-overlapper">
  <div class="overlapper"><!-- elemento solapado --></div>
</section>
```

---

## CSS: Layout y grids

### Grids de columnas
| Clase | Columnas | Breakpoint |
|---|---|---|
| `.pct-2cols` | 2 (1 en móvil) | md (768px) |
| `.pct-2cols-63` | 55% / resto | md |
| `.pct-2cols-60-40` | 60% / 40% | lg (1024px) |
| `.pct-3cols` | 3 (1 en móvil) | md |
| `.slider-2cols` | 2, items centrados, gap-0 | md |

Añadir `.double-gap` a `.pct-2cols` para duplicar el gap.

### Utilidades de layout
| Clase | Efecto |
|---|---|
| `.layout-site-width` | max-width + padding horizontal del sitio |
| `.full-width` | 100% ancho, max-width unset |
| `.full-height` | height: 100% |
| `.full-vh` | height: 100dvh |
| `.center` | text-center + flex justify-center |
| `.center-y` | flex + justify-center + padding-top: 0 |
| `.no-center` | fuerza text-align: left en hijos |
| `.align-y-bottom` | align-self: flex-end |
| `.hide-on-mobile` | hidden en mobile, block en lg+ |
| `.rtl-mobile` | invierte orden del 2º hijo en móvil |
| `.max-w-form` | max-width para contenedores de formulario |

### Utilidades de espaciado
| Clase | Efecto |
|---|---|
| `.no-pt` | padding-top: 0 |
| `.no-pb` | padding-bottom: 0 + margin-bottom: 0 |
| `.no-py` | padding vertical: 0 |
| `.no-mt` | margin-top: 0 |
| `.no-mb` | margin-bottom: 0 |
| `.no-gap` | gap: 0 |
| `.margin-b` | margin-bottom: var(--def-layout-x-padding) |
| `.margin-t` | margin-top: var(--def-layout-x-padding) |
| `.padding-b` | padding-bottom: var(--def-layout-x-padding) |
| `.padding-t` | padding-top: var(--def-layout-x-padding) |

---

## CSS: Tarjetas

### `.card` (base)
Bloque Grupo con border-radius, padding, fondo blanco/oscuro (dark mode).
```
Grupo [class: card]
  Grupo (contenedor)
    Imagen/SVG
    Grupo (texto)
      Heading
      Párrafo
      Botón
```

### Variantes de `.card`
| Modificador | Efecto |
|---|---|
| `.shadow` | box-shadow grande (shadow-2xl) |
| `.border` | borde 2px con color semitransparente |
| `.transparent` | fondo transparente, sin borde |
| `.bg-dark` | fondo oscuro, texto blanco |
| `.v-align-center` | align-self: center |
| `.media-left` | imagen a la izquierda (grid auto/1fr, stack en móvil) |
| `.media-img` | imagen fullcover sin padding en figura |
| `.full-width` | figura interna alignfull |
| `.overlap-right-col` | desborda 150% a la derecha (para columna izq de 2cols) |
| `.overlap-left-col` | desborda a la izquierda |
| `.stretch-h-full` | width: 50vw |
| `.cta` | fondo oscuro + decorador triangular color principal en esquina |
| `.agenda` | layout de agenda de eventos con sesiones |
| `.ponentes` | grid flex de ponentes/speakers |

### `.card.cta`
Usar `<em>` dentro del heading para resaltar palabras clave (color principal).

### `.card-ordered-num`
Tarjeta con número automático (CSS counter). Requiere que el contenedor padre tenga clase `.ordered-counter`.
```
Grupo [class: ordered-counter]
  Grupo [class: card-ordered-num]
    H3 + Párrafo
  Grupo [class: card-ordered-num]
  ...
```

### `.card-area-overlap.glass`
Tarjeta glassmorphism de 2 columnas (texto + imagen de fondo).
```
Grupo [class: card-area-overlap glass]
  Figura [position: absolute, imagen de fondo]
  Div [texto con backdrop-filter: blur]
```

### `.base-card`
Variante simplificada de `.card` (mismos border-radius + padding, sin los estilos de color automáticos).

### `.address-card`
Tarjeta de dirección: grid de 2 columnas (icono + contenido con borde izquierdo).

### `.card-webinar`
Tarjeta de archivo de webinar/ebook.

### `.card-contact`
Tarjeta de información de contacto.

---

## CSS: Fondos y patrones

### Patrones de fondo SVG
| Clase | Patrón |
|---|---|
| `.bg-pattern-cross` | Cruces SVG |
| `.bg-pattern-dot` | Puntos SVG |
| `.bg-pattern-dot-square` | Puntos cuadrados |
| `.bg-pattern-grid` | Cuadrícula |
| `.bg-pattern-footer` | Patrón específico de footer |
| `.bg-pattern-cross-dark` | Cruces oscuras |
| `.bg-theme-light` | Patrón tema claro (opacity: 0.6) |

### Máscaras de radial-gradient
| Clase | Efecto |
|---|---|
| `.masked-center` | Máscara radial centrada |
| `.masked-center-top` | Máscara desde centro-arriba |
| `.masked-center-bottom` | Máscara desde centro-abajo |
| `.masked-left` | Máscara desde la izquierda |
| `.masked-right` | Máscara desde la derecha |
| `.bg-scale-50` | Background a escala 50% |

### Efectos de fondo
| Clase | Efecto |
|---|---|
| `.bg-glow` | Fondo con glow multicolor |
| `.bg-dark` | Fondo oscuro → text: white |
| `.ken-burns` | Zoom Ken Burns en imágenes (infinite) |
| `.shine` | Efecto brillo en hover (requiere estar dentro de `<a>`) |

### Perspectivas isométricas
- `[class*="isometric"]` — transform isométrico
- `.plane-right` — perspectiva plano derecho
- `.plane-horizontal` — perspectiva plano horizontal
- `[class*="perspective-rotate"]` — rotación perspectiva

---

## CSS: Animaciones

### Variables de animación disponibles
```css
var(--animation-fade-in)
var(--animation-fade-in-bloom)      /* fade + bloom/blur */
var(--animation-fade-out)
var(--animation-slide-in-up)
var(--animation-slide-in-down)
var(--animation-slide-in-right)
var(--animation-slide-in-left)
var(--animation-slide-out-up)
var(--animation-slide-out-down)
var(--animation-slide-out-right)
var(--animation-slide-out-left)
var(--animation-scale-up)           /* scale 1 → 1.25 */
var(--animation-scale-down)         /* scale 1 → 0.75 */
var(--animation-shake-x)
var(--animation-shake-y)
var(--animation-shake-z)
var(--animation-spin)               /* 360° infinite */
var(--animation-ping)               /* ping/pulse */
var(--animation-blink)              /* parpadeo */
var(--animation-float)              /* flotación arriba/abajo */
var(--animation-bounce)             /* rebote */
```

Uso: `animation: var(--animation-fade-in);`

### `.pulsing-dot`
Punto pulsante con onda. Aplicar a un elemento inline vacío.

---

## Data attributes JS (animaciones y comportamientos)

### `data-anim_any` — Animaciones GSAP en texto y bloques (animation_any.js)

**ÚNICO atributo de animación activo.** `data-pctanim` NO existe (el módulo `animation_blocks.js` no está cargado).

Animaciones disponibles para `data-anim_any_animation`:
```
blurIn              clippedFromBottom   clippedFromLeft    clippedFromTop
slideFromBottom     slideFromTop        slideFromLeft      slideFromRight
```

Para animar un **bloque completo** (`whattoanim="self"`):
```html
<div data-anim_any=""
     data-anim_any_animation="slideFromRight"
     data-anim_any_whattoanim="self"
     data-anim_any_delay="0.3"
     data-anim_any_duration="0.8">
```

Para animar **texto por caracteres/palabras** (`whattoanim="chars"|"words"|"lines"`):
```html
<h2 data-anim_any=""
    data-anim_any_animation="blurIn"
    data-anim_any_whattoanim="chars"
    data-anim_any_stagger="0.04"
    data-anim_any_delay="0.3"
    data-anim_any_duration="1">
```

Todos los parámetros:
```html
data-anim_any_animation="slideFromBottom"   /* animación */
data-anim_any_whattoanim="self"             /* self | chars | words | lines */
data-anim_any_duration="1.5"               /* duración (defecto 1.5) */
data-anim_any_stagger="0.1"               /* stagger entre elementos */
data-anim_any_delay="0.2"                 /* retardo en segundos */
data-anim_any_repeat="true"              /* repetir al salir del viewport */
data-anim_any_slideamount="100"           /* desplazamiento en px (defecto 100) */
data-anim_any_chainanim="true"            /* encadenar con siguiente */
```

### `data-animask` — Máscara blob animada (image_mask_animated.js)
Aplicar en el bloque Grupo que contiene la imagen. El elemento queda invisible hasta que JS aplica el clip-path.
```html
<figure data-animask
  data-animask_points="6"
  data-animask_intensity="0.4"
  data-animask_speed="0.3"
  data-animask_gap="20"
  data-animask_ring1width="8"
  data-animask_ring1color="#ff6700"
  data-animask_ring1opacity="0.5"
  data-animask_ring2width="16"
  data-animask_ring2color="#ff6700"
  data-animask_ring2opacity="0.2">
  <img src="...">
</figure>
```

### `data-pin` — Elemento anclado en scroll (scrollTrigger_pin.js)
```html
<div data-pin
  data-pin_target=".contenedor-padre"
  data-pin_breakpoint="768"
  data-pin_start="top top">
  <!-- contenido que se queda fijo -->
</div>
```

### `data-dotnav` — Navegación por puntos (navigation_dot.js)
En el contenedor de secciones:
```html
<div data-dotnav="section" data-dotnav_position="right">
  <section data-label="Inicio">...</section>
  <section data-label="Servicios">...</section>
</div>
```

### `data-modal` — Popup modal (modal.js)
```html
<!-- Trigger -->
<a href="#modal-demo">Abrir modal</a>

<!-- Modal -->
<div data-modal="demo">
  <div class="backdrop"></div>
  <div class="content-wrapper">
    <div class="content">
      <h3>Título del modal</h3>
      <p>Contenido</p>
    </div>
  </div>
</div>
```

### `data-swiper` — Slider (Swiper)
```html
<div data-swiper>
  <div class="swiper-wrapper">
    <div class="swiper-slide">Slide 1</div>
    <div class="swiper-slide">Slide 2</div>
  </div>
</div>
```

### `data-scrollhorizontal` — Scroll horizontal
```html
<div data-scrollhorizontal>
  <div class="horiz-scroll">
    <section>Sección 1</section>
    <section>Sección 2</section>
  </div>
</div>
```

### `data-scrolloverlapvertical` — Secciones con solapamiento vertical
```html
<div data-scrolloverlapvertical>
  <section>...</section>
  <section>...</section>
</div>
```

### `data-lenis-prevent` — Prevenir smooth scroll en un elemento
```html
<div data-lenis-prevent><!-- scroll nativo aquí --></div>
```

### `data-bgcolor` — Cambio de color de texto al cambiar background
```html
<div data-bgcolor>Este texto cambia de color al cambiar el fondo</div>
```

---

## CSS: Listas

### `.ordered-list`
Lista numerada con círculos de color. No requiere JS, solo CSS counter.
```html
<ul class="ordered-list">
  <li>Primer elemento</li>
  <li>Segundo elemento</li>
</ul>
```
Personalizar con variables CSS en el contenedor:
```css
--numberedColor: white;
--numberedBgColor: var(--main-color);
--dim: 2em;
```

### `.pct-ul`
Lista no numerada personalizada.
- `.pct-ul.pct-2cols` — en 2 columnas
- `.ul-2cols` — 2 columnas (alternativa directa en `<ul>`)

### Listas de contacto / iconos
- `.contact-list` — lista de datos de contacto
- `.list-icons` — lista con iconos

---

## CSS: Tipografía

| Clase / Selector | Efecto |
|---|---|
| `h1.smaller` | H1 más pequeño |
| `h2.smaller` | H2 más pequeño |
| `h2.smaller-2` | H2 aún más pequeño |
| `.subheader` | Texto subtítulo estilizado |
| `.uppercase` | text-transform: uppercase |
| `p.bigger` | Párrafo con font-size mayor |
| `em` dentro de heading | Color principal, resaltado |
| `[data-split_text]` | Preparar texto para split animation |

---

## CSS: Efectos sobre imágenes y figuras

| Clase | Efecto |
|---|---|
| `.img-shadow` | Drop-shadow 20px |
| `.img-shadow-small` | Drop-shadow 5px |
| `.rounded` | border-radius: var(--card-border-radius) + overflow: hidden |
| `.ken-burns` | Zoom Ken Burns infinito |
| `.shine` | Efecto brillo en hover (contenedor `<a>`) |
| `figure.full-height-col` | Figura absolute 100% alto (para columnas con imagen fondo) |
| `figure.local-video` | Contenedor vídeo local (grid 1/1 para overlay) |
| `figure.local-video.rounded` | Con border-radius |
| `figure.local-video.shadow` | Con sombra |
| `.enlarged-image` | Estado de imagen ampliada |

---

## CSS: Sliders

| Clase | Uso |
|---|---|
| `.slider` | Contenedor slider hero principal |
| `.slider.has-video-bg` | Slider con vídeo de fondo |
| `.slider-cover` | Overlay/cover del slider |
| `.slider-bg` | Fondo de slider vía Swiper (posición absoluta, z-index: -1) |
| `.slider-cards` | Slider de tarjetas vertical |
| `.slider-webinars` | Slider de webinars |
| `[data-swiper]` | Cualquier slider Swiper (opacity: 0 hasta init) |
| `.swiper-wrapper` | Contenedor de slides |
| `.swiper-slide` | Slide individual |
| `.swiper-slide-active` | Slide activo |

---

## CSS: Filtros y grids especiales

### `.filtered-grid` + `.logos-grid`
Grid de logos con filtro por categoría. Gestión automática por JS.
```html
<div class="filtered-grid">
  <div class="filter-ui">
    <span>Categoría 1</span>
    <span class="checked">Categoría 2</span>
  </div>
  <div class="logos-grid">
    <figure><img src="..."></figure>
    ...
  </div>
</div>
```

### `.figures-grid` + `.counter-item`
Grid de estadísticas con contador animado.
```html
<div class="figures-grid">
  <div class="counter-item">
    <div class="pct-counter">
      <h2>0</h2>  <!-- El JS anima hasta el número final -->
      <span class="counter-prefix">+</span>
    </div>
    <p>Descripción</p>
  </div>
</div>
```

### `.animated-grid`
Grid con animación de entrada.

---

## CSS: Navegación

| Clase | Elemento |
|---|---|
| `#masthead` | Header principal |
| `.main-header-wrap` | Wrapper del header |
| `.main-nav-desktop` | Nav desktop (visible en lg+) |
| `.main-nav-mobile` | Nav mobile |
| `.scrolledHeader` | Estado header con scroll |
| `.fixed-header` | Modo header fijo (clase en body) |
| `.fixed-autohide-header` | Modo header autooculto (clase en body) |
| `.nav-opened` | Menú móvil abierto |

---

## CSS: Formularios

| Clase | Uso |
|---|---|
| `.pct-form-container` | Contenedor de formulario |
| `.pct-form-pasti` | Formulario con auto layout |
| `.placeholder_as_label` | Estilo Material Design (label sobre input) |
| `.submit_on_email` | Formulario con submit en email |
| `.pct-legal` | Bloque de aceptación legal |
| `.multi-checkbox` | Grupo de checkboxes múltiples |
| `.wpcf7` | Wrapper CF7 (estilos automáticos del tema) |
| `.pct-msg-sent-ok` | Mensaje de éxito personalizado |

---

## CSS: Efectos de scroll especiales

| Clase/Atributo | Efecto |
|---|---|
| `[data-scrolloverlapvertical]` | Solapamiento vertical de secciones |
| `[data-scrollhorizontal]` | Scroll horizontal de secciones |
| `.will-pin` | Elemento marcado para pinning |
| `[data-pin]` | Elemento que se pega con ScrollTrigger |

---

## Shortcodes del tema

### Medios e imágenes
```
[svg filename="nombre-archivo" class="mi-clase" width="100" height="100" figure="true"]
  — SVG inline desde /uploads/. figure="true" lo envuelve en <figure>.

[featured-image size="full"]
  — Imagen destacada del post actual en <figure class="is-bg only-img">

[featured-b-image size="full"]
  — Imagen destacada en <figure> sin clases

[myImg id="123" size="large"]
  — Imagen de media library por ID

[image-random src="url1,url2,url3" class="mi-clase" random="yes" width="800" height="600"]
  — Imagen aleatoria de un set (con JS al cargar)
```

### Vídeo
```
[video-bg src="/ruta/video.mp4" overlayopacity="0.3" mobile="false" poster="/ruta/poster.jpg"
  webm="/ruta/video.webm" align="center" noautoplay="false"]
  — Vídeo como fondo absoluto. Wrapper: <div data-video><video class="video-bg">
```

### UI e interacción
```
[dark-switcher]
  — Toggle de modo oscuro (checkbox + SVG)

[lang-switcher flag="false" ui="horizontal"]
  — Selector de idioma (Polylang). ui: horizontal|vertical

[rive src="/ruta/animacion.riv" font="inter2.ttf" i18n="false"]
  — Canvas de animación Rive vectorial

[svg-inline limit="0"]
  — Wrapper que convierte <img src="*.svg"> en SVGs inline
```

### Información del sitio
```
[social]                    — Iconos de redes sociales
[pictau-copyright]          — Bloque de copyright completo (logo + social + menú)
[siteURL]                   — URL del sitio
[myBaseURL]                 — URL base de media
[myThemeURL]                — URL del tema
[myYear]                    — Año actual (4 dígitos)
[current-date format="d-m-Y"]  — Fecha actual formateada
```

### Utilidades WordPress
```
[pct-cpt-block title="Título del bloque"]
  — Renderiza un bloque de CPT "pictau-blocks" por título

[category-ui id="123"]
  — Filtro de categorías con botones .cat-filter-item

[pct_wdgt_custom_archives]
  — Archivos de los últimos 6 meses como <ul>
```

---

## Estructura de archivos CSS

```
/tailwind/custom/components/
  animations.css          — Keyframes y variables CSS de animación
  backgrounds.css         — Fondos, patrones, máscaras
  blog.css                — Estilos de blog y archivos
  buttons.css             — Botones y estados
  cards.css               — Tarjetas y variantes
  components.css          — Variables CSS globales (:root)
  font-styles.css         — Tipografía y fuentes
  forms.css               — Formularios y CF7
  gdpr_cookieNotice.css   — GDPR/cookies (no tocar)
  layout.css              — Layout global, secciones, grids, FAQ, modales
  layout-navigation.css   — Header, navegación, menús
  scrollbars.css          — Scrollbars custom
  style.css               — Estilos de customización del tema
  unordered-lists.css     — Listas custom
```

### Dónde añadir nuevo CSS según la categoría

| Tipo de elemento | Archivo |
|---|---|
| Nueva sección / layout | `layout.css` |
| Nueva variante de tarjeta | `cards.css` |
| Nueva animación | `animations.css` |
| Nuevo fondo / patrón | `backgrounds.css` |
| Nuevo botón | `buttons.css` |
| Nuevo componente de formulario | `forms.css` |
| Tipografía | `font-styles.css` |
| Componente de UI nuevo (sin categoría clara) | `style.css` |

---

## Clases de estado JS (no aplicar manualmente en editor)

Estas clases las gestiona JavaScript automáticamente:
- `.opened` — FAQ abierta
- `.showing` — Modal o submenu visible
- `.scrolledHeader` — Header con scroll
- `.nav-opened` — Menú móvil abierto
- `.stop-scrolling` — Body cuando menú abierto
- `.touch-device` — HTML cuando es dispositivo táctil
- `.is-flipped` — Flip card volteada
- `.checked` — Item de filtro activo en `.filtered-grid`
- `.show-prefix` — Prefijo de contador visible
- `.anim-intro-triggered` — Intro del slider disparada
- `.enlarged-image` — Imagen ampliada (lightbox)

---

## Patrones de bloque Gutenberg frecuentes

### Hero con fondo de vídeo
```
Sección [class: pct-section full-width, style: min-height: 100dvh]
  Shortcode: [video-bg src="..."]
  Grupo [class: center]
    H1 [data-anim_any="blurIn"]
    Párrafo
    Botones
```

### Sección de 2 columnas texto + imagen
```
Sección [class: pct-section]
  Columnas [class: pct-2cols]
    Columna
      H2 [data-anim_any, data-anim_any_animation="slideFromLeft", data-anim_any_whattoanim="self"]
      Párrafo
      Botón
    Columna
      Imagen [data-anim_any, data-anim_any_animation="slideFromRight", data-anim_any_whattoanim="self", class: img-shadow]
```

### Grid de tarjetas de 3 columnas
```
Sección [class: pct-section]
  H2 [class: section-heading]
  Grupo [class: pct-3cols]
    Grupo [class: card shadow, data-anim_any, data-anim_any_animation="slideFromBottom", data-anim_any_whattoanim="self", data-anim_any_delay="0"]
      Grupo
        SVG / Imagen
        Grupo
          H3
          Párrafo
    Grupo [class: card shadow, data-anim_any, data-anim_any_animation="slideFromBottom", data-anim_any_whattoanim="self", data-anim_any_delay="0.15"]
      ...
    Grupo [class: card shadow, data-anim_any, data-anim_any_animation="slideFromBottom", data-anim_any_whattoanim="self", data-anim_any_delay="0.3"]
      ...
```

### FAQ
```
Sección [class: pct-section]
  H2
  Grupo [class: pct-faqs]
    Grupo [class: faq]
      Párrafo  ← pregunta
      Grupo    ← respuesta
        Grupo
          Párrafo
    Grupo [class: faq]
      ...
```

### Sección de estadísticas con contador
```
Sección [class: pct-section full-width has-bg]
  Imagen [class: is-bg]    ← directamente en el bloque Imagen
  Grupo [class: figures-grid pct-3cols center]
    Grupo [class: counter-item]
      Grupo [class: pct-counter]
        H2  ← número final (ej: 150)
        Párrafo [class: counter-prefix]  ← símbolo (ej: +)
      Párrafo  ← descripción
```

### Sección con imagen blob animada
```
Sección [class: pct-section]
  Columnas [class: pct-2cols]
    Columna
      H2
      Párrafo
    Columna
      Figura [data-animask, data-animask_intensity="0.3", data-animask_ring1color="#ff6700"]
        Imagen
```
