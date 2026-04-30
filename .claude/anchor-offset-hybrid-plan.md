## Plan: Offset híbrido para anclas GSAP

Implementar un offset híbrido sobre el sistema actual de javascript/modules/scrollToAName.js. La base seguirá siendo la altura dinámica de #masthead y, encima de esa base, se añadirá un offset declarativo opcional por target y por enlace. La precedencia cerrada es: header dinámico + offset global opcional + offset del target + offset del enlace. Así se mantiene el comportamiento actual y se habilita ajuste fino sin tocar el motor Lenis/GSAP.

## Steps

1. Consolidar el cálculo del offset total en javascript/modules/scrollToAName.js para que exista una única función responsable de sumar todas las capas del offset.
2. Mantener como base la altura real de #masthead, reutilizando el comportamiento actual para no introducir regresiones en enlaces ya existentes.
   - Cachear `document.querySelector('#masthead')` fuera del click handler (evita el query de DOM por cada click). Leer `.getBoundingClientRect().height` sí debe ocurrir dentro del handler porque el header puede cambiar de tamaño.
3. Añadir un offset global opcional en el propio módulo como valor por defecto del proyecto (`const GLOBAL_SCROLL_OFFSET = 0`). Debe valer 0 si no se configura nada.
4. Añadir soporte de offset por target leyendo un data attribute del elemento destino.
   Recomendación: usar data-scroll-offset en la sección o nodo con id para que el ajuste viva junto al contenido que lo necesita.
5. ~~Añadir soporte de offset por enlace~~ (descartado del scope inicial — el caso de dos anclas al mismo target con aterrizajes distintos es demasiado raro para justificar la complejidad). Dejar el cuarto parámetro como hueco opcional en la firma de `getOffset(anchor, targetElement)` por si en el futuro se necesita, pero sin implementar lógica ni añadir data-anchor-offset al README.
6. Definir la precedencia final del cálculo: altura de #masthead + offset global + offset del target. Si no existe alguno de esos valores, aporta 0.
7. Mover el handler de hash inicial de `DOMContentLoaded` a `window.load` para que `getBoundingClientRect()` del target sea fiable cuando hay imágenes sin dimensiones declaradas por encima. (Nota: el comentario en línea 13 del archivo ya sugería este cambio — era una decisión pendiente.)
   - Reutilizar el mismo cálculo de offset que en el click handler, porque actualmente la ruta de hash usa `offset: 0` y quedaría inconsistente.
8. Corregir bug en `linkToSamePage` (línea 73 actual): el operador `??` final es inerte porque `&&` entre booleans nunca devuelve `null`/`undefined`. Simplificar a `return origin === linkElement.origin && pathname === linkElement.pathname`.
9. Revisar javascript/modules/navigation_dot.js sólo para coherencia visual. No debería cambiar la navegación, pero puede requerir ajustar start/end de ScrollTrigger si el nuevo aterrizaje hace que el dot activo cambie demasiado pronto o demasiado tarde.
10. Documentar en README.md la nueva API de offsets si finalmente se implementa, porque añade una capacidad nueva del tema.

## Relevant files

- javascript/modules/scrollToAName.js — punto central del cálculo de Y, clicks en anclas y hash inicial.
- javascript/modules/navigation_dot.js — posible ajuste de coherencia visual en los umbrales activos de ScrollTrigger.
- javascript/modules/desktopMenuNav.js — referencia de cálculo dinámico basado en altura real del header.
- javascript/modules/smooth_scroll.js — Lenis activo, que no debe cambiar de contrato.
- README.md — documentación de la nueva capacidad de offset híbrido.

## Verification

1. Probar un enlace sin data attributes y confirmar que conserva el comportamiento actual basado en #masthead.
2. Probar un target con data-scroll-offset y verificar que el scroll aterriza con ese margen adicional.
3. Probar carga directa con hash inicial y navegación atrás/adelante para asegurar que la ruta de hash usa la misma lógica de offset.
4. Verificar que el timing de `window.load` resuelve el desplazamiento incorrecto con imágenes sin dimensiones declaradas.
5. Validar en navegador con Lenis activo que la Y final coincide con la esperada y que no aparecen errores de consola.
6. Si se usa el entorno local con automatización, comprobar también que la navegación de puntos sigue marcando la sección correcta.

## Decisions

- Incluido: offset híbrido con 3 capas combinables (header + global + target) y fallback seguro al comportamiento actual.
- Incluido: mover hash inicial a `window.load` para fiabilidad con imágenes sin dimensiones declaradas.
- Incluido: corrección del bug `??` en `linkToSamePage`.
- Incluido: cacheo de `#masthead` fuera del handler.
- Excluido: offset por enlace (`data-anchor-offset`) — caso de uso demasiado raro, complejidad no justificada. Se deja la firma preparada para añadirlo sin romper nada.
- Excluido: rediseño del dot nav, cambio de motor de scroll o sistema de configuración global más amplio.
- Excluido: CSS `scroll-margin-top` como alternativa — no funciona con scroll sintético de Lenis/GSAP.
- API recomendada: data-scroll-offset en el target.
- Precedencia cerrada: header dinámico + global + target.
