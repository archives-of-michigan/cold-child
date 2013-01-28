<?php
/**
 * Theme Header
 *
 */

global $e404_options;
if($post) {
	$e404_options['post_id'] = $post->ID;
	$e404_options['post_parent'] = $post->post_parent;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
</head>

<body>
<div id="header_wrapper">
	<div id="header">
		<div class="leftside" id="logo"><a href="<?php echo home_url(); ?>"><img src="<?php echo $e404_options['logo_url']; ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" /></a></div>
		<div id="header_nav" class="rightside">
			<div id="social_icons" class="leftside">
				<?php e404_show_header_social_icons(); ?>
			</div>
		<?php if(!$e404_options['remove_search_form']) : ?>
			<div id="search" class="leftside">
				<form action="<?php echo home_url(); ?>" method="get">
					<input type="text" name="s" value="<?php _e('Search...', 'cold'); ?>" onfocus="if (this.value == '<?php _e('Search...', 'cold'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search...', 'cold'); ?>';}" />
					<input type="submit" value="<?php _e('Go', 'cold'); ?>" />
				</form>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
		<div id="navigation">
		<?php wp_nav_menu(array('theme_location' => 'header-menu', 'container' => false, 'menu_class' => 'sf-menu')); ?>
			<br class="clear" />
		</div>
<div id="wrapper">
	
