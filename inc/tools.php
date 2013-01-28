<?php
// Social Sites
$social_options = array('twitter' => 'Twitter', 'facebook' => 'Facebook', 'google-buzz' => 'Google Buzz', 'digg' => 'Digg', 'delicious' => 'Delicious', 'stumbleupon' => 'StumbleUpon', 'reddit' => 'Reddit', 'technorati' => 'Technorati', 'newsvine' => 'Newsvine', 'google-bookmarks' => 'Google Bookmarks', 'friendfeed' => 'FriendFeed', 'myspace' => 'MySpace', 'slashdot' => 'Slashdot', 'linkedin' => 'LinkedIn', 'yahoo-bookmarks' => 'Yahoo Bookmarks', 'yahoo-buzz' => 'Yahoo Buzz', 'mixx' => 'Mixx', 'mr-wong' => 'Mr Wong', 'xing' => 'Xing');

function e404_img_scale($img, $width = 0, $height = 0, $crop = true) {
	if($crop)
		$crop = 1;
	else
		$crop = 0;
	return OF_DIRECTORY.'/lib/timthumb.php?src='.$img.'&amp;w='.$width.'&amp;h='.$height.'&amp;zc='.$crop;
}

function e404_get_tweets($user, $count) {
	$text = $status_text = '';
	$last_update = get_option('e404_twitter_'.$user.'_last_update_'.(int)$count);
	$text = get_option('e404_twitter_'.$user.'_last_response_'.(int)$count);
	if(($last_update < (time() - get_option('e404_twitter_expire'))) || !$text) {
		$url = "http://api.twitter.com/1/statuses/user_timeline.xml?screen_name=".$user."&include_rts=true&count=".(int)$count;
		$xml = @simplexml_load_file($url);
		if($xml === false) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			$result = curl_exec($ch);
			curl_close($ch);
			$xml = @simplexml_load_string($result);
		}
		$text = array();
		if(is_object($xml)) {
			foreach($xml->status as $status) {
				$status_response['text'] = (string)$status->text;
				$status_response['time'] = getRelativeTime($status->created_at);
				$status_response['id'] = (string)$status->id;
				$text[] = $status_response;
			}
			update_option('e404_twitter_'.$user.'_last_update_'.(int)$count, time());
			if(count($text) > 0) {
				update_option('e404_twitter_'.$user.'_last_response_'.(int)$count, $text);
			}
		}
	}
	
    return $text;
}

function plural($num) {
	if ($num != 1)
		return "s";
}

function getRelativeTime($date) {
	$diff = time() - strtotime($date);
	if ($diff < 60)
		return "about ".$diff." second".plural($diff)." ago";
	$diff = round($diff / 60);
	if ($diff < 60)
		return "about ".$diff." minute".plural($diff)." ago";
	$diff = round($diff / 60);
	if ($diff < 24)
		return "about ".$diff." hour".plural($diff)." ago";
	$diff = round($diff / 24);
	if ($diff < 7)
		return "about ".$diff." day".plural($diff)." ago";
	$diff = round($diff / 7);
	if ($diff < 4)
		return "about ".$diff." week".plural($diff)." ago";
	return "on ".date("F j, Y", strtotime($date));
}

function e404_get_tweets_js($user, $count) {
	$output = "
	<script type=\"text/javascript\">
		jQuery(document).ready(function($) {
			 $('#intro').tweet({
				username: ['".$user."'],
				count: 1,
				template: function(i){return i['text'] + ' ' + i['time']},
				seconds_ago_text: 'about %d seconds ago',
				a_minutes_ago_text: 'about a minute ago',
				minutes_ago_text: 'about %d minutes ago',
				a_hours_ago_text: 'about an hour ago',
				hours_ago_text: 'about %d hours ago',
				a_day_ago_text: 'about a day ago',
				days_ago_text: 'about %d days ago',
				view_text: 'view tweet on twitter'
			 });
		});
	</script>";

	return $output;
}

