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

if (defined('LEPTON_URL')) {
	$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings");
	$settings_fetch = $query_settings->fetchRow();	

	
	// Add field extensions to mod_members_settings
	if(!isset($settings_fetch['extensions'])){
		if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_members_settings` ADD `extensions` VARCHAR(255) NOT NULL DEFAULT ''")) {
			echo '<span class="good">Database Field extensions added successfully</span><br />';
		}
			echo '<span class="bad">'.$database->get_error().'</span><br />';
	} else {
		echo '<span class="ok">Database Field extensions exists update not needed</span><br />';
	}
	

	if($database->is_error()) {
		echo ("OOPS, something went wrong. If it's a duplicate error then it's okay - it means that your database has already been modified.<br/>The error was: ".$database->get_error());
	} else {
		echo ("SUCCESS: The required changes have been made to your database.");
	}	

}

?>