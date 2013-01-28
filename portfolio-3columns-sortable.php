<?php
/**
 * Template name: Portfolio - 3 columns sortable
 *
 */

get_header(); ?>
	
	<div id="main_wrapper" class="portfolio-sortable">
		<?php if($e404_options['breadcrumbs']) : ?><div id="breadcrumb"><?php e404_breadcrumbs(); ?></div><?php endif; ?>
		<?php include(OF_FILEPATH.'/portfolio-intro-box.php'); ?>

		<div class="nav-wrap">
			<ul id="pcats" class="group">
				<li class="current_page_item_li"><a href="#" rel="all">All</a></li>
<?php
		$params = 'title_li=&taxonomy=portfolio-category';
		$params .= '&hierarchical=0';
		$categories = get_categories($params);
		
		foreach($categories as $category) {
			?>
			<li><a href="#" rel="<?php echo $category->slug; ?>" class="pcat"><?php echo $category->name; ?></a></li>
			<?php
		}
?>
			</ul>
		</div>
	
		<ul id="items" class="portfolio portfolio-columns">
<?php
$query = "paged=".$paged."&post_type=portfolio&orderby=menu_order date&nopaging=true";
if(isset($taxonomy))
	$query .= "&taxonomy=".$taxonomy;
if(isset($term))
	$query .= "&term=".$term;
$i = 0;
$custom_query = new WP_Query($query);
if($custom_query->have_posts()) : while ($custom_query->have_posts()) : $custom_query->the_post(); $i++;
				$item_categories = get_the_terms($post->ID, 'portfolio-category');
				$cats = '';
				if(is_array($item_categories)) {
					foreach($item_categories as $item_category)
						$cats .= ' '.$item_category->slug;
				}
				?>
				<li class="one_third portfolio-item<?php if($i % 3 == 0) echo ' last'; echo $cats; ?>" id="post-<?php the_ID(); ?>">
					<?php
					if (has_post_thumbnail()) {
						$large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
						$img_shortcode = '[image border="true" shadow="true" align="none" title="'.the_title_attribute('echo=0').'" size="medium" height="280"';
						$img_shortcode .= ' href="'.get_permalink().'"';
						$img_shortcode .= ']'.$large_image_url[0].'[/image]';
						echo do_shortcode($img_shortcode);
					} ?>
					<?php if($e404_options['portfolio_titles']) : ?><div class="fancy_meta_pf"><h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3></div><?php endif; ?>

				</li>
<?php endwhile; wp_reset_query(); ?>
<?php else :
			_e('Nothing Found', 'lunar');
endif; ?>
			
		</ul>

		<br class="clear" />
	</div>

<?php get_footer(); ?>
