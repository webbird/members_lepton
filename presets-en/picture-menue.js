thepresetsdescription = 'A mainmenue made with picture-links. Find out the page_ids of the target pages.';

document.edit.pic_loc.value = '/members';
document.edit.extensions.value = 'gif jpg png jpeg';
selectDropdownOption (document.edit.sort_grp_name, 0);
selectDropdownOption (document.edit.delete_grp_members, 0);
selectDropdownOption (document.edit.sort_mem_name, 0);
selectDropdownOption (document.edit.sort_mem_desc, 0);
selectDropdownOption (document.edit.hide_email, 0);
document.edit.t_memberpage_id.value = 'Target Page (page_id)';
document.edit.t_link.value = '';
document.edit.t_short1.value = 'Subline';
document.edit.t_short2.value = '';
document.edit.t_long1.value = '';
document.edit.t_long2.value = '';
document.edit.header.value = '<!-- Picture Menue -->\n<!--Copy these styles to your stylesheet-->\n<style type=\"text/css\">\n.pmenu {width:100%;}\n.pmenu a {display:block; float:left; width:120px; height:140px; text-decoration:none ! important; border:3px solid #dddddd; margin:2px;}\n.pmenu a:hover {background-color:#dddddd; border-color:#cc0000}\n\n.pmenu img {width:120px; height:90px;}\n.pmenu .name {display:block; margin:2px; padding:0;font-size:12px;font-style:bold;}\n.pmenu .short {margin:2px; padding:0;font-size:10px;}\n</style>';
document.edit.footer.value = '<!-- Module Footer -->\n	';
document.edit.grp_head.value = '<div class=\"members-head\">\n<h2>[GROUPNAME]</h2>\n<p>[GROUPDESC]</p>\n</div>\n<div class=\"pmenu\">';
document.edit.member_loop.value = '<a href=\"[MEMBERPAGE]\">\n<img src=\"[PICTURE]\" alt=\"[NAME]\" />\n<span class=\"name\">[NAME]</span>\n<span class=\"short\">[SHORT1]</span></a>\n';
document.edit.grp_foot.value = '</div><!-- Group Footer -->';

document.getElementById('presetsdescription').innerHTML = thepresetsdescription;
alert('jepp!');