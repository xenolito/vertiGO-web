## Plan: Offset híbrido para anclas GSAP

Implementar un offset híbrido sobre el sistema actual de javascript/modules/scrollToAName.js. La base seguirá siendo la altura dinámica de #masthead y, encima de esa base, se añadirá un offset declarativo opcional por target y por enlace. La precedencia cerrada es: header dinámico + offset global opcional + offset del target + offset del enlace. Así se mantiene el comportamiento actual y se habilita ajuste fino sin tocar el motor Lenis/GSAP.

## Steps

1. Consolidar el cálculo del offset total en javascript/modules/scrollToAName.js para que exista una única función responsable de sumar todas las capas del offset.
2. Mantener como base la altura real de #masthead, reutilizando el comportamiento actual para no introducir regresiones en enlaces ya existentes.
3. Añadir un offset global opcional en el propio módulo como valor por defecto del proyecto. Debe valer 0 si no se configura nada.
4. Añadir soporte de offset por target leyendo un data attribute del elemento destino.
Recomendación: usar data-scroll-offset en la sección o nodo con id para que el ajuste viva junto al contenido que lo necesita.
5. Añadir soporte de offset por enlace leyendo un data attribute del anchor pulsado.
Recomendación: usar data-anchor-offset para permitir excepciones cuando distintos enlaces al mismo destino necesiten aterrizajes distintos.
6. Definir la precedencia final del cálculo: altura de #masthead + offset global + offset del target + offset del enlace. Si no existe alguno de esos valores, aporta 0.
7. Reutilizar ese mismo cálculo tanto en el click handler como en la carga inicial con hash en URL, porque hoy esa ruta entra con offset 0 y quedaría inconsistente.
8. Revisar javascript/modules/navigation_dot.js sólo para coherencia visual. No debería cambiar la navegación, pero puede requerir ajustar start/end de ScrollTrigger si el nuevo aterrizaje hace que el dot activo cambie demasiado pronto o demasiado tarde.
9. Documentar en README.md la nueva API de offsets si finalmente se implementa, porque añade una capacidad nueva del tema.

## Relevant files

- javascript/modules/scrollToAName.js — punto central del cálculo de Y, clicks en anclas y hash inicial.
- javascript/modules/navigation_dot.js — posible ajuste de coherencia visual en los umbrales activos de ScrollTrigger.
- javascript/modules/desktopMenuNav.js — referencia de cálculo dinámico basado en altura real del header.
- javascript/modules/smooth_scroll.js — Lenis activo, que no debe cambiar de contrato.
- README.md — documentación de la nueva capacidad de offset híbrido.

## Verification

1. Probar un enlace sin data attributes y confirmar que conserva el comportamiento actual basado en #masthead.
2. Probar un target con data-scroll-offset y verificar que el scroll aterriza con ese margen adicional.
3. Probar un enlace con data-anchor-offset hacia un target ya configurado y confirmar que ambos offsets se acumulan en el orden previsto.
4. Probar carga directa con hash inicial y navegación atrás/adelante para asegurar que la ruta de hash usa la misma lógica de offset.
5. Validar en navegador con Lenis activo que la Y final coincide con la esperada y que no aparecen errores de consola.
6. Si se usa el entorno local con automatización, comprobar también que la navegación de puntos sigue marcando la sección correcta.

## Decisions

- Incluido: offset híbrido con capas combinables y fallback seguro al comportamiento actual.
- Excluido: rediseño del dot nav, cambio de motor de scroll o sistema de configuración global más amplio.
- API recomendada: data-scroll-offset en el target y data-anchor-offset en el enlace.
- Precedencia cerrada: header dinámico + global + target + enlace.
