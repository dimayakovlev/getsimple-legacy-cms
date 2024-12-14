<?php
/**
 * Theme
 *
 * @package GetSimple Legacy
 * @subpackage Theme
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

# variable settings
login_cookie_check();
$path = GSDATAOTHERPATH;
$file = "website.xml";
$theme_options = '';

# was the form submitted?
if ((isset($_POST['submitted'])) && (isset($_POST['template']))) {

	# check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = $_POST['nonce'];
		if (!check_nonce($nonce, 'activate')) {
			die('CSRF detected!');
		}
	}

	# get passed value from form
	$TEMPLATE = var_out($_POST['template']);
	if (!path_is_safe(GSTHEMESPATH . $TEMPLATE, GSTHEMESPATH)) die();

	# backup old website.xml file
	$bakpath = GSBACKUPSPATH . 'other/';
	createBak($file, $path, $bakpath);

	// # udpate website.xml file with new theme
	$xml = getXML($path . $file);
	$xml->TEMPLATE = null;
	$xml->TEMPLATE->addCData($TEMPLATE);
	$status = XMLsave($xml, $path . $file);

	$success = i18n_r('THEME_CHANGED');
}

# get available themes (only look for folders)
$themes_handle = opendir(GSTHEMESPATH) or die('Unable to open ' . GSTHEMESPATH);
while ($file = readdir($themes_handle)) {
	$curpath = GSTHEMESPATH . $file;
	if (is_dir($curpath) && $file != '.' && $file != '..') {
		if (file_exists($curpath . '/template.php')) {
			$theme_options .= '<option' . (($TEMPLATE == $file) ? ' selected' : '') . ' value="' . $file . '">' . $file . '</option>';
		}
	}
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('THEME_MANAGEMENT'));

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent clearfix">

	<div id="maincontent">
		<div class="main">
		<h3><?php i18n('CHOOSE_THEME');?></h3>
		<form action="<?php myself(); ?>" method="post" accept-charset="utf-8">
			<input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce("activate"); ?>">
		<?php
			$theme_path = str_replace(GSROOTPATH, '', GSTHEMESPATH);
			if ($SITEURL) {
				echo '<p><b>' . i18n_r('THEME_PATH') . ': &nbsp;</b> <code>' . $SITEURL . $theme_path . $TEMPLATE . '/</code></p>';
			}
		?>
			<p><select id="theme_select" class="text" name="template"><?php echo $theme_options; ?></select>&nbsp;&nbsp;&nbsp;<input class="submit" type="submit" name="submitted" value="<?php i18n('ACTIVATE_THEME');?>"></p>
		</form>
		<?php
			$theme_screenshot_exists = file_exists('../theme/' . $TEMPLATE . '/images/screenshot.png');
			echo '<p><img id="theme_preview" style="display: ' . (!$theme_screenshot_exists ? 'none' : 'block') . ';" src="../' . $theme_path.$TEMPLATE . '/images/screenshot.png" alt="' . i18n_r('THEME_SCREENSHOT') . '"><span id="theme_no_img" style="' . ($theme_screenshot_exists ? 'display: none;' : '') . '"><em>' . i18n_r('NO_THEME_SCREENSHOT') . '</em></span></p>';
			exec_action('theme-extras');
		?>

		</div>

	</div>

	<div id="sidebar">
		<?php include('template/sidebar-theme.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>