# Pictau Theme — Instrucciones para Claude

## Antes de crear contenido en Gutenberg

**SIEMPRE** leer `.claude/pictau-design-system.md` antes de crear o modificar bloques de contenido. Contiene la referencia completa de clases CSS, variantes de tarjetas, data-attributes, shortcodes y variables del tema.

---

## Principio fundamental: reutilizar antes de crear

El tema tiene un sistema de diseño completo en `/tailwind/custom/components/`. Antes de escribir una sola línea de CSS nuevo, verificar que no existe ya una clase o utilidad que resuelva el problema. Solo si no existe, crear nuevo CSS.

---

## Dónde añadir CSS nuevo

| Tipo de CSS | Archivo destino |
|---|---|
| Utilidades y patrones globales reutilizables | `style.css` |
| CSS específico de una sección o contenido concreto de la home | `sections-home.css` |
| CSS específico de otro contenido o página | crear archivo nuevo descriptivo en `/tailwind/custom/components/` |

**Nunca añadir CSS de secciones o contenido específico a `layout.css`.**  
`layout.css` es exclusivamente para utilidades de layout globales (grids, columnas, paddings, patrones de sección) reutilizables en toda la web.

Cualquier archivo `.css` nuevo en `/tailwind/custom/components/` se incluye automáticamente en el build (via `@import-glob`).

---

## Colores en secciones con fondo oscuro o imagen de fondo

Siempre añadir la clase `theme-color-A` a la sección cuando tenga fondo oscuro o imagen de fondo. Esta clase ya implementa los colores correctos para headings y párrafos (texto blanco). **No crear reglas CSS propias para esto.**

```
Sección [class: pct-section has-bg full-width theme-color-A]
  Imagen [class: is-bg]    ← is-bg va directamente en el bloque Imagen (<figure>), sin grupo contenedor
  Contenido (headings y texto heredan colores de theme-color-A automáticamente)
```

Si se necesita acento de color en un elemento concreto (como `<em>` en naranja), sí se puede añadir esa única regla con especificidad suficiente para sobreescribir `theme-color-A`.

---

## Estructura de contenido Gutenberg

### Sección de página

Cada sección de página usa el bloque nativo **"Sección"** (`<section>`) con clase `pct-section`. Esta es la unidad estructural básica.

```
Sección [tag: section, class: pct-section]
  → contenido de la sección
```

Variantes de sección frecuentes (añadir al `pct-section`):
- `full-width` o `alignfull` — ancho completo (100vw)
- `has-bg` — cuando contiene un `.is-bg` como fondo
- `center` — contenido centrado
- `no-pt` / `no-pb` — eliminar padding top/bottom

### Fondo en una sección (patrón `.is-bg`)

Para poner una imagen o vídeo como fondo absoluto:
```
Sección [class: pct-section has-bg full-width]
  Grupo [class: is-bg]         ← fondo absoluto (position: absolute; inset: 0)
    Imagen                      ← object-fit: cover automático
  → resto del contenido encima (z-index 1 automático por el JS)
```

### Tarjeta (card)

Bloque **Grupo** con clase `.card`. Siempre usar esta estructura interna:
```
Grupo [class: card {variantes}]
  Grupo  ← contenedor interno
    Imagen / SVG / shortcode [svg]
    Grupo
      Heading
      Párrafo(s)
      Botón (opcional)
```

Variantes disponibles de `.card` (añadir como clases extra al Grupo):
- `.shadow` — sombra
- `.border` — borde 2px
- `.transparent` — fondo transparente
- `.bg-dark` — fondo oscuro, texto blanco
- `.v-align-center` — alineación vertical centrada
- `.media-left` — imagen a la izquierda (grid auto/1fr)
- `.media-img` — imagen full-cover
- `.overlap-right-col` — desborda a la derecha (para layouts de 2 columnas)
- `.overlap-left-col` — desborda a la izquierda
- `.cta` — tarjeta CTA oscura con decorador de color principal
- `.agenda` — layout para agenda de eventos
- `.ponentes` — grid de ponentes

### Tarjetas numeradas

Para listas de tarjetas con numeración automática:
```
Grupo [class: ordered-counter]    ← resetea el contador CSS
  Grupo [class: card-ordered-num]
    Heading + contenido
  Grupo [class: card-ordered-num]
  ...
```

### FAQ / Acordeón

```
Grupo [class: pct-faqs]           ← o pct-faqs icon-left
  Grupo [class: faq]
    Párrafo  ← pregunta (se convierte en .faq-question por JS)
    Grupo    ← respuesta
      Grupo
        Párrafo(s)
  Grupo [class: faq]
  ...
```

---

## Grids y layout

- `.pct-2cols` — 2 columnas, responsive (1 col en móvil)
- `.pct-2cols-63` — 55% / resto (bueno para texto + imagen)
- `.pct-2cols-60-40` — 60% / 40%
- `.pct-3cols` — 3 columnas, responsive
- `.slider-2cols` — 2 columnas para sliders hero
- `.layout-site-width` — contenedor con max-width del sitio + padding
- `.center` — centrado (texto + flex)
- `.center-y` — flex centrado vertical
- `.rtl-mobile` — invierte el orden de columnas en móvil

Aplicar estas clases al bloque Columnas o al Grupo contenedor.

---

## Animaciones (data attributes)

**Único atributo de animación: `data-anim_any`** — sirve tanto para bloques completos como para texto.  
`data-pctanim` NO existe (módulo no cargado).

### Para animar un bloque/elemento completo
```
data-anim_any=""
data-anim_any_animation="slideFromRight"   ← slideFromRight/Left/Bottom/Top
data-anim_any_whattoanim="self"
data-anim_any_delay="0.3"
data-anim_any_duration="0.8"
```

### Para animar texto (por caracteres, palabras o líneas)
```
data-anim_any=""
data-anim_any_animation="blurIn"           ← blurIn / clippedFromBottom / clippedFromLeft…
data-anim_any_whattoanim="chars"           ← chars / words / lines / self
data-anim_any_stagger="0.04"
data-anim_any_delay="0.3"
data-anim_any_duration="1"
```

### Para máscaras blob en imágenes
- `data-animask` en el grupo contenedor de la imagen

---

## Reglas para nuevo CSS

1. **Verificar primero** en `/tailwind/custom/components/` (layout.css, cards.css, animations.css, backgrounds.css, style.css, etc.)
2. Si no existe solución: crear la clase en el archivo `.css` de la categoría correcta
3. **Nunca usar Tailwind** — vanilla CSS moderno con CSS nesting
4. Naming: kebab-case, nunca BEM (`__` ni `--`)
5. Usar siempre variables CSS del tema, nunca valores hardcodeados:
   - Colores: `var(--main-color)`, `var(--secondary-color)`
   - Espaciado: `var(--card-padding)`, `var(--def-layout-x-padding)`, `var(--default-section-vert-padding)`
   - Bordes: `var(--def-border-radius)`, `var(--card-border-radius)`
   - Sombras: `var(--box-shadow)`, `var(--box-shadow-md)`
   - Fuentes: `var(--def-font-family)`, `var(--def-heading-font-family)`

---

## PHP y WordPress

- Etiqueta PHP siempre `<?php`, nunca `<?=`
- Textos visibles al usuario: `esc_html__( 'Texto', 'pictau' )`
- Evitar BEM en clases PHP/HTML generado
