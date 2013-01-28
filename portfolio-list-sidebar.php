<?php
/**
 * Template name: Portfolio - small images list with sidebar
 *
 */

get_header(); ?>
	
	<div id="main_wrapper">
		<?php if($e404_options['breadcrumbs']) : ?><div id="breadcrumb"><?php e404_breadcrumbs(); ?></div><?php endif; ?>
		<?php include(OF_FILEPATH.'/portfolio-intro-box.php'); ?>
	<?php if($e404_options['portfolio_layout'] == 'sidebar-left') : ?>
		<div id="sidebar" class="one_third">
	<?php get_sidebar('portfolio'); ?>
		</div>
	<?php endif; ?>
		<div id="page-content" class="two_third<?php if($e404_options['portfolio_layout'] == 'sidebar-left') echo ' last'; ?>">
			<div class="portfolio portfolio-list">
<?php
$query = "paged=".$paged."&post_type=portfolio&orderby=menu_order date&posts_per_page=".$e404_options['portfolio_posts_per_page'];
if(isset($taxonomy))
	$query .= "&taxonomy=".$taxonomy;
if(isset($term))
	$query .= "&term=".$term;
$custom_query = new WP_Query($query);
if($custom_query->have_posts()) : while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="portfolio-item">
				<div class="one_half">
					<?php
					if (has_post_thumbnail()) {
						$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
						$img_shortcode = '[image border="true" shadow="true" align="none" title="'.the_title_attribute('echo=0').'" size="medium"';
						if($e404_options['portfolio_prettyphoto'])
							$img_shortcode .= ' lightbox="true"';
						else
							$img_shortcode .= ' href="'.get_permalink().'"';
						$img_shortcode .= ']'.$large_image_url[0].'[/image]';
						echo do_shortcode($img_shortcode);
					} ?>
				</div>
				<div class="one_half last">
					<?php if($e404_options['portfolio_titles']) : ?><h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3><?php endif; ?>
					<?php if($e404_options['portfolio_item_categories']) : ?>
					<div class="portfolio-meta">
						<div class="posted-meta"><?php the_terms($post->ID, 'portfolio-category', '', ', '); ?></div>
					</div>
					<?php endif; ?>
					<?php the_excerpt(''); ?>
				<?php if(!is_single() && $e404_options['portfolio_read_more']) : ?>
					<p class="more"><a href="<?php the_permalink(); ?>"><?php echo($e404_options['portfolio_read_more_text']); ?></a></p>
				<?php endif; ?>
				</div>
				<br class="clear" />
			</div>
<?php endwhile; wp_reset_query();
else :
			_e('Nothing Found', 'cold');
endif; ?>
		</div>
			
	<?php if(function_exists('wp_pagenavi'))
		wp_pagenavi(array('query' => $custom_query));
	else { ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'cold'), $custom_query->max_num_pages ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'cold') ); ?></div>
	<?php } ?>
	</div>
	<?php if($e404_options['portfolio_layout'] == 'sidebar-right') : ?>
		<div id="sidebar" class="one_third last">
	<?php get_sidebar('portfolio'); ?>
		</div>
	<?php endif; ?>
	<br class="clear" />
</div>
<?php get_footer(); ?>
