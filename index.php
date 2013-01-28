<?php
/**
 * The main template file.
 *
 */

get_header(); ?>
	
	<div id="main_wrapper">
	<?php if($e404_options['blog_slider']) : ?>
		<div id="featured">
			<?php if($e404_options['blog_slider_ribbon']) : ?><div class="ribbon"></div><?php endif; ?>
			<?php e404_show_slider(); ?>
		</div>
	<?php endif; ?>
	<?php if($e404_options['breadcrumbs']) : ?><div id="breadcrumb"><?php e404_breadcrumbs(); ?></div><?php endif; ?>
	<?php include(OF_FILEPATH.'/blog-intro-box.php'); ?>

	<?php if($e404_options['blog_layout'] == 'sidebar-left') : ?>
		<div id="sidebar" class="one_third">
	<?php get_sidebar('blog'); ?>
		</div>
	<?php endif; ?>
		<div id="page-content" class="two_third<?php if($e404_options['blog_layout'] == 'sidebar-left') echo ' last'; ?>">
		
	<?php
		if (have_posts())
			the_post();
		if (is_day())
			printf('<div class="full_page"><h1 class="page-title">'.__('Daily Archives: <span>%s</span>', 'cold').'</h1></div>', get_the_date());
		elseif(is_month())
			printf('<div class="full_page"><h1 class="page-title">'.__('Monthly Archives: <span>%s</span>', 'cold').'</h1></div>', get_the_date('F Y'));
		elseif(is_year())
			printf('<div class="full_page"><h1 class="page-title">'.__('Yearly Archives: <span>%s</span>', 'cold').'</h1></div>', get_the_date('Y'));
		elseif(is_tag())
			printf('<div class="full_page"><h1 class="page-title">'.__('Tag Archives: <span>%s</span>', 'cold').'</h1></div>', single_tag_title('', false));
		rewind_posts();
	?>
		
<?php
while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if(!is_attachment()) : ?>
				<div class="meta-date">
				   <span class="meta-month"><?php the_time('M'); ?></span>
					<span class="meta-day"><?php the_time('d'); ?></span>
					<span class="meta-year"><?php the_time('Y'); ?></span>
					<?php if(!is_attachment() && comments_open($post->ID)) : ?><div class="meta-comments"><a href="<?php comments_link(); ?>"><span><?php comments_number('0', '1', '%'); ?></span></a></div><?php endif; ?>
				 </div>
				<h2 class="blog-header"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="meta posted-meta">
				<?php if($e404_options['blog_post_author']) { _e(' by ', 'cold'); echo the_author_link().' '; } ?><?php if($e404_options['blog_post_categories']) { _e('in ', 'cold'); the_category(', '); } ?><?php edit_post_link(__('Edit', 'cold'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
				<?php if($e404_options['blog_post_tags']) : ?><div class="meta tags-meta"><?php the_tags('', ' '); ?></div><?php endif; ?>
				<br class="clear" />
			<?php endif; ?>
			<?php
			if($e404_options['blog_use_thumbnail'] && !is_single()) {
				if (has_post_thumbnail()) {
					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
					$img_shortcode = '[image border="true" align="'.$e404_options['blog_thumbnails_align'].'" title="'.the_title_attribute('echo=0').'" shadow="true" size="'.$e404_options['blog_thumbnails_size'].'"';
					if($e404_options['blog_prettyphoto'])
						$img_shortcode .= ' lightbox="true"';
					else
						$img_shortcode .= ' href="'.get_permalink().'"';
					if(!empty($e404_options['blog_thumbnails_height']) && $e404_options['blog_thumbnails_height'] > 0)
						$img_shortcode .= ' height="'.$e404_options['blog_thumbnails_height'].'"';
					$img_shortcode .= ']'.$large_image_url[0].'[/image]';
					echo do_shortcode($img_shortcode);
				}
				if($e404_options['blog_use_excerpt']) the_excerpt(); else the_content(''); 
			}
			else {
				if($e404_options['blog_use_excerpt'] && !is_single()) the_excerpt(); else the_content(''); 
				?>
				<br class="clear" />
				<?php
			}
			?>

			<?php if(!is_single() && $e404_options['blog_read_more']) : ?>
				<p class="more"><a href="<?php the_permalink(); ?>"><?php echo($e404_options['blog_read_more_text']); ?></a></p>
			<?php endif; ?>

			<?php if(is_single() && !is_attachment() && $e404_options['blog_share_it']) : ?>
				<div class="share-this">
					<?php e404_share_this(); ?>
				</div>
			<?php endif; ?>
			<?php if(is_single() && $e404_options['blog_author_bio'] && !is_attachment()) : ?>
				<?php $user = get_user_by('id', $post->post_author);
				if($user->description) : ?>
				<div id="post-author" class="fancy-box gray-box">
					<div class="fancy-box-title">
						<h3><?php _e('About the Author', 'cold'); ?></h3>
					</div>
					<div class="fancy-box-content">
						<div class="border-img alignleft"><?php echo get_avatar($post->post_author, 80, OF_DIRECTORY.'/images/avatar.png'); ?></div>
						<h4><?php _e('Written by', 'cold'); ?> <?php echo the_author_link(); ?></h4>
						<p><?php echo $user->description; ?></p>
						<br class="clear" />
					</div>
				</div>
				<?php endif; ?>
			<?php endif; ?>

				<?php if(is_single() && !is_attachment() && comments_open($post->ID)) {
					comments_template( '', true );
				} ?>
			</div>
<?php endwhile; ?>
			
			<?php get_template_part('navigation'); ?>

		</div>
	<?php if($e404_options['blog_layout'] == 'sidebar-right') : ?>
		<div id="sidebar" class="one_third last">
	<?php get_sidebar('blog'); ?>
		</div>
	<?php endif; ?>
		<br class="clear" />
	</div>

<?php get_footer(); ?>
