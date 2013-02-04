<?php
/**
 * Theme Header
 *
 */

global $e404_options;
if($post) {
	$e404_options['post_id'] = $post->ID;
	$e404_options['post_parent'] = $post->post_parent;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="/wp-content/themes/cold-child/searchCustom/search_wp.css" />
<script type="text/javascript" src="/wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript" src="/wp-content/themes/cold-child/searchCustom/cdm_for_wp.js"></script>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
</head>

<body>
<div id="header_wrapper">
	<div id="header">
		<div class="leftside" id="logo"><a href="<?php echo home_url(); ?>"><img src="<?php echo $e404_options['logo_url']; ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" /></a></div>
		<div style="display: block;" id="search">
			<div id="search_content" class="leftside">
				<div class="search_content_container leftside" style="margin-top: 5px;">
					<span id="search_content_text">Search Digital Archive:</span>&nbsp;
				</div>
				<div class="search_content_container leftside" style="margin-top: 0px;">
				  <input id="search_content_box" name="search_content_box" class="search_content_box_noresults" value="" autocomplete="off" type="text">
				  <input id="search_results_button_mode" value="0" type="hidden">
				</div>
				<input name="cdm_searchbox_mode" id="cdm_searchbox_mode" value="results" type="hidden">
				<input name="searchterm" id="searchterm" value="" type="hidden">
				<div class="search_content_container leftside" style="margin-top: 0px;">
					<input tabindex="2" id="simple_search_button" class="search_content_button spaceMar15L" value="go" type="button">
				</div>
				
			</div>
			<div class="search_content_container_advanced">
				<div id="search_content_adv_link" tabindex="6">Advanced Search</div>
			</div>
		</div><!--- end search --->
		<span class="clear"></span>
	</div><!--- end header --->	
</div><!--- ene header_wrapper --->
      	
<div id="adv_search">
	<div id="adv_search_content" class="leftside">
		<div id="adv_search_col_1">
			<h2 class="cdm_style leftside" style="margin-bottom:0;">Find results with:</h2>
			<div id="adv_search_error" class="leftside spacePad10T spacePad10L spaceMar10L ui-state-error ui-corner-all" style="display:none;height:24px;width:500px;">
					<span class="icon_10 ui-icon-alert"></span>
						error div
			</div>
			<span class="clear"></span>
			<ul id="adv_search_query_builder_list" style="float:left;padding:0;margin:0;margin-bottom:15px;list-style-type:none;">
					<li id="rid0" class="adv_search_row ">
						<ul class="adv_search_ul_row">
							<li class="leftside">
								<select id="rid0_mode" class="adv_search_type_dd" >
									<option selected="selected" value="all">All of the words</option>
									<option value="any">Any of the words</option>
									<option value="exact">The exact phrase</option>
									<option value="none">None of the words</option>
								</select>
							</li>
							<li class="leftside spaceMar5L">
								<input id="rid0_term" class="adv_search_str" type="text" value="">
							</li>
							<li class="leftside spaceMar5L spacePad5">in</li>
							<li class="leftside spaceMar5L">
								<select id="rid0_field" class="adv_search_domain_dd">
								<option selected="selected" value="all">All fields</option>
								<option value="title">Title</option>
								<option value="subjec">Subject</option>
								<option value="descri">Description</option>
								<option value="date">Date</option>
							</select>
						</li>
						<li class="leftside spaceMar5L">
							<select id="rid0_connector" class="adv_search_and_or_dd">
								<option selected="selected" value="and">and</option>
								<option value="or">or</option>
							</select>
						</li>
						<li class="adv_search_option_remove_link_box leftside spaceMar10L spacePad5">
							<a class="remove_adv_search_row_link action_link_10" href="javascript://" rid="rid0"></a>
						</li>
					</ul>
					<span class="clear"></span>
				</li>
				<li id="rid1" class="adv_search_row adv_search_row_bgcolor">
					<ul class="adv_search_ul_row">
					<li class="leftside">
					<select id="rid1_mode" class="adv_search_type_dd">
						<option selected="selected" value="all">All of the words</option>
						<option value="any">Any of the words</option>
						<option value="exact">The exact phrase</option>
						<option value="none">None of the words</option>
					</select>
				</li>
				<li class="leftside spaceMar5L">
					<input id="rid1_term" class="adv_search_str" type="text" value="">
				</li>
				<li class="leftside spaceMar5L spacePad5">in</li>
				<li class="leftside spaceMar5L">
					<select id="rid1_field" class="adv_search_domain_dd">
						<option selected="selected" value="all">All fields</option>
						<option value="title">Title</option>
						<option value="subjec">Subject</option>
						<option value="descri">Description</option>
						<option value="date">Date</option>
					</select>
				</li>
				<li class="leftside spaceMar5L">
					<select id="rid1_connector" class="adv_search_and_or_dd">
						<option selected="selected" value="and">and</option>
						<option value="or">or</option>
					</select>
				</li>
				<li class="adv_search_option_remove_link_box leftside spaceMar10L spacePad5">
					<a class="remove_adv_search_row_link action_link_10" href="javascript://" rid="rid1">remove</a>
				</li>
			</ul>
			<span class="clear"></span>
		</li>
	</ul>
	<span class="clear"></span>
	<div>
		<a id="adv_search_add_field_link" class="action_link_10" href="javascript://">Add another field</a>
	</div>
	<span class="clear"></span>
	<div id="adv_search_col_1_bottom" class="spaceMar10T">
	<span id="icon_adv_search_datearrow" class="icon_10 icon_adv_search ui-icon-triangle-1-e"></span>
	<span class="icon_10 icon_adv_search ui-icon-calendar"></span>
	<div id="adv_search_by_date_link" class="action_link_10">Search by date</div>
	<div id="adv_search_by_date_container" class="spaceMar10T">
	<ul id="adv_search_datepicker_list" style="list-style-type:none;padding:0;margin:0;">
	<li class="leftside">
	<select id="adv_search_date_range" class="adv_search_date_range">
	<option selected="selected" value="from">from</option>
	<option value="after">after</option>
	<option value="before">before</option>
	<option value="on">on</option>
	</select>
	</li>
	<li class="leftside spaceMar15L">
	<input id="datepicker1" class="datestring" type="text" value="mm/dd/yyyy">
	</li>
	<li class="leftside spaceMar15L spacePad5">
	<span id="datepickerTo">to</span>
	</li>
	<li class="leftside spaceMar15L">
	<input id="datepicker2" class="datestring" type="text" value="mm/dd/yyyy">
	</li>
	</ul>

	</div>
	</div>
		<span class="clear"></span>
		<div class="spaceMar15R spaceMar15T leftside">
			<input id="advanced_search_button" class="search_content_button" type="button" value="Search">
		</div>

		<span class="clear"></span>
	</div>
	</div>
		<div id="adv_search_col_2">
			<h3 class="cdm_style">Searching collections:</h3>
			<span class="clear"></span>
			<div>
				<!---<a id="advanced_max_collections_link" class="action_link_10" href="javascript://"> Add or remove collections </a>--->
				<img id="advanced_max_collections_link" src="http://seekingmichigan.org//wp-includes/js/tinymce/themes/advanced/skins/default/img/progress.gif" >
			</div>
		</div>
		<span class="clear"></span>

	</div>
	
<span class="clear"></span>
<div id="navigation">
	<?php wp_nav_menu(array('theme_location' => 'header-menu', 'container' => false, 'menu_class' => 'sf-menu')); ?>
		<br class="clear" />
</div>
<div id="wrapper">
	
