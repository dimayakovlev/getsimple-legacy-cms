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
$userid = login_cookie_check();
$file = 'components.xml';
$path = GSDATAOTHERPATH;
$bakpath = GSBACKUPSPATH . 'other/';
$update = '';
$table = '';
$list = '';

# check to see if form was submitted
if (isset($_POST['submitted'])) {
	// check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
		if (!check_nonce($nonce, 'modify_components')) {
			die('CSRF detected!');
		}
	}
	if (isset($_POST['components']) && !is_array($_POST['components'])) {
		redirect('components.php?upd=comp-error');
	}
	$components_tmp = $components_new = array();
	foreach ($_POST['components'] as $key => $component) {
		$component_title = isset($component['title']) ? safe_slash_html(trim($component['title'])) : '';
		if ($component_title == '') {
			$component_title = uniqid('Component ');
		}
		$component['title'] = $component_title;
		$component_slug = isset($component['slug']) ? trim($component['slug']) : '';
		if ($component_slug == '') {
			$component_slug = html_entity_decode($component_title, ENT_QUOTES, 'UTF-8');
		}
		$component_slug = doTransliteration($component_slug);
		// replace non letter or digits by -, preserve _
		$component_slug = preg_replace('~[^\pL\d_]+~u', '-', $component_slug);
		// remove unwanted characters
		$component_slug = preg_replace('~[^-\w]+~', '', $component_slug);
		// trim
		$component_slug = trim($component_slug, '-');
		// remove duplicate -
		$component_slug = preg_replace('~-+~', '-', $component_slug);
		// lowercase
		$component_slug = strtolower($component_slug);
		if ($component_slug == '') {
			$component_slug = uniqid('component-');
		}
		$component['slug'] = $component_slug;
		// $component['id'] = isset($component['id']) ? intval($component['id']) : $key;
		$component['order'] = isset($component['order']) ? intval($component['order']) : $key + 1;
		if ($component['order'] <= 0) $component['order'] = count($_POST['components']);
		$component['disabled'] = isset($component['disabled']) && (string) $component['disabled'] == '1' ? 1 : null;
		$component['description'] = isset($component['description']) ? safe_slash_html($component['description']) : '';
		$component['value'] = isset($component['value']) ? safe_slash_html($component['value']) : '';
		if (isset($component['new']) && $component['new'] == '1') {
			$components_new[] = $component;
		} else {
			$components_tmp[] = $component;
		}
	}
	$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><components></components>');
	if ($components_tmp) {
		$components_tmp = subval_sort(subval_sort($components_tmp, 'title'), 'order');
	}
	if ($components_new) {
		$components_new = subval_sort(subval_sort($components_new, 'title'), 'order');
	}
	$components_tmp = array_merge($components_tmp, $components_new);
	if ($components_tmp) {
		// make components slugs unique
		$components_slugs = array_column($components_tmp, 'slug');
		$components_slugs_tmp = array();
		foreach ($components_slugs as $key => $component_slug) {
			if (in_array($component_slug, $components_slugs_tmp)) {
				$component_slug_new = $component_slug;
				$count = 1;
				while (in_array($component_slug_new, $components_slugs_tmp) || in_array($component_slug_new, $components_slugs)) {
					$component_slug_new = $component_slug . '-' . $count;
					$count++;
				}
				$components_slugs_tmp[$key] = $component_slug_new;
				$components_tmp[$key]['slug'] = $component_slug_new;
			} else {
				$components_slugs_tmp[$key] = $component_slug;
			}
		};
		$components_tmp = subval_sort(subval_sort($components_tmp, 'title'), 'order');
		foreach ($components_tmp as $component) {
			$item = $xml->addChild('item');
			$item->addChild('title')->addCData($component['title']);
			$item->addChild('slug', $component['slug']);
			$item->addChild('disabled', $component['disabled']);
			$item->addChild('description')->addCData($component['description']);
			$item->addChild('value')->addCData($component['value']);
			// $item->addChild('id', $component['id']);
			$item->addChild('order', $component['order']);
		}
	}
	# create backup file for undo
	createBak($file, $path, $bakpath);
	exec_action('component-save');
	XMLsave($xml, $path . $file);
	if (XMLsave($xml, $path . $file)) {
		redirect('components.php?upd=comp-success');
	} else {
		redirect('components.php?upd=comp-error');
	}
}

# if undo was invoked
if (isset($_GET['undo'])){

	# check for csrf
	$nonce = $_GET['nonce'];
	if (!check_nonce($nonce, 'undo')) {
		die('CSRF detected!');
	}

	# perform the undo
	undo($file, $path, $bakpath);
	redirect('components.php?upd=comp-restored');
}