function e404_breadcrumbs() {
	global $wp_query, $post;

	$home_page_id = get_option('page_for_posts');
	$blog_page_id = get_option('page_on_front');
	$front_page_type = get_option('show_on_front');
	
	echo '<a href="'.home_url().'">'.__('Home', 'cold').'</a>';
	if($front_page_type == 'page' && e404_get_current_template() == 'index.php') {
		echo ' <span>&rsaquo;</span> ';
		$page_data = get_page(get_option('page_for_posts'));
		echo '<a href="'.get_permalink(get_option('page_for_posts')).'">'.get_the_title($page_data).'</a>';
	}
	if(!is_front_page() && !is_home()) {
		echo ' <span>&rsaquo;</span> ';
	}
	if ((is_category() || is_single()) && get_post_type() != 'portfolio') {
		if (is_single()) {
			the_category(', ');
			echo " <span>&rsaquo;</span> ";
			the_title();
		}
		else {
			$links = get_category_parents($wp_query->query_vars['cat'], true, ' <span>&rsaquo;</span> ');
			$links = substr($links, 0, strlen($links) - 23);
			echo $links;
		}
	} elseif (is_search()) {
		_e('Search', 'cold');
	} elseif (is_404()) {
		_e('Nothing Found', 'cold');
	} elseif (is_page()) {
		if(is_object($post)) {
			$parent = $post->post_parent;
			$links = '';
			while($parent) {
				$page = get_page($parent);
				$links = '<a href="'.get_permalink($page).'">'.get_the_title($page).'</a> <span>&rsaquo;</span> '.$links;
				$parent = $page->post_parent;
			}
			echo $links;
		}
		echo the_title();
	} elseif (is_tag()) {
		echo single_tag_title();
	} elseif (is_tax()) {
		e404_show_portfolio_link();
		$obj = $wp_query->get_queried_object();
		echo " <span>&rsaquo;</span> ";
		echo $obj->name;
	} elseif (get_post_type() == 'portfolio') {
		e404_show_portfolio_link();
		if (is_single()) {
			echo " <span>&rsaquo;</span> ";
			the_title();
		}
	} elseif (is_archive()) {
		_e('Archive', 'cold');
	}
}

function e404_show_portfolio_link() {
	$page_id = get_option('e404_portfolio_page');
	if($page_id != 0) {
		$page = get_page($page_id);
		echo '<a href="'.get_page_link($page_id).'">'.get_the_title($page).'</a>';
	}
}

// subpages navigation
function e404_subpages_nav() {
	global $post;
	if(is_category() || is_tag() || is_tax())
		return;
	if($post->post_parent)
		$post_id = $post->post_parent;
	else
		$post_id = $post->ID;
	$top_page = get_page($post_id);
	?>
		<div class="widgets subpages-widget">
			<h3><?php echo get_the_title($top_page); ?></h3>
			<?php wp_list_pages(array('child_of' => $post_id, 'sort_column' => 'menu_order, post_title', 'title_li' => '', 'depth' => 2)); ?>
		</div>
	<?php
}

// Source: Twitter for Wordpress
function twitter_hyperlinks($text) {
	// match URLs
	$text = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:\(\)=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" class=\"twitter-link\">\\2</a>", $text);
	// match name@address
	$text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
	// match #trendingtopics. Props to Michael Voigt
	$text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);
	return $text;
}

// Source: Twitter for Wordpress
if(!function_exists('twitter_users')) {
	function twitter_users($text) {
		$text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
		return $text;
	}
}

function e404_get_flickr_photos($user_id, $tags = '', $number = 9, $random = false) {
	if(!empty($tags))
		$tags = 'tags='.$tags;

	$cache_key = md5($user_id.$tags);
	$last_update = get_option('e404_flickr_last_update_'.$cache_key);
	$feed = get_option('e404_flickr_last_response_'.$cache_key);
	if(($last_update < (time() - get_option('e404_flickr_expire'))) || !$feed) {
		$url = "http://api.flickr.com/services/feeds/photos_public.gne?id=".urlencode($user_id)."&format=php_serial&lang=en-us".$tags;
		$feed = unserialize(@file_get_contents($url));
		if(!is_array($feed)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			$result = curl_exec($ch);
			curl_close($ch);
			$feed = unserialize($result);
			if(!is_array($feed))
				return array();
		}
		update_option('e404_flickr_last_update_'.$cache_key, time());
		update_option('e404_flickr_last_response_'.$cache_key, $feed);
	}
	$photos = array();
	for($a = 0; $a < $number; $a++)
	foreach($feed['items'] as $photo) {
		$photos[$a]['title'] = $feed['items'][$a]['title'];
		$photos[$a]['url'] = $feed['items'][$a]['url'];
		$photos[$a]['photo'] = str_replace("_m.jpg", "_b.jpg", $feed['items'][$a]['photo_url']);
		$photos[$a]['thumb'] = $feed['items'][$a]['thumb_url'];
	}
	if($random)
		shuffle($photos);
	return $photos;
}

