function get_collections_js(){
	//using ajax with Wordpress
	//requires setup in functions.php
	//sets up function for call via PHP function in getCollections.php
	jQuery.ajax({
		url: my_collections_ajax.ajaxurl,
		data:({action : 'getCollectionSelect'}),
		cache: false
	}).done(function( html ) {
		jQuery("#advanced_max_collections_link").parent().append(html);
		jQuery("#advanced_max_collections_link").remove();
		/* no death records on DNR
		jQuery('.collBoxes').click(function(){
			deathBoxes();
		});*/
	});
	
}

function deathBoxes(){
	
		var deathCol = false;
		jQuery('.collBoxes:checked').each(function() {
			if (jQuery(this).val() == '/p129401coll7'){
				deathCol = true;	
			}
		});
		
		if (deathCol){
			if (jQuery('#rid0_field option').size() < 6){
				jQuery('.adv_search_domain_dd').each(function() {
					jQuery(this).append('<option value="title">County</option><option value="subjec">City/village/township</option><option value="creato">Last Name</option><option value="descri">Given Name</option><option value="publis">Birth year</option><option value="contri">Age</option><option value="format">Death Year</option><option value="identi">Father\'s Given</option><option value="source">Father\'s Last</option>');
				});
			}
		}else{
			jQuery('.adv_search_domain_dd').each(function() {
			
				var fieldSelID = '#' + jQuery(this).attr('id');
				var myOption = fieldSelID + ' option:gt(4)';	
				jQuery(myOption).remove();
			});
		}	
}