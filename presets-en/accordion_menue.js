
	thepresetsdescription = 'Default for the Accordion Menu.<br/>Please fix the paths';


	sort_grp_name = 0;
	sort_mem_name = 2;
	sort_mem_desc = 0;
	
	t_memberpage_id= '';
	t_link = 'link';
	
	t_short1 = 'Title';
	t_short2 = '';
	t_long1 = 'Description'; 
	t_long2 = '';
	
	header = '<!--accordion menue-->\n<!--required librarys\nPlease fix the paths! -->\n<!--Yahoo! User Interface Library : http:developer.yahoo.com/yui/index.html-->\n<script type="text/javascript" src="/wb/modules/members/accordion/yahoo_2.0.0-b2.js"></script>\n<script type="text/javascript" src="/wb/modules/members/accordion/event_2.0.0-b2.js" ></script>\n<script type="text/javascript" src="/wb/modules/members/accordion/dom_2.0.2-b3.js"></script>\n<script type="text/javascript" src="/wb/modules/members/accordion/animation_2.0.0-b3.js"></script>';	
	header += '<script type="text/javascript" src="/wb/modules/members/accordion/accordion-menu-v2.js"></script>\n<link rel="stylesheet" type="text/css" href="/wb/modules/members/accordion/accordion-menu-v2.css" />\n<!--//required library-->';

	footer = '<!-- Module Footer -->';
	grp_head = '<hr/>\n<h3>[GROUPNAME]</h3>\n<p>[GROUPDESC]</p>\n<dl class="accordion-menu">\n';
	grp_foot = '</dl><!-- Group Footer -->';
	member_loop = '<dt class="a-m-t"><strong>[NAME]</strong></dt>\n<dd class="a-m-d"><div class="bd">\n<img src="[PICTURE]" width="80" height="50" alt="" style="float:left;margin: 0  5px 0 0;"/>\n<div class="someclass"><b>Title:</b> [SHORT1]<br/><b>Description:</b> [LONG1]<br/>{LINK}</div>\n</div></dd>\n';
	

	//Change the fields:
	selectDropdownOption (document.edit.sort_grp_name, sort_grp_name);
	selectDropdownOption (document.edit.sort_mem_name, sort_mem_name);
	selectDropdownOption (document.edit.sort_mem_desc, sort_mem_desc);
	//element = document.edit.sort_grp_name


	document.edit.t_short1.value = t_short1;
	document.edit.t_short2.value = t_short2;
	document.edit.t_long1.value = t_long1;
	document.edit.t_long2.value = t_long2;
	document.edit.t_memberpage_id.value = t_memberpage_id;
	document.edit.t_link.value = t_link;
	
	document.edit.header.value = header;
	document.edit.footer.value = footer;
	document.edit.grp_head.value = grp_head;
	document.edit.member_loop.value = member_loop;
	document.edit.grp_foot.value = grp_foot;
	
	document.getElementById('presetsdescription').innerHTML = thepresetsdescription;