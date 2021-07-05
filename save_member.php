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

// Get id
if(!isset($_POST['member_id']) OR !is_numeric($_POST['member_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$member_id = $_POST['member_id'];
}


// Include admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(LEPTON_PATH.'/modules/admin.php');

// Include the functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

// Include the ordering class
//require(LEPTON_PATH.'/framework/class.order.php');

// Validate  fields
if($admin->get_post('m_name') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], LEPTON_URL.'/modules/modify_member.php?page_id='.$page_id.'&section_id='.$section_id.'&member_id='.$member_id);
} else {

	$m_name = $admin->get_post('m_name');
	$m_sortt = addslashes(strip_tags($admin->get_post('m_sortt')));
	$m_score = (int)$admin->get_post('m_score');
	
	
	$isalias = (int) $admin->get_post('isalias');
	if ($isalias == 0) {  //is NO alias, search for aliases od this member
		$query_alias = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE m_isalias = '$member_id'");
		if($query_alias->numRows() > 0) {
		 //Fix m_sortt, m_name:
		 $database->query("UPDATE ".TABLE_PREFIX."mod_members SET m_name='".addslashes($m_name)."',  m_sortt='".$m_sortt."' WHERE m_isalias = '".$member_id."'");
		}
	}


	$html_allowed = 0;
	require('module_settings.php');
	
	$group_id = (int)$admin->get_post('group_id');
	$newgroup = (int)$admin->get_post('newgroup');
	$active = (int)$admin->get_post('active');
	$m_memberpage_id = (int)$admin->get_post('m_memberpage_id');
	
	$m_picture = addslashes(strip_tags($admin->get_post('m_picture')));
	$m_link = addslashes(trim(strip_tags($admin->get_post('m_link'))));	
	
	
	
	
	$m_short1 = $admin->get_post('m_short1');		
	$m_short2 = $admin->get_post('m_short2');
	$m_long1 = $admin->get_post('m_long1');
	$m_long2 = $admin->get_post('m_long2');
	
	
	
	if ($html_allowed != 1) {
		$m_name = my_htmlspecialchars($m_name);
		$m_short1 = my_htmlspecialchars($m_short1);			
		$m_short2 = my_htmlspecialchars($m_short2);
		$m_long1 = my_htmlspecialchars($m_long1);
		$m_long2 = my_htmlspecialchars($m_long2);	
	}
	
	
	
	$m_name = addslashes($m_name);
	$m_short1 = addslashes($m_short1);	
	$m_short2 = addslashes($m_short2);
	$m_long1 = addslashes($m_long1);
	$m_long2 = addslashes($m_long2);
	
	
}


//clear cache
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache='',  group_search=''");
		
if ($newgroup <> $group_id AND $newgroup > 1) {
		echo "moved from: ".$group_id. "  to: " .$newgroup;
			
		
		$group_id = $newgroup;
		
		//change page_id to get back to new page!
		$query_groups = $database->query("SELECT page_id, section_id FROM `".TABLE_PREFIX."mod_members_groups` WHERE group_id = '".$group_id."'");
		if($query_groups->numRows() > 0) {
			$group_fetch = $query_groups->fetchRow();
			$page_id = $group_fetch['page_id'];
			$section_id = $group_fetch['section_id'];
		}
		
		$order = new LEPTON_order(TABLE_PREFIX.'mod_members', 'position', 'member_id', 'group_id');
		$position = $order->get_new($group_id);
}

if ($newgroup == 1) { $group_id=1;}



// Update row
$query = ("UPDATE ".TABLE_PREFIX."mod_members SET "
					. " group_id = '$group_id', "
					. " m_name = '$m_name', "
					. " m_isalias = '$isalias', "
					. " active = '$active', "					
					. " m_sortt = '$m_sortt', "
					. " m_score = '$m_score', "
					. " m_short1 = '$m_short1', "
					. " m_short2 = '$m_short2', "
					. " m_long1 = '$m_long1', "
					. " m_long2 = '$m_long2', "
					. " m_memberpage_id = '$m_memberpage_id', "
					. " m_link = '$m_link', "
					. " m_picture = '$m_picture'");
					if (isset($position)) {$query .= ", position = '$position' ";}					
					$query .= " WHERE member_id = '$member_id'";
					//die($query);
					
					$database->query($query);

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), LEPTON_URL.'/modules/members/modify_member.php?page_id='.$page_id.'&section_id='.$section_id.'&member_id='.$member_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&hlmember='.$member_id);
}




// Print admin footer
$admin->print_footer();

?>