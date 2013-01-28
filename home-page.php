<?php
/**
 * Template Name: Home Page
 *
 */

get_header();

$boxes = get_option('e404_home_featured_boxes');
if($boxes < 1)
	$extra_height = 0;
else
	$extra_height = 110;

?>

	<div id="main_wrapper">
	<?php if($e404_options['home_slider']) : ?>
		<div id="featured" style="height: <?php echo (e404_get_slider_height() + $extra_height); ?>px;">
			<?php if($e404_options['home_slider_ribbon']) : ?><div class="ribbon"></div><?php endif; ?>
			<?php e404_show_slider(); ?>
			<?php e404_show_featured_boxes(); ?>
		</div>
	<?php endif; ?>

<?php include(OF_FILEPATH.'/home-intro-box.php'); ?>
		
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
<?php endwhile; ?>

	</div>

<?php get_footer(); ?>
