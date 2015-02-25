$(document).ready(function() {
    console.log( "ready!" );
    js_detect();
});

function js_detect() {
	document.login.js_var.value = 'TRUE';
	console.log("TEST");
}

/*
function pageforward(page) {
	page = parseInt(page);

	start = parseInt(document.search.startval.value);
	limit = parseInt(document.search.limit.value);

	var total_records = '<?php echo $_SESSION['total_records']; ?>';

	var newstart = parseInt(page*limit-limit+1);

	document.search.startval.value = newstart;
	document.search.page.value = page;

	document.getElementById('search').submit();
}

function number(e) {
	var key;
	var keychar;

	if (window.event) {
		key = window.event.keyCode;
	}
	else if (e){
		ey = e.which;
	}
	else {
		return true;
	}

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27)){
		return true;
	}
	else if ((("0123456789").indexOf(keychar) > -1)) {
		return true;
	}
	else {
		return false;
	}
}

function checkAll(field) {
	for (i = 0; i < field.length; i++) {
		field[i].checked = true ;
	}
}

function uncheckAll(field){
	for (i = 0; i < field.length; i++){
		field[i].checked = false ;
	}
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0  
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");  
	if (restore) {
		selObj.selectedIndex=0;
	} 
}*/