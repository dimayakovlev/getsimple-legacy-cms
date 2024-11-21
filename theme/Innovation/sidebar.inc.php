<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 			sidebar.inc.php
* @Package:		GetSimple Legacy
* @Action:		Innovation theme for GetSimple Legacy CMS
*
*****************************************************/
?><aside id="sidebar">
<?php
	if ($innov_settings) {
?>
	<div class="section" id="socialmedia">
		<h2>Connect</h2>
		<div class="icons">
		<?php
			foreach($innov_settings as $id => $setting){
				if ($setting != '') {
					echo '<a href="' . $setting . '"><img src="' . get_theme_url(false) . '/assets/images/' . $id . '.png" alt="' . $id . '"/></a>';
				}
			}
		?>
		</div>
	</div>
<?php
	}
?>
	<!-- wrap each sidebar section like this -->
	<div class="section">
		<?php get_component('sidebar');	?>
	</div>
</aside>
