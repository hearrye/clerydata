<?php
include_once('include.php');

if (isset($_GET['id'])){
	$school_id = $_GET['id'];
} else {
	if (isset($_GET['school_name'])){
		$schoolSearch = '\' +'.str_replace(' ', '* +', $_GET['school_name']).'*\'';
		$result = $db->prepare("SELECT id,name FROM schools WHERE MATCH (name) AGAINST (? IN BOOLEAN MODE) LIMIT 1");
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

echo '<div style="text-align: center;">'."\n";

echo '<h3>'.$school_name.'</h3>'."\n";
echo '<table style="margin-top: 0.75em; margin-left: auto; margin-right: auto;">'."\n";
echo '<tr><th>Year</th><th># of reports</th><th>Expected # of reports</th></tr>'."\n";
$result = $db->prepare("SELECT year, SUM(total_incidents), women_total AS numReports FROM reported_data WHERE INSTNM=? GROUP BY year");
$result->bind_param('s', $school_name);
$result->execute();
$result->store_result();
$result->bind_result($r_year, $numReports, $numWomen);
while ($result->fetch()){
	echo '<tr><td>'.$r_year.'</td><td>'.$numReports.'</td><td>'.round($numWomen/5/4).'</td>'."\n";
}
$result->free_result();
echo '</table>';

echo '</div>'."\n";

fin();
?>