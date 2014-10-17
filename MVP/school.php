<?php
include_once('include.php');

if (isset($_GET['id'])){
	$school_id = $_GET['id'];
} else {
	if (isset($_GET['school_name'])){
		$schoolSearch = '%'.$_GET['school_name'].'%';
		$result = $db->prepare("SELECT id,name FROM schools WHERE name LIKE ? LIMIT 1");
		$result->bind_param('s', $schoolSearch);
		$result->execute();
		$result->store_result();
		$result->bind_result($school_id,$school_name);
		if ($result->num_rows > 0){
			$result->fetch();
		} else {
			fin('Invalid page');
		}
		$result->free_result();
	} else {
		fin('Invalid page.');
	}
}
?>
<div style="text-align: center;">
	<h2><?php echo $school_name; ?></h2>
	<div style="margin-top: 0.8em; text-align: center; font-size: 1.5em;"><span id="reportingGap" style="color: #FF6666;">0.0%</span> reporting gap</div>
	<div id="dynamicDataDisplay" style="position: relative; height: 260px; width: 800px; margin-left: auto; margin-right: auto; margin-top: 0.5em;"></div>
<?php
$reportTotal = 0;
$estTotal = 0;

$schoolData = 'year,reports,expected'."\n";
echo '<table style="margin-top: 0.75em; margin-left: auto; margin-right: auto;">'."\n";
echo '<tr><th>Year</th><th># of reports</th><th>Expected # of reports</th></tr>'."\n";
$result = $db->prepare("SELECT year, SUM(total_incidents), women_total AS numReports FROM reported_data WHERE INSTNM=? AND on_or_off_campus='Total on or off campus' GROUP BY year");
$result->bind_param('s', $school_name);
$result->execute();
$result->store_result();
$result->bind_result($r_year, $numReports, $numWomen);
while ($result->fetch()){
	echo '<tr><td>'.$r_year.'</td><td>'.$numReports.'</td><td>'.round($numWomen/5/4).'</td>'."\n";
	$schoolData .= $r_year.','.$numReports.','.round($numWomen/5/4)."\n";
	
	$reportTotal += $numReports;
	$estTotal += round($numWomen/5/4);
}
$avgEst = $estTotal/($result->num_rows);
$avgReport = $reportTotal/($result->num_rows);
$result->free_result();
echo '</table>';

echo '</div>'."\n";
?>
<div class="chart" style="margin-top: 1em; margin-left: auto; margin-right: auto; width: 600px; border: 1px solid #999999;"></div>
<script type="text/javascript">
	var timerVar;
	
	if (window.addEventListener){
		window.addEventListener('load', function(){ timerVar = window.setInterval(addDataPt, 3); }, false);
	} else if (window.attachEvent){
		window.attachEvent('onload', function(){ timerVar = window.setInterval(addDataPt, 3); });
	}
	
	var dDD = document.getElementById('dynamicDataDisplay');
	var rG = document.getElementById('reportingGap');
	var curDataPt = 1;
	var curX = 1;
	var curY = 1;
	
	function addDataPt(){
		if (curDataPt <= avgEst){
			var ptX = 10*curX;
			var ptY = 250 - 10*curY;
			
			
			
			var datPt = document.createElement('div');
			datPt.className = "dataPt";
			if (Math.random() <= (repLeft/(avgEst - curDataPt))){
				datPt.className += " act";
				ptX -= 1;
				ptY -= 1;
				repLeft -= 1;
			} else {
				datPt.className += " est";
			}
			datPt.style.left = ptX + 'px';
			datPt.style.top = ptY + 'px';
			dDD.appendChild(datPt);
			
			rG.innerHTML = (Math.round((curDataPt/avgEst) * reportGap * 1000)/10) + '%';
			
			curDataPt += 1;
			if ((curDataPt-1)%25 == 0){
				curX += 1;
				curY = 1;
			} else {
				curY += 1;
			}
		} else {
			clearInterval(timerVar);
		}
		
		dDD.style.width = (curX*10 + 10) + 'px';
	}
	
	var schoolData = <?php echo json_encode($schoolData); ?>;
	var avgEst = parseInt(<?php echo json_encode($avgEst); ?>);
	var avgReport = parseInt(<?php echo json_encode($avgReport); ?>);
	var repLeft = parseInt(<?php echo json_encode($avgReport); ?>);
	var avgRatio = Math.round(avgEst/avgReport);
	var reportGap = 1 - avgReport/avgEst;

	var margin = {top: 20, right: 20, bottom: 30, left: 40},
		width = 600 - margin.left - margin.right,
		height = 400 - margin.top - margin.bottom;

	var x = d3.scale.ordinal()
		.rangeRoundBands([0, width], .1);

	var y = d3.scale.linear()
		.range([height, 0]);

	var xAxis = d3.svg.axis()
		.scale(x)
		.orient("bottom");

	var yAxis = d3.svg.axis()
		.scale(y)
		.orient("left")

	var svg = d3.select(".chart").append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
	  .append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	var csvData = d3.csv.parse(schoolData, type);

	x.domain(csvData.map(function(d) { return d.year; }));
	y.domain([0, d3.max(csvData, function(d) { return d.reports; })]);

	svg.append("g")
	  .attr("class", "x axis")
	  .attr("transform", "translate(0," + height + ")")
	  .call(xAxis);

	svg.append("g")
	  .attr("class", "y axis")
	  .call(yAxis)
	.append("text")
	  .attr("transform", "rotate(-90)")
	  .attr("y", 6)
	  .attr("dy", ".71em")
	  .style("text-anchor", "end")
	  .text("# of reports");

	svg.selectAll(".bar")
	  .data(csvData)
	.enter().append("rect")
	  .attr("class", "bar")
	  .attr("x", function(d) { return x(d.year); })
	  .attr("width", x.rangeBand())
	  .attr("y", function(d) { return y(d.reports); })
	  .attr("height", function(d) { return height - y(d.reports); });

	function type(d) {
	  d.reports = +d.reports;
	  return d;
	}
</script>
<?php
fin();
?>