function e404_word_limiter($text, $limit = 100) {
	$text = strip_tags($text);
	if(strlen($text) > $limit) {
		$words = explode(" ", $text);
		$text_ = "";
		foreach($words as $word) {
			$text_ .= $word." ";
			if(strlen($text_) > $limit)
				break;
		}
		$text = substr($text_, 0, strlen($text_) - 1)."...";
	}
	return $text;
}

function e404_share_this($echo = true) {
	global $post, $social_options, $e404_all_options;
	
	$sites = array(
					'twitter' => 'http://twitter.com/intent/tweet?status=%title%%20-%20%url%',
					'facebook' => 'http://www.facebook.com/sharer.php?u=%url%',
					'google-buzz' => 'http://www.google.com/reader/link?url=%url%&title=%title%',
					'digg' => 'http://digg.com/submit?url=%url%&title=%title%&media=NEWS&thumbnails=1',
					'delicious' => 'http://del.icio.us/post?url=%url%&title=%title%',
					'stumbleupon' => 'http://www.stumbleupon.com/submit?url=%url%&title=%title%',
					'reddit' => 'http://www.reddit.com/submit?url=%url%&title=%title%',
					'technorati' => 'http://www.technorati.com/faves/?add=%url%',
					'newsvine' => 'http://www.newsvine.com/_tools/seed&save?u=%url%&h=%title%',
					'google-bookmarks' => 'http://www.google.com/bookmarks/mark?op=edit&bkmk=%url%&title=%title%',
					'friendfeed' => 'http://friendfeed.com/share?url=%url%&title=%title%',
					'myspace' => 'http://www.myspace.com/Modules/PostTo/Pages/?l=3&u=%url%&t=%title%',
					'slashdot' => 'http://slashdot.org/bookmark.pl?url=%url%&title=%title%',
					'linkedin' => 'http://www.linkedin.com/shareArticle?mini=true&url=%url%&title=%title%',
					'yahoo-bookmarks' => 'http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&u=%url%&t=%title%',
					'yahoo-buzz' => 'http://buzz.yahoo.com/submit/?submitUrl=%url%/&submitHeadline=%title%',
					'mixx' => 'http://www.mixx.com/submit?page_url=%url%',
					'mr-wong' => 'http://www.mister-wong.com/index.php?action=addurl&bm_url=%url%&bm_description=%title%',
					'xing' => 'https://www.xing.com/app/user?op=share&url=%url%&title=%title%',
				   );
	
	$output = '<span class="share_button"><a href="#" title="Share This"><img src="'.OF_DIRECTORY.'/images/social/add-this.png" alt="Share This" /></a></span>';
	$output .= '<span class="share_buttons">';
	if($e404_all_options['e404_share_buttons_target'] == 'true')
		$target = ' target="_blank"';
	else
		$target = '';
	foreach($sites as $site => $url_pattern) {
		if(get_option('e404_share_this_sites_'.$site) == 'true') {
			$url = str_replace(array('%url%', '%title%'), array(rawurlencode(get_permalink()), rawurlencode($post->post_title)), $url_pattern);
			$output .= '<a href="'.$url.'"'.$target.' title="'.$social_options[$site].'"><img src="'.OF_DIRECTORY.'/images/social/'.$site.'.png" alt="'.$social_options[$site].'" /></a> ';
		}
	}
	$output .= '</span>';
	
	if($echo)
		echo $output;
	else
		return $output;
}

