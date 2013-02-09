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
<?php
	$portfolio_page_id = get_option('e404_portfolio_page');
	$portfolio_page = get_page($portfolio_page_id);
	$portfolio_cdmid = get_post_meta($post->ID, 'wpcf-wpcf-cdmid', true);
	$portfolio_url = "http://cdm15867.contentdm.oclc.org/cdm/search/collection/".$portfolio_cdmid;
?>

<?php if($e404_options['page_titles']) : ?><div id="Portfolio-title"><h2><?php the_title(); ?></h2></div><?php endif; ?>
<div id="contentdm-button"><a href="<?php echo $portfolio_url; ?>">View Entire Collection</a></div>
<br class="clear" />
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
