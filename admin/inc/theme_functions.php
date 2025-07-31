<?php if(!defined('IN_GS')) { die('you cannot load this page directly.'); }
/**
 * Theme Functions
 *
 * These functions are used within the front-end of a GetSimple Legacy installation
 *
 * @link http://get-simple.info/docs/theme-codex/
 *
 * @package GetSimple Legacy
 * @subpackage Theme-Functions
 */

/**
 * Get Page Content
 *
 * @since 1.0
 * @uses $content 
 * @uses exec_action
 * @uses exec_filter
 * @uses strip_decode
 *
 * @return string Echos.
 */
function get_page_content() {
	global $content;
	exec_action('content-top');
	$content = strip_decode($content);
	$content = exec_filter('content',$content);
	if(getDef('GSCONTENTSTRIP',true)) $content = strip_content($content);
	echo $content;
	exec_action('content-bottom');
}

/**
 * Get Page Excerpt
 *
 * @since 2.0
 * @uses $content
 * @uses exec_filter
 * @uses strip_decode
 *
 * @param string $n Optional, default is 200.
 * @param bool $striphtml Optional, default false, true will strip html from $content
 * @param string $ellipsis Optional, Default '...', specify an ellipsis
 * @return string Echos.
 */
function get_page_excerpt($len=200, $striphtml=true, $ellipsis = '...') {
	GLOBAL $content;
	if ($len<1) return '';
	$content_e = strip_decode($content);
	$content_e = exec_filter('content',$content_e);
	if(getDef('GSCONTENTSTRIP',true)) $content_e = strip_content($content_e);	
	echo getExcerpt($content_e, $len, $striphtml, $ellipsis);
}


/**
 * Get Page Meta Keywords
 *
 * @since 2.0
 * @uses $metak
 * @uses strip_decode
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_meta_keywords($echo=true) {
	global $metak;
	$myVar = encode_quotes(strip_decode($metak));
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Meta Description
 *
 * @since 2.0
 * @uses $metad
 * @uses strip_decode
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_meta_desc($echo=true) {
	global $metad;
	$myVar = encode_quotes(strip_decode($metad));
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Title
 *
 * @since 1.0
 * @uses $title
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_title($echo=true) {
	global $title;
	$myVar = strip_decode($title);
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Clean Title
 *
 * This will remove all HTML from the title before returning
 *
 * @since 1.0
 * @uses $title
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_clean_title($echo=true) {
	global $title;
	$myVar = strip_tags(strip_decode($title));
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Subtitle
 *
 * This function retrieves the subtitle of a page, optionally echoing it.
 *
 * @since 2025.2.0
 * @uses $subtitle
 * @uses strip_decode()
 *
 * @param bool $echo Optional, default is true. False will 'return' value.
 * @return string|null Echos or returns string based on param $echo.
 */

