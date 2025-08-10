<?php
/**
 * Sidebar Plugins Template
 *
 * @package GetSimple Legacy
 */
?>
<ul class="snav">
	<li id="sb_plugins"><a href="plugins.php" <?php check_menu('plugins'); ?> accesskey="<?php echo find_accesskey(i18n_r('SHOW_PLUGINS'));?>"><?php i18n('SHOW_PLUGINS'); ?></a></li>
<?php if (defined('GSCUSTOMPHPCODE') && GSCUSTOMPHPCODE === true) { ?>
	<li id="sb_custom-php-code"><a href="custom-php-code.php" <?php check_menu('custom-php-code'); ?>><?php i18n('SIDE_CUSTOM_PHP_CODE'); ?></a></li>
<?php } ?>
<?php exec_action('plugins-sidebar'); ?>
</ul>