<?php

/**
 * Utility functions and hooks that enhance the theme.
 *
 * This file only contains the blog-related functionality ported from the
 * base theme (balanzia project): "categoría principal" metabox, "entrada
 * destacada" metabox, the [blog_section] shortcode, and the admin post-list
 * "Destacada" state. Other utilities present in the base theme's
 * inc/utilities.php (catalog cards, casos de éxito grid, etc.) are specific
 * to other client projects and are intentionally not included here.
 *
 * @package pictau_tw
 */

if (!defined('ABSPATH')) exit;

//! BLOG/POSTS: AÑADIR FUNCIONALIDAD PARA DEFINIR UNA CATEGORÍA ASIGNADA A UN POST COMO PRINCIPAL O DESTACADA
// Añadimos el metabox
function categoria_principal_metabox()
{
	add_meta_box(
		'categoria_principal_metabox',
		'Categoría Principal',
		'render_categoria_principal_metabox',
		'post',
		'side',
		'high'
	);
}
add_action('add_meta_boxes', 'categoria_principal_metabox');


// Renderizar el campo de selección
function render_categoria_principal_metabox($post)
{
	$categorias = get_the_category($post->ID);
	$selected = get_post_meta($post->ID, '_categoria_principal', true);

	if (empty($categorias)) {
		echo __('Este post no tiene categorías asignadas.', 'pictau');
		return;
	}

	echo '<label for="categoria_principal_select">' . __('Selecciona una categoría principal:', 'pictau') . '</label><br>';
	echo '<select name="categoria_principal" id="categoria_principal_select">';
	foreach ($categorias as $cat) {
		$is_selected = ($selected == $cat->term_id) ? 'selected' : '';
		echo "<option value='{$cat->term_id}' $is_selected>{$cat->name}</option>";
	}
	echo '</select>';
	echo wp_nonce_field('guardar_categoria_principal', 'categoria_principal_nonce');
}

// 3. Guardar la categoría principal
function guardar_categoria_principal($post_id)
{
	if (!isset($_POST['categoria_principal_nonce']) || !wp_verify_nonce($_POST['categoria_principal_nonce'], 'guardar_categoria_principal')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	if (isset($_POST['categoria_principal'])) {
		update_post_meta($post_id, '_categoria_principal', intval($_POST['categoria_principal']));
	}
}
add_action('save_post', 'guardar_categoria_principal', 100);


////! Mostrar la categoría principal en el frontend
function categoria_principal_admin_script_condicional()
{
	$screen = get_current_screen();
	if ($screen->base !== 'post' || $screen->post_type !== 'post') return;

?>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			if (!wp || !wp.data || !wp.apiFetch) return;

			const metabox = document.getElementById('categoria_principal_metabox');
			const select = document.getElementById('categoria_principal_select');
			if (!metabox || !select) return;

			let lastCatIds = [];

			const updateIfChanged = () => {
				const categories = wp.data.select('core/editor').getEditedPostAttribute('categories') || [];

				// Comparar con las anteriores para evitar parpadeo
				const isSame = categories.length === lastCatIds.length &&
					categories.every((id, i) => id === lastCatIds[i]);

				if (isSame) return;

				// Guardar estado actual
				lastCatIds = [...categories];

				// Mostrar u ocultar metabox
				if (categories.length > 1) {
					metabox.style.display = '';
				} else {
					metabox.style.display = 'none';
				}

				if (categories.length === 0) {
					select.innerHTML = '';
					return;
				}

				// Cargar los nombres vía API
				wp.apiFetch({
						path: '/wp/v2/categories?include=' + categories.join(',')
					})
					.then(results => {
						const current = select.value;
						select.innerHTML = '';

						results.forEach(cat => {
							const option = document.createElement('option');
							option.value = cat.id;
							option.textContent = cat.name;
							if (cat.id == current) option.selected = true;
							select.appendChild(option);
						});

						// Si ninguna opción coincide, seleccionamos la primera
						if (!select.value && select.options.length > 0) {
							select.options[0].selected = true;
						}
					});
			};

			// Ejecutar al cargar
			updateIfChanged();

			// Suscribirse y ejecutar solo si cambian las categorías
			wp.data.subscribe(() => {
				updateIfChanged();
			});
		});
	</script>
<?php
}
add_action('admin_footer-post.php', 'categoria_principal_admin_script_condicional');



