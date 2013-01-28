<?php
if ( STYLESHEETPATH == TEMPLATEPATH ) {
	define('OF_FILEPATH', TEMPLATEPATH);
	define('OF_DIRECTORY', get_template_directory_uri());
} else {
	define('OF_FILEPATH', STYLESHEETPATH);
	define('OF_DIRECTORY', get_stylesheet_directory_uri());
}

if(!isset($content_width))
	$content_width = 930;

load_theme_textdomain('cold', OF_FILEPATH.'/languages/');

require_once(OF_FILEPATH.'/inc/theme-defaults.php');
require_once(OF_FILEPATH.'/inc/dashboard-widget.php');

require_once(OF_FILEPATH.'/admin/admin-functions.php');
require_once(OF_FILEPATH.'/admin/admin-interface.php');

require_once(OF_FILEPATH.'/inc/custom-sidebars.php');

require_once(OF_FILEPATH.'/inc/meta-boxes.php');
require_once(OF_FILEPATH.'/inc/custom-post-types.php');

if(is_admin())
	require_once (OF_FILEPATH.'/admin/theme-options.php');
require_once (OF_FILEPATH.'/admin/theme-functions.php');

require_once(OF_FILEPATH.'/inc/shortcodes.php');
require_once(OF_FILEPATH.'/inc/tools.php');
require_once(OF_FILEPATH.'/inc/widgets.php');

require_once(OF_FILEPATH.'/inc/theme-options.php');

require_once(OF_FILEPATH.'/inc/shortcode-manager.php');

// theme settings
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');

// register sidebars
register_sidebar(array(	'id' => 'e404_blog_sidebar',
						'name' => 'Blog Sidebar',
						'before_widget' => '<div id="%1$s" class="widgets %2$s">',
						'after_widget' => "</div>\n",
						'before_title' => '<h3>',
						'after_title' => "</h3>\n"));

register_sidebar(array(	'id' => 'e404_page_sidebar',
						'name' => 'Page Sidebar',
						'before_widget' => '<div id="%1$s" class="widgets %2$s">',
						'after_widget' => "</div>\n",
						'before_title' => '<h3>',
						'after_title' => "</h3>\n"));

register_sidebar(array(	'id' => 'e404_portfolio_sidebar',
						'name' => 'Portfolio Sidebar',
						'before_widget' => '<div id="%1$s" class="widgets %2$s">',
						'after_widget' => "</div>\n",
						'before_title' => '<h3>',
						'after_title' => "</h3>\n"));

register_sidebar(array(	'id' => 'e404_gallery_sidebar',
						'name' => 'Gallery Sidebar',
						'before_widget' => '<div id="%1$s" class="widgets %2$s">',
						'after_widget' => "</div>\n",
						'before_title' => '<h3>',
						'after_title' => "</h3>\n"));

if($e404_all_options['e404_footer_columns'] == '1')
	$footer_class = 'full_page';
elseif($e404_all_options['e404_footer_columns'] == '2')
	$footer_class = 'one_half';
elseif($e404_all_options['e404_footer_columns'] == '3')
	$footer_class = 'one_third';
elseif($e404_all_options['e404_footer_columns'] == '4')
	$footer_class = 'one_fourth';
else
	$footer_class = 'one_fourth';

register_sidebar(array(	'id' => 'e404_footer_sidebar',
						'name' => 'Footer Sidebar',
						'before_widget' => '<div id="%1$s" class="'.$footer_class.' widgets %2$s">',
						'after_widget' => "</div>\n",
						'before_title' => '<h3>',
						'after_title' => "</h3>\n"));

// register menu
function e404_register_header_menu() {
	register_nav_menus(array('header-menu' => __('Header Menu', 'cold')));
}
add_action('init', 'e404_register_header_menu');

