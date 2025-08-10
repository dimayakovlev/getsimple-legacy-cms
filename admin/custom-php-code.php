<?php
/**
 * Custom PHP Code
 *
 * Displays and changes custom PHP code
 *
 * @package GetSimple Legacy
 * @subpackage Custom PHP Code
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

login_cookie_check();

# if Custom PHP Code disabled, redirect
if (!defined('GSCUSTOMPHPCODE') || GSCUSTOMPHPCODE !== true) {
	redirect($cookie_redirect);
}

$custom_php_code = '';
$custom_php_code_enabled = false;
$custom_php_code_file = GSDATAOTHERPATH . 'custom-php-code.xml';
$custom_php_code_xml_attributes = array();

# check for form submission
if (isset($_POST['submitsave'])) {
	# check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = $_POST['nonce'];
		if (!check_nonce($nonce, 'save')) {
			die('CSRF detected!');
		}
	}
	$custom_php_code_enabled = filter_input(INPUT_POST, 'custom-php-code-enabled', FILTER_VALIDATE_BOOLEAN);
	$custom_php_code = filter_input(INPUT_POST, 'custom-php-code', FILTER_DEFAULT);
	if (is_readable($custom_php_code_file)) {
		$custom_php_code_xml = simplexml_load_file($custom_php_code_file, 'SimpleXMLExtended');
		if (is_object($custom_php_code_xml)) {
			$custom_php_code_xml_attributes = (array) $custom_php_code_xml->attributes();
			$custom_php_code_xml_attributes = $custom_php_code_xml_attributes['@attributes'];
		}
	}
	$custom_php_code_xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?><item><enabled/><code/></item>', 'SimpleXMLExtended');

	$custom_php_code_xml->enabled = (string) $custom_php_code_enabled;
	$custom_php_code_xml->code->addCData($custom_php_code);

	$custom_php_code_xml->addAttribute('created', isset($custom_php_code_xml_attributes['created']) ? $custom_php_code_xml_attributes['created'] : date('r'));
	$custom_php_code_xml->addAttribute('modified', date('r'));
	$custom_php_code_xml->addAttribute('creator', isset($custom_php_code_xml_attributes['creator']) ? $custom_php_code_xml_attributes['creator'] : $USR);
	$custom_php_code_xml->addAttribute('lastModifiedBy', $USR);
	$custom_php_code_xml->addAttribute('revision', isset($custom_php_code_xml_attributes['revision']) && (int) $custom_php_code_xml_attributes['revision'] > 0 ? (int) $custom_php_code_xml_attributes['revision'] + 1 : 1);
	$custom_php_code_xml->addAttribute('appName', GSNAME);
	$custom_php_code_xml->addAttribute('appVersion', GSVERSION);

	createBak('custom-php-code.xml', GSDATAOTHERPATH, GSBACKUPSPATH . 'other/');
	exec_action('custom-php-code-save');
	
	if ($custom_php_code_xml->asXml($custom_php_code_file)) {
		redirect('custom-php-code.php?upd=cphpc-success');
	} else {
		redirect('custom-php-code.php?upd=cphpc-error');
	}
}

if (isset($_GET['undo'])) {
	# check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = $_GET['nonce'];
		if (!check_nonce($nonce, 'undo')) {
			die('CSRF detected!');
		}
	}
	undo('custom-php-code.xml', GSDATAOTHERPATH, GSBACKUPSPATH . 'other/');
	redirect('custom-php-code.php?upd=cphpc-restored');
}

if (is_readable($custom_php_code_file)) {
	$custom_php_code_xml = simplexml_load_file($custom_php_code_file);
	if (is_object($custom_php_code_xml)) {
		$custom_php_code = (string) $custom_php_code_xml->code;
		$custom_php_code_enabled = filter_var($custom_php_code_xml->enabled, FILTER_VALIDATE_BOOLEAN);
	}
}

if ($CODEEDITOR != '') {
	register_script('codemirror', $SITEURL . $GSADMIN . '/template/js/codemirror/lib/codemirror-compressed.js', '0.2.0', false);

	register_style('codemirror-css', $SITEURL . $GSADMIN . '/template/js/codemirror/lib/codemirror.css', 'screen', false);
	register_style('codemirror-theme', $SITEURL . $GSADMIN . '/template/js/codemirror/theme/default.css', 'screen', false);

	queue_script('codemirror', GSBACK);

	queue_style('codemirror-css', GSBACK);
	queue_style('codemirror-theme', GSBACK);
}

exec_action('custom-php-code-hook');
get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('CUSTOM_PHP_CODE'));
?>
<?php include('template/include-nav.php'); ?>
<?php if ($CODEEDITOR != '') { ?>
<script>
window.onload = function() {
	var foldFunc = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder);
	function keyEvent(cm, e) {
		if (e.keyCode == 81 && e.ctrlKey) {
			if (e.type == 'keydown') {
				e.stop();
				setTimeout(function() {foldFunc(cm, cm.getCursor().line);}, 50);
			}
			return true;
		}
	}
	function toggleFullscreenEditing() {
		var editorDiv = $('.CodeMirror-scroll');
		if (!editorDiv.hasClass('fullscreen')) {
			toggleFullscreenEditing.beforeFullscreen = { height: editorDiv.height(), width: editorDiv.width() }
			editorDiv.addClass('fullscreen');
			editorDiv.height('100%');
			editorDiv.width('100%');
			editor.refresh();
		} else {
			editorDiv.removeClass('fullscreen');
			editorDiv.height(toggleFullscreenEditing.beforeFullscreen.height);
			editorDiv.width(toggleFullscreenEditing.beforeFullscreen.width);
			editor.refresh();
		}
	}
	var editor = CodeMirror.fromTextArea(document.getElementById('codetext'), {
		lineNumbers: true,
		matchBrackets: true,
		indentUnit: 4,
		indentWithTabs: true,
		enterMode: 'keep',
		lineWrapping: true,
		mode: 'application/x-httpd-php',
		tabMode: 'shift',
		theme: 'default',
		onGutterClick: foldFunc,
		extraKeys: {"Ctrl-Q": function(cm) {foldFunc(cm, cm.getCursor().line);}, "F11": toggleFullscreenEditing, "Esc": toggleFullscreenEditing},
		onCursorActivity: function() {
			editor.setLineClass(hlLine, null);
			hlLine = editor.setLineClass(editor.getCursor().line, 'activeline');
		}
	});
	var hlLine = editor.setLineClass(0, 'activeline');
}
</script>
<?php } ?>
<div class="bodycontent clearfix">
	<div id="maincontent">
		<div class="main">
			<h3><?php i18n('EDIT_CUSTOM_PHP_CODE'); ?></h3>
			<form action="<?php myself(); ?>" method="POST" accept-charset="utf-8">
				<input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce('save'); ?>">
				<p class="inline"><input name="custom-php-code-enabled" id="custom-php-code-enabled" type="checkbox" value="1"<?php echo $custom_php_code_enabled ? ' checked' : ''; ?>> &nbsp;<label for="custom-php-code-enabled"><?php i18n('ENABLE_CUSTOM_PHP_CODE'); ?></label></p>
				<textarea class="text" name="custom-php-code" id="codetext" wrap='off' placeholder="<?php i18n('CUSTOM_PHP_CODE_PLACEHOLDER'); ?>"><?php echo htmlentities($custom_php_code, ENT_QUOTES, 'UTF-8'); ?></textarea>
				<p id="submit_line">
					<span><input class="submit" type="submit" name="submitsave" value="<?php i18n('BTN_SAVECHANGES'); ?>"></span> &nbsp;&nbsp;<?php i18n('OR'); ?>&nbsp;&nbsp; <a class="cancel" href="custom-php-code.php?cancel"><?php i18n('CANCEL'); ?></a>
				</p>
			</form>
		</div>
	</div>
	<div id="sidebar"><?php include('template/sidebar-plugins.php'); ?></div>
</div>

<?php get_template('footer'); ?>