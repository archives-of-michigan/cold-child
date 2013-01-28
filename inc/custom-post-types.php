<?php
add_action('init', 'e404_slideshow_taxonomy_init');
function e404_slideshow_taxonomy_init() {
	$labels = array(
					'name' => __('Slideshows', 'cold'),
					'singular_name' => __('Slideshows', 'cold'),
					'all_items' => __('Slideshows', 'cold'),
					'edit_item' => __('Edit Slideshow', 'cold'),
					'update_item' => __('Update Slideshow', 'cold'),
					'add_new_item' => __('Add New Slideshow', 'cold'),
					'new_item_name' => __('New Slideshow Name', 'cold'),
					'search_items' => __('Search Slideshows', 'cold'),
					'parent_item' => __('Parent Slideshow', 'cold')
	);
	register_taxonomy('e404_slideshow', 'e404_slide',
		array(
			'labels' => $labels,
			'hierarchical' => true,
			'sort' => true,
			'args' => array('orderby' => 'term_order')
		)
	);
}

add_action('init', 'e404_slide_post_type_init');
function e404_slide_post_type_init() {
	$labels = array(
					'name' => __('Slides', 'cold'),
					'singular_name' => __('Slide', 'cold'),
					'add_new_item' => __('Add New Slide', 'cold'),
					'edit_item' => __('Edit Slide', 'cold'),
					'new_item' => __('New Slide', 'cold'),
					'view_item' => __('View Slide', 'cold'),
					'search_items' => __('Search Slides', 'cold'),
					'not_found' => __('No slides found', 'cold')
	);
	register_post_type('e404_slide',
		array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'has_archive' => false,
			'supports' => array('title', 'editor', 'page-attributes', 'thumbnail'),
			'taxonomies' => array('e404_slideshow'),
			'rewrite' => array('slug' => 'slide')
		)
	);
}

add_filter('manage_e404_slide_posts_columns', 'e404_slideshow_columns');
function e404_slideshow_columns($defaults) {
	$defaults['e404_slideshow'] = __('Slideshow', 'cold');
	return $defaults;
}

add_action('manage_posts_custom_column', 'e404_slideshow_column', 10, 2);
function e404_slideshow_column($column_name, $post_id) {
	$post_type = get_post_type($post_id);
	$terms = get_the_terms($post_id, $column_name);
	if (!empty($terms)) {
		foreach ($terms as $term)
			$post_terms[] = "<a href='edit.php?post_type={$post_type}&{$column_name}={$term->slug}'> " . esc_html($term->name) . "</a>";
		echo join( ', ', $post_terms );
	} else {
		echo '<i>'.__('None', 'cold').'</i>';
	}
}

add_action('init', 'e404_portfolio_post_type_init');
function e404_portfolio_post_type_init() {
	$labels = array(
					'name' => __('Portfolio', 'cold'),
					'singular_name' => __('Portfolio', 'cold'),
					'add_new_item' => __('Add New Portfolio Item', 'cold'),
					'edit_item' => __('Edit Portfolio Item', 'cold'),
					'new_item' => __('New Portfolio Item', 'cold'),
					'view_item' => __('View Portfolio Item', 'cold'),
					'search_items' => __('Search Portfolio', 'cold'),
					'not_found' => __('No portfolio items found', 'cold')
	);
	$slug = get_option('e404_portfolio_slug');
	if(!$slug)
		$slug = 'portfolio';
	register_post_type('portfolio',
		array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => false,
			'supports' => array('title', 'editor', 'page-attributes', 'thumbnail', 'excerpt'),
			'taxonomies' => array('portfolio-category', 'portfolio-tag'),
			'rewrite' => array('slug' => $slug)
		)
	);
	
	// add custom rewrite rule to avoid taxonomy, custom post types and page slug conflict
	$page_id = get_option('e404_portfolio_page');
	if($page_id != 0) {
		$page = get_page($page_id);
		if($page->post_name == $slug) {
			add_rewrite_rule($page->post_name.'/page/([0-9]+)/?$', 'index.php?pagename='.$page->post_name.'&paged=$matches[1]', 'top');
			$child_pages = get_pages(array('child_of' => $page_id));
			foreach($child_pages as $child_page) {
				add_rewrite_rule($page->post_name.'/'.$child_page->post_name.'/?$', 'index.php?pagename='.$page->post_name.'/'.$child_page->post_name, 'top');
				add_rewrite_rule($page->post_name.'/'.$child_page->post_name.'/page/([0-9]+)/?$', 'index.php?pagename='.$page->post_name.'/'.$child_page->post_name.'&paged=$matches[1]', 'top');
			}
		}
	}
}