if(!is_admin()) {
	add_action('wp_header', 'e404_custom_colors_css');
	
	require_once(OF_FILEPATH.'/inc/tweaks.php');
	require_once(OF_FILEPATH.'/inc/sliders.php');

	add_action('init', 'e404_enqueue_scripts_and_styles');

	function e404_enqueue_scripts_and_styles() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('preloader', OF_DIRECTORY.'/js/preloader.js');
	
		$disabled = get_option('e404_disable_galleria');
		if($disabled != 'true') {
			wp_enqueue_script('galleria', OF_DIRECTORY.'/js/galleria.min.js', '', '', true);
			wp_enqueue_script('galleria-classic', OF_DIRECTORY.'/js/galleria.classic.min.js', '', '', true);
			wp_enqueue_style('galleria', OF_DIRECTORY.'/css/galleria.classic.css');
		}
	
		$disabled = get_option('e404_disable_video_shortcode');
		if($disabled != 'true') {
			wp_enqueue_script('flowplayer', OF_DIRECTORY.'/js/flowplayer.min.js', '', '', true);
		}
	
		// menu 
		wp_enqueue_script('hoverintent', OF_DIRECTORY.'/js/hoverIntent.js');
		wp_enqueue_script('superfish', OF_DIRECTORY.'/js/superfish.js');
		wp_enqueue_style('superfish', OF_DIRECTORY.'/css/menu.css');
	
		// scrollable
		$disabled = get_option('e404_disable_scrollable');
		if($disabled != 'true') {
			wp_enqueue_script('scrollable', OF_DIRECTORY.'/js/scrollable.min.js');
			wp_enqueue_style('scrollable', OF_DIRECTORY.'/css/scrollable.css');
		}
	
		// Nivo shortcode
		$disabled = get_option('e404_disable_nivo');
		if($disabled != 'true') {
			wp_enqueue_script('nivo', OF_DIRECTORY.'/js/jquery.nivo.slider.pack.js', '', '', true);
			wp_enqueue_style('nivo', OF_DIRECTORY.'/css/nivo-slider.css');
		}	
	
		wp_enqueue_script('prettyphoto', OF_DIRECTORY.'/js/jquery.prettyphoto.js');
		wp_enqueue_script('prettyphoto-init', OF_DIRECTORY.'/js/prettyphoto_init.js');
		wp_enqueue_style('prettyphoto', OF_DIRECTORY.'/css/prettyphoto.css');
	
		// sortable portfolio
		function e404_sortable_scripts() {
			$sortable_templates = array('portfolio-3columns-sortable.php', 'portfolio-4columns-sortable.php');
			if(in_array(e404_get_current_template(), $sortable_templates)) {
				wp_enqueue_script('quicksand', OF_DIRECTORY.'/js/jquery.quicksand.js', '', '', true);
				wp_enqueue_script('sortable', OF_DIRECTORY.'/js/sortable.js', '', '', true);
			}
		}
		add_action('get_header', 'e404_sortable_scripts');

		// custom JS scripts
		wp_enqueue_script('cold-custom', OF_DIRECTORY.'/js/cold_custom.js', '', '', true);
	}
	
	$gwf = get_option('e404_google_web_fonts');
	if($gwf == 'true') {
		add_action('init', 'e404_google_web_fonts');
		add_action('wp_head', 'e404_google_web_fonts_css');
	}
}