# create components form html
// $data = getXML($path . $file);
// @since 2023.3 Use load_components() instead of getXML()
load_components();
$count = 0;
if ($components && count($components) > 0) {
	foreach ($components as $component) {
		$count++;
		$component_disabled = (isset($component->disabled) && (string) $component->disabled == '1');
		$table .= '<div class="compdiv' . ($component_disabled ? ' disabled' : '') . '" data-slug="' . htmlentities((string) $component->slug, ENT_QUOTES, 'UTF-8') . '" id="section-' . $count . '"><table class="comptable"><tr><td><h4><a href="#section-' . $count . '" class="compdatatoggle">'. htmlentities((string) $component->title, ENT_QUOTES, 'UTF-8', false) . '</a></h4></td>';
		$table .= '<td style="text-align:right;"><code>&lt;?php get_component(<span class="compslugcode">\'' . htmlentities((string) $component->slug, ENT_QUOTES, 'UTF-8', false) . '\'</span>); ?&gt;</code></td><td class="delete">';
		$table .= '<a href="#" title="' . i18n_r('DELETE_COMPONENT') . ': '. htmlentities((string) $component->title, ENT_QUOTES, 'UTF-8', false) . '" class="delcomponent" rel="' . $count . '">&times;</a></td></tr></table>';
		$table .= '<div class="compdata" style="display: none;">';
		$table .= '<div class="leftopt"><p><label>' . i18n_r('COMPONENT_TITLE') . ':</label><input class="text comptitle" type="text" name="components[' . $count . '][title]" value="' . htmlentities((string) $component->title, ENT_QUOTES, 'UTF-8', false) . '" required></p></div>';
		$table .= '<div class="rightopt"><p><label>' . i18n_r('COMPONENT_SLUG') . ':</label><input class="text compslug" type="text" name="components[' . $count . '][slug]" value="' . htmlentities((string) $component->slug, ENT_QUOTES, 'UTF-8', false) . '" data-initial="' . htmlentities((string) $component->slug, ENT_QUOTES, 'UTF-8', false) . '"></p></div><div class="clear"></div>';
		$table .= '<div class="leftopt"><p class="inline"><input class="compdisable" type="checkbox" value="1"' . ($component_disabled ? ' checked' : '') . ' name="components[' . $count . '][disabled]" /> &nbsp;<label>' . i18n_r('COMPONENT_DISABLE') . '</label></p></div>
		<div class="rightopt"><p><label>' . i18n_r('COMPONENT_ORDER') . ':</label><input class="text comporder" type="number" min="1" name="components[' . $count . '][order]" value="' . (isset($component->order) ? intval((string) $component->order) : '0') . '"></p></div>
		<div class="clear"></div>';
		$table .= '<div class="wideopt"><p><label>'. i18n_r('COMPONENT_DESCRIPTION') . ':</label><textarea class="text compdescription" name="components[' . $count . '][description]">' . htmlentities((string) $component->description, ENT_QUOTES, 'UTF-8', false) . '</textarea></p></div>';
		$table .= '</div>';
		$table .= '<textarea class="text compvalue" name="components[' . $count . '][value]">' . stripslashes((string) $component->value) . '</textarea>';
		$table .= '<input type="hidden" name="components[' . $count . '][id]" value="' . $count . '" />';
		exec_action('component-extras');
		$table .= '</div>';
	}
}
# create list to show on sidebar for easy access
$listc = ''; $submitclass = '';
if ($count > 1) {
	$item = 1;
	foreach ($components as $component) {
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
	var components = document.querySelectorAll("textarea.compvalue");
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
	<h3 class="floated"><?php i18n('EDIT_COMPONENTS');?></h3>
	<div class="edit-nav">
		<a href="#" id="addcomponent" accesskey="<?php echo find_accesskey(i18n_r('ADD_COMPONENT'));?>"><?php i18n('ADD_COMPONENT');?></a>
		<div class="clear"></div>
	</div>

	<form class="manyinputs" action="<?php myself(); ?>" method="post" accept-charset="utf-8">
		<input type="hidden" id="id" value="<?php echo $count; ?>">
		<input type="hidden" id="nonce" name="nonce" value="<?php echo get_nonce('modify_components'); ?>">
		<div id="divTxt"></div>
		<?php echo $table; ?>
		<p id="submit_line" class="<?php echo $submitclass; ?>">
			<span><input type="submit" class="submit" name="submitted" id="button" value="<?php i18n('SAVE_COMPONENTS');?>"></span> &nbsp;&nbsp;<?php i18n('OR'); ?>&nbsp;&nbsp; <a class="cancel" href="components.php?cancel"><?php i18n('CANCEL'); ?></a>
		</p>
	</form>
	<script>
		formisdirty = false;
		window.onbeforeunload = function(){
			if (formisdirty == true) {
				return "<?php i18n('UNSAVED_INFORMATION'); ?>";
			}
		}
		$('form input, form textarea, form checkbox').live('keyup change paste input', function(){
			if (!formisdirty) {
				formisdirty = true;
				$('#changednotify').show();
				$('#changednotify').text("<?php i18n('UNSAVED_CHANGES')?>");
				$('input[type=submit]').css('border-color', '#CC0000');
			}
		});
		$('form').submit(function(){
			formisdirty = false;
		});
	</script>
	</div>
	</div>

	<div id="sidebar">
		<?php include('template/sidebar-theme.php'); ?>
		<?php if ($listc != '') { echo '<div class="compdivlist">' . $listc . '</div>'; } ?>
		<p id="changednotify"></p>
	</div>

</div>
<?php get_template('footer'); ?>
