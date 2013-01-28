<?php
require_once(OF_FILEPATH.'/lib/meta-box.php');

$prefix = 'e404_';

$meta_boxes = array();

// Slide meta box
$meta_boxes[] = array(
	'id' => 'e404_slide',
	'title' => __('Slide options', 'cold'),
	'pages' => array('e404_slide'),

	'fields' => array(
		array(
			'name' => __('Target URL (optional)', 'cold'),
			'id' => $prefix.'slide_target_url',
			'type' => 'text'
		)
	)
);

$meta_sidebars = array();
foreach($e404_custom_sidebars as $sidebar) {
	$meta_sidebars[$sidebar] = $sidebar;
}

$meta_boxes[] = array(
	'id' => 'e404_blog_custom_sidebar',
	'title' => __('Custom Sidebar', 'cold'),
	'pages' => array('page', 'post'),
	'context' => 'side',

	'fields' => array(
		array(
			'name' => __('Sidebar', 'cold'),
			'id' => $prefix.'custom_sidebar',
			'type' => 'select',
			'options' => $meta_sidebars
		)
	)
);

foreach ($meta_boxes as $meta_box) {
	$my_box = new RW_Meta_Box($meta_box);
}

add_action('do_meta_boxes', 'e404_slide_image_box');
function e404_slide_image_box() {
	remove_meta_box('postimagediv', 'e404_slide', 'side');
	add_meta_box('postimagediv', __('Slide Image', 'cold'), 'post_thumbnail_meta_box', 'e404_slide', 'normal', 'high');
}

?>