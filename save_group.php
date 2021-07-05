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
if(!isset($_POST['group_id']) OR !is_numeric($_POST['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$group_id = $_POST['group_id'];
}

// Include admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(LEPTON_PATH.'/modules/admin.php');

// Include the functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

// Vagroup_idate all fields
if($admin->get_post('group_name') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], LEPTON_URL.'/modules/members/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$group_id);
} else {
	$html_allowed = 0;
	require('module_settings.php');
	
	$active = $admin->get_post('active');
	
	$group_name = $admin->get_post('group_name');
	$group_desc = $admin->get_post('group_desc');
	
	if ($html_allowed != 1) {
		$group_name = my_htmlspecialchars($group_name);
		$group_desc = my_htmlspecialchars($group_desc);
	}
	
	$group_name = addslashes($group_name);
	$group_desc = addslashes($group_desc);
	
}
// die(LEPTON_tools::display( $_POST));
//clear cache:
	$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache='',  group_search='' WHERE group_id = '".$group_id."'");

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_name = '$group_name', group_desc = '$group_desc', active = '$active' WHERE group_id = '$group_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), LEPTON_URL.'/modules/members/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$group_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>