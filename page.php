<?php
/**
 * Template name: Single page without sidebar
 *
 */

get_header(); ?>
	
	<div id="main_wrapper">
		<?php if($e404_options['breadcrumbs']) : ?><div id="breadcrumb"><?php e404_breadcrumbs(); ?></div><?php endif; ?>
		<?php include(OF_FILEPATH.'/main-intro-box.php'); ?>
		<div class="full_page">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php if($e404_options['page_titles']) : ?><h2 class="fancy-header"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2><?php endif; ?>
			<div id="post-<?php the_ID(); ?>">
				<?php if(!is_attachment() && $e404_options['page_comments']) : ?><div class="comments"><a href="<?php comments_link(); ?>"><?php comments_number('0', '1', '%'); ?></a></div><?php endif; ?>

			<?php the_content(); ?>

			<?php if(!is_attachment() && $e404_options['page_comments']) {
				comments_template('', true);
			} ?>
			</div>
<?php endwhile; ?>
			
			<?php get_template_part('navigation'); ?>

		</div>
		<br class="clear" />
	</div>

<?php get_footer(); ?>
