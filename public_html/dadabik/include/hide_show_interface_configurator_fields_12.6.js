function hide_show_interface_configurator_fields(field, field_position){

	
for (i=1;i<=26;i++){
	if (document.getElementById(i) && i != 17){ // 17 is the "Field present in the search form?" and the id is used just to display a notice 
	var tableRow = document.getElementById(i);

	

	tableRow.style.display = 'none';
	}   
}


if(field.value == 'select_single' || field.value == 'select_single_radio' || field.value == 'select_multiple_menu' || field.value == 'select_multiple_checkbox'){

var tableRow = document.getElementById('1');

tableRow.style.display = 'table-row';
var tableRow = document.getElementById('2');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('3');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('4');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('5');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('6');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('7');
tableRow.style.display = 'table-row';

var tableRow = document.getElementById('14');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';


}

if(field.value == 'select_single' || field.value == 'select_single_radio'){

var tableRow = document.getElementById('9');
tableRow.style.display = 'table-row';


}

if(field.value == 'select_single' || field.value == 'select_single_radio'){

var tableRow = document.getElementById('21');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('22');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('23');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('11');
tableRow.style.display = 'table-row';
}

if(field.value == 'select_single' || field.value == 'select_multiple_menu'){

var tableRow = document.getElementById('24');
tableRow.style.display = 'table-row';

var tableRow = document.getElementById('25');
tableRow.style.display = 'table-row';
}

if(field.value == 'text' || field.value == 'textarea' || field.value == 'rich_editor'){

var tableRow = document.getElementById('10');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('11');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('12');
tableRow.style.display = 'table-row';

var tableRow = document.getElementById('14');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';

}

if(field.value == 'text' || field.value == 'textarea'){


var tableRow = document.getElementById('16');
tableRow.style.display = 'table-row';
}

if(field.value == 'text' || field.value == 'textarea' || field.value == 'select_single'  || field.value == 'select_single_radio' || field.value == 'select_multiple_menu' || field.value == 'select_multiple_checkbox'){

var tableRow = document.getElementById('20');
tableRow.style.display = 'table-row';
}

if( field.value == 'textarea' || field.value == 'rich_editor'){


var tableRow = document.getElementById('13');
tableRow.style.display = 'table-row';
}

/*
if(field.value == 'password'){
var tableRow = document.getElementById('12');
tableRow.style.display = 'table-row';

var tableRow = document.getElementById('14');
tableRow.style.display = 'table-row';

}
*/

if(field.value == 'date' || field.value == 'date_time'){


var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';


var tableRow = document.getElementById('11');
tableRow.style.display = 'table-row';


}

if(field.value == 'generic_file' || field.value == 'image_file' || field.value == 'camera'){


var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('16');
tableRow.style.display = 'table-row';

}

if(field.value == 'text' || field.value == 'textarea' || field.value == 'rich_editor' || field.value == 'date' || field.value == 'date_time' || field.value == 'select_single' || field.value == 'select_single_radio' || field.value == 'select_multiple_menu' || field.value == 'select_multiple_checkbox'){



	var tableRow = document.getElementById('26');
	tableRow.style.display = 'table-row';

}

}
