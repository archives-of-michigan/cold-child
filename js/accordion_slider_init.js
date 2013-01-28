jQuery(document).ready(function() {
	jQuery('.kwicks li').css('width', slideParams['liwidth']);
	jQuery('.kwicks li').css('height', slideParams['height']);
	jQuery('.kwicks.horizontal p.title').css('opacity', slideParams['opacity']);
	jQuery(".kwicks").krioImageLoader({ onStart: function() {
			jQuery(this).kwicks({
				spacing: 1,
				max: slideParams['maxWidth'],
				easing: slideParams['effect'],
				duration: slideParams['duration']
			});
	}});
});
