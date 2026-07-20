<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2026 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>
<?php
function build_results_table_report($data, $head_labels)
// goal: build an HTML table for the pivot
// input: $data, an array containing the data to show; $head_labels, an array containing the heading labels
{
	
	global $enable_row_highlighting, $max_number_rows_pivot, $normal_messages_ar;
		
    $results_table = '<table class="results" id="results_table_pivot" cellpadding="5" cellspacing="0"><thead>';

    $results_table .= '<tr class="tr_results_header">';
    
    foreach($head_labels as $label){
        $results_table .= '<th class="results"><span class="text_heading">'.$label.'</span></th>';
    }
    
    $results_table .= '</tr></thead><tbody>';
    $tr_results_class = 'tr_results_1';
    $td_controls_class = 'controls_1';
    
    $cnt_rows = 0;

    // build the table body
    foreach($data['labels'] as $key => $label){
    
        $cnt_rows++;

        if ($tr_results_class === 'tr_results_1') {
            $td_controls_class = 'controls_2';
            $tr_results_class = 'tr_results_2';
        } // end if
        else {
            $td_controls_class = 'controls_1';
            $tr_results_class = 'tr_results_1';
        } // end else

        if (false && $enable_row_highlighting === 1) {
            $results_table .= "<tr class=\"".$tr_results_class."\" onmouseover=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_highlighted_onmouseover'}\" onmouseout=\"if (this.className!='tr_highlighted_onclick'){this.className='".$tr_results_class."'}\" onclick=\"if (this.className == 'tr_highlighted_onclick'){ this.className='".$tr_results_class."';}else{ this.className='tr_highlighted_onclick';}\">";
        } // end if
        else {
            $results_table .= "<tr class=\"".$tr_results_class."\">";
        } // end else
        
        $results_table .= "</td>";
        
        $results_table .= '<td>'.$label.'</td>'; // this is the first colum
        
        $i = 1;
        $suffix = '';
        
        // for each aggregate function I added (column 2 .... N)
        while (isset($data['datasets'][0]['data'.$suffix][$key])){
        
            $results_table .= '<td>'.$data['datasets'][0]['data'.$suffix][$key].'</td>';
            $i++;
            $suffix = '_'.$i;
        }
        
        
        $results_table .= "</tr>";
    } // end while
    
    $results_table .= "</tbody></table>";
    
    if ($cnt_rows === $max_number_rows_pivot){
        $results_table .= $normal_messages_ar['you_might_have_additional_rows_admin_set_to'].' '.$max_number_rows_pivot;
    }

    return $results_table;
    
}

?>