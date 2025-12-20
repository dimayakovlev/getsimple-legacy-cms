<?php
/**
 * Index
 *
 * Where it all starts
 *
 * @package GetSimple Legacy
 * @subpackage FrontEnd
 */


/* pre-common setup, load gsconfig and get GSADMIN path */

/* GSCONFIG definitions */
if (!defined('GSFRONT')) define('GSFRONT', 1);
if (!defined('GSBACK')) define('GSBACK', 2);
if (!defined('GSBOTH')) define('GSBOTH', 3);
if (!defined('GSSTYLEWIDE')) define('GSSTYLEWIDE', 'wide'); // wide style sheet
if (!defined('GSSTYLE_SBFIXED')) define('GSSTYLE_SBFIXED', 'sbfixed'); // fixed sidebar

define('GSFRONTEND', true);
# Check and load gsconfig
if (file_exists('gsconfig.php')) {
	require_once('gsconfig.php');
}

# Apply GSADMIN env
$GSADMIN = defined('GSADMIN') ? (string) GSADMIN : 'admin';

# Apply canonical redirect
$should_canonical_redirect = defined('GSCANONICAL') ? (bool) GSCANONICAL : false;

# setup paths 
# @todo wtf are these for ?
$admin_relative = $GSADMIN . '/inc/';
$lang_relative = $GSADMIN . '/';

$load['plugin'] = true;
$base = true;

/* end */

# Include common.php
include($GSADMIN . '/inc/common.php');

# Hook to load page Cache
exec_action('index-header');

# get page id (url slug) that is being passed via .htaccess mod_rewrite
$id = isset($_GET['id']) ? lowercase(str_replace(array('..', '/'), '', $_GET['id'])) : 'index';

// filter to modify page id request
$id = exec_filter('indexid', $id);
// $_GET['id'] = $id; // support for plugins that are checking get?

$data_index = null;

if (is_maintenance_mode() && !is_logged_in()) {
	if (defined('GS_503_CUSTOM_SLUG') && isset($pagesArray[GS_503_CUSTOM_SLUG])) {
		$data_index = getXml(GSDATAPAGESPATH . GS_503_CUSTOM_SLUG . '.xml');
		if (is_object($data_index)) {
			$data_index->private = '';
		}
	}
	if (!is_object($data_index)) {
		$data_index = getXml(GSDATAOTHERPATH . '503.xml');
	}
	if (!is_object($data_index)) {
		redirect('503');
	}
	$should_canonical_redirect = false;
	header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');
} elseif (isset($pagesArray[$id])) {
	// apply page data if page id exists
	$data_index = getXml(GSDATAPAGESPATH . $id . '.xml');
}

// filter to modify data_index obj
$data_index = exec_filter('data_index', $data_index);

if (is_object($data_index)) {
	// private page handling
	if (in_array((string) $data_index->private, array('Y', '1'))) {
		if (defined('GS_404_CUSTOM_SLUG') && GS_404_CUSTOM_SLUG == $id) {
			// reset private field value if view custom 404 page directly
			$data_index->private = '';
		} elseif (!is_logged_in()) {
			// reset page data if not logged in to process as not found page
			$data_index = null;
		}
	}
}

// page not found handling
if (!is_object($data_index)) {
	if (defined('GS_404_CUSTOM_SLUG') && isset($pagesArray[GS_404_CUSTOM_SLUG])) {
		$data_index = getXml(GSDATAPAGESPATH . GS_404_CUSTOM_SLUG . '.xml');
		if (is_object($data_index)) {
			// do legacy 404 redirect if configured
			if (defined('GS_404_CUSTOM_SLUG_REDIRECT') && (bool) GS_404_CUSTOM_SLUG_REDIRECT) {
				header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
				redirect(find_url((string) $data_index->url, (string) $data_index->parent));
			}
			// reset private field value
			$data_index->private = '';
		}
	}
	if (!is_object($data_index)) {
		$data_index = getXml(GSDATAOTHERPATH . '404.xml');
	}
	if (!is_object($data_index)) {
		redirect('404');
	}
	$should_canonical_redirect = false;
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
	exec_action('error-404');
}

$title         = $data_index->title;
$subtitle = $data_index->subtitle;
$summary = $data_index->summary;
$featured_image = $data_index->featuredImage;
$date          = $data_index->pubDate;
$metak         = $data_index->meta;
$metad         = $data_index->metad;
$url           = $data_index->url;
$content       = $data_index->content;
$parent        = $data_index->parent;
$template_file = $data_index->template;
$private       = $data_index->private;

// after fields from dataindex, can modify globals here or do whatever by checking them
exec_action('index-post-dataindex');

# check for correctly formed url
if ($should_canonical_redirect) {
	if ($_SERVER['REQUEST_URI'] != find_url($url, $parent, 'relative')) {
		redirect(find_url($url, $parent));
	}
}

# include the functions.php page if it exists within the theme
if (file_exists(GSTHEMESPATH . $TEMPLATE . '/functions.php')) {
	include(GSTHEMESPATH . $TEMPLATE . '/functions.php');
}

# call pretemplate Hook
exec_action('index-pretemplate');

# include the template and template file set within theme.php and each page
if ( (!file_exists(GSTHEMESPATH .$TEMPLATE."/".$template_file)) || ($template_file == '') ) { $template_file = "template.php"; }
include(GSTHEMESPATH .$TEMPLATE."/".$template_file);

# call posttemplate Hook
exec_action('index-posttemplate');
