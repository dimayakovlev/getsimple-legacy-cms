<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); }
/**
 * Header Admin Template
 *
 * @package GetSimple Legacy
 */

global $SITENAME, $SITEURL;

$GSSTYLE         = getDef('GSSTYLE') ? GSSTYLE : '';
$GSSTYLE_sbfixed = in_array('sbfixed', explode(',', $GSSTYLE));
$GSSTYLE_wide    = in_array('wide', explode(',', $GSSTYLE));

$bodyclass = 'class="';
if ($GSSTYLE_sbfixed) $bodyclass .= ' sbfixed';
if ($GSSTYLE_wide) $bodyclass .= ' wide';
$bodyclass .= '"';

if (get_filename_id() != 'index') exec_action('admin-pre-header');

?>
<!DOCTYPE html>
<html lang="<?php echo get_site_lang(true); ?>">
<head>
	<meta charset="UTF-8">
	<title><?php echo $title ?></title>
	<?php if (!isAuthPage()) { ?><meta name="generator" content="<?php echo GSNAME; ?> <?php echo GSVERSION; ?>">
	<link rel="shortcut icon" href="favicon.png" type="image/png">
	<link rel="author" href="humans.txt">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<?php } ?>
	<meta name="robots" content="noindex, nofollow">
	<link rel="stylesheet" type="text/css" href="template/style.php?<?php echo 's=' . $GSSTYLE . '&amp;v=' . GSVERSION . (isDebug() ? '&amp;nocache' : ''); ?>" media="screen">
	<!--[if IE 6]><link rel="stylesheet" type="text/css" href="template/ie6.css?v=<?php echo GSVERSION; ?>" media="screen" /><![endif]-->
	<?php
		if($GSSTYLE_sbfixed) queue_script('scrolltofixed', GSBACK);
		get_scripts_backend();
	?>
	<script type="text/javascript" src="template/js/jquery.getsimple.js?v=<?php echo GSVERSION; ?>"></script>
	<!--[if lt IE 9]><script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js" ></script><![endif]-->
	<?php if(((get_filename_id() == 'upload') || (get_filename_id() == 'image')) && (!getDef('GSNOUPLOADIFY', true))) { ?>
	<script type="text/javascript" src="template/js/uploadify/jquery.uploadify.js?v=3.0"></script>
	<?php } ?>
	<?php if (get_filename_id() == 'image') { ?>
	<script type="text/javascript" src="template/js/jcrop/jquery.Jcrop.min.js"></script>
	<link rel="stylesheet" type="text/css" href="template/js/jcrop/jquery.Jcrop.css" media="screen">
	<?php } ?>
<?php
	# Plugin hook to allow insertion of stuff into the header
	if (!isAuthPage()) {
		exec_action('header');
?>
	<script type="text/javascript">
		// init gs namespace and i18n
		var GS = {};
		GS.i18n = new Array();
		GS.i18n['PLUGIN_UPDATED'] = '<?php i18n('PLUGIN_UPDATED'); ?>';
		GS.i18n['ERROR'] = '<?php i18n('ERROR'); ?>';
<?php if (get_filename_id() == 'components') { ?>
		GS.i18n['SAVE_COMPONENTS_TO_UPDATE_CODE'] = '<?php i18n('SAVE_COMPONENTS_TO_UPDATE_CODE'); ?>';
		GS.i18n['DELETE_COMPONENT'] = '<?php i18n('DELETE_COMPONENT'); ?>';
		GS.i18n['COMPONENT_TITLE'] = '<?php i18n('COMPONENT_TITLE'); ?>';
		GS.i18n['COMPONENT_DISABLE'] = '<?php i18n('COMPONENT_DISABLE'); ?>';
		GS.i18n['COMPONENT_NEW'] = '<?php i18n('COMPONENT_NEW'); ?>';
		GS.i18n['COMPONENT_DESCRIPTION'] = '<?php i18n('COMPONENT_DESCRIPTION'); ?>';
		GS.i18n['COMPONENT_SLUG'] = '<?php i18n('COMPONENT_SLUG'); ?>';
		GS.i18n['COMPONENT_ORDER'] = '<?php i18n('COMPONENT_ORDER'); ?>';
<?php } ?>
	</script>
	<?php } ?>
</head>

<body <?php filename_id(); echo ' ' . $bodyclass; ?>>
<?php
	if (!isAuthPage()) { ?>
	<div class="header" id="header">
		<div class="wrapper clearfix">
<?php
		exec_action('header-body');
	}
?>