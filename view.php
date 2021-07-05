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
if(defined('LEPTON_PATH') == false) { exit("Cannot access this file directly"); }

//$LEPTON_URL = LEPTON_URL;

include ('module_settings.php');
global $oLEPTON;

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(LEPTON_PATH.'/modules/members/languages/EN.php');
    if(file_exists(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php')) {
        require_once(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php');
    }
}

// Load CSS file
if ($use_frontend_cssjs > 0) {
	if ($use_frontend_cssjs > 1) { 
		echo "\n<style type=\"text/css\">\n<!--\n"; include ('frontend.css'); echo "-->\n</style>";
		echo '<script type="text/javascript">
		<!--
		function showmembermail(n,d,t) {
		var mail = n+\'@\'+d;
		if (t==\'\') {t = mail;}
		document.write(\'<a href=\"mailto:\'+ mail + \'\">\'+ t + \'</\'+\'a>\');
		} // -->
		</script>';	
	
	} else { 
		echo '<link rel="stylesheet" type="text/css" href="'.LEPTON_URL.'/modules/members/frontend.css" />'."\n";
		echo '<script type="text/javascript" src="'.LEPTON_URL.'/modules/members/frontend.js"></script>'."\n";
	}	
}



// Get information on what groups and members are sorted by
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() <> 1) { die('No settings'); }
$settings_fetch = $query_settings->fetchRow();

$hide_email = (int)$settings_fetch['hide_email'];
$pic_loc = $settings_fetch['pic_loc'];
	
$sort_grp_name = $settings_fetch['sort_grp_name'];
if ($sort_grp_name == 1) {$sort_grp_by = "group_name";} else {$sort_grp_by = "position";}
	


// Print header
echo stripslashes($settings_fetch['header']);


// Loop through groups
$query_groups = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups WHERE section_id = '".$section_id."' AND active = '1' ORDER BY '".$sort_grp_by."' ASC");

if($query_groups->numRows() > 0) {

	
	while($group = $query_groups->fetchRow()) {
		$group_id = (int)$group['group_id'];
		
		//Cache
		$output = '';
		if ($use_caching == 1) {
			$query_content = $database->query("SELECT group_cache FROM ".TABLE_PREFIX."mod_members_groups WHERE group_id = '$group_id'");
			if($query_content->numRows() > 0) {
				$fetch_cache = $query_content->fetchRow();	
				$output = $fetch_cache['group_cache'];
				
				if (strlen($output) > 200) { 
				$oLEPTON->preprocess($output);
				echo $output; continue;
				}
			}
		}
		$output = '';

		$m_groupname = stripslashes($group['group_name']); $f_groupname='';
		if ($m_groupname != '') { $f_groupname = '<'.$block_tag.' class="mgroup-name">'.$m_groupname.'</'.$block_tag.'>'; }		
		$m_groupdesc = nl2br(stripslashes($group['group_desc'])); $f_groupdesc='';
		if ($m_groupdesc != '') { $f_groupdesc = '<'.$block_tag.' class="mgroup-desc">'.$m_groupdesc.'</'.$block_tag.'>'; }	
		
		$vars = array( '[GROUPNAME]', '[GROUPDESC]', '[GROUP_ID]', '{GROUPNAME}', '{GROUPDESC}' );
		$values = array ($m_groupname, $m_groupdesc, $group_id, $f_groupname, $f_groupdesc);		
		$output .= str_replace($vars, $values, stripslashes($settings_fetch['grp_head']));
		
		// Sort member by m_score - m_sortt - m_name or position
		$sort_mem_name = $settings_fetch['sort_mem_name'];
		$sort_mem_desc = $settings_fetch['sort_mem_desc'];		

		// Sorting members by m_score - m_sortt - m_name or position
		if ($sort_mem_desc == 1) {$sort_ad = ' DESC';} else {$sort_ad = ' ASC';}
		$sort_by = "position".$sort_ad;
		// Sorting members by m_score - m_sortt - m_name or position
		if ($sort_mem_name == 1) {$sort_by = "m_name".$sort_ad;}
		if ($sort_mem_name == 2) {$sort_by = "m_sortt".$sort_ad.", m_name".$sort_ad;}
		if ($sort_mem_name == 3) {$sort_by = "m_score".$sort_ad.", m_sortt".$sort_ad.", m_name".$sort_ad;}

		// Query tem members in this group
		$query_members = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE group_id = '".$group_id."' AND active = '1' ORDER BY ".$sort_by );
		if($query_members->numRows() > 0) {			
			$rowcount = 0;
			// Loop through all links in this group		
			while($membersmember = $query_members->fetchRow()) {
				$member_id = (int)$membersmember['member_id'];				
				
				$m_name = stripslashes($membersmember['m_name']);
				$the_score = (int)$membersmember['m_score'];
				if ($the_score > 0) { $m_score = ''.$the_score;  $f_score = '<div class="member-score">'.$m_score.'</div>'; } else {$m_score = ''; $f_score = '';}
				
				
				
				$isalias = (int)$membersmember['m_isalias'];
				if ($isalias == 0) {
					$the_member = $membersmember;
				} else {
					$query_alias = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$isalias."'");
					$the_member = $query_alias->fetchRow();				
					//var_dump($the_member);
				}
				
				$rowcount++;
				$mrow = $rowcount % 2;
				
				$m_sortt = $the_member['m_sortt']; $f_sortt='';				
				if ($m_sortt != '') { $m_sortt = stripslashes($m_sortt); $f_sortt = '<'.$block_tag.' class="member-sortt">'.$m_sortt.'</'.$block_tag.'>'; }
				
				
				$m_short1 = $the_member['m_short1']; $f_short1='';
				if ($settings_fetch['t_short1'] == '') {$m_short1 = ''; $f_short1 = '';} else { 
					$m_short1 = stripslashes($m_short1); 
					if ($backslash_to_br) $m_short1 = str_replace("\\", "<br/>", $m_short1);
				}
				if ($m_short1 != '') { $f_short1 = '<'.$block_tag.' class="member-short1">'.$m_short1.'</'.$block_tag.'>'; }
				
				$m_long1 = $the_member['m_long1']; $f_long1='';
				if ($settings_fetch['t_long1'] == '') {$m_long1 = ''; $f_long1 = '';} else { $m_long1 = nl2br(stripslashes($m_long1)); }			
				if ($m_long1 != '') { $f_long1 = '<'.$block_tag.' class="member-long1">'.$m_long1.'</'.$block_tag.'>'; }
				
				$m_short2 = $the_member['m_short2']; $f_short2='';
				if ($settings_fetch['t_short2'] == '') {$m_short2 = ''; $f_short2 = '';} else { 
					$m_short2 = stripslashes($m_short2); 
					if ($backslash_to_br) $m_short2 = str_replace("\\", "<br/>", $m_short2);
				}
				if ($m_short2 != '') { $f_short2 = '<'.$block_tag.' class="member-short2">'.$m_short2.'</'.$block_tag.'>'; }
				
				$m_long2 = $the_member['m_long2']; $f_long2='';
				if ($settings_fetch['t_long2'] == '') {$m_long2 = ''; $f_long2 = '';} else {$m_long2 = nl2br(stripslashes($m_long2)); }
				if ($m_long2 != '') { $f_long2 = '<'.$block_tag.' class="member-long2">'.$m_long2.'</'.$block_tag.'>'; }
				
				//m_link: could be: Mail, Link or Text
				$m_link = ''.stripslashes($the_member['m_link']); $f_link ='';
				if ($m_link != '') { 
					require_once(LEPTON_PATH.'/modules/members/functions.inc.php');
					$m_link = convert_member_link ($m_link, $hide_email);
					$f_link = '<'.$block_tag.' class="member-link">'.$m_link.'</'.$block_tag.'>';
				}
				
				//m_memberpage_id: must be a valid page_id
				$m_memberpage = '';	
				$f_memberpage = '';	
				$m_memberpage_id = (int)stripslashes($the_member['m_memberpage_id']);
				if ($m_memberpage_id > 0) {
					$query_pages= $database->query("SELECT link, page_title FROM ".TABLE_PREFIX."pages WHERE page_id = '$m_memberpage_id'");
					if($query_pages->numRows() <> 1) { 
						$m_memberpage = ''; 
					} else {
						$pages_fetch = $query_pages->fetchRow();
						$memberpage_text = stripslashes($pages_fetch['page_title']);											
						if ($use_caching == 1) {
							$m_memberpage = '[wblink'.$m_memberpage_id.']';
							$f_memberpage = '<'.$block_tag.' class="member-page"><a href="'.$m_memberpage.'">'.$memberpage_text.'</a></'.$block_tag.'>';
						} else {
							$pagelink = PAGES_DIRECTORY.$pages_fetch['link'].PAGE_EXTENSION;							
							$m_memberpage = LEPTON_URL.$pagelink;							
							$f_memberpage = '<'.$block_tag.' class="member-page"><a href="'.$m_memberpage.'">'.$memberpage_text.'</a></'.$block_tag.'>';
						}					
					}					
				}
				
				
				$members_pic = $the_member['m_picture'];
				if ($members_pic == '') { 
					$members_pic = LEPTON_URL. '/modules/members/img/nopic.jpg'; 
				} else {
					$members_pic = LEPTON_URL.''.MEDIA_DIRECTORY.''.$pic_loc . '/' . $members_pic;
				}
				
				
				
				$vars = array( '[MEMBER_ID]', '[GROUP_ID]', '[IS_ALIAS]', '[PICTURE]', '[NAME]', '[SCORE]','[SHORT1]', '[LONG1]', '[SHORT2]', '[LONG2]', '[LINK]', '[MEMBERPAGE]', '[SORTT]', '[ROWCOUNT]', '[MROW]', '{SCORE}','{SHORT1}', '{LONG1}', '{SHORT2}', '{LONG2}', '{LINK}', '{MEMBERPAGE}', '{SORTT}' );
				$values = array ($member_id, $group_id,  $isalias, $members_pic, $m_name, $m_score, $m_short1, $m_long1, $m_short2, $m_long2, $m_link, $m_memberpage, $m_sortt, $rowcount, $mrow, $f_score, $f_short1, $f_long1, $f_short2, $f_long2, $f_link, $f_memberpage, $f_sortt );
	
				//output the set based upon $grp_head template					
				$output .= str_replace($vars, $values, stripslashes($settings_fetch['member_loop']));
				
			}
		}
		
		if ($use_caching == 1) { 
			$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_cache= '".addslashes($output)."' WHERE group_id = '$group_id' ");
			$text = strip_tags($output);
			$text = preg_replace('/\s+/', ' ', $text);			
			$text = addslashes($text);
			$database->query("UPDATE ".TABLE_PREFIX."mod_members_groups SET group_search= '".$text."' WHERE group_id = '$group_id' ");
			
		}
		$oLEPTON->preprocess($output);
		echo $output;
		
		
		
		
		//Group Footer
		echo stripslashes($settings_fetch['grp_foot']);
		
	}
	
}



?>