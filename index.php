<html lang="en"> 
<head> 
<meta charset="utf-8" /> 
<title>Bandwidth Monitor - download speeds in last 24 hours</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
<?php date_default_timezone_set('Asia/Kuala_Lumpur'); ?>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body>
<h3 style="font-family: 'Roboto', sans-serif; text-align: center">
TM UNIFI Download speeds - last 24 hours (HOME FIBER 300 mbps Package) <br/>
UTC Time <?php echo gmdate('d/m/Y H:i:s')?> NOW<br/>
Read News About IT On <a href="http://techsemut.com">Techsemut.com</a>.
</h3>
<label for="Server">Server Location Test:</label>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<select onchange="update();" name="server" id="server">
  <option <?php if ($_POST['server'] == '10327') { echo "selected"; } ?> value="10327">TM Kuala Lumpur</option>
  <option <?php if ($_POST['server'] == '8875'){ echo "selected"; } ?> value="8875">MyRAN Cyberjaya</option>
  <option <?php if ($_POST['server'] == '14565'){ echo "selected"; } ?> value="14565">UNITEN Putrajaya</option>
</select>
  <input type="submit">
</form>
<canvas id="myChart" width="1100px" height="500px"></canvas>
<script>
	
 
	var bandwidth_data = <?php
	
	class MyDB extends SQLite3 {
		function __construct() {
			$this->open('/usr/local/etc/bandwidth.db');
		}
	}
	$server = $_POST['server'];
	if (empty($server)) {
		$tserver = '10327';
	}
	else {
		$tserver = $server;
	}

	$db = new MyDB();
	if(!$db) {
		echo $db->lastErrorMsg();
	} else {
		echo "";
	}
	$sql = 'SELECT serverid, strftime("%H:%M",times) || " " || strftime("%d/%m/%Y", times) AS timestamp, sponsor, servername, download
	FROM bandwidth WHERE serverid = '.$tserver.' ORDER BY times LIMIT 24 OFFSET (SELECT COUNT(*)/3 FROM bandwidth)-24';
	
	$ret = $db->query($sql);
	if(!$ret){
		echo $db->lastErrorMsg();
	} else {
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
			$results[] = $row;
		}
	$ukdata = json_encode($results);
	}
	echo $ukdata;
	$db->close();
	?>

	;
	var bwlabels = [], bwdata = [];
	var mbps, bvalue;
	for (var i = 0; i < bandwidth_data.length ; i++){
		bwlabels.push(bandwidth_data[i].timestamp);
		// convert bps to Mbps rounded with 3 decimal places in local format
		mbps = Math.round(bandwidth_data[i].download/1000).toFixed(3)/1000;
		bvalue = mbps.toLocaleString(undefined, { minimumFractionDigits: 3 });
		bwdata.push(bvalue);
	}
	var bar_color = 'rgba(0, 128, 255, 0.9)';
	var ctx = document.getElementById("myChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: bwlabels,
			datasets: [{
				label: 'Mbps download',
				data: bwdata,
				backgroundColor: bar_color,
				borderColor: bar_color,
				borderWidth: 1
			}]
		},
		options: {
			// we override the default tooltip which drops trailing zeros even though we already put them there
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var value = data.datasets[0].data[tooltipItem.index];
						var label = 'download: ';
						var retvalue = value.toLocaleString(undefined, { minimumFractionDigits: 3 });
						return label + ' ' + retvalue + ' Mbps';
					}
				}
			},
			responsive: false,
			scales: {
				xAxes: [{
					ticks: {
						autoSkip: false,
						maxTicksLimit: 24
					}
				}],
				yAxes: [{
					ticks: {
						beginAtZero:true
					}
				}]
			}
		}
	});
</script>
</body>
</html>
