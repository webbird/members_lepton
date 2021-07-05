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

// Must include code to stop this file being access directly
if(!defined('LEPTON_PATH')) { exit("Cannot access this file directly"); }

// include functions to edit the optional module CSS files (frontend.css, backend.css)
require_once('css.functions.php');

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php')) {
		require_once(LEPTON_PATH.'/modules/members/languages/EN.php');
	} else {
		require_once(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php');
	}
}


require('module_settings.php');
if (isset($_GET['hlmember'])) {$hlmember = 0 + (int)$_GET['hlmember'];} else {$hlmember = 0;}

//Delete all links and groups with no m_name
$database->query("DELETE FROM ".TABLE_PREFIX."mod_members  WHERE m_name=''");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_members_groups  WHERE group_id > '1' AND group_name=''");

// Get information on what groups and members are sorted by
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() <> 1) { die('No settings'); }

$settings_fetch = $query_settings->fetchRow();
$sort_grp_name = $settings_fetch['sort_grp_name'];
if ($sort_grp_name == 1) {$sort_grp_by = "'group_name'";} else {$sort_grp_by = "'position'";}
	
$sort_mem_name = $settings_fetch['sort_mem_name'];
$sort_mem_desc = $settings_fetch['sort_mem_desc'];

// Sorting members by m_score - m_sortt - m_name or position
if ($sort_mem_desc == 1) {$sort_ad = ' DESC';} else {$sort_ad = ' ASC';}
$sort_by = "position".$sort_ad;
// Sorting members by m_score - m_sortt - m_name or position
if ($sort_mem_name == 1) {$sort_by = "m_name".$sort_ad;}
if ($sort_mem_name == 2) {$sort_by = "m_sortt".$sort_ad.", m_name".$sort_ad;}
if ($sort_mem_name == 3) {$sort_by = "m_score".$sort_ad.", m_sortt".$sort_ad.", m_name".$sort_ad;}





$picurl = LEPTON_URL.'/modules/members/img/';
$mod_gl = LEPTON_URL.'/modules/members/modify_group.php?';
$mod_ml = LEPTON_URL.'/modules/members/modify_member.php?';
$add_ml = LEPTON_URL.'/modules/members/add_member.php?';
$mod_param = 'page_id='.$page_id.'&section_id='.$section_id.'&group_id=';

?>


<table width="30" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><a href="<?php echo LEPTON_URL .'/modules/members/add_group.php?'. $mod_param.'"><img src="'.$picurl.'top_new.gif" alt="'. $TEXT['ADD'].' '.$METEXT['GROUP']; ?>" width="80" height="47" border="0" /></a></td>
    <?php if ($noadmin_nooptions > 0 AND $admin->get_group_id() != 1 ) {} else { echo '<td nowrap>&nbsp;&nbsp;&nbsp;<a href="'.LEPTON_URL .'/modules/members/modify_settings.php?'. $mod_param.'"><img src="'.$picurl.'top_options.gif" alt="'. $TEXT['SETTINGS']. '" width="41" height="47" border="0" /></a></td>' ;}?>
    <td nowrap>&nbsp;&nbsp;&nbsp;<a href="<?php echo LEPTON_URL .'/modules/members/find_ghosts.php?'. $mod_param.'1"><img src="'.$picurl.'top_ghosts.gif" alt="'. $METEXT['MANAGEGHOSTS']; ?>" width="41" height="47" border="0"></a></td>
    <td nowrap>&nbsp;&nbsp;&nbsp;<a href="<?php echo LEPTON_URL .'/modules/members/help.php?'. $mod_param.'"><img src="'.$picurl.'top_help.gif" alt="'. $MENU['HELP']; ?>" width="41" height="47" border="0"></a></td>
  </tr>
</table>
<br />



<table cellpadding="2" cellspacing="0" border="0" width="100%" class="membertable">
<?php



echo $database->get_error();
// Loop through existing groups
$query_groups = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members_groups` WHERE section_id = '$section_id' ORDER BY ".$sort_grp_by." ASC");

if($database->is_error())
{
    echo $database->get_error();
}

if($query_groups->numRows() > 0) {
	
	while($group_fetch = $query_groups->fetchRow()) {
		$group_id = $group_fetch['group_id'];		
		$group_active = $group_fetch['active'];
		
		?>
		<tr class="grouptr">
			<td width="50" class="grouptd1">
				<a href="<?php echo $mod_gl.$mod_param.$group_id.'"><img src="'.$picurl; ?>modg.gif" alt="Modify Group" /></a>
				<a href="<?php echo $add_ml.$mod_param.$group_id.'"><img src="'.$picurl; ?>addgm.gif" alt="Add Member" /></a>
			</td>
			<td class="grouptd2"><a href="<?php echo $mod_gl.$mod_param.$group_id.'">'.stripslashes($group_fetch['group_name']); ?></a></td>			
			<td width="30"><img src="<?php echo $picurl.'gactive'. $group_active; ?>.gif" alt="" /></td>
			<td width="30">
				<a href="<?php echo LEPTON_URL.'/modules/members/move_up.php?'.$mod_param.$group_id.'" name="'.$TEXT['MOVE_UP']; ?>"><img src="<?php echo LEPTON_URL; ?>/modules/lib_lepton/backend_images/up_16.png" border="0" alt="^" /></a>
			</td>
			<td width="30">
				<a href="<?php echo LEPTON_URL.'/modules/members/move_down.php?'.$mod_param.$group_id.'" name="'.$TEXT['MOVE_DOWN']; ?>"><img src="<?php echo LEPTON_URL; ?>/modules/lib_lepton/backend_images/down_16.png" border="0" alt="v" /></a>
			</td>
			<td width="30">
				<a href="#" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo LEPTON_URL; ?>/modules/members/delete_group.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&group_id=<?php echo $group_id; ?>');" m_name="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo LEPTON_URL; ?>/modules/lib_lepton/backend_images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		
		
		<?php //-------------
		
		
		
		
		// Loop through existing members

	$query_members = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE group_id = '$group_id' ORDER BY ".$sort_by);

	$countquery_members = $query_members->numRows();
	if($countquery_members > 0) {		
		$countmembers = 0;
		$countalias = 0;
		$countaliasofmembers = 0;
		include('memberlist.inc.php');
		echo '<tr >
		    <td colspan="6" class="groupinfo">'.$countmembers.'/'.$countquery_members.' '.$METEXT['ITEMS']; if ($countalias > 0) {echo ', '.$METEXT['PARTALIASES'].': '. $countalias; } if ($countaliasofmembers > 0) {echo ', (*)'. $METEXT['MEMWITHALIASES'].': '.$countaliasofmembers; } echo '</td></tr>' ;
	
	} else {
		echo '<tr><td colspan="6" class="groupinfo">'.$TEXT['NONE_FOUND'].'</td></tr>';
	}
	echo '<tr ><td colspan="6">&nbsp;</td></tr>';
	//----------------------------------

}
?>
	
</table>
	<?php
	
} else {
	echo $TEXT['NONE_FOUND'];
}

echo '<div class="admininfo ">sort_by: '.$sort_by.'</div>';
echo '<p>'; css_edit(); echo '</p><hr/>';
?>


