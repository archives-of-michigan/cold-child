<?php
$e404_all_options['e404_slider_width'] = 930;

function e404_show_slider() {
	$slider_type = e404_get_slider_type();
	if($slider_type) {
		switch ($slider_type) {
			case 'nivo':
				e404_show_nivo_slider();
                break;
			case 'accordion':
				e404_show_accordion_slider();
                break;
			case 'galleria':
				e404_show_galleria_slider();
                break;
			case 'anything':
				e404_show_anything_slider();
                break;
		}
	}
}

function e404_get_slider_type() {
	global $e404_options, $e404_all_options;
	$slider_type = false;
	$current_template = e404_get_current_template();
	if($current_template == 'index.php') {
		if($e404_all_options['e404_blog_slider_home'] == 'true') {
			if(is_home())
				$slider_type = $e404_all_options['e404_blog_slider'];
			else
				$e404_options['blog_slider'] = '';
		}
		else {
			$slider_type = $e404_all_options['e404_blog_slider'];
		}
	}
	if($current_template == 'home-page.php') {
		$slider_type = $e404_all_options['e404_home_slider'];
	}
	return $slider_type;
}

function e404_get_slideshow() {
	$slideshow = false;
	$current_template = e404_get_current_template();
	if($current_template == 'index.php') {
		$slideshow = get_option('e404_blog_slideshow');
	}
	if($current_template == 'home-page.php') {
		$slideshow = get_option('e404_home_slideshow');
	}
	return $slideshow;
}

function e404_get_slider_height() {
	$height = 300;
	$current_template = e404_get_current_template();
	if($current_template == 'index.php') {
		$height = get_option('e404_blog_slider_height');
	}
	if($current_template == 'home-page.php') {
		$height = get_option('e404_home_slider_height');
	}
	return $height;
}

add_action('get_header', 'e404_init_slider');
function e404_init_slider() {
	global $e404_all_options;
	$slider_type = e404_get_slider_type();
	if($slider_type) {
		switch ($slider_type) {
			case 'nivo':
				wp_enqueue_script('nivo', OF_DIRECTORY.'/js/jquery.nivo.slider.pack.js', '', '', true);
				wp_enqueue_style('nivo', OF_DIRECTORY.'/css/nivo-slider.css');
				wp_enqueue_script('nivo-init', OF_DIRECTORY.'/js/nivo_slider_init.js', '', '', true);
				break;
			case 'accordion':
				wp_enqueue_script('easing', OF_DIRECTORY.'/js/jquery.easing.js', '', '', true);
				wp_enqueue_script('kwicks', OF_DIRECTORY.'/js/jquery.kwicks.pack.js', '', '', true);
				wp_enqueue_style('accordion', OF_DIRECTORY.'/css/accordion-slider.css');
				wp_enqueue_script('accordion-init', OF_DIRECTORY.'/js/accordion_slider_init.js', '', '', true);
				break;
			case 'galleria':
				wp_enqueue_script('galleria', OF_DIRECTORY.'/js/galleria.min.js', '', '', true);
				wp_enqueue_script('galleria-classic', OF_DIRECTORY.'/js/galleria.classic.min.js', '', '', true);
				wp_enqueue_style('galleria', OF_DIRECTORY.'/css/galleria.classic.css');
				wp_enqueue_script('galleria-init', OF_DIRECTORY.'/js/galleria_slider_init.js', '', '', true);
				break;
			case 'anything':
				wp_enqueue_script('easing', OF_DIRECTORY.'/js/jquery.easing.js', '', '', true);
				wp_enqueue_script('anything', OF_DIRECTORY.'/js/jquery.anythingslider.min.js', '', '', true);
				if($e404_all_options['e404_anything_video_extension'] == 'true')
					wp_enqueue_script('anything-video', OF_DIRECTORY.'/js/jquery.anythingslider.video.min.js', '', '', true);
				wp_enqueue_style('anything', OF_DIRECTORY.'/css/anything-slider.css');
				wp_enqueue_script('anything-init', OF_DIRECTORY.'/js/anything_slider_init.js', '', '', true);
				break;
		}
	}
}

function e404_get_slideshow_slides() {
	$slideshow = e404_get_slideshow();
	$slideshow_name = get_term_by('id', $slideshow, 'e404_slideshow');
	$args = array('post_type' => 'e404_slide', 'numberposts' => 99, 'orderby' => 'menu_order date', 'suppress_filters' => 0);
	if($slideshow) {
		$args['e404_slideshow'] = $slideshow_name->slug;
    }
	$slides = get_posts($args);

	return $slides;
}

