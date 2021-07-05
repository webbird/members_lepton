<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config/config.php');

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');


global $oLEPTON;
// Load Language file
if(LANGUAGE_LOADED) {
    require_once(LEPTON_PATH.'/modules/members/languages/EN.php');
    if(file_exists(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php')) {
        require_once(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php');
    }
}

include(LEPTON_PATH.'/modules/members/module_settings.php');

if ($use_getfrom) { echo '<a href="#" onclick=makevisible("getfromtable"); >Get from</a>'; }
if ($use_getfrom && $use_presets) echo " | ";
if ($use_presets) { echo '<a href="#" onclick=makevisible("presetstable"); >Presets</a>'; }
if ($use_getfrom || $use_presets) echo "<br/>";

if ($use_getfrom) { 
	echo '<script type="text/javascript"> var theurl = "' .LEPTON_URL.'/modules/members/getsettings.php?"; </script>';
	echo '<table cellpadding="2" cellspacing="0" border="0" width="100%" id="getfromtable" style="display:none;">
	<tr><td width="30%" valign="top">Get from:<br/>
	<form name="getsettings" action="#" method="get" style="margin: 0;">
	<select name="choosesettings" id="getpresets" onchange="changesettings(this.options[this.selectedIndex].value);">'; 

	echo '<option value="page_id='.$page_id.'&section_id='.$section_id.'">This one (reload)</option>';
			
	//Get other settings:
	$query_others = $database->query("SELECT page_id, section_id FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id <> '$section_id'  ORDER BY page_id ASC");
	if($query_others->numRows() > 0) { 	
		while($others = $query_others->fetchRow()) {
			$p_id = (int)$others['page_id'];
			$s_id = (int)$others['section_id'];
			$query_page = $database->query("SELECT menu_title, link FROM ".TABLE_PREFIX."pages WHERE page_id = '$p_id'");
			$fetch_menu = $query_page->fetchRow();
			$menutitle = $fetch_menu['menu_title'];
			$the_link = $fetch_menu['link'];
			echo '<option value="page_id='.$p_id.'&section_id='.$s_id.'">'.$menutitle .' (sid'.$s_id.')</option>';		
		}
	}
	
	echo '</select></form></td><td><div id="getfromdescription">NOTE: the get-from option will change the setting. If you dont want to keep the changes, do NOT save!</div></td></tr></table>';
	
}



if ($use_presets) { 
	//get presets	
	$thelanguage = strtolower(LANGUAGE);
	if (!is_dir(LEPTON_PATH.'/modules/members/presets-'.$thelanguage)) { $thelanguage = 'en';}
	$presets_files = LEPTON_PATH.'/modules/members/presets-'.$thelanguage;
	echo '<script type="text/javascript"> var thelanguage = "' .$thelanguage. '"; </script>';

	echo '<table cellpadding="2" cellspacing="0" border="0" width="100%" id="presetstable" style="display:none;">
<tr><td width="30%" valign="top">Presets:<br/>

<form name="presets" action="#" method="get" style="margin: 0;">
<select name="getpresets" id="getpresets" onchange="changepresets(this.options[this.selectedIndex].value);">  
     <option value="">----------</option>';
	 
	$presets_dir = opendir($presets_files);				
	while ($file=readdir($presets_dir)) {
		if ($file != "." && $file != "..") {						
			
			if (1 === preg_match('/\.js/i',$file))
			{
			    $filename = substr($file, 0, -3);
			    if ($filename == "default") continue;
			    echo '<option value="'.$filename.'">'.$filename.'</option>'; 
			}
		}
	}
	echo '</select></form>
	</td><td><div id="presetsdescription">NOTE: the presets-option will change the setting. If you dont want to keep the changes, do NOT save!</div></td></tr></table>';


 } 

echo '<hr/>';
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
$fetch_content = $query_content->fetchRow(); ?>

<form name="edit" action="<?php echo LEPTON_URL; ?>/modules/members/save_settings.php" method="post" style="margin: 0;">

	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<strong><?php echo $METEXT['MNSETTINGS']; ?></strong>
<div id="settings1">
<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
		
		<tr >
			<td width="30%" valign="top"><?php echo $METEXT['PIC_LOC']; ?>:</td>
			<td>
				<?php
				$pic_loc = stripslashes($fetch_content['pic_loc']);
				if ($pic_loc == '') { $pic_loc1 = '/members'; }
				?>
				<input name="pic_loc" type="text" value="<?php echo $pic_loc; ?>" style="width: 50%;">
			</td>
		</tr>
		
		<tr >
		  <td valign="top"><?php echo $METEXT['EXTENSIONS']; ?>:</td>
		  <td><?php
		  
				$extensions = ''.stripslashes($fetch_content['extensions']);
				if ($extensions == '') { $extensions = $defaultextensions ; }				
				?>
				<input name="extensions" type="text" value="<?php echo $extensions; ?>" style="width: 50%;">        
	  </tr>
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['SORT_GRP_BY']; ?>:</td>
			<td>
			<?php $sort_grp_name = stripslashes($fetch_content['sort_grp_name']); ?>
			
			<select name="sort_grp_name" style="width: 50%;">
				<option value ="0" <?php if ($sort_grp_name == 0) { echo "selected"; } echo '>'.$METEXT['SORT_BY_ORDER']; ?></option>
				<option value ="1" <?php if ($sort_grp_name == 1) { echo "selected"; } echo '>'.$METEXT['SORT_GRP_BY_NAME']; ?></option>
			</select>
		</tr>
		<!-- delete_grp_members -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['DELETE_GRP_MEM']; ?>:</td>
			<td>
			<?php $delete_grp_members = stripslashes($fetch_content['delete_grp_members']); ?>
			
			<select name="delete_grp_members" style="width: 50%;">
				<option value="0" <?php if ($delete_grp_members == 0) { echo "selected"; } echo '>'.$TEXT['NO']; ?></option>
				<option value="1" <?php if ($delete_grp_members == 1) { echo "selected"; } echo '>'.$TEXT['YES']; ?></option>
			</select>
		</tr>
		<!-- sort_mem_name -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['SORT_MEM_BY']; ?>:</td>
			<td>
			<?php $sort_mem_name = stripslashes($fetch_content['sort_mem_name']); ?>
			
			<select name="sort_mem_name" style="width: 50%;">
				<option value ="0" <?php if ($sort_mem_name == 0) { echo "selected"; } echo '>'.$METEXT['SORT_BY_ORDER']; ?></option>
				<option value ="1" <?php if ($sort_mem_name == 1) { echo "selected"; } echo '>'.$METEXT['SORT_BY_NAME']; ?></option>
				<option value ="2" <?php if ($sort_mem_name == 2) { echo "selected"; } echo '>'.$METEXT['SORT_BY_SORTER']; ?></option>
				<option value ="3" <?php if ($sort_mem_name == 3) { echo "selected"; } echo '>'.$METEXT['SORT_BY_SCORE']; ?></option>
			</select>
		</tr>
		<!-- sort_mem_desc -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['SORT_ASC_DESC']; ?>:</td>
			<td>
			<?php $sort_mem_desc = stripslashes($fetch_content['sort_mem_desc']); ?>
			
			<select name="sort_mem_desc" style="width: 50%;">
				<option value ="0" <?php if ($sort_mem_desc == 0) { echo "selected"; } echo '>'.$METEXT['SORT_ASC']; ?></option>
				<option value ="1" <?php if ($sort_mem_desc == 1) { echo "selected"; } echo '>'.$METEXT['SORT_DESC']; ?></option>
			</select>
		</tr>
		<!-- hide_email -->
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['HIDEMAIL']; ?>:</td>
			<td>
			<?php $hide_email = stripslashes($fetch_content['hide_email']); ?>
			
			<select name="hide_email" style="width: 50%;">
				<option value ="0" <?php if ($hide_email == 0) { echo "selected"; } echo '>'.$TEXT['NO']; ?></option>
				<option value ="1" <?php if ($hide_email == 1) { echo "selected"; } ?> >Javascript</option>
			</select>
		</tr>

  </table>
  </div>
  <div id="settings2">
<hr />
<p><?php echo $METEXT['MODIFYFIELDS']; ?>:</p>

  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="options-names">
    <tr>
      <td width="30%">Short1<br/>
          <?php $t_short1 = stripslashes($fetch_content['t_short1']); ?>
          <input name="t_short1" type="text" value="<?php echo $t_short1; ?>" style="width: 90%;">
      </td>
      <td width="30%">Long1<br/>
          <?php $t_long1 = stripslashes($fetch_content['t_long1']); ?>
          <input name="t_long1" type="text" value="<?php echo $t_long1; ?>" style="width: 90%;">
      </td>
      <td>Memberpage ID<br/>
          <?php $t_memberpage_id = stripslashes($fetch_content['t_memberpage_id']); ?>
          <input name="t_memberpage_id" type="text" value="<?php echo $t_memberpage_id; ?>" style="width: 90%;">
      </td>
    </tr>
    <tr>
      <td width="30%">Short2<br/>
          <?php $t_short2 = stripslashes($fetch_content['t_short2']); ?>
          <input name="t_short2" type="text" value="<?php echo $t_short2; ?>" style="width: 90%;">
      </td>
      <td width="30%">Long2<br/>
          <?php $t_long2 = stripslashes($fetch_content['t_long2']); ?>
          <input name="t_long2" type="text" value="<?php echo $t_long2; ?>" style="width: 90%;">
      </td>
      <td>Link/Mail<br/>
          <?php $t_link = stripslashes($fetch_content['t_link']); ?>
          <input name="t_link" type="text" value="<?php echo $t_link; ?>" style="width: 90%;">
      </td>
    </tr>
  </table>
  </div>
  <div id="settings3">
<hr />	
<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 3px;">
		<tr>
			<td colspan="2"><strong><?php echo $METEXT['LTSETTINGS']; ?></strong></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $TEXT['HEADER']; ?>:</td>
			<td>
				<textarea name="header" style="width: 98%; height: 50px;"><?php echo stripslashes(htmlspecialchars($fetch_content['header'])); ?></textarea>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $TEXT['FOOTER']; ?>:</td>
			<td>
				<textarea name="footer" style="width: 98%; height: 50px;"><?php echo stripslashes(htmlspecialchars($fetch_content['footer'])); ?></textarea>
		</tr>
		<tr><td colspan="2"><hr/>
		</td>
		</tr>
		<tr>
			<td width="30%" valign="top" class="newsection"><?php echo $METEXT['GPHEADER']; ?></td>
			<td class="newsection"><textarea name="grp_head" style="width:98%; height: 200px;"><?php echo stripslashes(htmlspecialchars($fetch_content['grp_head'])); ?></textarea>
</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td><hr/>
	      </td>
	  </tr>
		<tr>
			<td width="30%" valign="top"><?php echo $METEXT['TMLOOP']; ?></td>
			<td><textarea name="member_loop" style="width:98%; height: 200px;"><?php echo stripslashes(htmlspecialchars($fetch_content['member_loop'])); ?></textarea>
</td>
		</tr>
		<tr><td width="30%">&nbsp;</td><td><hr/></td></tr>
		<tr>
			<td width="30%" valign="top" class="newsection"><?php echo $METEXT['GPFOOTER']; ?></td>
			<td class="newsection"><textarea name="grp_foot" style="width:98%; height: 50px;"><?php echo stripslashes(htmlspecialchars($fetch_content['grp_foot'])); ?></textarea>
</td>
		</tr>
		<tr>
			<td colspan="2" valign="top"><hr>
</td>
		</tr>
  </table>
</div>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
		  <td align="left">
				<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"> 
			  <?php if ($admin->get_group_id() == 1) {echo '<input type="checkbox" name="forall" value="1">'.$METEXT['FORALL'];}  ?>
			</form>
			</td>
			<td align="right">
                <input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>

<?php

 
// Print admin footer
$admin->print_footer();

?>