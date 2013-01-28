<?php
add_action('init','of_options');

if (!function_exists('of_options')) {
function of_options() {
	
// VARIABLES
$themename = get_theme_data(STYLESHEETPATH . '/style.css');
$themename = "Theme Options";
$shortname = "e404";

global $of_options, $e404_options, $social_options;
$of_options = get_option('of_options');

$GLOBALS['template_path'] = OF_DIRECTORY;

// Stylesheets Reader
$alt_stylesheet_path = OF_FILEPATH . '/styles/';
$alt_stylesheets = array();
if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}
sort($alt_stylesheets);
$alt_styles = array();
foreach($alt_stylesheets as $alt_style) {
	$alt_styles[$alt_style] = OF_DIRECTORY.'/styles/'.str_replace('.css', '.png', $alt_style);
}

// More Options
$uploads_arr = wp_upload_dir();
$all_uploads_path = $uploads_arr['path'];
$all_uploads = get_option('of_uploads');

// Slider options
$slider_options = array('' => 'None (disabled)', 'nivo' => 'Nivo Slider', 'accordion' => 'Accordion Slider', 'anything' => 'Anything Slider', 'galleria' => 'Galleria Slideshow');
$slideshows = get_terms('e404_slideshow');
$slideshow_options[0] = 'All';
foreach($slideshows as $slideshow)
	$slideshow_options[$slideshow->term_id] = $slideshow->name;

// Nivo options
$nivo_effects = array('random', 'sliceDown', 'sliceDownLeft', 'sliceUp', 'sliceUpLeft', 'sliceUpDown', 'sliceUpDownLeft', 'fold', 'fade', 'slideInRight', 'slideInLeft', 'boxRandom', 'boxRain', 'boxRainReverse');

// Accordion options
$easing_effects = array('none', 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint', 'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce');

// Galleria Slideshow options
$galleria_effects = array('fade', 'flash', 'pulse', 'slide', 'fadeslide');

// Intro text options
$intro_main_options = array('none' => 'Disabled', 'title' => 'Page Title', 'title-excerpt' => 'Page Title & Excerpt (if available)', 'html' => 'HTML/Text', 'twitter' => 'Last Twitter status');
$intro_options = array_merge($intro_main_options, array('main' => 'The same as defined in the main setting'));

// Background textures
$bgm_path = OF_DIRECTORY.'/images/bg/mini';
$background_textures = array('' => $bgm_path.'/blank.png', 'bg1' => $bgm_path.'/bg1.png', 'bg2' => $bgm_path.'/bg2.png', 'bg3' => $bgm_path.'/bg3.png', 'bg4' => $bgm_path.'/bg4.png', 'bg5' => $bgm_path.'/bg5.png',
							 'bg6' => $bgm_path.'/bg6.png', 'bg7' => $bgm_path.'/bg7.png', 'bg8' => $bgm_path.'/bg8.png', 'bg9' => $bgm_path.'/bg9.png', 'bg10' => $bgm_path.'/bg10.png',
							 'bg11' => $bgm_path.'/bg11.png', 'bg12' => $bgm_path.'/bg12.png', 'bg13' => $bgm_path.'/bg13.png', 'bg14' => $bgm_path.'/bg14.png', 'bg15' => $bgm_path.'/bg15.png',
							 'bg16' => $bgm_path.'/bg16.png', 'bg17' => $bgm_path.'/bg17.png', 'bg18' => $bgm_path.'/bg18.png', 'bg19' => $bgm_path.'/bg19.png', 'bg20' => $bgm_path.'/bg20.png',
							 'bg21' => $bgm_path.'/bg21.png', 'bg22' => $bgm_path.'/bg22.png', 'bg23' => $bgm_path.'/bg23.png', 'bg24' => $bgm_path.'/bg24.png', 'bg25' => $bgm_path.'/bg25.png',
							 'bg26' => $bgm_path.'/bg26.png', 'bg27' => $bgm_path.'/bg27.png', 'bg28' => $bgm_path.'/bg28.png', 'bg29' => $bgm_path.'/bg29.png', 'bg30' => $bgm_path.'/bg30.png',
							 'bg31' => $bgm_path.'/bg31.png', 'bg32' => $bgm_path.'/bg32.png', 'bg33' => $bgm_path.'/bg33.png', 'bg34' => $bgm_path.'/bg34.png');

$background_pictures = array('' => $bgm_path.'/blank.png', 'abstraction1' => $bgm_path.'/abstraction1.png', 'abstraction2' => $bgm_path.'/abstraction2.png',
							 'abstraction3' => $bgm_path.'/abstraction3.png', 'abstraction4' => $bgm_path.'/abstraction4.png', 'abstraction5' => $bgm_path.'/abstraction5.png',
							 'abstraction6' => $bgm_path.'/abstraction6.png');

// Pages
$a_pages = get_pages();
$all_pages = array();
$all_pages[0] = '-- none --';
foreach($a_pages as $a_page) {
	$all_pages[$a_page->ID] = get_the_title($a_page);
}

// Google Web Fonts
include(OF_FILEPATH.'/inc/google-fonts-list.php');

$options = array();

$options[] = array( "name" => "General Settings",
                    "type" => "heading");

$options[] = array( "name" => "Custom Logo",
					"desc" => "Upload a logo for your theme, or specify the image address of your online logo. (http://yoursite.com/logo.png)",
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px png/gif image that will represent your website's favicon.",
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload"); 

$options[] = array( "name" => "Show Breadcrumbs",
					"desc" => "Show the breadcrumb trail navigation.",
					"id" => $shortname."_breadcrumbs",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Page Titles",
					"desc" => "Show page titles above the page content.",
					"id" => $shortname."_page_titles",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Disable Search Form",
					"desc" => "Remove the search form from the header.",
					"id" => $shortname."_remove_search_form",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Enable PrettyPhoto",
					"desc" => "Enable PrettyPhoto (Lightbox clone) support for images.",
					"id" => $shortname."_gallery_prettyphoto",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Allow Page Comments",
					"desc" => "Enable comments on pages.",
					"id" => $shortname."_page_comments",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");                                                    
    
$options[] = array( "name" => "Background Settings",
                    "type" => "heading");

$options[] = array( "name" => "Top Background Texture",
					"desc" => "Select a top background texture.",
					"id" => $shortname."_background_texture",
					"std" => "",
					"type" => "images",
					"options" => $background_textures);

$options[] = array( "name" => "Custom Top Background Texture",
					"desc" => "Upload your own top background texture (above selection will be ignored).",
					"id" => $shortname."_custom_background_texture",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Top Background Picture",
					"desc" => "Select a top background picture (background texture will be disabled).",
					"id" => $shortname."_background_picture",
					"std" => "",
					"type" => "images",
					"options" => $background_pictures);

$options[] = array( "name" => "Custom Top Background Picture",
					"desc" => "Upload your own top background picture (above selection will be ignored).",
					"id" => $shortname."_custom_background_picture",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Styling Options",
					"type" => "heading");
					
$options[] = array( "name" => "Theme Variations",
					"desc" => "Select a theme color variation. You can change the color of specific elements below.",
					"id" => $shortname."_theme_style",
					"std" => "style_00.css",
					"type" => "images",
					"options" => $alt_styles);

$options[] = array( "name" => "Enable Theme Customization",
					"desc" => "Enable the theme customization (see options below).",
					"id" => $shortname."_custom_style",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Background Color",
					"desc" => "Set background color.",
					"id" => $shortname."_color_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Main Text Color",
					"desc" => "Set main text color.",
					"id" => $shortname."_color_main",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Forms Text Color",
					"desc" => "Set forms text color.",
					"id" => $shortname."_color_forms",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Link Color",
					"desc" => "Set links color.",
					"id" => $shortname."_color_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Link Hover Color",
					"desc" => "Set links hover color.",
					"id" => $shortname."_color_links_hover",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Headers Text Color",
					"desc" => "Set headers (h1 - h6) text color.",
					"id" => $shortname."_color_headers",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Headers Link Color",
					"desc" => "Set headers (h1 - h6) links color.",
					"id" => $shortname."_color_headers_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Headers Link Hover Color",
					"desc" => "Set headers (h1 - h6) links hover color.",
					"id" => $shortname."_color_headers_links_hover",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "H2 Left Border Color",
					"desc" => "Set H2 header left border color color.",
					"id" => $shortname."_color_header_h2_border_left",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Header Background Color",
					"desc" => "Set header background color.",
					"id" => $shortname."_color_header_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Header Social Icons Opacity",
					"desc" => "Set the opacity of header social icons (in percents).",
					"id" => $shortname."_social_icons_opacity",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Header Social Icons Hover Opacity",
					"desc" => "Set the hover opacity of header social icons (in percents).",
					"id" => $shortname."_social_icons_opacity_hover",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Intro Box Text Color",
					"desc" => "Set intro box text color.",
					"id" => $shortname."_color_intro",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Intro Box Strong Text Color",
					"desc" => htmlspecialchars("Set intro box <strong> text color."),
					"id" => $shortname."_color_intro_strong",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Intro Box Links Color",
					"desc" => "Set intro box links color.",
					"id" => $shortname."_color_intro_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Intro Box Headers Text Color",
					"desc" => "Set intro box headers (h1 - h6) text color.",
					"id" => $shortname."_color_intro_headers",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Slider Title Text Color",
					"desc" => "Set slider title text color.",
					"id" => $shortname."_color_slider_title",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Slider Title Background Color",
					"desc" => "Set slider background text color.",
					"id" => $shortname."_color_slider_title_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Footer Wrapper Color",
					"desc" => "Set footer wrapper color.",
					"id" => $shortname."_color_footer_wrapper",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Footer Background Color",
					"desc" => "Set footer background color.",
					"id" => $shortname."_color_footer_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Footer Headers Color",
					"desc" => "Set footer headers (h1 - h6) color.",
					"id" => $shortname."_color_footer_headers",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Footer Text Color",
					"desc" => "Set footer text color.",
					"id" => $shortname."_color_footer",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Footer Links Color",
					"desc" => "Set footer links color.",
					"id" => $shortname."_color_footer_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Footer Border",
					"desc" => "Enable or disable the footer border (empty for theme default).",
					"id" => $shortname."_footer_border",
					"std" => "",
					"type" => "select",
					"options" => array('', 'enabled', 'disabled'));

$options[] = array( "name" => "Copyright Text Color",
					"desc" => "Set copyright text color.",
					"id" => $shortname."_color_copyright_text",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Copyright Links Color",
					"desc" => "Set copyright text color.",
					"id" => $shortname."_color_copyright_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Copyright Background Color",
					"desc" => "Set copyright background color.",
					"id" => $shortname."_color_copyright_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Submit Button Style",
					"desc" => "Select a style of form submit buttons.",
					"id" => $shortname."_submit_style",
					"std" => "glass",
					"type" => "select",
					"options" => array('', 'none', 'glass', 'gradient'));

$options[] = array( "name" => "Submit Button Text Color",
					"desc" => "Set submit buttons text color.",
					"id" => $shortname."_submit_text",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Submit Button Background Color",
					"desc" => "Set submit buttons background color.",
					"id" => $shortname."_submit_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Menu Background Color",
					"desc" => "Set menu background color; you have also set the menu background opacity.",
					"id" => $shortname."_menu_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Menu Background Opacity",
					"desc" => "Set the opacity of the menu background (in percents); you have also set the menu background color.",
					"id" => $shortname."_menu_background_opacity",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Menu Links Color",
					"desc" => "Set menu links color.",
					"id" => $shortname."_menu_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Menu Links Hover & Current Page Text Color",
					"desc" => "Set menu links hover and current page text color.",
					"id" => $shortname."_menu_current_text",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Menu Links Hover & Current Page Bottom Border Color",
					"desc" => "Set menu links hover and current page bottom border color.",
					"id" => $shortname."_menu_current_border_bottom",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Submenu Background Color",
					"desc" => "Set submenu background color.",
					"id" => $shortname."_menu_submenu_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Submenu Top Border Color",
					"desc" => "Set submenu top border color.",
					"id" => $shortname."_menu_submenu_top_border",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Submenu Links Color",
					"desc" => "Set submenu links color.",
					"id" => $shortname."_menu_submenu_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Submenu Links Hover Color",
					"desc" => "Set submenu links hover color.",
					"id" => $shortname."_menu_submenu_hover",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Pricebox - Featured Box Border Color",
					"desc" => "Set featured box border color.",
					"id" => $shortname."_pricebox_featured_border",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Pricebox - Featured Box Header Text Color",
					"desc" => "Set featured box header text color.",
					"id" => $shortname."_pricebox_featured_header_text",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Pricebox - Featured Box Header Background Color",
					"desc" => "Set featured box header background color.",
					"id" => $shortname."_pricebox_featured_header_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Pricebox - Featured Box Price Text Color",
					"desc" => "Set featured box price text color.",
					"id" => $shortname."_pricebox_featured_price",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Home Page Featured Box Text Color",
					"desc" => "Set featured box text color.",
					"id" => $shortname."_featured_text",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Home Page Featured Box Links Color",
					"desc" => "Set featured box links color.",
					"id" => $shortname."_featured_links",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Home Page Featured Box Background Color (light)",
					"desc" => "Set light featured box background color.",
					"id" => $shortname."_featured_background_light",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Home Page Featured Box Background Color (dark)",
					"desc" => "Set dark featured box background color.",
					"id" => $shortname."_featured_background_dark",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Home Page Featured Box Hover Color",
					"desc" => "Set featured box hover color.",
					"id" => $shortname."_featured_hover",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Home Page Featured Box Top Border Color",
					"desc" => "Set featured box top border color.",
					"id" => $shortname."_featured_top_border",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Home Page Featured Box Title Color",
					"desc" => "Set featured box title color.",
					"id" => $shortname."_featured_title",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Widgets Background Color",
					"desc" => "Set widgets background color.",
					"id" => $shortname."_widgets_background",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Widgets Text Color",
					"desc" => "Set widgets text color.",
					"id" => $shortname."_widgets_text",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Widgets Headers Color",
					"desc" => "Set widgets headers color.",
					"id" => $shortname."_widgets_headers",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Widget Box Border Color",
					"desc" => "Set widget box border color.",
					"id" => $shortname."_widgets_border",
					"std" => "",
					"type" => "color"); 

$options[] = array( "name" => "Custom CSS",
                    "desc" => "Quickly add some CSS to your theme by adding it to this block.",
                    "id" => $shortname."_custom_css",
                    "std" => "",
                    "type" => "textarea");

$options[] = array( "name" => "Fonts Options",
					"type" => "heading");

$options[] = array( "name" => "Enable Fonts Replacement",
					"desc" => "Enable the fonts replacement with Google Web Fonts.<br />Go to the <a href='http://www.google.com/webfonts'>Google Font Directory</a> for a preview of available fonts.",
					"id" => $shortname."_google_web_fonts",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "body Font",
					"desc" => "Select a font to assign to 'body' tag.",
					"id" => $shortname."_gwf_body",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "p (paragraph) Font",
					"desc" => "Select a font to assign to 'p' tag.",
					"id" => $shortname."_gwf_p",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "h1 Font",
					"desc" => "Select a font to assign to 'h1' tag.",
					"id" => $shortname."_gwf_h1",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "h2 Font",
					"desc" => "Select a font to assign to 'h2' tag.",
					"id" => $shortname."_gwf_h2",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "h3 Font",
					"desc" => "Select a font to assign to 'h3' tag.",
					"id" => $shortname."_gwf_h3",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "h4 Font",
					"desc" => "Select a font to assign to 'h4' tag.",
					"id" => $shortname."_gwf_h4",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "h5 Font",
					"desc" => "Select a font to assign to 'h5' tag.",
					"id" => $shortname."_gwf_h5",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "h6 Font",
					"desc" => "Select a font to assign to 'h6' tag.",
					"id" => $shortname."_gwf_h6",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "blockquote Font",
					"desc" => "Select a font to assign to 'blockquote' tag.",
					"id" => $shortname."_gwf_blockquote",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "li Font",
					"desc" => "Select a font to assign to 'li' tag (lists).",
					"id" => $shortname."_gwf_li",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "Menu Font",
					"desc" => "Select a font to assign to the dropdown menu.",
					"id" => $shortname."_gwf_menu",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "Submenu Font",
					"desc" => "Select a font to assign to the dropdown menu (submenus).",
					"id" => $shortname."_gwf_submenu",
					"std" => "",
					"type" => "select2",
					"options" => $google_web_fonts);

$options[] = array( "name" => "Intro Text Box",
					"type" => "heading");

$options[] = array( "name" => "Intro Text Box",
					"desc" => "Select a content to display in the intro text box. You can choose other settings for different page types below.",
					"id" => $shortname."_intro_type",
					"std" => "title-excerpt",
					"type" => "select2",
					"options" => $intro_main_options);

$options[] = array( "name" => "HTML/Text",
					"desc" => "HTML/Text to display in the intro text box.",
					"id" => $shortname."_intro_text",
					"std" => "<p><strong>Welcome to Cold Theme: The ultimate all-in-one template.</strong><br />\nCreate outstanding website or blog in minutes!</p>",
					"type" => "textarea");                                                    

$options[] = array( "name" => "Intro Text Box for Home Page",
					"desc" => "Select a content to display in the intro text box on the Home Page.",
					"id" => $shortname."_home_intro_type",
					"std" => "html",
					"type" => "select2",
					"options" => $intro_options);

$options[] = array( "name" => "HTML/Text",
					"desc" => "HTML/Text to display in the intro text box on Home Page.",
					"id" => $shortname."_home_intro_text",
					"std" => "[three_fifth]\n<p><strong>Welcome to Cold Theme: The ultimate all-in-one template.</strong><br />\nCreate outstanding website or blog in minutes!</p>\n[/three_fifth]\n[two_fifth_last align=\"center\"]\n[button size=\"big\" icon=\"arrow\" style=\"gradient\" stroke=\"true\" href=\"http://themeforest.net/user/e404\"]Available on ThemeForest[/button]\n[/two_fifth_last]",
					"type" => "textarea");                                                    

$options[] = array( "name" => "Intro Text Box for Blog Pages",
					"desc" => "Select a content to display in the intro text box on Blog Pages.",
					"id" => $shortname."_blog_intro_type",
					"std" => "main",
					"type" => "select2",
					"options" => $intro_options);

$options[] = array( "name" => "HTML/Text",
					"desc" => "HTML/Text to display in the intro text box on Blog Pages.",
					"id" => $shortname."_blog_intro_text",
					"std" => "<h1>Blog</h1>\n<p>You can enter your own text here!</p>",
					"type" => "textarea");                                                    

$options[] = array( "name" => "Intro Text Box for Portfolio Pages",
					"desc" => "Select a content to display in the intro text box on Portfolio Pages.",
					"id" => $shortname."_portfolio_intro_type",
					"std" => "main",
					"type" => "select2",
					"options" => $intro_options);

$options[] = array( "name" => "HTML/Text",
					"desc" => "HTML/Text to display in the intro text box on Portfolio Pages.",
					"id" => $shortname."_portfolio_intro_text",
					"std" => "<h1>Portfolio</h1>\n<p>Show your best projects in several different ways.</p>",
					"type" => "textarea");                                                    

$options[] = array( "name" => "Intro Text Box for Gallery Pages",
					"desc" => "Select a content to display in the intro text box on Gallery Pages.",
					"id" => $shortname."_gallery_intro_type",
					"std" => "main",
					"type" => "select2",
					"options" => $intro_options);

$options[] = array( "name" => "HTML/Text",
					"desc" => "HTML/Text to display in the intro text box on Gallery Pages.",
					"id" => $shortname."_gallery_intro_text",
					"std" => "<h1>Gallery</h1>\n<p>Show your pictures with WordPress built-in gallery or Galleria slideshow.</p>",
					"type" => "textarea");                                                    

$options[] = array( "name" => "Post Excerpt Length",
					"desc" => "Define the maximum length of a post excerpt.",
					"id" => $shortname."_excerpt_length",
					"std" => "100",
					"type" => "text");

$options[] = array( "name" => "Footer Options",
					"type" => "heading");

$options[] = array( "name" => "Footer Columns",
					"desc" => "Pick the number of footer columns.",
					"id" => $shortname."_footer_columns",
					"std" => "4",
					"type" => "select",
					"options" => array('1', '2', '3', '4'));

$options[] = array( "name" => "Text Below Footer - Left",
                    "desc" => "Text/HTML to display on the left side below the footer.",
                    "id" => $shortname."_footer_below_left",
                    "std" => "Copyright &copy; 2011 <a href=\"http://themeforest.net/user/e404\">Cold Theme</a>. All rights reserved.",
                    "type" => "textarea");

$options[] = array( "name" => "Text Below Footer - Right",
                    "desc" => "Text/HTML to display on the left side below the footer.",
                    "id" => $shortname."_footer_below_right",
                    "std" => "Powered by: <a href=\"http://wordpress.org\">WordPress</a>.",
                    "type" => "textarea");

$options[] = array( "name" => "Home Page Options",
					"type" => "heading");

$options[] = array( "name" => "Home Page Slider",
					"desc" => "Select a slider for the home page.",
					"id" => $shortname."_home_slider",
					"std" => "0",
					"type" => "select2",
					"options" => $slider_options);

$options[] = array( "name" => "Slideshow to display in slider",
					"desc" => "Select a slideshow for the slider. Slideshows are defined <a href='edit.php?post_type=e404_slide'>here</a>.",
					"id" => $shortname."_home_slideshow",
					"std" => "0",
					"type" => "select2",
					"options" => $slideshow_options);

$options[] = array( "name" => "Slider Height",
					"desc" => "Define the height of the slider (in pixels).",
					"id" => $shortname."_home_slider_height",
					"std" => "300",
					"type" => "text");

$options[] = array( "name" => "Show Slider Ribbon",
					"desc" => "Enable the slider ribbon (right top corner).",
					"id" => $shortname."_home_slider_ribbon",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Number of Featured Boxes",
					"desc" => "Select a number of featured boxes (placed below the home page slider).",
					"id" => $shortname."_home_featured_boxes",
					"std" => "4",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => '1', 2 => '2', 3 => '3', 4 => '4'));

$featured_box_icons = array('(none)', 'abacus', 'address-book', 'airplane', 'android', 'book', 'bubbles', 'calendar', 'camera', 'chart', 'clapboard', 'clock', 'cloud', 'cog', 'companies', 'cup', 'film-camera', 'flag', 'folder', 'globe', 'group', 'help', 'home', 'imac', 'image', 'ipad', 'iphone', 'ipod', 'lab', 'link', 'lock', 'magnifying-glass', 'mail', 'marker', 'mobile-phone', 'mouse', 'paint-brush', 'pencil', 'phone', 'piggy-bank', 'plugin', 'presentation', 'price', 'printer', 'recycle', 'refresh', 'ruler', 'scissors', 'shopping-basket', 'shopping-cart', 'sign', 'sound', 'suitcase', 't-shirt', 'tag', 'tools', 'tree', 'truck', 'tv', 'user', 'wifi', 'applications', 'write', 'info', 'alert', 'download', 'upload', 'preview', 'megaphone', 'microphone', 'calculator', 'frames', 'graph');
sort($featured_box_icons);

$options[] = array( "name" => "#1 Featured Box Title",
					"desc" => "The title of the first featured box.",
					"id" => $shortname."_home_featured_1_title",
					"std" => "40+ Shortcodes",
					"type" => "text");

$options[] = array( "name" => "#1 Featured Box URL",
					"desc" => "The URL for the first featured box (optional).",
					"id" => $shortname."_home_featured_1_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "#1 Featured Box Icon",
					"desc" => "Select an icon for the first featured box.",
					"id" => $shortname."_home_featured_1_icon",
					"std" => "ruler",
					"type" => "select",
					"options" => $featured_box_icons);

$options[] = array( "name" => "#1 Featured Box Text",
					"desc" => "The text for the first featured box (shortcodes enabled).",
					"id" => $shortname."_home_featured_1_text",
					"std" => "Lorem <a href=\"#\">ipsum dolor</a> sit amet, consetetur sadipscing elitr.",
					"type" => "textarea");

$options[] = array( "name" => "#2 Featured Box Title",
					"desc" => "The title of the second featured box.",
					"id" => $shortname."_home_featured_2_title",
					"std" => "Color Variations",
					"type" => "text");

$options[] = array( "name" => "#2 Featured Box URL",
					"desc" => "The URL for the second featured box (optional).",
					"id" => $shortname."_home_featured_2_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "#2 Featured Box Icon",
					"desc" => "Select an icon for the second featured box.",
					"id" => $shortname."_home_featured_2_icon",
					"std" => "paint-brush",
					"type" => "select",
					"options" => $featured_box_icons);

$options[] = array( "name" => "#2 Featured Box Text",
					"desc" => "The text for the second featured box (shortcodes enabled).",
					"id" => $shortname."_home_featured_2_text",
					"std" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
					"type" => "textarea");

$options[] = array( "name" => "#3 Featured Box Title",
					"desc" => "The title of the third featured box.",
					"id" => $shortname."_home_featured_3_title",
					"std" => "Powerful Panel",
					"type" => "text");

$options[] = array( "name" => "#3 Featured Box URL",
					"desc" => "The URL for the third featured box (optional).",
					"id" => $shortname."_home_featured_3_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "#3 Featured Box Icon",
					"desc" => "Select an icon for the third featured box.",
					"id" => $shortname."_home_featured_3_icon",
					"std" => "tools",
					"type" => "select",
					"options" => $featured_box_icons);

$options[] = array( "name" => "#3 Featured Box Text",
					"desc" => "The text for the third featured box (shortcodes enabled).",
					"id" => $shortname."_home_featured_3_text",
					"std" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
					"type" => "textarea");

$options[] = array( "name" => "#4 Featured Box Title",
					"desc" => "The title of the fourth featured box.",
					"id" => $shortname."_home_featured_4_title",
					"std" => "Customer Support",
					"type" => "text");

$options[] = array( "name" => "#4 Featured Box URL",
					"desc" => "The URL for the fourth featured box (optional).",
					"id" => $shortname."_home_featured_4_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "#4 Featured Box Icon",
					"desc" => "Select an icon for the fourth featured box.",
					"id" => $shortname."_home_featured_4_icon",
					"std" => "help",
					"type" => "select",
					"options" => $featured_box_icons);

$options[] = array( "name" => "#4 Featured Box Text",
					"desc" => "The text for the fourth featured box (shortcodes enabled).",
					"id" => $shortname."_home_featured_4_text",
					"std" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr.",
					"type" => "textarea");

$options[] = array( "name" => "Blog Options",
					"type" => "heading");

$options[] = array( "name" => "Blog Slider",
					"desc" => "Select a slider for the blog page.",
					"id" => $shortname."_blog_slider",
					"std" => "0",
					"type" => "select2",
					"options" => $slider_options);

$options[] = array( "name" => "Slideshow to display in slider",
					"desc" => "Select a slideshow for the slider. Slideshows are defined <a href='edit.php?post_type=e404_slide'>here</a>.",
					"id" => $shortname."_blog_slideshow",
					"std" => "0",
					"type" => "select2",
					"options" => $slideshow_options);

$options[] = array( "name" => "Slider Height",
					"desc" => "Define the height of the slider (in pixels).",
					"id" => $shortname."_blog_slider_height",
					"std" => "300",
					"type" => "text");

$options[] = array( "name" => "Show Slider Ribbon",
					"desc" => "Enable the slider ribbon (right top corner).",
					"id" => $shortname."_blog_slider_ribbon",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Slider on Blog Home Page Only",
					"desc" => "Enable the blog slider only on the Blog Home Page (it will be hidden on every other blog page).",
					"id" => $shortname."_blog_slider_home",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Blog Layout",
					"desc" => "Select blog layout.",
					"id" => $shortname."_blog_layout",
					"std" => "sidebar-right",
					"type" => "images",
					"options" => array(
						'sidebar-right' => OF_DIRECTORY.'/admin/images/2cr.png',
						'sidebar-left' => OF_DIRECTORY.'/admin/images/2cl.png')
					);

$options[] = array( "name" => "Use Post Thumbnails",
					"desc" => "Use post thumbnail on the posts list.",
					"id" => $shortname."_blog_use_thumbnail",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Thumbnails Size",
					"desc" => "Select the thumbnails size.",
					"id" => $shortname."_blog_thumbnails_size",
					"std" => "huge",
					"type" => "select2",
					"options" => array('huge' => 'Full width', 'medium' => 'Medium (290 px)', 'small' => 'Small (210 px)'));

$options[] = array( "name" => "Thumbnails Height",
					"desc" => "Define the height of post thumbnails on the posts list (in pixels; empty for default).",
					"id" => $shortname."_blog_thumbnails_height",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Thumbnails Align",
					"desc" => "Select the thumbnails align.",
					"id" => $shortname."_blog_thumbnails_align",
					"std" => "center",
					"type" => "select",
					"options" => array('center', 'left', 'right'));

$options[] = array( "name" => "Use Post Excerpts",
					"desc" => htmlentities("Show excerpts on the posts list instead of the content before <!--more--> tag."),
					"id" => $shortname."_blog_use_excerpt",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Enable PrettyPhoto",
					"desc" => "Enable PrettyPhoto (Lightbox clone) support for images in WordPress built-in galleries and featured images.",
					"id" => $shortname."_blog_prettyphoto",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Share Icons",
					"desc" => "Show social networks sharing links on blog post pages.",
					"id" => $shortname."_blog_share_it",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show 'Read more' links",
					"desc" => "Show 'Read more' links on the blog page.",
					"id" => $shortname."_blog_read_more",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "'Read more' link text",
					"desc" => "Text to display in 'Read more' links.",
					"id" => $shortname."_blog_read_more_text",
					"std" => "Read more",
					"type" => "text");

$options[] = array( "name" => "Show Post Author",
					"desc" => "Show the post author.",
					"id" => $shortname."_blog_post_author",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Post Categories",
					"desc" => "Show post categories.",
					"id" => $shortname."_blog_post_categories",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Post Tags",
					"desc" => "Show post tags.",
					"id" => $shortname."_blog_post_tags",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Author Bio",
					"desc" => "Show the box with informations about post author (taken from author profile).",
					"id" => $shortname."_blog_author_bio",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Portfolio Options",
					"type" => "heading");

$options[] = array( "name" => "Portfolio Items List Layout",
					"desc" => "Select a layout for portfolio items list (only for templates with sidebar).",
					"id" => $shortname."_portfolio_layout",
					"std" => "sidebar-right",
					"type" => "images",
					"options" => array(
						'sidebar-right' => OF_DIRECTORY.'/admin/images/2cr.png',
						'sidebar-left' => OF_DIRECTORY.'/admin/images/2cl.png')
					);

$options[] = array( "name" => "Portfolio Page",
					"desc" => "Select your portfolio page.",
					"id" => $shortname."_portfolio_page",
					"std" => "0",
					"type" => "select2",
					"options" => $all_pages);

$options[] = array( "name" => "Portfolio Items Per Page",
					"desc" => "Number of portfolio items to show on a portfolio page.",
					"id" => $shortname."_portfolio_posts_per_page",
					"std" => "10",
					"type" => "text");

$options[] = array( "name" => "Portfolio Slug",
					"desc" => "Slug for portfolio pages.",
					"id" => $shortname."_portfolio_slug",
					"std" => "portfolio",
					"type" => "text");

$options[] = array( "name" => "Portfolio Categories Slug",
					"desc" => "Slug for portfolio categories.",
					"id" => $shortname."_portfolio_category_slug",
					"std" => "category",
					"type" => "text");

$options[] = array( "name" => "Portfolio Categories Name",
					"desc" => "Name for portfolio categories (for example 'Work type').",
					"id" => $shortname."_portfolio_categories_name",
					"std" => "Portfolio Categories",
					"type" => "text");

$options[] = array( "name" => "Portfolio Tags Slug",
					"desc" => "Slug for portfolio tags.",
					"id" => $shortname."_portfolio_tag_slug",
					"std" => "tag",
					"type" => "text");

$options[] = array( "name" => "Portfolio Tags Name",
					"desc" => "Name for portfolio tags (for example 'Media' or 'Used technologies').",
					"id" => $shortname."_portfolio_tags_name",
					"std" => "Portfolio Tags",
					"type" => "text");

$options[] = array( "name" => "PrettyPhoto Support",
					"desc" => "Add PrettyPhoto (Lightbox clone) support for portfolio items featured images.",
					"id" => $shortname."_portfolio_prettyphoto",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show 'View project' (aka 'Read more') button",
					"desc" => "Enable 'View project' buttons on the portfolio page.",
					"id" => $shortname."_portfolio_read_more",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "'View project' (aka 'Read more') button text",
					"desc" => "Text to display in 'View project' buttons.",
					"id" => $shortname."_portfolio_read_more_text",
					"std" => "View project",
					"type" => "text");

$options[] = array( "name" => "Show Titles",
					"desc" => "Show portfolio items title (only in column portfolio templates).",
					"id" => $shortname."_portfolio_titles",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Excerpts",
					"desc" => "Show portfolio items excerpt (only in column portfolio templates).",
					"id" => $shortname."_portfolio_excerpts",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Categories",
					"desc" => "Show portfolio items categories.",
					"id" => $shortname."_portfolio_item_categories",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Show Tags",
					"desc" => "Show tags on portfolio item pages.",
					"id" => $shortname."_portfolio_item_tags",
					"std" => "true",
					"type" => "checkbox"); 

$options[] = array( "name" => "Nivo Slider Options",
					"type" => "heading"); 	   

$options[] = array( "name" => "Transition Effect",
					"desc" => "Select an effect to use on slideshow.",
					"id" => $shortname."_nivo_effect",
					"std" => "random",
					"type" => "select",
					"options" => $nivo_effects);

$options[] = array( "name" => "Slices",
					"desc" => "Define a number of elements in which the image will be sliced.",
					"id" => $shortname."_nivo_slices",
					"std" => "10",
					"type" => "text");

$options[] = array( "name" => "Animation Speed",
					"desc" => "Define the animation speed (in miliseconds).",
					"id" => $shortname."_nivo_animspeed",
					"std" => "600",
					"type" => "text");

$options[] = array( "name" => "Pause Time",
					"desc" => "Define the delay between slides (in miliseconds).",
					"id" => $shortname."_nivo_pausetime",
					"std" => "4000",
					"type" => "text");

$options[] = array( "name" => "Slide Titles",
					"desc" => "Enable or disable slide titles.",
					"id" => $shortname."_nivo_title",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Next & Prev Buttons",
					"desc" => "Enable or disable navigation buttons (Next & Prev).",
					"id" => $shortname."_nivo_directionnav",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Auto-hide Next & Prev Buttons",
					"desc" => "Enable or disable auto-hiding navigation buttons (Next & Prev).",
					"id" => $shortname."_nivo_directionnavhide",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Control Navigation",
					"desc" => "Enable or disable Control Navigation (bubbles below a slider).",
					"id" => $shortname."_nivo_controlnav",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Keyboard Navigation",
					"desc" => "Enable or disable Keyboard Navigation.",
					"id" => $shortname."_nivo_keyboardnav",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Pause on Hover",
					"desc" => "Enable or disable pausing slideshow on mouse hover.",
					"id" => $shortname."_nivo_pauseonhover",
					"std" => "0",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Stop At End",
					"desc" => "When enabled the slideshow will stop on last slide.",
					"id" => $shortname."_nivo_stopatend",
					"std" => "0",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Caption Opacity",
					"desc" => "Define the opacity of caption (from 0.1 to 1.0).",
					"id" => $shortname."_nivo_captionopacity",
					"std" => "0.5",
					"type" => "text");

$options[] = array( "name" => "Accordion Slider Options",
					"type" => "heading"); 	   

$options[] = array( "name" => "Max Width",
					"desc" => "Define the width of expanded slider (in pixels).",
					"id" => $shortname."_accordion_max_width",
					"std" => "700",
					"type" => "text");

$options[] = array( "name" => "Transition Effect",
					"desc" => "Select an effect to use on slideshow.",
					"id" => $shortname."_accordion_effect",
					"std" => "swing",
					"type" => "select",
					"options" => $easing_effects);

$options[] = array( "name" => "Animation Speed",
					"desc" => "Define the animation speed (in miliseconds).",
					"id" => $shortname."_accordion_effect_duration",
					"std" => "600",
					"type" => "text");

$options[] = array( "name" => "Slide Titles",
					"desc" => "Enable or disable slide titles.",
					"id" => $shortname."_accordion_title",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Title Opacity",
					"desc" => "Define the title opacity (from 0.1 to 1.0).",
					"id" => $shortname."_accordion_title_opacity",
					"std" => "0.6",
					"type" => "text");

$options[] = array( "name" => "Anything Slider Options",
					"type" => "heading"); 	   

$options[] = array( "name" => "Transition Effect",
					"desc" => "Select an effect to use on slideshow.",
					"id" => $shortname."_anything_effect",
					"std" => "swing",
					"type" => "select",
					"options" => $easing_effects);

$options[] = array( "name" => "Animation Time",
					"desc" => "Define the animation time (in miliseconds).",
					"id" => $shortname."_anything_animationtime",
					"std" => "600",
					"type" => "text");

$options[] = array( "name" => "Pause Time",
					"desc" => "Define the delay between slides (in miliseconds).",
					"id" => $shortname."_anything_delay",
					"std" => "4000",
					"type" => "text");

$options[] = array( "name" => "Next & Prev Buttons",
					"desc" => "Enable or disable navigation buttons (Next & Prev).",
					"id" => $shortname."_anything_buildarrows",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Auto-hide Next & Prev Buttons",
					"desc" => "Enable or disable auto-hiding navigation buttons (Next & Prev).",
					"id" => $shortname."_anything_togglearrows",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Control Navigation",
					"desc" => "Enable or disable Control Navigation (bubbles below a slider).",
					"id" => $shortname."_anything_buildnavigation",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Keyboard Navigation",
					"desc" => "Enable or disable Keyboard Navigation.",
					"id" => $shortname."_anything_enablekeyboard",
					"std" => "1",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Pause on Hover",
					"desc" => "Enable or disable pausing slideshow on mouse hover.",
					"id" => $shortname."_anything_pauseonhover",
					"std" => "0",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Stop At End",
					"desc" => "When enabled the slideshow will stop on last slide.",
					"id" => $shortname."_anything_stopatend",
					"std" => "0",
					"type" => "select2",
					"options" => array(0 => 'disabled', 1 => 'enabled'));

$options[] = array( "name" => "Enable Video Extension",
					"desc" => "Use the Anything Slider video extension.",
					"id" => $shortname."_anything_video_extension",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Galleria Slider Options",
					"type" => "heading"); 	   

$options[] = array( "name" => "Background Color",
					"desc" => "Define the slider background color.",
					"id" => $shortname."_galleria_background",
					"std" => "#ffffff",
					"type" => "color");          

$options[] = array( "name" => "Autoplay",
					"desc" => "Define the interval between slides (in miliseconds). Enter 0 (zero) to disable autoplay.",
					"id" => $shortname."_galleria_autoplay",
					"std" => "5000",
					"type" => "text");

$options[] = array( "name" => "Transition Effect",
					"desc" => "Select an effect to use on slideshow.",
					"id" => $shortname."_galleria_transition",
					"std" => "fade",
					"type" => "select",
					"options" => $galleria_effects);

$options[] = array( "name" => "Transition Speed",
					"desc" => "Define the animation speed (in miliseconds).",
					"id" => $shortname."_galleria_transitionspeed",
					"std" => "500",
					"type" => "text");

$options[] = array( "name" => "Thumbnails",
					"desc" => "Enable or disable thumbnails.",
					"id" => $shortname."_galleria_thumbnails",
					"std" => "true",
					"type" => "select2",
					"options" => array('false' => 'disabled', 'true' => 'enabled'));

$options[] = array( "name" => "Counter",
					"desc" => "Enable or disable slides counter (for example 1/4, meaning picture 1 of 4).",
					"id" => $shortname."_galleria_showcounter",
					"std" => "false",
					"type" => "select2",
					"options" => array('false' => 'disabled', 'true' => 'enabled'));

$options[] = array( "name" => "Image Info",
					"desc" => "Enable or disable image info ('i' button in left top corner).",
					"id" => $shortname."_galleria_showinfo",
					"std" => "true",
					"type" => "select2",
					"options" => array('false' => 'disabled', 'true' => 'enabled'));

$options[] = array( "name" => "Navigation (Next & Prev buttons)",
					"desc" => "Enable or disable Next & Prev buttons.",
					"id" => $shortname."_galleria_showimagenav",
					"std" => "true",
					"type" => "select2",
					"options" => array('false' => 'disabled', 'true' => 'enabled'));

$options[] = array( "name" => "Social Networks & RSS",
					"type" => "heading"); 	   

$options[] = array( "name" => "Share Buttons",
					"desc" => "Choose sites to show in the Share This field.",
					"id" => $shortname."_share_this_sites",
					"std" => "facebook",
					"type" => "multicheck",
					"options" => $social_options);

$options[] = array( "name" => "Open share links in new window",
					"desc" => "Check to open sharing links in a new browser window.",
					"id" => $shortname."_share_buttons_target",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Open header social links in new window",
					"desc" => "Check to open header social icons links in a new browser window.",
					"id" => $shortname."_header_social_icons_target",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Social Icons Color Variant",
					"desc" => "Select the color variant of header social icons. Empty for theme default.",
					"id" => $shortname."_social_icons_variant",
					"std" => "",
					"type" => "select2",
					"options" => array('' => 'Theme Default', 'white' => 'White', 'black' => 'Black'));

$options[] = array( "name" => "RSS Channel",
					"desc" => "URL address of your RSS channel.",
					"id" => $shortname."_rss",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Contact Page",
					"desc" => "URL address of your contact page.",
					"id" => $shortname."_contact",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Twitter username",
					"desc" => "Your Twitter username.",
					"id" => $shortname."_twitter",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Twitter cache expiration time",
					"desc" => "Define a life time of Twitter cache (in seconds).",
					"id" => $shortname."_twitter_expire",
					"std" => "3600",
					"type" => "text"); 

$options[] = array( "name" => "Facebook profile/page URL",
					"desc" => "Full URL address of your Facebook profile or page.",
					"id" => $shortname."_facebook",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Flickr profile URL",
					"desc" => "Full URL address of your Flickr profile.",
					"id" => $shortname."_flickr",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Flickr cache expiration time",
					"desc" => "Define a life time of Flickr cache (in seconds).",
					"id" => $shortname."_flickr_expire",
					"std" => "3600",
					"type" => "text"); 

$options[] = array( "name" => "Foursquare profile URL",
					"desc" => "Full URL address of your Foursquare profile.",
					"id" => $shortname."_foursquare",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Google Buzz profile URL",
					"desc" => "Full URL address of your Google Buzz profile.",
					"id" => $shortname."_buzz",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Google+ profile URL",
					"desc" => "Full URL address of your Google+ profile.",
					"id" => $shortname."_plus",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Behance profile URL",
					"desc" => "Full URL address of your Behance profile.",
					"id" => $shortname."_behance",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Delicious profile URL",
					"desc" => "Full URL address of your Delicious profile.",
					"id" => $shortname."_delicious",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Digg profile URL",
					"desc" => "Full URL address of your Digg profile.",
					"id" => $shortname."_digg",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Dribbble profile URL",
					"desc" => "Full URL address of your Dribbble profile.",
					"id" => $shortname."_dribbble",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "DropBox profile URL",
					"desc" => "Full URL address of your DropBox profile.",
					"id" => $shortname."_dropbox",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "iChat URL",
					"desc" => "Full URL address of your iChat connection.",
					"id" => $shortname."_ichat",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Last.fm profile URL",
					"desc" => "Full URL address of your Last.fm profile.",
					"id" => $shortname."_lastfm",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "LinkedIn profile URL",
					"desc" => "Full URL address of your LinkedIn profile.",
					"id" => $shortname."_linkedin",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "MobyPicture profile URL",
					"desc" => "Full URL address of your MobyPicture profile.",
					"id" => $shortname."_mobypicture",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "MySpace profile URL",
					"desc" => "Full URL address of your MySpace profile.",
					"id" => $shortname."_myspace",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Skype URL",
					"desc" => "Full URL address of your Skype connection.",
					"id" => $shortname."_skype",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "StumbleUpon profile URL",
					"desc" => "Full URL address of your StumbleUpon profile.",
					"id" => $shortname."_stumbleupon",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Tumblr URL",
					"desc" => "Full URL address of your Tumblr blog/profile.",
					"id" => $shortname."_tumblr",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Vimeo profile URL",
					"desc" => "Full URL address of your Vimeo profile.",
					"id" => $shortname."_vimeo",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "YouTube profile URL",
					"desc" => "Full URL address of your YouTube profile.",
					"id" => $shortname."_youtube",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Xing profile URL",
					"desc" => "Full URL address of your Xing profile.",
					"id" => $shortname."_xing",
					"std" => "",
					"type" => "text"); 

$options[] = array( "name" => "Tweaks",
					"type" => "heading");

$options[] = array( "name" => "Disable Galleria Shortcode",
					"desc" => "Disable <code>[galleria]</code> shortcode if you don't want to use it (the Galleria script will not be loaded). This setting doesn't affect slider options.",
					"id" => $shortname."_disable_galleria",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Disable Nivo Shortcode",
					"desc" => "Disable <code>[nivo]</code> shortcode if you don't want to use it (the Nivo script will not be loaded). This setting doesn't affect slider options.",
					"id" => $shortname."_disable_nivo",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Disable Scrollable Shortcode",
					"desc" => "Disable <code>[scrollable]</code> shortcode if you don't want to use it (the Scrollable script will not be loaded).",
					"id" => $shortname."_disable_scrollable",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Disable Video Shortcode",
					"desc" => "Disable <code>[video]</code> shortcode if you don't want to use it (the Projekktor script will not be loaded).",
					"id" => $shortname."_disable_video_shortcode",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Disable Media Shortcodes",
					"desc" => "Disable <code>[youtube]</code>, <code>[vimeo]</code> and <code>[dailymotion]</code> shortcodes (if you prefer to use different embedding shortcodes).",
					"id" => $shortname."_disable_media_shortcodes",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Remove 'generator' Meta Tag",
					"desc" => "Remove the 'generator' meta tag from the 'head' section.",
					"id" => $shortname."_remove_generator",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Remove 'index', 'next' and 'prev' Links",
					"desc" => "Remove 'index', 'next' and 'prev' links from the 'head' section.",
					"id" => $shortname."_remove_nav_links",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Remove Feeds Links",
					"desc" => "Remove links to post and comment feeds from the 'head' section.",
					"id" => $shortname."_remove_feeds",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Remove Extra Feeds Links",
					"desc" => "Remove links to the extra feeds (category feed etc.) from the 'head' section.",
					"id" => $shortname."_remove_extra_feeds",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Remove RSD Link",
					"desc" => "Remove links to the <a href='http://en.wikipedia.org/wiki/Really_Simple_Discovery' target='_blank'>Really Simple Discovery</a> service endpoint from the 'head' section.",
					"id" => $shortname."_remove_rsd",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Remove WLW Manifest",
					"desc" => "Remove links to the Windows Live Writer manifest file from the 'head' section.",
					"id" => $shortname."_remove_wlw",
					"std" => "false",
					"type" => "checkbox"); 

$options[] = array( "name" => "Enable Shortcodes Preview",
					"desc" => "Enable the shortcodes code preview in the Shortcode Manager. Useful for testing or educational purposes.",
					"id" => $shortname."_shortcodes_preview",
					"std" => "false",
					"type" => "checkbox"); 

update_option('of_template', $options); 					  
update_option('of_themename', $themename);   
update_option('of_shortname', $shortname);

}
}
?>