//! BLOG/POSTS: METABOX NATIVO "DESTACADA" (sustituye al campo booleano 'featured' de Pods)
function featured_post_metabox()
{
	add_meta_box(
		'featured_post_metabox',
		esc_html__('Entrada destacada', 'pictau'),
		'render_featured_post_metabox',
		'post',
		'side',
		'default'
	);
}
add_action('add_meta_boxes', 'featured_post_metabox');

function render_featured_post_metabox($post)
{
	$is_featured = get_post_meta($post->ID, 'featured', true);
	echo '<label><input type="checkbox" name="featured_post" value="1" ' . checked($is_featured, '1', false) . '> ' . esc_html__('Marcar como destacada', 'pictau') . '</label>';
	wp_nonce_field('guardar_featured_post', 'featured_post_nonce');
}

function guardar_featured_post($post_id)
{
	if (!isset($_POST['featured_post_nonce']) || !wp_verify_nonce($_POST['featured_post_nonce'], 'guardar_featured_post')) return;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	update_post_meta($post_id, 'featured', isset($_POST['featured_post']) ? '1' : '0');
}
add_action('save_post', 'guardar_featured_post', 100);



//! BLOG SECTION ANYWHERE, WITH FEATURED POST, FEATURED POSTS ROW (metabox nativo), AND GRID OF LATEST POSTS

