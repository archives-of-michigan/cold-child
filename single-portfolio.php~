<?php
/**
 * Portfolio - single page
 *
 */

get_header(); ?>
	
	<div id="main_wrapper">
		<?php if($e404_options['breadcrumbs']) : ?><div id="breadcrumb"><?php e404_breadcrumbs(); ?></div><?php endif; ?>
		<?php include(OF_FILEPATH.'/portfolio-intro-box.php'); ?>
		<div class="portfolio portfolio-page">
<?php
while ( have_posts() ) : the_post(); ?>
			<?php if($e404_options['page_titles']) : ?><h2><?php the_title(); ?></h2><?php endif; ?>
<?php if($e404_options['portfolio_item_categories']) : ?>
			<div class="portfolio-meta">
				<div class="posted-meta"><span><?php echo e404_get_taxonomy_name('categories'); ?>:</span> <?php the_terms($post->ID, 'portfolio-category', '', ', '); ?></div>
			</div>
<?php endif; ?>
			<?php the_content(''); ?>

<?php endwhile; ?>
<?php if($e404_options['portfolio_item_tags']) : ?>
			<br class="clear" />
			<div class="full_page tags-meta"><span><?php echo e404_get_taxonomy_name('tags'); ?>:</span><?php the_terms($post->ID, 'portfolio-tag', '', ' '); ?></div>
<?php endif; ?>
		</div>
	<br class="clear" />
</div>
<?php get_footer(); ?>