// add Google Web Fonts scripts to page header
function e404_google_web_fonts() {
	$gwf_fonts[] = $gwf['body'] = get_option('e404_gwf_body');
	$gwf_fonts[] = $gwf['h1'] = get_option('e404_gwf_h1');
	$gwf_fonts[] = $gwf['h2'] = get_option('e404_gwf_h2');
	$gwf_fonts[] = $gwf['h3'] = get_option('e404_gwf_h3');
	$gwf_fonts[] = $gwf['h4'] = get_option('e404_gwf_h4');
	$gwf_fonts[] = $gwf['h5'] = get_option('e404_gwf_h5');
	$gwf_fonts[] = $gwf['h6'] = get_option('e404_gwf_h6');
	$gwf_fonts[] = $gwf['p'] = get_option('e404_gwf_p');
	$gwf_fonts[] = $gwf['blockquote'] = get_option('e404_gwf_blockquote');
	$gwf_fonts[] = $gwf['li'] = get_option('e404_gwf_li');
	$gwf_fonts[] = $gwf['.sf-menu li'] = get_option('e404_gwf_menu');
	$gwf_fonts[] = $gwf['.sf-menu li li'] = get_option('e404_gwf_submenu');
	
	$gwf_fonts = array_unique($gwf_fonts);
	wp_cache_add('e404_gwf', $gwf);
	foreach($gwf_fonts as $font) {
		if($font != '') {
			wp_enqueue_style(str_replace(array(':', '+'), '-', $font), 'http://fonts.googleapis.com/css?family='.$font);
		}
	}
}

// generate Google Web Fonts custom CSS
function e404_google_web_fonts_css() {
	$output = '<style type="text/css">';
	$gwf = wp_cache_get('e404_gwf');
	foreach($gwf as $tag => $font) {
		if($font != '') {
			$font = explode(':', $font);
			$output .= $tag." { font-family: '".str_replace('+', ' ', $font[0])."', arial, serif; }\n";
		}
	}
	$output .= '</style>';
	echo $output;
}

