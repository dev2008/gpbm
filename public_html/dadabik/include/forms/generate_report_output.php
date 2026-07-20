
<script src="./include/chart_4.5.1.min.js"></script>
<script>
const chartTextColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-text-color').trim();

if (chartTextColor) {
  Chart.defaults.color = chartTextColor;
}
</script>

<script src="./include/palette.js"></script>

<!--<span id="chart">-->
<div id="chart_container"><canvas id="myChart" width="<?php echo $_GET['width_chart']; ?>" height="<?php echo $_GET['height_chart']; ?>"></canvas></div>
<!--</span>-->

<?php if (isset($_GET['show_legend']) && ( $report_type === 'pie' || $report_type === 'doughnut') ){ ?>

<!--
<style>
.legend li span {
    width: 1em;
    height: 1em;
    display: inline-block;
    margin-right: 5px;
}
.legend {
    list-style: none;    
}
</style>
-->
<?php } ?>



<!--<div id="legend" style="display: inline-block;vertical-align:top;"></div>-->

<?php if (isset($data)){ ?>

<script>
// Get the context of the canvas element we want to select
var ctx = document.getElementById("myChart").getContext("2d");
var data = <?php echo json_encode($data); ?>;

var options = 
{	

	responsive: false,
	locale: 'en-us',
	
	<?php if ( false && $report_type !== 'pie' && $report_type !== 'doughnut' ){ // old chart.js 3.x approach ?>	
scales: {
    yAxes: {
        display: true,
        suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
    }
},
<?php } elseif ($report_type !== 'pie' && $report_type !== 'doughnut' ){ ?>	
// new chart.js 4.x approach
scales: {
    y: {
        display: true,
        suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
    }
},
<?php }  ?>	
	plugins: {

	tooltip: {
			//bodyFontSize: 100,
 <?php if ( $_GET['report_input_type'] == 'simple' && isset($_GET['group_by_operator']) && $_GET['group_by_operator'] === 'percentage' ){ ?>
 
              callbacks: {
                  //label: function(tooltipItem, data) {
                  label: function(context) {
                      //var value = data.datasets[0].data[tooltipItem.index];
                      //var label = data.labels[tooltipItem.index];
                      var value = context.dataset.data[context.dataIndex];
                      var label = context.label;
                      return label + ': ' + value + '%';
                  }
              }
 <?php } ?>
              
          },
          
<?php if (  $report_type === 'pie' || $report_type === 'doughnut' ){ ?>	
legend: {
position: 'right'
},
<?php } else{ ?>
legend: {
display: false
},
<?php } ?>	
  


} // end plugins
} // end options
<?php if ( $report_type !== 'line' && $report_type !== 'bar'){ ?> 
		data.datasets[0].backgroundColor = palette('tol-rainbow', data.datasets[0].data.length).map(function(hex) {
	return '#' + hex;
})
<?php } ?>

<?php if (  $report_type === 'bar'){ ?> 
	data.datasets[0].backgroundColor = ['<?= implode("','", $barchart_bin_colors) ?>'];
<?php } ?>

var myChart = new Chart(ctx, {
    type: '<?php echo $report_type; ?>',
    data: data,
    options: options
});

<?php if (isset($_GET['show_labels']) && ( $report_type === 'pie' || $report_type === 'doughnut') ){ ?>

	// hack to show tooltip as labels again
	/*
	$("#myChart").on('mouseleave', function (){
		myChart.showTooltip(myChart.segments, true);
	});
*/
<?php } ?>


<?php if (isset($_GET['show_legend']) && ( $report_type === 'pie' || $report_type === 'doughnut') ){ ?> 

	//var legend = myChart.generateLegend();
	//$("#legend").html(legend);

<?php } ?>

</script>

<?php } ?>