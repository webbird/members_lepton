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

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

require('../../config/config.php');

// Get id
if(!isset($_GET['member_id']) OR !is_numeric($_GET['member_id'])) {
  header("Location: ".ADMIN_URL."/pages/index.php");
} else {
  $member_id = $_GET['member_id'];
  $group_id = $_GET['group_id'];
}

if (!$group_id) $group_id = 1;
  

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

require('module_settings.php');

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php')) {
		require_once(LEPTON_PATH.'/modules/members/languages/EN.php');
	} else {
		require_once(LEPTON_PATH.'/modules/members/languages/'.LANGUAGE.'.php');
	}
}

$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE member_id = '$member_id'");
$fetch_content = $query_content->fetchRow();

$isalias = 0 + $fetch_content['m_isalias'];


//the Group Selection box:
$m_selection = '<select style="width:150px;" name="newgroup"><option value="1">'. $TEXT['NONE'].'</option>';
$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups WHERE page_id = $page_id ORDER BY position ASC");				
if($query->numRows() > 0) {
	$linestyle=' topline';	
	// Loop through groups
	while($group = $query->fetchRow()) {
		$gid = $group['group_id'];									
		if ( $gid  < 2) {continue(1);}
		$m_selection .=  '<option value="'.$gid.'"'; 
		if ($gid  == $group_id) { 						
			$m_selection .=  ' class="thismember'.$linestyle . '" selected>'. stripslashes($group['group_name'])."</option>\n";
		} else {
			$m_selection .=  ' class="thisgroup'.$linestyle . '">'. stripslashes($group['group_name'])."</option>\n";
		}
	$linestyle='';
	}					
}
				
$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_groups WHERE page_id <> $page_id ORDER BY position ASC");				
if($query->numRows() > 0) {				
	// Loop through groups
	$linestyle=' topline';
	while($group = $query->fetchRow()) {									
		if ($group['group_id'] < 2) {continue(1);}
		$m_selection .=   '<option value="'.$group['group_id'].'" class="otherpage'.$linestyle.'">'. stripslashes($group['group_name'])."</option>\n";
		$linestyle='';
	}
}

$m_selection .= '</select>';

$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
			  if($query_settings->numRows() > 0) {
			    $settings_fetch = $query_settings->fetchRow();
			    $pic_loc = $settings_fetch['pic_loc'];
				
				$listextensions = $settings_fetch['extensions'];
				if (''.$listextensions=='') {
					$listextensions = ".gif|.GIF|.jpg|.JPG|.png|.PNG|.jpeg|.JPEG";
				} else {
					$learray = explode(' ', $listextensions);
					$listextensions = '';
					foreach ($learray as $ext) {
						$listextensions .= '|.'.$ext.'|.'.strtoupper($ext);
					}
					$listextensions = substr($listextensions, 1, strlen($listextensions));				
				}
								
				$sort_mem_name = $settings_fetch['sort_mem_name'];
			  }