// generate custom colors CSS
function e404_custom_colors_css() {
	global $e404_all_options;
	
	$css = '<style type="text/css">'."\n";

	if(!empty($e404_all_options['e404_custom_background_picture'])) {
		$css .= "#header_wrapper {\n";
		$css .= "    background-image: url('".$e404_all_options['e404_custom_background_picture']."');\n";
		$css .= "    background-repeat: no-repeat;\n";
		$css .= "    background-position: 50% 0;\n";
		$css .= "}\n";
	}
	elseif(!empty($e404_all_options['e404_background_picture'])) {
		$css .= "#header_wrapper {\n";
		$css .= "    background-image: url('".OF_DIRECTORY."/images/bg/".$e404_all_options['e404_background_picture'].".png');\n";
		$css .= "    background-repeat: no-repeat;\n";
		$css .= "    background-position: 50% 0;\n";
		$css .= "}\n";
	}
	elseif(!empty($e404_all_options['e404_custom_background_texture'])) {
		$css .= "#header_wrapper {\n";
		$css .= "    background-image: url('".$e404_all_options['e404_custom_background_texture']."');\n";
		$css .= "    background-repeat: repeat;\n";
		$css .= "}\n";
	}
	elseif(!empty($e404_all_options['e404_background_texture'])) {
		$css .= "#header_wrapper {\n";
		$css .= "    background-image: url('".OF_DIRECTORY."/images/bg/".$e404_all_options['e404_background_texture'].".png');\n";
		$css .= "    background-repeat: repeat;\n";
		$css .= "}\n";
	}

	if($e404_all_options['e404_custom_style'] != 'true') {
		$css .= "</style>\n";
		echo $css;
		return;
	}

	if(!empty($e404_all_options['e404_color_background'])) {
		$css .= "body {\n    background-color: ".$e404_all_options['e404_color_background'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_main'])) {
		$css .= "html, body, form {\n    color: ".$e404_all_options['e404_color_main'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_forms'])) {
		$css .= "input, textarea, button, select, option {\n    color: ".$e404_all_options['e404_color_forms'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_links'])) {
		$css .= "a {\n    color: ".$e404_all_options['e404_color_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_links_hover'])) {
		$css .= "a:hover {\n    color: ".$e404_all_options['e404_color_links_hover'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_header_background'])) {
		$css .= "#header_wrapper {\n    background-color: ".$e404_all_options['e404_color_header_background'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_header_h2_border_left'])) {
		$css .= "#main_wrapper h2 {\n    border-left-color: ".$e404_all_options['e404_color_header_h2_border_left'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_social_icons_opacity'])) {
		$css .= "#social_icons a img {\n    filter: alpha(opacity=".$e404_all_options['e404_social_icons_opacity']."); opacity: ".($e404_all_options['e404_social_icons_opacity']/100).";\n}\n";
	}
	if(!empty($e404_all_options['e404_social_icons_opacity_hover'])) {
		$css .= "#social_icons a:hover img {\n    filter: alpha(opacity=".$e404_all_options['e404_social_icons_opacity_hover']."); opacity: ".($e404_all_options['e404_social_icons_opacity_hover']/100).";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_headers'])) {
		$css .= "h1, h2, h3, h4, h5, h6 {\n    color: ".$e404_all_options['e404_color_headers'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_headers_links'])) {
		$css .= "h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {\n    color: ".$e404_all_options['e404_color_headers_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_headers_links_hover'])) {
		$css .= "h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {\n    color: ".$e404_all_options['e404_color_headers_links_hover'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_intro'])) {
		$css .= "#intro {\n    color: ".$e404_all_options['e404_color_intro'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_intro_strong'])) {
		$css .= "#intro strong {\n    color: ".$e404_all_options['e404_color_intro_strong'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_intro_links'])) {
		$css .= "#intro a {\n    color: ".$e404_all_options['e404_color_intro_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_intro_headers'])) {
		$css .= "#intro h1, #intro h2, #intro h3, #intro h4, #intro h5, #intro h6 {\n    color: ".$e404_all_options['e404_color_intro_headers'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_footer_background'])) {
		$css .= "#footer {\n    background-color: ".$e404_all_options['e404_color_footer_background']."; \n}\n";
	}
	if(!empty($e404_all_options['e404_color_footer_wrapper'])) {
		$css .= "html, #footer_wrapper {\n    background-color: ".$e404_all_options['e404_color_footer_wrapper'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_footer'])) {
		$css .= "#footer, #footer form {\n    color: ".$e404_all_options['e404_color_footer'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_footer_links'])) {
		$css .= "#footer a {\n    color: ".$e404_all_options['e404_color_footer_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_footer_headers'])) {
		$css .= "#footer h1, #footer h2, #footer h3, #footer h4, #footer h5, #footer h6 {\n    color: ".$e404_all_options['e404_color_footer_headers'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_copyright_text'])) {
		$css .= "#copyright {\n    color: ".$e404_all_options['e404_color_copyright_text'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_copyright_background'])) {
		$css .= "#copyright {\n    background: ".$e404_all_options['e404_color_copyright_background'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_color_copyright_links'])) {
		$css .= "#copyright a {\n    color: ".$e404_all_options['e404_color_copyright_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_submit_style']) || !empty($e404_all_options['e404_submit_background']) || !empty($e404_all_options['e404_submit_text'])) {
		$css .= "form input[type=\"submit\"] {\n";
		if(!empty($e404_all_options['e404_submit_style'])) {
			if($e404_all_options['e404_submit_style'] == 'none')
				$css .= "    background-image: none;\n";
			elseif($e404_all_options['e404_submit_style'] == 'gradient')
				$css .= "    background-image: url('".OF_DIRECTORY."/images/gradient-btn.png');\n";
			else
				$css .= "    background-image: url('".OF_DIRECTORY."/images/glass-btn.png');\n";
		}
		if(!empty($e404_all_options['e404_submit_background']))
			$css .= "    background-color: ".$e404_all_options['e404_submit_background'].";\n";
		if(!empty($e404_all_options['e404_submit_text']))
			$css .= "    color: ".$e404_all_options['e404_submit_text'].";\n";
		$css .= "}\n";
	}
	if(!empty($e404_all_options['e404_menu_background']) && !empty($e404_all_options['e404_menu_background_opacity'])) {
		$bgcolor = hex2RGB($e404_all_options['e404_menu_background']);
		$bgopacity = $e404_all_options['e404_menu_background_opacity']/100;
		$css .= "#navigation {\n    background: rgb(".$bgcolor.");\n		background: rgba(".$bgcolor.", ".$bgopacity.");\n}\n";
	}
	if(!empty($e404_all_options['e404_menu_links'])) {
		$css .= ".sf-menu a {\n    color: ".$e404_all_options['e404_menu_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_menu_current_text']) || !empty($e404_all_options['e404_menu_current_border_bottom'])) {
		$css .= ".sf-menu li:hover a, .sf-menu li.current-menu-item a, .sf-menu li.current-page-parent a, .sf-menu li.current-page-ancestor a {\n";
		if(!empty($e404_all_options['e404_menu_current_text']))
			$css .= "    color: ".$e404_all_options['e404_menu_current_text'].";\n";
		if(!empty($e404_all_options['e404_menu_current_border_bottom']))
			$css .= "    border-bottom-color: ".$e404_all_options['e404_menu_current_border_bottom'].";\n";
		$css .= "}\n";
	}
	if(!empty($e404_all_options['e404_menu_current_border_bottom'])) {
		$css .= ".sf-menu li:hover li a:hover, .sf-menu li li.current-menu-item a {\n    background: ".$e404_all_options['e404_menu_current_border_bottom'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_menu_submenu_top_border'])) {
		$css .= ".sf-menu li ul {\n    border-top-color: ".$e404_all_options['e404_menu_submenu_top_border'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_menu_submenu_background'])) {
		$css .= ".sf-menu li:hover li a, .sf-menu li li a, .sf-menu li.current-menu-item li a, .sf-menu li.current-page-parent li a, .sf-menu li.current-page-ancestor li a {\n";
		$css .= "    background: ".$e404_all_options['e404_menu_submenu_background'].";\n";
		$css .= "}\n";
	}
	if(!empty($e404_all_options['e404_menu_submenu_links'])) {
		$css .= ".sf-menu li:hover li a, .sf-menu li li a, .sf-menu li.current-menu-item li a, .sf-menu li.current-page-parent li a, .sf-menu li.current-page-ancestor li a {\n    color: ".$e404_all_options['e404_menu_submenu_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_menu_submenu_hover'])) {
		$css .= ".sf-menu li:hover li a:hover, .sf-menu li li.current-menu-item a {\n    color: ".$e404_all_options['e404_menu_submenu_hover'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_pricebox_featured_border'])) {
		$css .= ".featured-box {\n    border-color: ".$e404_all_options['e404_pricebox_featured_border']." !important;\n}\n";
	}
	if(!empty($e404_all_options['e404_pricebox_featured_price'])) {
		$css .= ".featured-box strong {\n    color: ".$e404_all_options['e404_pricebox_featured_price']." !important;\n}\n";
	}
	if(!empty($e404_all_options['e404_pricebox_featured_header_text']) || !empty($e404_all_options['e404_pricebox_featured_header_background'])) {
		$css .= ".featured-box h3 {\n";
		if(!empty($e404_all_options['e404_pricebox_featured_header_text']))
			$css .= "    color: ".$e404_all_options['e404_pricebox_featured_header_text']." !important;\n";
		if(!empty($e404_all_options['e404_pricebox_featured_header_background']))
			$css .= "    background: ".$e404_all_options['e404_pricebox_featured_header_background']." !important;\n";
		$css .= "}\n";
	}
	if(!empty($e404_all_options['e404_color_slider_title']) || !empty($e404_all_options['e404_color_slider_title_background'])) {
		$css .= ".nivo-caption, .kwicks.horizontal p.title {\n";
		if(!empty($e404_all_options['e404_color_slider_title']))
			$css .= "    color: ".$e404_all_options['e404_color_slider_title']." !important;\n";
		if(!empty($e404_all_options['e404_color_slider_title_background']))
			$css .= "    background: ".$e404_all_options['e404_color_slider_title_background']." !important;\n";
		$css .= "}\n";
	}
	if($e404_all_options['e404_footer_border'] == 'enabled') {
		$css .= "#footer {\n";
		$css .= "	box-shadow: 4px 0 0 rgba(0, 0, 0, 0.02),-4px 0 0 rgba(0, 0, 0, 0.02);\n";
		$css .= "	-moz-box-shadow: 4px 0 0 rgba(0, 0, 0, 0.02),-4px 0 0 rgba(0, 0, 0, 0.02);\n";
		$css .= "	-webkit-box-shadow: 4px 0 0 rgba(0, 0, 0, 0.02),-4px 0 0 rgba(0, 0, 0, 0.02);\n";
		$css .= "}\n";
		$css .= "#copyright {\n";
		$css .= "	box-shadow: 4px 0 0 rgba(0, 0, 0, 0.02),-4px 0 0 rgba(0, 0, 0, 0.02),0 4px 0 rgba(0, 0, 0, 0.02);\n";
		$css .= "	-moz-box-shadow: 4px 0 0 rgba(0, 0, 0, 0.02),-4px 0 0 rgba(0, 0, 0, 0.02),0 4px 0 rgba(0, 0, 0, 0.02);\n";
		$css .= "	-webkit-box-shadow: 4px 0 0 rgba(0, 0, 0, 0.02),-4px 0 0 rgba(0, 0, 0, 0.02),0 4px 0 rgba(0, 0, 0, 0.02);\n";
		$css .= "}\n";
	}
	elseif($e404_all_options['e404_footer_border'] == 'disabled') {
		$css .= "#footer {\n";
		$css .= "	box-shadow: none;\n";
		$css .= "	-moz-box-shadow: none;\n";
		$css .= "	-webkit-box-shadow: none;\n";
		$css .= "}\n";
		$css .= "#copyright {\n";
		$css .= "	box-shadow: none;\n";
		$css .= "	-moz-box-shadow: none;\n";
		$css .= "	-webkit-box-shadow: none;\n";
		$css .= "}\n";
	}
	if(!empty($e404_all_options['e404_featured_text'])) {
		$css .= "#featured-boxes {\n    color: ".$e404_all_options['e404_featured_text'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_featured_top_border'])) {
		$css .= "#featured-boxes {\n    border-top-color: ".$e404_all_options['e404_featured_top_border'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_featured_links'])) {
		$css .= "#featured-boxes a, #featured-boxes h4 a {\n    color: ".$e404_all_options['e404_featured_links'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_featured_background_light'])) {
		$css .= "#featured-boxes li {\n    background-color: ".$e404_all_options['e404_featured_background_light'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_featured_background_dark'])) {
		$css .= "#featured-boxes li.featured-dark {\n    background-color: ".$e404_all_options['e404_featured_background_dark'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_featured_hover'])) {
		$css .= "#featured-boxes li:hover {\n    background-color: ".$e404_all_options['e404_featured_hover'].";\n}\n";
		$css .= "#featured-boxes:hover {\n    border-top-color: ".$e404_all_options['e404_featured_hover'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_featured_title'])) {
		$css .= "#featured-boxes h4 {\n    color: ".$e404_all_options['e404_featured_title'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_widgets_title'])) {
		$css .= "#sidebar .widgets h3, #page-content .widgets h3 {\n    color: ".$e404_all_options['e404_widgets_title'].";\n}\n";
	}
	if(!empty($e404_all_options['e404_widgets_background']) || !empty($e404_all_options['e404_widgets_text']) || !empty($e404_all_options['e404_widgets_border'])) {
		$css .= "#sidebar .widgets, #page-content .widgets {\n";
		if(!empty($e404_all_options['e404_widgets_background']))
			$css .= "    background: ".$e404_all_options['e404_widgets_background'].";\n";
		if(!empty($e404_all_options['e404_widgets_text']))
			$css .= "    color: ".$e404_all_options['e404_widgets_text'].";\n";
		if(!empty($e404_all_options['e404_widgets_border']))
			$css .= "    border: 1px solid ".$e404_all_options['e404_widgets_border'].";\n";
		$css .= "}\n";
	}

	$css .= "</style>\n";
	
	echo $css;
}
add_action('wp_head', 'e404_custom_colors_css');

// display header social icons
function e404_show_header_social_icons() {
	global $e404_all_options;
	
	$sites = array('Contact', 'RSS', 'Twitter', 'Facebook', 'Flickr', 'Buzz', 'Google+', 'Behance', 'Delicious', 'Digg', 'Dribbble', 'DropBox', 'Foursquare', 'iChat', 'LastFM', 'LinkedIn', 'MobyPicture', 'MySpace', 'Skype', 'StumbleUpon', 'Tumblr', 'Vimeo', 'YouTube', 'Xing');
	
	$color = ($e404_all_options['e404_social_icons_variant'] == 'black') ? '-b' : '';
	$social_media = array();

	$i = 0;
	foreach($sites as $site) {
		$name = $site;
		$site = strtolower($site);
		if($site == 'google+')
			$site = 'plus';
		if(isset($e404_all_options['e404_'.$site]) && trim($e404_all_options['e404_'.$site]) != '') {
			$social_media[$i]['name'] = $name;
			if($site == 'twitter')
				$social_media[$i]['url'] = 'http://twitter.com/'.$e404_all_options['e404_twitter'];
			else
				$social_media[$i]['url'] = $e404_all_options['e404_'.$site];
			$social_media[$i]['icon'] = OF_DIRECTORY.'/images/socialmedia/'.$site.$color.'.png';
			$i++;
		}
	}
	$output = '';
	if($e404_all_options['e404_header_social_icons_target'] == 'true')
		$target = ' target="_blank"';
	else
		$target = '';
	foreach($social_media as $site) {
		$output .= '<a href="'.$site['url'].'"'.$target.' title="'.$site['name'].'"><img src="'.$site['icon'].'" alt="'.$site['name'].'" /></a>'."\n";
	}
	echo $output;
}

// template redirects for portfolio sections
function e404_portfolio_template($templates) {
	$page_id = get_option('e404_portfolio_page');
	$template_name = get_post_meta($page_id, '_wp_page_template', true);
	$template = OF_FILEPATH.'/'.$template_name;
	if(!is_file($template)) {
		echo 'Portfolio page not found. Please choose your portfolio page in Appearance -> Theme Options -> Portfolio Options.';
		exit();
	}
	return $template;
}
add_filter('taxonomy_template', 'e404_portfolio_template');

// excerpt customization
function e404_excerpt_more($more) {
	return '';
}
add_filter('excerpt_more', 'e404_excerpt_more');
function e404_excerpt_length($length) {
	return 9999;
}
add_filter('excerpt_length', 'e404_excerpt_length');

// current templates magic
function e404_template_include($template) {
    $GLOBALS['e404_current_template'] = basename($template);
    return $template;
}
add_filter('template_include', 'e404_template_include', 1000);

function e404_get_current_template() {
    if(!isset( $GLOBALS['e404_current_template']))
        return false;
    else
        return $GLOBALS['e404_current_template'];
}

add_filter('gallery_style', 
	create_function(
		'$css',
		'return preg_replace("#<style type=\'text/css\'>(.*?)</style>#s", "", $css);'
		)
	);

// comment form
function e404_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li id="li-comment-<?php comment_ID(); ?>">
		<div <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div class="comment-box">
			<div class="border-img leftside avatar-box"><?php echo get_avatar($comment, 40, OF_DIRECTORY.'/images/avatar.png'); ?></div>
			<div class="comment-text">
			<?php printf( __( sprintf('<cite class="comment-author">%s</cite>', get_comment_author_link() ), 'cold')); ?>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _e('Your comment is awaiting moderation.', 'cold'); ?></em>
				<br />
			<?php endif; ?>
				<span class="comment-date"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php printf( __('%1$s at %2$s', 'cold'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link(__('(Edit)', 'cold'), ' '); ?>
				</span>
				<p><?php comment_text(); ?></p>
				<div class="comment-reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'cold'); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'cold'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

?>