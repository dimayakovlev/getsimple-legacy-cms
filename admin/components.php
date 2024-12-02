<?php
/**
 * Components
 *
 * Displays and creates static components
 *
 * @package GetSimple Legacy
 * @subpackage Components
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

# variable settings
$userid 	= login_cookie_check();
$file 		= "components.xml";
$path 		= GSDATAOTHERPATH;
$bakpath 	= GSBACKUPSPATH .'other/';
$update 	= ''; $table = ''; $list='';

# check to see if form was submitted
if (isset($_POST['submitted'])){
	// check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
		if (!check_nonce($nonce, 'modify_components')) {
			die('CSRF detected!');
		}
	}

	$value = isset($_POST['val']) && is_array($_POST['val']) ? $_POST['val'] : array();
	$slug = isset($_POST['slug']) && is_array($_POST['slug']) ? $_POST['slug'] : array();
	$disabled = isset($_POST['disabled']) && is_array($_POST['disabled']) ? $_POST['disabled'] : array();
	$title = isset($_POST['title']) && is_array($_POST['title']) ? $_POST['title'] : array();
	$ids = isset($_POST['id']) && is_array($_POST['id']) ? $_POST['id'] : array();

	# create backup file for undo
	createBak($file, $path, $bakpath);

	# start creation of top of components.xml file
	$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><channel></channel>');
	if (!empty($ids)) {

		$ct = 0; $coArray = array();
		foreach ($ids as $id) {
			$title[$ct] = isset($title[$ct]) ? trim($title[$ct]) : '';
			if ($title[$ct] == '') {
				$title[$ct] = 'Component ' . time();
			}
			$slug[$ct] = isset($slug[$ct]) ? trim($slug[$ct]) : '';
			if ($slug[$ct] == '') {
				$slug_tmp = doTransliteration($title[$ct]);
				// replace non letter or digits by -, preserve _
				$slug_tmp = preg_replace('~[^\pL\d_]+~u', '-', $slug_tmp);
				// remove unwanted characters
				$slug_tmp = preg_replace('~[^-\w]+~', '', $slug_tmp);
				// trim
				$slug_tmp = trim($slug_tmp, '-');
				// remove duplicate -
				$slug_tmp = preg_replace('~-+~', '-', $slug_tmp);
				// lowercase
				$slug_tmp = strtolower($slug_tmp);
				if ($slug_tmp == '') {
					$slug_tmp = 'component-' . time();
				}
				$slug[$ct] = $slug_tmp;
			}
			$coArray[$ct]['id'] = isset($ids[$ct]) ? intval($ids[$ct]) : 0;
			$coArray[$ct]['slug'] = $slug[$ct];
			$coArray[$ct]['disabled'] = isset($disabled[$ct]) && (string) $disabled[$ct] == '1' ? '1' : '';
			$coArray[$ct]['title'] = safe_slash_html($title[$ct]);
			$coArray[$ct]['value'] = isset($value[$ct]) ? safe_slash_html($value[$ct]) : '';
			$ct++;
		}
		
		$ids = subval_sort($coArray, 'title');

		$count = 0;
		foreach ($ids as $comp){
			# create the body of components.xml file
			$components = $xml->addChild('item');
			$c_note = $components->addChild('title');
			$c_note->addCData($comp['title']);
			$components->addChild('slug', $comp['slug']);
			$components->addChild('disabled', $comp['disabled']);
			$c_note = $components->addChild('value');
			$c_note->addCData($comp['value']);
			$count++;
		}
	}
	exec_action('component-save');
	XMLsave($xml, $path . $file);
	redirect('components.php?upd=comp-success');
}

# if undo was invoked
if (isset($_GET['undo'])){

	# check for csrf
	$nonce = $_GET['nonce'];
	if (!check_nonce($nonce, "undo")) {
		die("CSRF detected!");
	}

	# perform the undo
	undo($file, $path, $bakpath);
	redirect('components.php?upd=comp-restored');
}

# create components form html
$data = getXML($path . $file);
$componentsec = $data->item;
$count= 0;
if ($componentsec && count($componentsec) != 0) {
	foreach ($componentsec as $component) {
		$disabled = isset($component->disabled) && (string) $component->disabled == '1';
		$table .= '<div class="compdiv" id="section-' . $count . '"><table class="comptable" ><tr><td><b title="' . i18n_r('DOUBLE_CLICK_EDIT').'" class="editable">' . htmlentities((string) $component->title, ENT_QUOTES, 'UTF-8', false) . '</b></td>';
		$table .= '<td style="text-align:right;"><code>&lt;?php get_component(<span class="compslugcode">\'' . htmlentities((string) $component->slug, ENT_QUOTES, 'UTF-8', false) . '\'</span>); ?&gt;</code></td><td class="delete">';
		$table .= '<a href="#" title="' . i18n_r('DELETE_COMPONENT') . ': '. htmlentities((string) $component->title, ENT_QUOTES, 'UTF-8', false) . '?" class="delcomponent" rel="' . $count . '">&times;</a></td></tr></table>';
		$table .= '<p class="inline"><input class="compdisable" type="checkbox" value="1"' . ($disabled ? ' checked' : '') . ' /> &nbsp;<label>' . i18n_r('COMPONENT_DISABLE') . '</label></p>';
		$table .= '<textarea class="text" name="val[]">' . stripslashes((string) $component->value) . '</textarea>';
		$table .= '<input type="hidden" class="compslug" name="slug[]" value="' . htmlentities((string) $component->slug, ENT_QUOTES, 'UTF-8', false) . '" />';
		$table .= '<input type="hidden" class="comptitle" name="title[]" value="' . htmlentities((string) $component->title, ENT_QUOTES, 'UTF-8', false) . '" />';
		$table .= '<input type="hidden" name="id[]" value="' . $count . '" />';
		$table .= '<input type="hidden" name="disabled[]" value="' . ($disabled ? '1' : '') . '" />';
		exec_action('component-extras');
		$table .= '</div>';
		$count++;
	}
}
# create list to show on sidebar for easy access
$listc = ''; $submitclass = '';
if ($count > 1) {
	$item = 0;
	foreach ($componentsec as $component) {
		$listc .= '<a id="divlist-' . $item . '" href="#section-' . $item . '" class="component">' . htmlentities((string) $component->title, ENT_QUOTES, 'UTF-8', false) . '</a>';
		$item++;
	}
} elseif ($count == 0) {
	$submitclass = 'hidden';
}

if (!getDef('GSNOHIGHLIGHT', true)) {
	register_script('codemirror', $SITEURL.$GSADMIN.'/template/js/codemirror/lib/codemirror-compressed.js', '0.2.0', FALSE);

	register_style('codemirror-css',$SITEURL.$GSADMIN.'/template/js/codemirror/lib/codemirror.css','screen',FALSE);
	register_style('codemirror-theme',$SITEURL.$GSADMIN.'/template/js/codemirror/theme/default.css','screen',FALSE);

	queue_script('codemirror', GSBACK);

	queue_style('codemirror-css', GSBACK);
	queue_style('codemirror-theme', GSBACK);
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('COMPONENTS'));

include('template/include-nav.php');

if (!getDef('GSNOHIGHLIGHT', true)) {
?>
<script>
window.onload = function() {
	var foldFunc = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder);
	function keyEvent(cm, e){
		if (e.keyCode == 81 && e.ctrlKey) {
			if (e.type == "keydown") {
				e.stop();
				setTimeout(function(){foldFunc(cm, cm.getCursor().line);}, 50);
			}
			return true;
		}
	}
	function toggleFullscreenEditing(){
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
	var components = document.querySelectorAll("textarea[name^=val]");
	for (var i = 0, cnt = components.length; i < cnt; i++) {
		var editor = CodeMirror.fromTextArea(components[i], {
			lineNumbers: true,
			matchBrackets: true,
			indentUnit: 4,
			indentWithTabs: true,
			enterMode: "keep",
			lineWrapping: true,
			mode:"application/x-httpd-php",
			tabMode: "shift",
			theme:'default',
			onGutterClick: foldFunc,
			extraKeys: {"Ctrl-Q": function(cm){foldFunc(cm, cm.getCursor().line);}, "F11": toggleFullscreenEditing, "Esc": toggleFullscreenEditing},
			onCursorActivity: function(){
				editor.setLineClass(hlLine, null);
				hlLine = editor.setLineClass(editor.getCursor().line, "activeline");
			}
		});
		var hlLine = editor.setLineClass(0, "activeline");
	}
}
</script>
<?php
}
?>

<div class="bodycontent clearfix">

	<div id="maincontent">
	<div class="main">
	<h3 class="floated"><?php echo i18n('EDIT_COMPONENTS');?></h3>
	<div class="edit-nav">
		<a href="#" id="addcomponent" accesskey="<?php echo find_accesskey(i18n_r('ADD_COMPONENT'));?>"><?php i18n('ADD_COMPONENT');?></a>
		<div class="clear"></div>
	</div>

	<form class="manyinputs" action="<?php myself(); ?>" method="post" accept-charset="utf-8" >
		<input type="hidden" id="id" value="<?php echo $count; ?>" />
		<input type="hidden" id="nonce" name="nonce" value="<?php echo get_nonce("modify_components"); ?>" />
		<div id="divTxt"></div> 
		<?php echo $table; ?>
		<p id="submit_line" class="<?php echo $submitclass; ?>" >
			<span><input type="submit" class="submit" name="submitted" id="button" value="<?php i18n('SAVE_COMPONENTS');?>" /></span> &nbsp;&nbsp;<?php i18n('OR'); ?>&nbsp;&nbsp; <a class="cancel" href="components.php?cancel"><?php i18n('CANCEL'); ?></a>
		</p>
	</form>
	</div>
	</div>

	<div id="sidebar">
		<?php include('template/sidebar-theme.php'); ?>
		<?php if ($listc != '') { echo '<div class="compdivlist">'.$listc .'</div>'; } ?>
	</div>

</div>
<?php get_template('footer'); ?>
