<?php
/**
 * Footer Template
 *
 */

global $e404_options;
?>
</div>

<div id="footer_wrapper">
	<div id="footer">
<?php dynamic_sidebar('e404_footer_sidebar'); ?>
	<br class="clear" />
	</div>
	<div id="copyright">
		<div class="one_half"><?php echo $e404_options['footer_below_left']; ?></div>
		<div class="one_half last right"><?php echo $e404_options['footer_below_right']; ?></div>
		<br class="clear" />
	</div>
</div>
<?php
	wp_footer();
?>
</body>
</html>