function e404_show_nivo_slider() {
	global $e404_all_options;
	$slides = e404_get_slideshow_slides();
	$output = '';
	echo '<div class="slider" id="slider" style="height: '.e404_get_slider_height().'px;">';
	foreach($slides as $slide) {
		$slide->post_title = get_the_title($slide->ID);
		$slide_output = '';
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($slide->ID), 'single-post-thumbnail');
		if($e404_all_options['e404_nivo_title'] == '0')
			$slide->post_title = '';
		if($image) {
			$url = get_post_meta($slide->ID, 'e404_slide_target_url', true);
			if(empty($url)) {
				$slide_output .= '<img src="'.e404_img_scale($image[0], $e404_all_options['e404_slider_width'], e404_get_slider_height()).'" title="'.$slide->post_title.'" alt="'.$slide->post_title.'" />';
			}
			else {
				$slide_output .= '<a href="'.$url.'"><img src="'.e404_img_scale($image[0], $e404_all_options['e404_slider_width'], e404_get_slider_height()).'" title="'.$slide->post_title.'" alt="'.$slide->post_title.'" /></a>';
			}
			$output .= $slide_output;
		}
	}
	echo $output."\n";
	echo '</div>';
	e404_nivo_params();
}

function e404_nivo_params()
{
	global $e404_all_options;
	$nivo_js_params = "
	<script type=\"text/javascript\">
		var slideParams = []; 
		slideParams['effect'] = '".$e404_all_options['e404_nivo_effect']."'; 
		slideParams['slices'] = '".$e404_all_options['e404_nivo_slices']."'; 
		slideParams['animSpeed'] = '".$e404_all_options['e404_nivo_animspeed']."'; 
		slideParams['pauseTime'] = '".$e404_all_options['e404_nivo_pausetime']."'; 
		slideParams['directionNav'] = ".$e404_all_options['e404_nivo_directionnav']."; 
		slideParams['directionNavHide'] = ".$e404_all_options['e404_nivo_directionnavhide'].";
		slideParams['controlNav'] = ".$e404_all_options['e404_nivo_controlnav']."; 
		slideParams['keyboardNav'] = ".$e404_all_options['e404_nivo_keyboardnav']."; 
		slideParams['pauseOnHover'] = ".$e404_all_options['e404_nivo_pauseonhover']."; 
		slideParams['manualAdvance'] = false;
		slideParams['captionOpacity'] = ".$e404_all_options['e404_nivo_captionopacity'].";
		slideParams['stopAtEnd'] = ".$e404_all_options['e404_nivo_stopatend'].";
		slideParams['height'] = ".e404_get_slider_height().";
	</script>";
	echo $nivo_js_params;
}

function e404_show_accordion_slider() {
	global $e404_all_options;
	$slides = e404_get_slideshow_slides();
	$output = $class = '';
    $slides_number = count($slides);
	if($slides_number == 0)
		$slides_number = 1;
    $li_width = floor(($e404_all_options['e404_slider_width'] - $slides_number + 1) / $slides_number);
    echo '<ul class="kwicks horizontal" style="overflow: hidden; height: '.e404_get_slider_height().'px;">';
    $if_title = (bool)get_option('e404_accordion_title');
    $i = 0;
	foreach($slides as $slide) {
		$slide->post_title = get_the_title($slide->ID);
        $i++;
		$slide_output = '';
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($slide->ID), 'single-post-thumbnail');
		if($image) {
			$url = get_post_meta($slide->ID, 'e404_slide_target_url', true);
			if($i == $slides_number)
				$class = ' id="last"';
			if(empty($url)) {
				$slide_output .= '<li'.$class.'><img src="'.e404_img_scale($image[0], $e404_all_options['e404_accordion_max_width'], e404_get_slider_height()).'" title="'.$slide->post_title.'" alt="'.$slide->post_title.'" />';
				if($if_title)
					$slide_output .= '<p class="title">'.$slide->post_title.'</p>';
				$slide_output .= '</li>';
			}
			else {
				$slide_output .= '<li'.$class.'><a href="'.$url.'"><img src="'.e404_img_scale($image[0], $e404_all_options['e404_accordion_max_width'], e404_get_slider_height()).'" title="'.$slide->post_title.'" alt="'.$slide->post_title.'" />';
				if($if_title)
					$slide_output .= '<p class="title">'.$slide->post_title.'</p>';
				$slide_output .= '</a></li>';
			}
			$output .= $slide_output;
		}
	}
	echo $output."\n";
    echo '</ul>';
    e404_accordion_params($li_width);
}

function e404_accordion_params($li_width)
{
	global $e404_all_options;
	$accordion_js_params = "
	<script type=\"text/javascript\">
		var slideParams = []; 
		slideParams['maxWidth'] = ".$e404_all_options['e404_accordion_max_width'].";
		slideParams['effect'] = '".$e404_all_options['e404_accordion_effect']."';
		slideParams['duration'] = ".$e404_all_options['e404_accordion_effect_duration'].";
		slideParams['opacity'] = ".$e404_all_options['e404_accordion_title_opacity'].";
		slideParams['height'] = ".e404_get_slider_height().";
		slideParams['liwidth'] = ".$li_width.";
	</script>";
	echo $accordion_js_params;
}

