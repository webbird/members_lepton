<?php
	$use_caching = 0;
	$use_aliases = 1;
	$use_presets = true;
	$use_getfrom = true;
	
	$show_picture = 1; 
	$pic_loc_default = '/members'; //Default 
	$defaultextensions = 'gif jpg png jpeg';
	
	
	
	

	$html_allowed = 1;  //1: Alle Felder dürfen HTML enthalten. 0: Kein HTML (htmlspecialchars();)		
	$hide_email_default = 0; //Defaultwert, 1: Mail-Adressen in m_link werden mit Javascript maskiert.


	$use_frontend_cssjs = 0; 
	//0: no, 1: link, 2: embed:
	
	$noadmin_nooptions = 0; 
	//0: no, 1: Only the Admin may change Settings
	
//-------------------------------------------------------------

//Various:
	$previewpic_wha =  'width="150" alt="preview"';	
	$block_tag = 'div'; //Felder wie {SHORT1} werden mit block_tag und Class ausgegeben.
	$backslash_to_br = 1; //Nur KurzFelder: Backslash \ wird zu <br/>
	
	
//------------------------------------------------------------	
	
	//Standardvorgaben für die Optionen:
	//Default settings in options:
	
	$t_memberpage_id= 'MemberPage (page_id)';
	$t_link = 'Link or Mail';
	
	$t_short1 = 'Short Text 1';
	$t_short2 = 'Short Text 2';
	$t_long1 = 'Long Text 1'; 
	$t_long2 = 'Long Text 2';
	
	$header = '<!-- Module Header -->
	';
	$footer = '<!-- Module Footer -->
	';



$grp_head = '<div class="members-head">
<h2>[GROUPNAME]</h2>
<p>[GROUPDESC]</p>
</div>
';
$grp_foot = '<!-- Group Footer -->';

if ($show_picture = 1) {
$member_loop = '<table width="90%" class="members-member">
<tr valign="top"><td width="100">
<img src="[PICTURE]" width="100" alt="[NAME]" />{SCORE}</td>
<td align="left" class="member-text">
<h3 class="member-name">[NAME]</h3>
{SHORT1}{LONG1}{SHORT2}{LONG2}{LINK}{MEMBERPAGE}
</td></tr></table>
'; } else {
$member_loop = '<div class="members-member">
<h3 class="member-name">[NAME]</h3>
{SCORE}{SHORT1}{LONG1}{SHORT2}{LONG2}{LINK}{MEMBERPAGE}
';}



?>
