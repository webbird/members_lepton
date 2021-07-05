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

// Include the ordering class
//require(LEPTON_PATH.'/framework/class.order.php');
// Get new order
$order = new LEPTON_order(TABLE_PREFIX.'mod_members_groups', 'position', 'group_id', 'section_id');
$position = $order->get_new($section_id);
//die(LEPTON_tools::display($_POST));

// Insert new row into database
$database->query("INSERT INTO ".TABLE_PREFIX."mod_members_groups (section_id,page_id,position,active,group_desc,group_cache,group_search) VALUES ('$section_id','$page_id','$position','1','','','')");

if($database->is_error())
{
    die("E33: ".$database->get_error());
}
// Get the id
$group_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Say that a new record has been added, then redirect to modify page
if($database->is_error()) {
	$admin->print_error($database->get_error(), LEPTON_URL.'/modules/members/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$group_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], LEPTON_URL.'/modules/members/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$group_id);
}

// Print admin footer
$admin->print_footer();

?>