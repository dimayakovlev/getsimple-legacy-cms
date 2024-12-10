<?php
/**
 * Images
 *
 * Displays information on the passed image
 *
 * @package GetSimple Legacy
 * @subpackage Images
 */

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('inc/common.php');

// Variable Settings
login_cookie_check();

$subPath = (isset($_GET['path'])) ? $_GET['path'] : "";
if ($subPath != '') $subPath = tsl($subPath);

$src = strippath($_GET['i']);
$thumb_folder = GSTHUMBNAILPATH . $subPath;
$src_folder = '../data/uploads/';
$thumb_folder_rel = '../data/thumbs/' . $subPath;
if (!filepath_is_safe($src_folder . $subPath . $src, GSDATAUPLOADPATH)) redirect('upload.php');

// handle jcrop thumbnail creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$thumb_x = isset($_POST['x']) ? intval($_POST['x']) : 0;
	$thumb_y = isset($_POST['y']) ? intval($_POST['y']) : 0;
	$thumb_w = isset($_POST['w']) ? intval($_POST['w']) : 0;
	$thumb_h = isset($_POST['h']) ? intval($_POST['h']) : 0;
	if ($thumb_w > 0 && $thumb_h > 0) {
		require_once('inc/imagemanipulation.php');
		$objImage = new ImageManipulation($src_folder . $subPath . $src);
		if ($objImage->imageok) {
			$objImage->setCrop($thumb_x, $thumb_y, $thumb_w, $thumb_h);
			//$objImage->show();
			$objImage->save($thumb_folder . 'thumbnail.' . $src);
			$success = i18n_r('THUMB_SAVED');
		} else {
			$error = i18n_r('ERROR_CREATE_THUMBNAIL');
		}
	} else {
		$error = i18n_r('ERROR_CREATE_THUMBNAIL');
	}
}

$thumb_exists = $thwidth = $thheight = $thtype = $athttr = '';

list($imgwidth, $imgheight, $imgtype, $imgattr) = getimagesize($src_folder . $subPath . $src);

if (file_exists($thumb_folder . 'thumbnail.' . $src)) {
	list($thwidth, $thheight, $thtype, $athttr) = getimagesize($thumb_folder . 'thumbnail.'.$src);
	$thumb_exists = ' &nbsp; | &nbsp; <a href="'.$thumb_folder_rel . 'thumbnail.'. rawurlencode($src) .'" rel="facybox_i" >'.i18n_r('CURRENT_THUMBNAIL').'</a> <code>'.$thwidth.'x'.$thheight.'</code>';
}else{
	// if thumb is missing recreate it
	require_once('inc/imagemanipulation.php');
	if (genStdThumb($subPath,$src)) {
		list($thwidth, $thheight, $thtype, $athttr) = getimagesize($thumb_folder . 'thumbnail.'.$src);
		$thumb_exists = ' &nbsp; | &nbsp; <a href="'.$thumb_folder_rel . 'thumbnail.'. rawurlencode($src) .'" rel="facybox_i" >'.i18n_r('CURRENT_THUMBNAIL').'</a> <code>'.$thwidth.'x'.$thheight.'</code>';
	}
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('FILE_MANAGEMENT') . ' &raquo; ' . i18n_r('IMAGES'));

include('template/include-nav.php'); ?>

<div class="bodycontent clearfix">
	<div id="maincontent">

		<div class="main">
		<h3><?php i18n('IMG_CONTROl_PANEL');?></h3>
<?php
	echo '<div class="h5 clearfix"><div class="crumbs">/ <a href="upload.php">uploads</a> / ';
	$urlPath = '';
	foreach (explode('/', $subPath) as $pathPart) {
		if ($pathPart != '') {
			$urlPath .= $pathPart . '/';
			echo '<a href="upload.php?path=' . $urlPath . '">' . $pathPart . '</a> / ';
		}
	}
	echo '<span class="current">' . $src . '</span></div></div>';