// generate home page featured boxes
function e404_show_featured_boxes() {
	global $e404_all_options;
	
	$boxes = $e404_all_options['e404_home_featured_boxes'];
	if($boxes < 1)
		return;
	
	if($boxes == 1)
		$class = 'full';
	elseif($boxes == 2)
		$class = 'half';
	elseif($boxes == 3)
		$class = 'third';
	elseif($boxes == 4)
		$class = 'fourth';
	
	if($e404_all_options['e404_home_featured_1_icon'] == '(none)')
		$e404_all_options['e404_home_featured_1_icon'] = 'none';
	if($e404_all_options['e404_home_featured_2_icon'] == '(none)')
		$e404_all_options['e404_home_featured_2_icon'] = 'none';
	if($e404_all_options['e404_home_featured_3_icon'] == '(none)')
		$e404_all_options['e404_home_featured_3_icon'] = 'none';
	if($e404_all_options['e404_home_featured_4_icon'] == '(none)')
		$e404_all_options['e404_home_featured_4_icon'] = 'none';
	
	$output = '<ul id="featured-boxes">';
	$output .= '<li class="featured_'.$class.' icon-'.$e404_all_options['e404_home_featured_1_icon'].' featured-dark">';
	$output .= '<div><h4>';
	if(empty($e404_all_options['e404_home_featured_1_url']))
		$output .= stripslashes($e404_all_options['e404_home_featured_1_title']).'</h4>';
	else
		$output .= '<a href="'.$e404_all_options['e404_home_featured_1_url'].'">'.stripslashes($e404_all_options['e404_home_featured_1_title']).'</a></h4>';
	$output .= '<p>'.do_shortcode(stripslashes($e404_all_options['e404_home_featured_1_text'])).'</p>';
	$output .= '</div></li>';
	if($boxes > 1) {
		$output .= '<li class="featured_'.$class.' icon-'.$e404_all_options['e404_home_featured_2_icon'].'">';
		$output .= '<div><h4>';
		if(empty($e404_all_options['e404_home_featured_2_url']))
			$output .= stripslashes($e404_all_options['e404_home_featured_2_title']).'</h4>';
		else
			$output .= '<a href="'.$e404_all_options['e404_home_featured_2_url'].'">'.stripslashes($e404_all_options['e404_home_featured_2_title']).'</a></h4>';
		$output .= '<p>'.do_shortcode(stripslashes($e404_all_options['e404_home_featured_2_text'])).'</p>';
		$output .= '</div></li>';
	}
	if($boxes > 2) {
		$output .= '<li class="featured_'.$class.' icon-'.$e404_all_options['e404_home_featured_3_icon'].' featured-dark">';
		$output .= '<div><h4>';
		if(empty($e404_all_options['e404_home_featured_3_url']))
			$output .= stripslashes($e404_all_options['e404_home_featured_3_title']).'</h4>';
		else
			$output .= '<a href="'.$e404_all_options['e404_home_featured_3_url'].'">'.stripslashes($e404_all_options['e404_home_featured_3_title']).'</a></h4>';
		$output .= '<p>'.do_shortcode(stripslashes($e404_all_options['e404_home_featured_3_text'])).'</p>';
		$output .= '</div></li>';
	}
	if($boxes > 3) {
		$output .= '<li class="featured_'.$class.'_last icon-'.$e404_all_options['e404_home_featured_4_icon'].'">';
		$output .= '<div><h4>';
		if(empty($e404_all_options['e404_home_featured_4_url']))
			$output .= stripslashes($e404_all_options['e404_home_featured_4_title']).'</h4>';
		else
			$output .= '<a href="'.$e404_all_options['e404_home_featured_4_url'].'">'.stripslashes($e404_all_options['e404_home_featured_4_title']).'</a></h4>';
		$output .= '<p>'.do_shortcode(stripslashes($e404_all_options['e404_home_featured_4_text'])).'</p>';
		$output .= '</div></li>';
	}
	
	$output .= '</ul><br class="clear" />';
	
	echo $output;
}

// source: PHP Manual
function hex2RGB($hexStr, $returnAsString = true, $seperator = ',') {
	$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
    $rgbArray = array();
    if(strlen($hexStr) == 6) {
		$colorVal = hexdec($hexStr);
		$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) {
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false;
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray;
}

// Add 'rel="prettyPhoto"' to gallery images
function activate_post_links($content) {
	if (!is_feed() && ((get_option('e404_blog_prettyphoto') == 'true' && e404_get_current_template() == 'index.php')
		|| (get_option('e404_gallery_prettyphoto') == 'true' && (substr(e404_get_current_template(), 0, 7) == 'gallery')
		|| (substr(e404_get_current_template(), 0, 4) == 'page')
		|| (substr(e404_get_current_template(), 0, 16) == 'single-portfolio')))) {
		$matches = array();
		if (preg_match_all("/\<a[^\>]*href=[^\s]+\.(?:jp[e]*g|gif|png).*?\>/i", $content, $matches)) {
			global $post;
			foreach ($matches[0] as $link) {
				$link_new = $link;
				$rel = '';
				if (strpos(strtolower($link_new), ' rel=') !== false && preg_match("/\s+rel=(?:\"|')(.*?)(?:\"|')(\s|\>)/i", $link_new, $rel)) {
					$link_new = str_replace($rel[0], $rel[2], $link_new);
					$rel = $rel[1];
				}
				if (strpos($rel, 'prettyPhoto') === false) {
					$rel .= '' . 'prettyPhoto[g' . $post->ID . ']';
					$link_new = '<a rel="' . $rel . '"' . substr($link_new,2);
					$content = str_replace($link, $link_new, $content);
				}
			}
		}
	}
	return $content; 
}
add_filter('the_content', 'activate_post_links', 99);	
?>