function e404_show_galleria_slider() {
	global $e404_all_options;
	$slides = e404_get_slideshow_slides();
	$output = '';
	echo '<div id="galleria">';
	foreach($slides as $slide) {
		$slide->post_title = get_the_title($slide->ID);
		$slide_output = '';
		$height = e404_get_slider_height();
		if($e404_all_options['e404_galleria_thumbnails'] == 'true')
			$height = $height - 60;
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($slide->ID), 'single-post-thumbnail');
		if($image) {
			$url = get_post_meta($slide->ID, 'e404_slide_target_url', true);
			if(empty($url)) {
				$slide_output .= '<img src="'.e404_img_scale($image[0], 966, $height).'" alt="'.$slide->post_title.'" />'."\n";
			}
			else {
				$slide_output .= '<a href="'.$url.'"><img src="'.e404_img_scale($image[0], 966, $height).'" alt="'.$slide->post_title.'" /></a>';
			}
			$output .= $slide_output;
		}
	}
	echo $output."\n";
	echo '</div>';
	e404_galleria_params();
}

function e404_galleria_params()
{
	global $e404_all_options;
	
	$galleria_js_params = "
	<script type=\"text/javascript\">
		var slideParams = []; 
		slideParams['height'] = ".e404_get_slider_height().";
		slideParams['autoplay'] = ".$e404_all_options['e404_galleria_autoplay'].";
		slideParams['thumbnails'] = ".$e404_all_options['e404_galleria_thumbnails'].";
		slideParams['showCounter'] = ".$e404_all_options['e404_galleria_showcounter'].";
		slideParams['showInfo'] = ".$e404_all_options['e404_galleria_showinfo'].";
		slideParams['showImagenav'] = ".$e404_all_options['e404_galleria_showimagenav'].";
		slideParams['transition'] = '".$e404_all_options['e404_galleria_transition']."';
		slideParams['transitionSpeed'] = ".$e404_all_options['e404_galleria_transitionspeed'].";
		slideParams['background'] = '".$e404_all_options['e404_galleria_background']."';
	</script>";
	echo $galleria_js_params;
}

function e404_show_anything_slider() {
	global $e404_all_options;
	
	$slides = e404_get_slideshow_slides();
	$output = '';
	echo '<ul id="aslider" style="overflow: hidden; height: '.e404_get_slider_height().'px; width: '.$e404_all_options['e404_slider_width'].'px;">';
	foreach($slides as $slide) {
		$slide->post_title = get_the_title($slide->ID);
		$slide_output = '';
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($slide->ID), 'single-post-thumbnail');
		if($image) {
			$url = get_post_meta($slide->ID, 'e404_slide_target_url', true);
			if(empty($url)) {
				$slide_output .= '<li><img src="'.e404_img_scale($image[0], $e404_all_options['e404_slider_width'], e404_get_slider_height()).'" title="'.$slide->post_title.'" alt="'.$slide->post_title.'" /></li>';
			}
			else {
				$slide_output .= '<li><a href="'.$url.'"><img src="'.e404_img_scale($image[0], $e404_all_options['e404_slider_width'], e404_get_slider_height()).'" title="'.$slide->post_title.'" alt="'.$slide->post_title.'" /></a></li>';
			}
		}
		else {
			$slide_output .= '<li>'.str_replace(array('<p>', '</p>'), "", apply_filters('the_content', $slide->post_content)).'</li>';
		}
		$output .= $slide_output;
	}
	echo $output."\n";
	echo '</ul>';
	e404_anything_params();
}

function e404_anything_params()
{
	global $e404_all_options;

	$anything_js_params = "
	<script type=\"text/javascript\">
		var slideParams = []; 
		slideParams['height'] = ".e404_get_slider_height().";
		slideParams['effect'] = '".$e404_all_options['e404_anything_effect']."';
		slideParams['animationTime'] = ".$e404_all_options['e404_anything_animationtime'].";
		slideParams['delay'] = ".$e404_all_options['e404_anything_delay'].";
		slideParams['buildArrows'] = ".$e404_all_options['e404_anything_buildarrows'].";
		slideParams['toggleArrows'] = ".$e404_all_options['e404_anything_togglearrows'].";
		slideParams['buildNavigation'] = ".$e404_all_options['e404_anything_buildnavigation'].";
		slideParams['enableKeyboard'] = ".$e404_all_options['e404_anything_enablekeyboard'].";
		slideParams['pauseOnHover'] = ".$e404_all_options['e404_anything_pauseonhover'].";
		slideParams['stopAtEnd'] = ".$e404_all_options['e404_anything_stopatend'].";
		slideParams['startText'] = '".__('Start', 'cold')."';
		slideParams['stopText'] = '".__('Stop', 'cold')."';

	</script>";
	echo $anything_js_params;
}

?>