function e404_get_taxonomy_name($taxonomy) {
	$name = '';
	switch ($taxonomy) {
		case 'categories':
			$name = get_option('e404_portfolio_categories_name');
			if(!$name || empty($name))
				$name = __('Portfolio Categories', 'cold');
			break;
		case 'tags':
			$name = get_option('e404_portfolio_tags_name');
			if(!$name || empty($name))
				$name = __('Portfolio Tags', 'cold');
			break;	
	}
	return $name;
}

add_action('init', 'e404_portfolio_category_taxonomy_init');
function e404_portfolio_category_taxonomy_init() {
	$categories_name = e404_get_taxonomy_name('categories');
	$labels = array(
					'name' => $categories_name
	);
	$slug = get_option('e404_portfolio_slug');
	if(!$slug)
		$slug = 'portfolio';
	$category_slug = get_option('e404_portfolio_category_slug');
	if(!$category_slug)
		$category_slug = 'category';
	register_taxonomy('portfolio-category', 'portfolio',
		array(
			'labels' => $labels,
			'hierarchical' => true,
			'sort' => true,
			'args' => array('orderby' => 'term_order'),
			'rewrite' => array('slug' => $slug.'/'.$category_slug))
	);
	add_rewrite_rule($slug.'/'.$category_slug.'/page/([0-9]+)/?$', 'index.php?portfolio-category=$matches[1]&paged=$matches[2]', 'top');
	add_rewrite_rule($slug.'/'.$category_slug.'/([^/]+)/?$', 'index.php?portfolio-category=$matches[1]', 'top');
}

add_action('init', 'e404_portfolio_tag_taxonomy_init');
function e404_portfolio_tag_taxonomy_init() {
	$tags_name = e404_get_taxonomy_name('tags');
	$labels = array(
					'name' => $tags_name
	);
	$slug = get_option('e404_portfolio_slug');
	if(!$slug)
		$slug = 'portfolio';
	$tag_slug = get_option('e404_portfolio_tag_slug');
	if(!$tag_slug)
		$tag_slug = 'tag';
	register_taxonomy('portfolio-tag', 'portfolio',
		array(
			'labels' => $labels,
			'hierarchical' => false,
			'sort' => true,
			'args' => array('orderby' => 'term_order'),
			'rewrite' => array('slug' => $slug.'/'.$tag_slug))
	);
	add_rewrite_rule($slug.'/'.$tag_slug.'/page/([0-9]+)/?$', 'index.php?portfolio-tag=$matches[1]&paged=$matches[2]', 'top');
	add_rewrite_rule($slug.'/'.$tag_slug.'/([^/]+)/?$', 'index.php?portfolio-tag=$matches[1]', 'top');
}

add_filter('manage_portfolio_posts_columns', 'e404_portfolio_columns');
function e404_portfolio_columns($defaults) {
	$defaults['portfolio-category'] = __('Category', 'cold');
	$defaults['portfolio-tag'] = __('Tags', 'cold');
	return $defaults;
}

add_action('init', 'e404_flush_rules', 99);
function e404_flush_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function e404_get_front() {
	global $wp_rewrite;
	return substr($wp_rewrite->front, 1, strlen($wp_rewrite->front) - 1);
}

// source: http://themeforest.net/forums/thread/wordpress-custom-page-type-taxonomy-pagination/43010?page=2#401663
$option_posts_per_page = get_option('posts_per_page');
add_action('init', 'e404_portfolio_per_page', 0);
function e404_portfolio_per_page() {
	add_filter('option_posts_per_page', 'e404_option_portfolio_per_page');
}
function e404_option_portfolio_per_page($value) {
	global $option_posts_per_page;
	if(is_tax('portfolio-category')) {
		return get_option('e404_portfolio_posts_per_page');
	} else {
		return $option_posts_per_page;
	}
}

?>