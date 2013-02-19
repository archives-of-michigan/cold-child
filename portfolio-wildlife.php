<?php
/**
 * Template name: Portfolio - Wildlife
 *
 */

get_header();

// Show only items that are in the wildlife portfolio-category

$taxonomy = "portfolio-category";
$term = "wildlife";

?>
	
	<div id="main_wrapper">
		<?php if($e404_options['breadcrumbs']) : ?><div id="breadcrumb"><?php e404_breadcrumbs(); ?></div><?php endif; ?>
		<div class="portfolio portfolio-columns">		

<?php
$query = "paged=".$paged."&post_type=portfolio&orderby=menu_order date&posts_per_page=".$e404_options['portfolio_posts_per_page'];
if(isset($taxonomy))
	$query .= "&taxonomy=".$taxonomy;
if(isset($term))
	$query .= "&term=".$term;
$i = 0;
$custom_query = new WP_Query($query);
if($custom_query->have_posts()) : while ($custom_query->have_posts()) : $custom_query->the_post(); $i++; ?>
				
				<?php
					$portfolio_page_id = get_option('e404_portfolio_page');
					$portfolio_page = get_page($portfolio_page_id);
					$portfolio_cdmid = get_post_meta($post->ID, 'wpcf-wpcf-cdmid', true);
					$portfolio_url = "http://cdm15867.contentdm.oclc.org/cdm/search/collection/".$portfolio_chmid;
				?>

				<div class="one_half portfolio-item<?php if($i % 2 == 0) echo ' last'; ?>" id="post-<?php the_ID(); ?>">
					<?php
					if (has_post_thumbnail()) {
						$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
						$img_shortcode = '[image border="true" shadow="true" align="none" title="'.the_title_attribute('echo=0').'" size="large"';
						if($e404_options['portfolio_prettyphoto'])
							$img_shortcode .= ' lightbox="true"';
						else
							$img_shortcode .= ' href="'.get_permalink().'"';
						$img_shortcode .= ']'.$large_image_url[0].'[/image]';
						echo do_shortcode($img_shortcode);
					} ?>
					<?php if($e404_options['portfolio_titles']) : ?><h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3><?php endif; ?>
					<?php if($e404_options['portfolio_item_categories']) : ?>
					<div class="portfolio-meta">
						<div class="posted-meta"><?php the_terms($post->ID, 'portfolio-category', '', ', '); ?></div>
					</div>
					<?php endif; ?>
					<?php if($e404_options['portfolio_excerpts']) { the_excerpt(''); } ?>

				<?php if(!is_single() && $e404_options['portfolio_read_more']) : ?>
					<p class="more"><a href="<?php echo $portfolio_url; ?>">View Entire Collection</a></p>
				<?php endif; ?>

				</div>
				<?php if($i % 2 == 0) echo '<br class="clear" />'; ?>
<?php endwhile; wp_reset_query(); ?>
			<?php if($i % 2 != 0) echo '<br class="clear" />'; ?>
<?php else :
			_e('Nothing Found', 'cold');
endif; ?>
			
		<div class="wp-pagenavi">
	<?php if(function_exists('wp_pagenavi'))
		wp_pagenavi(array('query' => $custom_query));
	else { ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'cold'), $custom_query->max_num_pages ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'cold') ); ?></div>
	<?php } ?>
			</div>
		</div>
		<br class="clear" />
	</div>

<?php get_footer(); ?>
