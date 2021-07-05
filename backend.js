function changepic() {
	var bildname = document.modify.m_picture.options[document.modify.m_picture.selectedIndex].value;
	o = 6 + bildname.indexOf(".gif") +  bildname.indexOf(".jpg")  +  bildname.indexOf(".png")+ bildname.indexOf(".GIF") +  bildname.indexOf(".JPG")  +  bildname.indexOf(".PNG");
	
	if (o > 0) {
		document.images['memberpic'].src = memberpicloc + bildname;
		document.images['memberpic'].style.display = "block";
	} else {
		document.images['memberpic'].style.display = "none";
	}
}

function makevisible(what) {
	document.getElementById(what).style.display="block";
	if (what != 'getfromtable' && document.getElementById("getfromtable")) document.getElementById("getfromtable").style.display="none";
	if (what != 'presetstable' && document.getElementById("presetstable")) document.getElementById("presetstable").style.display="none";
	
	
}


function changesettings(sid) {

	if( !document.createElement ) {
 		alert('No createElement, sorry');
  		return;
 	}
	
	var script = document.createElement( 'script' );
	if ( script ) {
    	script.setAttribute( 'type', 'text/javascript' );
    	script.setAttribute( 'src', theurl + sid);
 		//alert(theurl + sid);
	
    	var head = document.getElementsByTagName( 'head' )[ 0 ];
    	if ( head ) {
     		head.appendChild( script );
    	}
   	}

}

function changepresets(thefile) {
	
	if (!thelanguage) {thelanguage = "en";}
	
	if( !document.createElement ) {
 		alert('No createElement, sorry');
  		return;
 	}	
	if (script) { head.Child( script ).setAttribute( 'src', 'presets-'+thelanguage+'/'+thefile+'.js' ); }
	else {
 	var script = document.createElement( 'script' );
	if ( script ) {
    	script.setAttribute( 'type', 'text/javascript' );
    	script.setAttribute( 'src', 'presets-'+thelanguage+'/'+thefile+'.js' );
 
	
    	var head = document.getElementsByTagName( 'head' )[ 0 ];
    	if ( head ) {
     		head.appendChild( script );
    	}
   	}
	}
}


function selectDropdownOption(element,wert) {
	for (var i=0; i<element.options.length; i++) 
	{
		if (element.options[i].value == wert) 
		{
			element.options[i].selected = true;		
		} else	{
			element.options[i].selected = false;	
		}
	}
}

 