?>
<form name="modify" action="<?php echo LEPTON_URL; ?>/modules/members/save_member.php" method="post" style="margin: 0;">
	<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
	<input type="hidden" name="isalias" value="<?php echo $isalias; ?>">
	
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr valign="top">
	<td width="160" class="lefttd">
	<div style="margin-bottom:10px; width:150px;"><?php echo $TEXT['GROUP']; ?>:<br/>
				<?php echo $m_selection;?>				
	  	</div>
	
	<?php 
	//sortt & sortv 
	//See Note#1 for details
	if ($isalias  == 0 AND $sort_mem_name > 0) {
	//m_sortt: Sort by Text	
		echo '<div id="sortt">'.$METEXT['M_SORT_T'].'<br/>';
	 	echo '<input type="text" name="m_sortt" value="'.stripslashes($fetch_content['m_sortt']).'" style="width:90px;" maxlength="7" /><br/>
		'.$METEXT['SORTERHELP'].'</div>';
	} else {
		echo '<input type="hidden" name="m_sortt" value="'.stripslashes($fetch_content['m_sortt']).'">';
	}
	//Both Members and Alias can have a own score:
	if ($sort_mem_name == 3) {
		echo '<div id="m_score">'.$METEXT['M_SORT_V'].'<br/>';
	 	echo '<input type="text" name="m_score" value="'.stripslashes($fetch_content['m_score']).'" style="width:90px;" maxlength="10" /></div>';	
	} else {
		echo '<input type="hidden" name="m_score" value="'.stripslashes($fetch_content['m_score']).'">';
	}
	
	
	
	//-------------------------------------------------------------
	// the picture:
	
	
	if ($isalias  > 0) {
	//is an alias:
		if ($pic_loc <> "") {			
			$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members WHERE member_id = '".$isalias."'");				
			if($query->numRows() <> 1) { 
				//Maybe delete alias here and then die??
				die("Error: No such member");
			}		
			$aliasof = $query->fetchRow();	
			$picfile = $aliasof['m_picture'];
			if ($picfile == "" OR $pic_loc == "") { $previewpic =  LEPTON_URL . "/modules/members/img/nopic.jpg"; } else { $previewpic =  LEPTON_URL.''.MEDIA_DIRECTORY.''.$pic_loc.'/'.$picfile; }			  
			echo '<img src="'.$previewpic.'" '.$previewpic_wha.' name="memberpic" id="memberpic" />';
		}
	} else {
		if ($pic_loc <> "") {
			//is NO alias, get picture selection:
			echo $TEXT['IMAGE'].":"; 
			// this piece of code scans the given directory and creates the selector
			  
			if ($pic_loc == "") { $file_dir = "";} else { $file_dir= LEPTON_PATH.'/'.MEDIA_DIRECTORY.'/'.$pic_loc; }
			$picfile = $fetch_content['m_picture'];
			if ($picfile == "" OR $pic_loc == "") { $previewpic =  LEPTON_URL . "/modules/members/img/nopic.jpg"; } else { $previewpic =  LEPTON_URL.''.MEDIA_DIRECTORY.''.$pic_loc.'/'.$picfile; }
			  
			$check_pic_dir=is_dir("$file_dir");
			if ($check_pic_dir=='1') {
				$pic_dir=opendir($file_dir);
				
				echo '<select style="width:150px;" name="m_picture" onChange="javascript:changepic()">'."\n";
				echo "<option value=\"\">None selected</option>\n";
				
				while ($file=readdir($pic_dir)) {
					if ($file != "." && $file != "..") {						
			    		if (ereg($listextensions,$file)) {
			        		echo "<option value=\"".$file."\"";
							if($picfile == $file) { echo " Selected"; } 
			          		echo ">".$file."</option>\n"; 
			       	 	}
			     	}
				}
				echo "</select>\n";
			} else {
			Echo $METEXT['DIRECTORY'].$pic_loc.$METEXT['NOT_EXIST']; 
			}
			echo '<img src="'.$previewpic.'" '.$previewpic_wha.' name="memberpic" id="memberpic" />';
		}
	}
	?>
	
	</td><td>
	
	<?php
	//------------------------------------------------------------------------------
	//Right Block
	//------------------------------------------------------------------------------
	
	$the_div = '<div style="margin-bottom:10px;">';
	$the_divend = '" style="width: 99%;" maxlength="255" /></div>'."\n";
	
	if ($isalias  > 0) {
	//________________________________________________
		//Is Alias:
		echo '<input type="hidden" name="m_name" value="'.stripslashes($aliasof['m_name']).'"/>';
		
		echo '<h2>'.$METEXT['IS_ALIAS_OF'].'</h2>';
		echo $the_div.stripslashes($aliasof['m_name']).'</div>';
	
		$m_short1 = stripslashes($aliasof['m_short1']);		
		if ($m_short1 <> "") {echo $the_div.stripslashes($settings_fetch['t_short1']).':<br/>'.stripslashes($aliasof['m_short1']).'</div>';}
		
		$m_long1 = stripslashes($aliasof['m_long1']);				
		if ($m_long1 <> "") {echo $the_div.stripslashes($settings_fetch['t_long1']).':<br/>'.stripslashes($aliasof['m_long1']).'</div>';}		
		
		$m_short2 = stripslashes($aliasof['m_short2']);		
		if ($m_short2 <> "") {echo $the_div.stripslashes($settings_fetch['t_short2']).':<br/>'.stripslashes($aliasof['m_short2']).'</div>';}
		
		$m_long2 = stripslashes($aliasof['m_long2']);		
		if ($m_long2 <> "") {echo $the_div.stripslashes($settings_fetch['t_long2']).':<br/>'.stripslashes($aliasof['m_long2']).'</div>';}
		
		$m_memberpage_id = stripslashes($aliasof['m_memberpage_id']);		
		if ($m_memberpage_id > 0 ) {echo $the_div.stripslashes($settings_fetch['t_memberpage_id']).': '.stripslashes($aliasof['m_memberpage_id']).'</div>';}
		
		$m_link = stripslashes($aliasof['m_link']);		
		if ($m_link <> "") {echo $the_div.stripslashes($settings_fetch['t_link']).': '.stripslashes($aliasof['m_link']).'</div>';}
	
	
	
	} else { 
	//________________________________________________
	//is NO alias:
	
	$html_spch = 0;
		echo $the_div.$TEXT['NAME'].'<br/><input type="text" name="m_name" value="';
		$t = stripslashes($fetch_content['m_name']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.$the_divend; 
		
		
		if ($settings_fetch['t_short1'] <> '') { 
			echo $the_div.stripslashes($settings_fetch['t_short1']).':<br/><input type="text" name="m_short1" value="';
			$t = stripslashes($fetch_content['m_short1']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.$the_divend; 
		}
		
		if ($settings_fetch['t_long1'] <> '') { 
			echo $the_div.stripslashes($settings_fetch['t_long1']).':<br/><textarea name="m_long1" style="width:99%; height: 80px;">';
			$t = stripslashes($fetch_content['m_long1']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.'</textarea></div>'; 
		}
		
		if ($settings_fetch['t_short2'] <> '') { 
			echo $the_div.stripslashes($settings_fetch['t_short2']).':<br/><input type="text" name="m_short2" value="';
			$t = stripslashes($fetch_content['m_short2']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.$the_divend; 
		}
		
		if ($settings_fetch['t_long2'] <> '') { 
			echo $the_div.stripslashes($settings_fetch['t_long2']).':<br/><textarea name="m_long2" style="width:99%; height: 80px;">';
			$t = stripslashes($fetch_content['m_long2']); if ($html_spch == 1) {$t = htmlspecialchars($t);} echo $t.'</textarea></div>'; 
		}
		
		if ($settings_fetch['t_link'] <> '') { echo $the_div.stripslashes($settings_fetch['t_link']).':<br/><input type="text" name="m_link" value="'.stripslashes($fetch_content['m_link']).$the_divend; }
		if ($settings_fetch['t_memberpage_id'] <> '') { echo $the_div.stripslashes($settings_fetch['t_memberpage_id']).':&nbsp;<input type="text" name="m_memberpage_id" style="width:30px;" maxlength="4" value="'.(int)stripslashes($fetch_content['m_memberpage_id']).$the_divend; }
				
		
		
		echo '<p>'; if ($html_allowed != 1) {echo $METEXT['HTMLNOTALLOWED']; } else {echo $METEXT['HTMLALLOWED']; } echo '</p>';
		
	} 
	
	// Members AND Alias:
	echo '<div>'.$TEXT['ACTIVE']; ?>:<br/>
	<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_content['active'] == 1) { echo ' checked'; } ?> />
	<a href="javascript: toggle_checkbox('active_true');"><?php echo $TEXT['YES']; ?></a>&nbsp;
	<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_content['active'] == 0) { echo ' checked'; } ?> />
	<a href="javascript: toggle_checkbox('active_false');"><?php echo $TEXT['NO']; ?></a>
	</div>
		
	<?php if ($pic_loc <> "") { echo '<script type="text/javascript">'."\n";
				echo 'var memberpicloc = "'.LEPTON_URL.''.MEDIA_DIRECTORY.''.$pic_loc.'/"'."\n";
				echo 'var membergroup = '.$group_id."\n"; } ?>
	</script> 
		
		
		
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
	  		<input name="save" type="submit" value="<?php echo $TEXT['SAVE'].' '.$METEXT['MEMBER']; ?>" style="width: 200px; margin-top: 5px;"></form>
		</td>
		<td align="right">
			<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
</table>
</td></tr></table>
<script type="text/javascript">
		<!--
		changepic();
		 // -->
		</script>
<?php

// Print admin footer
$admin->print_footer();

?>