function get_page_subtitle($echo = true) {
	global $subtitle;
	$result = strip_decode((string) $subtitle);
	if ($echo) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * Get Page Clean Subtitle
 *
 * Retrieves the subtitle of a page, stripped of HTML tags and decoded, optionally echoing it.
 *
 * @since 2025.2.0
 * @uses $subtitle
 * @uses strip_decode()
 * @uses strip_tags()
 *
 * @param bool $echo Optional, default is true. If true, the subtitle is echoed.
 * @return string|null The clean subtitle of the page, either echoed or returned based on the $echo parameter.
 */

function get_page_clean_subtitle($echo = true) {
	global $subtitle;
	$result = strip_tags(strip_decode((string) $subtitle));
	if ($echo) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * Get Page Summary
 *
 * Retrieves the summary of a page, optionally echoing it.
 *
 * @since 2025.2.0
 * @uses $summary
 * @uses strip_decode()
 *
 * @param bool $echo Optional, default is true. If true, the summary is echoed.
 * @return string|null The summary of the page, either echoed or returned based on the $echo parameter.
 */
function get_page_summary($echo = true) {
	global $summary;
	$result = strip_decode((string) $summary);
	if ($echo) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * Get Clean Page Summary
 *
 * Retrieves the summary of a page, stripped of HTML tags and decoded, optionally echoing it.
 *
 * @since 2025.2.0
 * @uses $summary
 * @uses strip_decode()
 * @uses strip_tags()
 *
 * @param bool $echo Optional, default is true. If true, the summary is echoed.
 * @return string|null The clean summary of the page, either echoed or returned based on the $echo parameter.
 */
function get_page_clean_summary($echo = true) {
	global $summary;
	$result = stip_tags(strip_decode((string) $summary));
	if ($echo) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * Get Page Featured Image
 *
 * Retrieves the featured image of a page, optionally echoing it.
 *
 * @since 2025.2.0
 * @uses $featured_image
 *
 * @param bool $echo Optional, default is true. If true, the featured image is echoed.
 * @return string|null The featured image of the page, either echoed or returned based on the $echo parameter.
 */
function get_page_featured_image($echo = true) {
	global $featured_image;
	if ($echo) {
		echo (string) $featured_image;
	} else {
		return (string) $featured_image;
	}
}

/**
 * Get Page Slug
 *
 * This will return the slug value of a particular page
 *
 * @since 1.0
 * @uses $url
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_slug($echo=true) {
	global $url;
	$myVar = $url;
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Slug of Parent Page
 *
 * This will return the slug value of a particular page's parent
 *
 * @since 2025.2.0
 * @uses $parent
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_parent($echo = true) {
	global $parent;
	if ($echo) {
		echo (string) $parent;
	} else {
		return (string) $parent;
	}
}

/**
 * Get Page Date
 *
 * This will return the page's updated date/timestamp
 *
 * @since 1.0
 * @uses $date
 * @uses $TIMEZONE
 *
 * @param string $i Optional, default is "l, F jS, Y - g:i A"
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_date($i = "l, F jS, Y - g:i A", $echo=true) {
	global $date;
	global $TIMEZONE;
	if ($TIMEZONE != '') {
		if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set($TIMEZONE);
		}
	}
	
	$myVar = date($i, strtotime($date));
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Full URL
 *
 * This will return the full url
 *
 * @since 1.0
 * @uses $parent
 * @uses $url
 * @uses $SITEURL
 * @uses $PRETTYURLS
 * @uses find_url
 *
 * @param bool $echo Optional, default is false. True will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_url($echo=false) {
	global $url;
	global $SITEURL;
	global $PRETTYURLS;
	global $parent;

	if (!$echo) {
		echo find_url($url, $parent);
	} else {
		return find_url($url, $parent);
	}
}

/**
 * Get Page Header HTML
 *
 * This will return header html for a particular page. This will include the 
 * meta desriptions & keywords, canonical and title tags
 *
 * @since 1.0
 * @uses exec_action
 * @uses get_page_url
 * @uses strip_quotes
 * @uses get_page_meta_desc
 * @uses get_page_meta_keywords
 * @uses $metad
 * @uses $title
 * @uses $content
 * @uses $site_full_name from configuration.php
 * @uses GSADMININCPATH
 *
 * @since 2024.2 Don't include file configuration.php
 *
 * @return void
 */
function get_header($full = true){
	global $metad;
	global $title;
	global $content;

	// meta description
	if ($metad != '') {
		$desc = get_page_meta_desc(false);
	} elseif(getDef('GSAUTOMETAD', true)) {
		// use content excerpt, NOT filtered
		$desc = strip_decode($content);
		if (getDef('GSCONTENTSTRIP', true)) $desc = strip_content($desc);
		$desc = cleanHtml($desc, array('style', 'script')); // remove unwanted elements that strip_tags fails to remove
		$desc = getExcerpt($desc, 160); // grab 160 chars
		$desc = strip_whitespace($desc); // remove newlines, tab chars
		$desc = encode_quotes($desc);
		$desc = trim($desc);
	}

	if (!empty($desc)) echo '<meta name="description" content="'.$desc.'" />'."\n";

	// meta keywords
	$keywords = get_page_meta_keywords(false);
	if ($keywords != '') echo '<meta name="keywords" content="'.$keywords.'" />'."\n";
	
	if ($full) echo '<link rel="canonical" href="'. get_page_url(true) .'" />'."\n";

	// script queue
	get_scripts_frontend();

	exec_action('theme-header');
}

/**
 * Get Page Footer HTML
 *
 * This will return footer html for a particular page. Right now
 * this function only executes a plugin hook so developers can hook into
 * the bottom of a site's template.
 *
 * @since 2.0
 * @uses exec_action
 *
 * @return string HTML for template header
 */
function get_footer() {
	get_scripts_frontend(TRUE);
	exec_action('theme-footer');
}

/**
 * Get Site URL
 *
 * This will return the site's full base URL
 * This is the value set in the control panel
 *
 * @since 1.0
 * @uses $SITEURL
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_site_url($echo=true) {
	global $SITEURL;
	
	if ($echo) {
		echo $SITEURL;
	} else {
		return $SITEURL;
	}
}

/**
 * Get Theme URL
 *
 * This will return the current active theme's full URL 
 *
 * @since 1.0
 * @uses $SITEURL
 * @uses $TEMPLATE
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_theme_url($echo=true) {
	global $SITEURL;
	global $TEMPLATE;
	$myVar = trim($SITEURL . "theme/" . $TEMPLATE);
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Site's Name
 *
 * This will return the value set in the control panel
 *
 * @since 1.0
 * @uses $SITENAME
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_site_name($echo=true) {
	global $SITENAME;
	$myVar = cl($SITENAME);
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Site Title
 *
 * @since 2025.2.0
 * @uses $SITENAME
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_title($echo = true) {
	global $SITENAME;
	if ($echo) {
		echo (string) $SITENAME;
	} else {
		return (string) $SITENAME;
	}
}
/**
 * Get Site Subtitle
 *
 * @since 2025.2.0
 * @uses $SITE_SUBTITLE
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_subtitle($echo = true) {
	global $SITE_SUBTITLE;
	if ($echo) {
		echo (string) $SITE_SUBTITLE;
	} else {
		return (string) $SITE_SUBTITLE;
	}
}

/**
 * Get Site Tagline
 *
 * @since 2025.2.0
 * @uses $SITE_TAGLINE
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_tagline($echo = true) {
	global $SITE_TAGLINE;
	if ($echo) {
		echo (string) $SITE_TAGLINE;
	} else {
		return (string) $SITE_TAGLINE;
	}
}

/**
 * Get Site Description
 *
 * @since 2025.2.0
 * @uses $SITE_DESCRIPTION
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_description($echo = true) {
	global $SITE_DESCRIPTION;
	if ($echo) {
		echo (string) $SITE_DESCRIPTION;
	} else {
		return (string) $SITE_DESCRIPTION;
	}
}

/**
 * Get Site Keywords
 *
 * @since 2025.2.0
 * @uses $SITE_KEYWORDS
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_keywords($echo = true) {
	global $SITE_KEYWORDS;
	if ($echo) {
		echo (string) $SITE_KEYWORDS;
	} else {
		return (string) $SITE_KEYWORDS;
	}
}

/**
 * Get Site Keywords as Array
 *
 * This function retrieves the site's keywords as an array, optionally ensuring uniqueness.
 *
 * @since 2025.2.0
 * @uses $SITE_KEYWORDS
 *
 * @param bool $array_unique Optional, default is true. If true, the resulting array will contain unique keywords.
 * @return array The site's keywords as an array, with optional uniqueness.
 */

function get_site_keywords_array($array_unique = true) {
	global $SITE_KEYWORDS;
	$result = array_map('trim', explode(',', (string) $SITE_KEYWORDS));
	if ($array_unique) {
		$result = array_unique($result, SORT_STRING);
	}
	return $result;
}

/**
 * Get Administrator's Email Address
 * 
 * This will return the value set in the control panel
 * 
 * @depreciated as of 3.0
 *
 * @since 1.0
 * @uses $EMAIL
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_site_email($echo=true) {
	global $EMAIL;
	$myVar = trim(stripslashes($EMAIL));
	
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}


/**
 * Get Site Credits
 *
 * This will return HTML that displays 'Powered by GetSimple Legacy XXXX.XX'
 * It will always be nice if developers left this in their templates 
 * to help promote GetSimple Legacy.
 *
 * @since 1.0
 * @uses $site_link_back_url from configuration.php
 * @uses $site_full_name from configuration.php
 * @uses GSVERSION
 * @uses GSADMININCPATH
 * 
 * @since 2024.1 Use constant GSNAME instead of $site_full_name
 * @uses GSNAME
 *
 * @since 2024.2 Use constant GSURL instead $site_link_back_url. Don't include configuration.php. Show GetSimple Legacy version if $version is true
 * @uses GSURL
 *
 * @param string $text Optional, default is 'Powered by'.
 * @param boolean $version Optional, default is false. If true will show GetSimple Legacy version
 * @return void
 */
function get_site_credits($text = 'Powered by ', $version = false){
	$text = (string) $text;
	$site_credit_link = '<a href="' . GSURL . '" target="_blank">' . htmlspecialchars($text) . GSNAME . ($version ? ' ' . GSVERSION : '') . '</a>';
	echo stripslashes($site_credit_link);
}

/**
 * Menu Data
 *
 * This will return data to be used in custom navigation functions
 *
 * @since 2.0
 * @uses GSDATAPAGESPATH
 * @uses find_url
 * @uses getXML
 * @uses subval_sort
 *
 * @param bool $xml Optional, default is false. 
 *				True will return value in XML format. False will return an array
 * @return array|string Type 'string' in this case will be XML 
 */
function menu_data($id = null,$xml=false) {
    $menu_extract = array();

    global $pagesArray; 
    $pagesSorted = subval_sort($pagesArray,'menuOrder');
    if (count($pagesSorted) != 0) { 
      $count = 0;
      if (!$xml){
        foreach ($pagesSorted as $page) {
          $text = (string)$page['menu'];
          $pri = (string)$page['menuOrder'];
          $parent = (string)$page['parent'];
          $title = (string)$page['title'];
          $slug = (string)$page['url'];
          $menuStatus = (string)$page['menuStatus'];
          $private = (string)$page['private'];
					$pubDate = (string)$page['pubDate'];
          
          $url = find_url($slug,$parent);
          
          $specific = array("slug"=>$slug,"url"=>$url,"parent_slug"=>$parent,"title"=>$title,"menu_priority"=>$pri,"menu_text"=>$text,"menu_status"=>$menuStatus,"private"=>$private,"pub_date"=>$pubDate);
          
          if ($id == $slug) { 
              return $specific; 
              exit; 
          } else {
              $menu_extract[] = $specific;
          }
        }
        return $menu_extract;
      } else {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><channel>';    
	        foreach ($pagesSorted as $page) {
            $text = $page['menu'];
            $pri = $page['menuOrder'];
            $parent = $page['parent'];
            $title = $page['title'];
            $slug = $page['url'];
            $pubDate = $page['pubDate'];
            $menuStatus = $page['menuStatus'];
            $private = $page['private'];
           	
            $url = find_url($slug,$parent);
            
            $xml.="<item>";
            $xml.="<slug><![CDATA[".$slug."]]></slug>";
            $xml.="<pubDate><![CDATA[".$pubDate."]]></pubDate>";
            $xml.="<url><![CDATA[".$url."]]></url>";
            $xml.="<parent><![CDATA[".$parent."]]></parent>";
            $xml.="<title><![CDATA[".$title."]]></title>";
            $xml.="<menuOrder><![CDATA[".$pri."]]></menuOrder>";
            $xml.="<menu><![CDATA[".$text."]]></menu>";
            $xml.="<menuStatus><![CDATA[".$menuStatus."]]></menuStatus>";
            $xml.="<private><![CDATA[".$private."]]></private>";
            $xml.="</item>";
	        }
	        $xml.="</channel>";
	        return $xml;
        }
    }
}

/**
 * Get Component
 *
 * This will return the component requested.
 * Components are parsed for PHP within them.
 *
 * @since 1.0
 *
 * @uses load_components()
 * @uses $components
 * @since 2024.2.1 Added $force parameter. Don't normalize id.
 * @since 2024.3 Refactored, use load_components()
 *
 * @param string $id This is the ID of the component you want to display
 *				True will return value in XML format. False will return an array
 * @param bool $force Optional, default is false. If true, will force the component to run
 * @return void
 */
function get_component($id, $force = false) {
	global $components;
	$id = (string) $id;
	load_components();
	if (count($components) > 0) {
		foreach ($components as $component) {
			if ($id == (string) $component->slug) {
				if (!$force && (string) $component->disabled == '1') continue;
				eval('?>' . strip_decode((string) $component->value) . '<?php ');
			}
		}
	}
}

/**
 * Check if a component exists
 *
 * This will check if a component with the given id exists in the component list
 *
 * @since 2024.3
 * @uses load_components()
 * @uses $components
 *
 * @param string $id The id of the component to check
 * @return bool True if component exists, false if not
 */
function component_exists($id) {
	global $components;
	$id = (string) $id;
	load_components();
	if (count($components) > 0) {
		foreach ($components as $component) {
			if ($id == (string) $component->slug) return true;
		}
	}
	return false;
}

/**
 * Check if a component is enabled or disabled
 *
 * @since 2024.3
 * @uses load_components()
 * @uses $components
 *
 * @param string $id The id of the component to check
 * @return bool|null Null if component does not exist, true if enabled, false if disabled
 */
function component_enabled($id) {
	global $components;
	$id = (string) $id;
	load_components();
	if (count($components) > 0) {
		foreach ($components as $component) {
			if ($id == (string) $component->slug) return (string) $component->disabled != '1';
		}
	}
	return null;
}

/**
 * Get the title of a component
 *
 * @since 2025.2.0
 * @uses load_components()
 * @uses $components
 *
 * @param string $id The id of the component to get
 * @param bool $echo If true, echo the result, else return it
 * @return string|null The title of the component or null if not found or echo
 */
function get_component_title($id, $echo = true) {
	global $components;
	$id = (string) $id;
	load_components();
	if (count($components) > 0) {
		foreach ($components as $component) {
			if ($id == (string) $component->slug) {
				if ($echo) {
					echo (string) $component->title;
					return null;
				} else {
					return (string) $component->title;
				}
			}
		}
	}
	return null;
}

/**
 * Get the description of a component
 *
 * @since 2025.2.0
 * @uses load_components()
 * @uses $components
 *
 * @param string $id The id of the component to get
 * @param bool $echo If true, echo the result, else return it
 * @return string|null The description of the component or null if not found or echo
 */
function get_component_description($id, $echo = true) {
	global $components;
	$id = (string) $id;
	load_components();
	if (count($components) > 0) {
		foreach ($components as $component) {
			if ($id == (string) $component->slug) {
				if ($echo) {
					echo (string) $component->description;
					return null;
				} else {
					return (string) $component->description;
				}
			}
		}
	}
	return null;
}

/**
 * Get Main Navigation
 *
 * This will return unordered list of main navigation
 * This function uses the menu opitions listed within the 'Edit Page' control panel screen
 *
 * @since 1.0
 * @uses GSDATAOTHERPATH
 * @uses getXML
 * @uses subval_sort
 * @uses find_url
 * @uses strip_quotes 
 * @uses exec_filter 
 *
 * @param string $currentpage This is the ID of the current page the visitor is on
 * @param string $classPrefix Prefix that gets added to the parent and slug classnames
 * @return string 
 */	
function get_navigation($currentpage = "",$classPrefix = "") {

	$menu = '';

	global $pagesArray,$id;
	if(empty($currentpage)) $currentpage = $id;
	
	$pagesSorted = subval_sort($pagesArray,'menuOrder');
	if (count($pagesSorted) != 0) { 
		foreach ($pagesSorted as $page) {
			$sel = ''; $classes = '';
			$url_nav = $page['url'];
			
			if ($page['menuStatus'] == 'Y') { 
				$parentClass = !empty($page['parent']) ? $classPrefix.$page['parent'] . " " : "";
				$classes = trim( $parentClass.$classPrefix.$url_nav);
				if ($currentpage == $url_nav) $classes .= " current active";
				if ($page['menu'] == '') { $page['menu'] = $page['title']; }
				if ($page['title'] == '') { $page['title'] = $page['menu']; }
				$menu .= '<li class="'. $classes .'"><a href="'. find_url($page['url'],$page['parent']) . '" title="'. encode_quotes(cl($page['title'])) .'">'.strip_decode($page['menu']).'</a></li>'."\n";
			}
		}
		
	}
	
	echo exec_filter('menuitems',$menu);
}

/**
 * Check if a user is logged in
 *
 * This will return true if user is logged in
 *
 * @since 3.2
 * @since 2024.2.1 Always return boolean value
 * @uses get_cookie();
 * @uses $USR
 *
 * @return bool
 */
function is_logged_in(){
	global $USR;
	return isset($USR) && $USR == get_cookie('GS_ADMIN_USERNAME');
}

/**
 * @depreciated as of 2.04
 */
function return_page_title() {
	return get_page_title(FALSE);
}
/**
 * @depreciated as of 2.04
 */
function return_parent() {
	return get_parent(FALSE);
}
/**
 * @depreciated as of 2.04
 */
function return_page_slug() {
  return get_page_slug(FALSE);
}
/**
 * @depreciated as of 2.04
 */
function return_site_ver() {
	return get_site_version(FALSE);
}
/**
 * @depreciated as of 2.03
 */
if (!function_exists('set_contact_page')) {
	function set_contact_page() {
		#removed functionality
	}
}

/**
 * 
 * @deprecated as of 2025.2.0
 * @see get_page_parent()
 */
function get_parent($echo = true) {
	get_page_parent((bool) $echo);
}
