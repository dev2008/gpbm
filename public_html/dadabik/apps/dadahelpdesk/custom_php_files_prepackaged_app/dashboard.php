<h1>Manager's Dashboard</h1>
<table width="100%">
<tr><td colspan="2">

<h2>Latest requests</h2><br>
<iframe src="index.php?tablename=requests&function=search&records_per_page=10&onlygrid=1&order=date_time_request&order_type=DESC&records_per_page=5&dont_consider_where_clause=1" style="border:0;" width="100%" height="250"></iframe>
</td>
</tr>

<tr><td colspan="2">

<h2>More urgent unsolved requests</h2><br>
<iframe src="index.php?tablename=unsolved_requests&function=search&where_clause=&page=0&order=expected_solution_date_request&order_type=ASC&onlygrid=1&order=expected_solution_date_request&order_type=ASC&records_per_page=5&dont_consider_where_clause=1" style="border:0;" width="100%" height="250"></iframe>
</td>
</tr>



<tr><td>
<h2>Requests by status</h2>
<iframe src="index.php?tablename=requests&function=generate_report&show_report_result=1&report_input_type=simple&report_type=pie&width_chart=400&height_chart=400&date_function=&group_by_field=%60requests%60.%60status_request%60&group_by_operator=count&report_value_field=%60requests%60.%60subject_request%60&sql_report=&only_graph=1&dont_consider_where_clause=1" style="border:0;" width="450" height="450"></iframe>
</td>
<td>

<h2>Requests by staff member</h2>
<iframe src="index.php?tablename=requests&function=generate_report&show_report_result=1&report_input_type=advanced&report_type=bar&width_chart=400&height_chart=400&show_labels=on&date_function=&group_by_field=%60requests%60.%60subject_request%60&group_by_operator=percentage&report_value_field=%60requests%60.%60subject_request%60&sql_report=select+username_user%2C+count%28*%29+from+requests+a+inner+join+dadabik_users+b+on+a.it_staff_member_request+%3D+b.id_user+group+by+it_staff_member_request&only_graph=1&dont_consider_where_clause=1" style="border:0;" width="400" height="400"></iframe>

</td>
</tr>
</table>






