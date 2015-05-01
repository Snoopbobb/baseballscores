<?php 

// Setup variables for use later
$output = NULL;
$headline = NULL;
$xml = NULL;

// Setup form with datepicker if no submission otherwise setup parse data from Get array for xml and h1 at top of page
if ($_GET['date'] === NULL) {
	$output = "<h1>Select a date to check MLB final scores</h1>
				<form action=\"\">
					<div class=\"form-group\">
			   			<input type=\"date\" name=\"date\" min=\"2005-04-01\">
						<button>Submit</button>
					</div>
			   </form>";
} else {
	// Keyword from form submission
	$date = explode("-", $_GET['date']);
	$year = $date[0];
	$month = $date[1];
	$day = $date[2];
	$xml = simplexml_load_file("http://gd2.mlb.com/components/game/mlb/year_$year/month_$month/day_$day/scoreboard.xml");

	// switch statement to substitute month number for month name used in headline
	switch ($month) {
		case $month == 1:
			$month = 'January';
			break;
		case $month == 2:
			$month = 'February';
			break;
		case $month == 3:
			$month = 'March';
			break;
		case $month == 4:
			$month = 'April';
			break;
		case $month == 5:
			$month = 'May';
			break;
		case $month == 6:
			$month = 'June';
			break;
		case $month == 7:
			$month = 'July';
			break;
		case $month == 8:
			$month = 'August';
			break;
		case $month == 9:
			$month = 'September';
			break;
		case $month == 10:
			$month = 'October';
			break;
		case $month == 11:
			$month = 'November';
			break;
		case $month == 12:
			$month = 'December';
			break;
	}
	$headline = "<h1>MLB Final Scores for $month $day, $year</h1>";
}

	// setup variables for previous day and next day buttons
	$date1 = str_replace('-', '/', $_GET['date']);
	$previous = date('Y-m-d',strtotime($date1 . "-1 days"));
	$next = date('Y-m-d',strtotime($date1 . "+1 days"));
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Baseball Scores</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    	body, html {
    		width: 100%;
    		box-sizing: border-box;
    	}
    	h1 {
    		text-align: center;
    		margin: 20px;
    	}
		table, th, td {
   			border: 1px solid black;
   			
		}
		.wrapper {
			text-align: center;
			width: 50%;
			margin: 0 auto;
		}
		form {
			text-align: center;
		}
		a {
			width: 100px;
			min-height: 80px;
			background-color: green;
			color: white;
			font-weight: bolder;
			border-radius: 5px;
			padding: 10px;
			text-decoration: none;
		}
		a:hover {
			color: green;
			background-color: lightgreen;
			text-decoration: none;
		}
		.table {
			width: 80%;
			margin: 10px auto;
		}
		.table-header {
			background-color: lightgreen;
		}
		.blank_row {
    		height: 15px !important; /* Overwrite any previous rules */
    		background-color: #FFFFFF;
		}
		.winner {
			background-color: #eaeaea;
			font-weight: bold;
		}
    </style>
  </head>
  <body>
  	<!-- If there is output it will be the date picker form otherwise NULL -->
	<?php echo $output; ?>
	<?php echo $headline; ?>
	<div class="wrapper">
		<a href="<?php echo "?date=$previous"; ?>"><< Previous Day's Scores</a>
		<a href="<?php echo "?date=$next"; ?>">Next Day's Scores >></a>
		<table class="table">
		<?php
			for ($g=0; $g < 30; $g++) {
				$check = $xml->go_game[$g];
				if(!empty($check)) {
					echo $check;
					for ($i=0; $i < 1; $i++) { 
						$team_name1 = $xml->go_game[$g]->team[$i]['name'];

						$runs1 = $xml->go_game[$g]->team[$i]->gameteam['R'];

						$hits1 = $xml->go_game[$g]->team[$i]->gameteam['H'];

						$errors1 = $xml->go_game[$g]->team[$i]->gameteam['E'];

						$team_name2 = $xml->go_game[$g]->team[$i + 1]['name'];

						$runs2 = $xml->go_game[$g]->team[$i + 1]->gameteam['R'];

						$hits2 = $xml->go_game[$g]->team[$i + 1]->gameteam['H'];

						$errors2 = $xml->go_game[$g]->team[$i + 1]->gameteam['E'];

						if (intval($runs1) > intval($runs2)) {
							echo "
								<tr class=\"table-header\">
									<th>Team</th>
									<th>Runs</th>
									<th>Hits</th>
									<th>Errors</th>
								</tr>
								<tr>
									<td>$team_name2</td>
									<td>$runs2</td>
									<td>$hits2</td>
									<td>$errors2</td>
								</tr>
								<tr class=\"winner\">
									<td>$team_name1</td>
									<td>$runs1</td>
									<td>$hits1</td>
									<td>$errors1</td>
								</tr>
								<tr class=\"blank_row\">
								</tr>";					
						} else {
							echo "
								<tr class=\"table-header\">
									<th>Team</th>
									<th>Runs</th>
									<th>Hits</th>
									<th>Errors</th>
								</tr>
								<tr class=\"winner\">
									<td>$team_name2</td>
									<td>$runs2</td>
									<td>$hits2</td>
									<td>$errors2</td>
								</tr>
								<tr>
									<td>$team_name1</td>
									<td>$runs1</td>
									<td>$hits1</td>
									<td>$errors1</td>
								</tr>
								<tr class=\"blank_row\">
								</tr>";	
						}
					}
				} else {
					break;
				}
			}
		?>
		</table>
		<a href="<?php echo "?date=$previous"; ?>"><< Previous Day's Scores</a>
		<a href="<?php echo "?date=$next"; ?>">Next Day's Scores >></a>
	</div>
	<!-- <h1><?php echo htmlentities($xml->go_game[0]->team[0]['name']); ?></h1>
	<h2><?php echo $xml->go_game[0]->gameteam; ?></h2>
	<h1><?php echo $xml->go_game[0]->team[1]['name']; ?></h1> -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  </body>
</html>