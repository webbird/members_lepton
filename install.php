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

if(defined('LEPTON_URL')) {
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_members`");
	$mod_members = 'CREATE TABLE `'.TABLE_PREFIX.'mod_members` ( '
					 . '`member_id` INT NOT NULL AUTO_INCREMENT,'
					 . '`group_id` INT NOT NULL DEFAULT \'0\','
					 . '`position` INT NOT NULL DEFAULT \'0\','
					 . '`active` INT NOT NULL DEFAULT \'0\','
					 . '`m_isalias` INT NOT NULL DEFAULT \'0\','
					 . '`m_score` INT NOT NULL DEFAULT \'0\','
					 . '`m_sortt` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_name` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_memberpage_id` INT NOT NULL DEFAULT \'0\','
					 . '`m_link` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_short1` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_short2` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`m_long1` TEXT NOT NULL,'
 					 . '`m_long2` TEXT NOT NULL,'					 
					 . '`m_picture` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . 'PRIMARY KEY (member_id)'
                . ' )';
	$database->query($mod_members);

    if($database->is_error())
    {
        echo "b1 ".$database->get_error();
    }

	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_members_groups`");
	$mod_members = 'CREATE TABLE `'.TABLE_PREFIX.'mod_members_groups` ( '
					 . '`group_id` INT NOT NULL AUTO_INCREMENT,'
					 . '`section_id` INT NOT NULL DEFAULT \'0\','
					 . '`page_id` INT NOT NULL DEFAULT \'0\','
					 . '`position` INT NOT NULL DEFAULT \'0\','
					 . '`active` INT NOT NULL DEFAULT \'0\','

					 . '`group_name` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`group_desc` TEXT NOT NULL,'

					 . '`group_cache` TEXT NOT NULL,'
					 . '`group_search` TEXT NOT NULL,'
					 . 'PRIMARY KEY (group_id)'
                . ' )';
	$database->query($mod_members);

    if($database->is_error())
    {
        echo "b2 ".$database->get_error();
    }
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_members_settings`");
	$mod_members = 'CREATE TABLE `'.TABLE_PREFIX.'mod_members_settings` ( '
					 . '`section_id` INT NOT NULL DEFAULT \'0\','
					 . '`page_id` INT NOT NULL DEFAULT \'0\','
					 . '`t_memberpage_id` VARCHAR(255) NOT NULL DEFAULT \'0\','
					 . '`t_link` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`t_short1` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`t_short2` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`t_long1` VARCHAR(255) NOT NULL DEFAULT \'\','
 					 . '`t_long2` VARCHAR(255) NOT NULL DEFAULT \'\','		 
					 
					 . '`header` TEXT NOT NULL,'
					 . '`footer` TEXT NOT NULL,'
					 
					 . '`grp_head` TEXT NOT NULL,'
					 . '`grp_foot` TEXT NOT NULL,'
					 . '`member_loop` TEXT NOT NULL,'
					 . '`pic_loc` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`extensions` VARCHAR(255) NOT NULL DEFAULT \'\','
					 . '`sort_grp_name` TINYINT(1) NOT NULL DEFAULT \'0\','
					 . '`delete_grp_members` TINYINT(1) NOT NULL DEFAULT \'0\','					 
					 . '`sort_mem_name` TINYINT(1) NOT NULL DEFAULT \'0\','
					 . '`sort_mem_desc` TINYINT(1) NOT NULL DEFAULT \'0\','

					 . '`hide_email` TINYINT(1) NOT NULL DEFAULT \'0\','
					 . 'PRIMARY KEY (section_id)'
                . ' )';
	$database->query($mod_members);

    if($database->is_error())
    {
        echo "b3 ".$database->get_error();
    }
	
	// Insert info into the search table
	// Module query info
	
	$field_info = array();
	$field_info['page_id'] = 'page_id';
	$field_info['title'] = 'page_title';
	$field_info['link'] = 'link';
	$field_info['description'] = 'description';
	$field_info['modified_when'] = 'modified_when';
	$field_info['modified_by'] = 'modified_by';
	$field_info = serialize($field_info);
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'members', '$field_info')");
	// Query start
	
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by FROM [TP]mod_members_groups, [TP]mod_members_settings, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'members')");

	// Query body
	$query_body_code = "
	[TP]pages.page_id = [TP]mod_members_groups.page_id AND [TP]mod_members_groups.group_search [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\' OR
	[TP]pages.page_id = [TP]mod_members_groups.page_id AND [TP]mod_members_groups.group_name [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\' OR
	[TP]pages.page_id = [TP]mod_members_groups.page_id AND [TP]mod_members_groups.group_desc  [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'
	";	
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'members')");

	// Query end
	$query_end_code = "";	

	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'members')");

    if($database->is_error())
    {
        echo "b4 ".$database->get_error();
    }
	
	// Insert blank rows (there needs to be at least on row for the search to work)
	
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_members_groups (section_id,page_id,group_id, group_desc, group_cache, group_search) VALUES ('0', '0', '0', '', '', '')");
    if($database->is_error())
    {
        echo "b5 ".$database->get_error();
    }

	$database->query("INSERT INTO ".TABLE_PREFIX."mod_members_settings (section_id,page_id, header, footer, grp_head, grp_foot, member_loop) VALUES ('0', '0', '', '', '', '', '')");
    
    if($database->is_error())
    {
        echo "b6 ".$database->get_error();
    }

	//______________________________________________


	//Add folder for images to media dir
	require_once(LEPTON_PATH.'/framework/summary.functions.php');
	make_dir(LEPTON_PATH.MEDIA_DIRECTORY.'/members');
	
}

?>