// Shortcode: [blog_section]
add_shortcode('blog_section', function ($atts = []) {
	if (!function_exists('get_template_part')) return '';

	$a = shortcode_atts([
		// Featured (fila 1)
		'featured_source'         => 'auto',
		'featured_thumb'          => 'medium_large',

		// Fila 2 — entradas marcadas como destacadas (metabox nativo, ver featured_post_metabox())
		'featured_count'          => 4,

		// Grid (fila 3)
		'count'                   => 4,
		'grid_thumb'              => 'medium',

		// Filtros opcionales
		'category'                => '',
		'tag'                     => '',

		// Visual
		'show_category'           => 'true',
		'view_transition'         => 'false',
		'wrapper_class'           => '',
	], $atts, 'blog_section');

	$show_category   = filter_var($a['show_category'], FILTER_VALIDATE_BOOLEAN);
	$view_transition = filter_var($a['view_transition'], FILTER_VALIDATE_BOOLEAN);

	ob_start();

	// Lista de IDs a excluir en Fila 3
	$exclude_ids = [];


	/* -----------------------------------------------------------
		! Destacado —> Featured original (sticky o latest)
		 ----------------------------------------------------------- */

?>

	<section class="pct-section blog-section <?php echo esc_attr($a['wrapper_class']); ?>">

		<section class="pct-section latest-entry" data-anim_any data-anim_any_delay="0.3" data-anim_any_duration="1" data-anim_any_slideamount="50">
			<?php
			$sticky_posts = get_option('sticky_posts');
			$use_sticky   = !empty($sticky_posts);

			if ($a['featured_source'] === 'sticky' && empty($sticky_posts)) {
				$use_sticky = false;
			} elseif ($a['featured_source'] === 'latest') {
				$use_sticky = false;
			}

			if ($use_sticky) {
				$featured_q = new WP_Query([
					'post_type'            => 'post',
					'post__in'             => $sticky_posts,
					'ignore_sticky_posts'  => 1,
					'posts_per_page'       => 1,
					'post_status'          => 'publish',
					'orderby'              => 'date',
					'order'                => 'DESC',
				]);
			} else {
				$featured_q = new WP_Query([
					'post_type'            => 'post',
					'ignore_sticky_posts'  => 1,
					'posts_per_page'       => 1,
					'post_status'          => 'publish',
					'orderby'              => 'date',
					'order'                => 'DESC',
				]);
			}

			$featured_post_id = 0;

			if ($featured_q->have_posts()) {
				while ($featured_q->have_posts()) {
					$featured_q->the_post();
					$featured_post_id = get_the_ID();
					$exclude_ids[]    = $featured_post_id;

					get_template_part(
						'template-parts/content/content',
						'excerpt',
						[
							'featured'        => true,
							'show_category'   => $show_category,
							'view_transition' => $view_transition,
							'thumbnail_size'  => $a['featured_thumb'],
						]
					);
				}
				wp_reset_postdata();
			}
			?>
		</section>
		<?php
		/*!-- -----------------------------------------------------------
		     //! Fila 2 — Posts con campo nativo "featured" = true (metabox "Entrada destacada")
		     ----------------------------------------------------------- --*/
		?>


		<?php
		$featured_posts_q = new WP_Query([
			'post_type'      => 'post',
			'posts_per_page' => (int) $a['featured_count'],
			'meta_query'     => [
				[
					'key'     => 'featured',
					'value'   => '1',   // Guardado por guardar_featured_post() (metabox nativo, ya no depende de Pods)
					'compare' => '='
				]
			],
			'post__not_in'   => $exclude_ids,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		]);

		$featured_ids = [];


		if ($featured_posts_q->have_posts()) {
			echo '<section class="pct-section featured-posts latest-entries no-pt theme-color-A" data-anim_any data-anim_any_delay="0.45" data-anim_any_duration="1" data-anim_any_slideamount="50">';
			while ($featured_posts_q->have_posts()) {
				$featured_posts_q->the_post();
				$id = get_the_ID();
				$featured_ids[] = $id;
				$exclude_ids[]  = $id;

				get_template_part(
					'template-parts/content/content',
					'excerpt',
					[
						'show_category'   => $show_category,
						'view_transition' => $view_transition,
						'thumbnail_size'  => $a['grid_thumb'], // misma plantilla que Fila 3
					]
				);
			}
			echo '</section>';
			wp_reset_postdata();
		}


		?>

		<?php
		/*-- -----------------------------------------------------------
		     //! Fila 3 — Últimos posts normales, excluyendo fila 1 y fila 2
		     ----------------------------------------------------------- --*/
		?>

		<section class="pct-section latest-entries no-pt theme-color-A" data-anim_any data-anim_any_delay="0.6" data-anim_any_duration="1" data-anim_any_slideamount="50">
			<?php
			$tax_query = [];

			if ($a['category'] !== '') {
				$tax_query[] = [
					'taxonomy' => 'category',
					'field'    => is_numeric($a['category']) ? 'term_id' : 'slug',
					'terms'    => is_numeric($a['category']) ? (int) $a['category'] : sanitize_title($a['category']),
				];
			}
			if ($a['tag'] !== '') {
				$tax_query[] = [
					'taxonomy' => 'post_tag',
					'field'    => is_numeric($a['tag']) ? 'term_id' : 'slug',
					'terms'    => is_numeric($a['tag']) ? (int) $a['tag'] : sanitize_title($a['tag']),
				];
			}

			$args = [
				'post_type'            => 'post',
				'posts_per_page'       => max(0, (int) $a['count']),
				'orderby'              => 'date',
				'order'                => 'DESC',
				'post__not_in'         => $exclude_ids, // Muy importante
				'ignore_sticky_posts'  => 1,
				'post_status'          => 'publish',
			];

			if (!empty($tax_query)) {
				$args['tax_query'] = $tax_query;
			}

			$latest_q = new WP_Query($args);

			if ($latest_q->have_posts()) {
				while ($latest_q->have_posts()) {
					$latest_q->the_post();

					get_template_part(
						'template-parts/content/content',
						'excerpt',
						[
							'show_category'   => $show_category,
							'view_transition' => $view_transition,
							'thumbnail_size'  => $a['grid_thumb'],
						]
					);
				}
				wp_reset_postdata();
			}
			?>
		</section>
	</section>

<?php
	return ob_get_clean();
});


//! Añadir estado personalizado "Destacada" en el listado de entradas si el custom field 'featured' está marcado. Complemento para el shortcode más arriba de [blog_section]

add_filter('display_post_states', function ($states, $post) {

	// Solo para posts
	if ($post->post_type !== 'post') {
		return $states;
	}

	// Solo si el post está publicado
	if ($post->post_status !== 'publish') {
		return $states;
	}

	// Comprobar el custom field 'featured' (guardado por el metabox nativo "Entrada destacada", ver guardar_featured_post())
	$is_featured = get_post_meta($post->ID, 'featured', true);

	// Si está marcado, añadimos el estado
	if ($is_featured == '1') {
		$states['featured-post'] = ' <span style="background-color:yellow;display:inline-flex;padding: .2ch .5ch;border:1px solid #cdcd05;">Destacada</span>';
	}

	return $states;
}, 10, 2);
