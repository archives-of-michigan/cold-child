<?php
/**
 * Page Not Found (error 404) template.
 *
 */

get_header(); ?>

	<div id="main_wrapper">
		<?php if($e404_options['breadcrumbs']) : ?><div id="breadcrumb"><?php e404_breadcrumbs(); ?></div><?php endif; ?>

<?php
if($e404_options['intro_type']) : ?>
	<div id="intro" class="<?php if($e404_options['intro_type'] == 'twitter') echo 'twitter'; else echo 'text'; ?>-intro">
		<?php if($e404_options['intro_type'] == 'title' || $e404_options['intro_type'] == 'title-excerpt') : ?>
		<h1><?php _e('Page not Found', 'cold'); ?></h1>
		<?php else : ?>
		<?php echo $e404_options['intro_text']; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>

		<div id="error" class="one_half">
			<p>
				<strong>404</strong>
				<span><?php _e('Page not Found', 'cold'); ?></span>
			</p>
		</div>
		<div id="error-info" class="one_half last">
			<p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'cold'); ?></p>
			<div class="content-search">
				<form action="<?php echo home_url(); ?>" method="get">
					<input type="text" name="s" value="<?php _e('Search...', 'cold'); ?>" onfocus="if (this.value == '<?php _e('Search...', 'cold'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search...', 'cold'); ?>';}" />
					<input type="submit" value="<?php _e('Search', 'cold'); ?>" />
				</form>
			</div>
    		<hr class="dotted" />
			<ul class="small-list small-arrow">
				<li><?php printf(__('Go to the <a href="%1$s">homepage</a>', 'cold'), home_url()); ?></li>
    		</ul>
		</div>
		<br class="clear" />
	</div>

<?php get_footer(); ?>