?>
			<?php echo '<p><a href="' . $src_folder . $subPath . rawurlencode($src) . '" rel="facybox_i" >' . i18n_r('ORIGINAL_IMG') . '</a> <code>' . $imgwidth . 'x' . $imgheight . '</code>' . $thumb_exists . '</p>'; ?>

			<form>
				<select class="text" id="img-info" style="width:50%">
					<option selected value="code-img-link"><?php i18n('LINK_ORIG_IMG');?></option>
					<option value="code-img-html"><?php i18n('HTML_ORIG_IMG');?></option>
					<?php if (!empty($thumb_exists)) { ?>
					<option value="code-thumb-html"><?php i18n('HTML_THUMBNAIL');?></option>
					<option value="code-thumb-link"><?php i18n('LINK_THUMBNAIL');?></option>
					<option value="code-imgthumb-html"><?php i18n('HTML_THUMB_ORIG');?></option>
					<?php } ?>
				</select>
				<textarea class="copykit text"><?php echo tsl($SITEURL) . 'data/uploads/' . $subPath . rawurlencode($src); ?></textarea>
				<p style="color:#666;font-size:11px;margin:-10px 0 0 0"><a href="#" class="select-all" ><?php i18n('CLIPBOARD_INSTR');?></a></p>
			</form>
			<div class="toggle">
				<p id="code-img-html">&lt;img src="<?php echo tsl($SITEURL) .'data/uploads/'. $subPath. rawurlencode($src); ?>" class="gs_image" height="<?php echo $imgheight; ?>" width="<?php echo $imgwidth; ?>" alt=""></p>
				<p id="code-img-link"><?php echo tsl($SITEURL) .'data/uploads/'. $subPath. rawurlencode($src); ?></p>
				<?php if(!empty($thumb_exists)) { ?>
				<p id="code-thumb-html">&lt;img src="<?php echo tsl($SITEURL) .'data/thumbs/'.$subPath.'thumbnail.'. rawurlencode($src); ?>" class="gs_image gs_thumb" height="<?php echo $thheight; ?>" width="<?php echo $thwidth; ?>" alt=""></p>
				<p id="code-thumb-link"><?php echo tsl($SITEURL) .'data/thumbs/'.$subPath.'thumbnail.'.rawurlencode($src); ?></p>
				<p id="code-imgthumb-html">&lt;a href="<?php echo tsl($SITEURL) .'data/uploads/'. $subPath. rawurlencode($src); ?>" class="gs_image_link" >&lt;img src="<?php echo tsl($SITEURL) .'data/thumbs/'.$subPath.'thumbnail.'.rawurlencode($src); ?>" class="gs_thumb" height="<?php echo $thheight; ?>" width="<?php echo $thwidth; ?>" alt="">&lt;/a></p>
				<?php } ?>
			</div>
	</div>

<?php
$jcrop = !empty($thumb_exists);
if($jcrop){ ?>
	<div id="jcrop_open" class="main">
		<img src="<?php echo $src_folder . $subPath.rawurlencode($src); ?>" id="cropbox">
		<div id="handw" class="toggle"><?php i18n('SELECT_DIMENTIONS'); ?><br /><span id="picw"></span> x <span id="pich"></span></div>
		<!-- This is the form that our event handler fills -->
		<form id="jcropform" action="<?php myself(); ?>?i=<?php echo rawurlencode($src); ?>&amp;path=<?php echo $subPath; ?>" method="post">
			<input type="hidden" id="x" name="x">
			<input type="hidden" id="y" name="y">
			<input type="hidden" id="w" name="w">
			<input type="hidden" id="h" name="h">
			<input type="submit" class="submit" value="<?php i18n('CREATE_THUMBNAIL');?>" /> &nbsp; <span style="color:#666;font-size:11px;"><?php i18n('CROP_INSTR_NEW');?></span>
		</form>
	</div>

<?php } ?>
	</div>

	<div id="sidebar">
		<?php include('template/sidebar-files.php'); ?>
	</div>

	<script>
		function updateCoords(c){
			$('#handw').show();
			$('#x').val(c.x);
			$('#y').val(c.y);
			$('#w').val(c.w);
			$('#h').val(c.h);
			$('#pich').html(c.h);
			$('#picw').html(c.w);
		};
		jQuery(document).ready(function(){
			$(window).load(function(){
				var api = $.Jcrop('#cropbox', {
					onChange: updateCoords,
					onSelect: updateCoords,
					boxWidth: 648,
					boxHeight: 500
				});
				var isCtrl = false;
				$(document).keyup(function (e) {
					api.setOptions({ aspectRatio: 0 });
					api.focus();
					if(e.which == 17) isCtrl=false;
				}).keydown(function (e) {
					if(e.which == 17) isCtrl=true;
					if(e.which == 66 && isCtrl == true) {
						api.setOptions({ aspectRatio: 1 });
						api.focus();
					}
				});
			});
			$('#jcropform').on('submit', function(){
				if ((parseInt($('#w').val()) > 0) && (parseInt($('#h').val()) > 0)) return true;
				alert('<?php i18n('THUMB_CROP_REGION_SELECT') ?>');
				return false;
			});
		});
	</script>

	</div>
<?php get_template('footer'); ?>
