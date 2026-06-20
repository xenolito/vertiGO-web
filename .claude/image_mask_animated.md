# OBJETIVO
Vamos a crear un módulo de javascript para crear el siguiente efecto/animación sobre un HTMLElement target que definamos a través de un atributo.

El target tendrá un DOM parecido a:

```
<div data-animask>
 <picture>
  <svg class="mask"></svg>
  <img src="...">
 </picture>
</div>
```

El efecto debe funcionar de la siguiente manera:
- Ver imagen de referencia "reference.png"
- Crear una  máscara sobre una imagen. La máscara debe ser un svg con path, ya que queremos animar el path.
- Como se ve en la imagen, la máscara debe crear una forma como de burbuja, con 2 bordes/strokes, uno ancho y otro fino, separados por una distancia "d" que podremos definir. También podremos definir el ancho de cada stroke y su color y opacidad.
- Ambos strokes / máscara deben animarse de manera orgánica y siempre deben ser paralelos, la animación debe ser similar en ambos strokes. Podremos definir/controlar la animación: velocidad, intensidad, etc.
- El svg puede ser creado programáticamente o añadido desde el dom como en el ejemplo.

# STACK
- vite
- vanilla javascript
- GSAP para la animación del path del svg

# TEMPLATE BASE DEL COMPONENTE
Vamos a utilizar como base del componente el que ya tenemos desarrollado en este tema de WordPress --> /Volumes/KRAKEN/HTDOCS/prefabricadosduero/app/public/wp-content/themes/pictau/javascript/modules/hero_slider.js

El módulo será importado desde /Volumes/KRAKEN/HTDOCS/prefabricadosduero/app/public/wp-content/themes/pictau/javascript/scripts.js

# FUNCIONALIDAD
Debemos usar intersectionObserver API para que la animación se detenga al salir del viewport, y se reanude al entrar en el viewport. Esto es muy importante por rendimiento.
