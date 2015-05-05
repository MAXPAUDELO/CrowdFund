<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtm11/DTD/xhtml"
<html xlmns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type content="text/html; charset=utf-8" />
	  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<title>Search Test</title>
</head>
<?php
$table = $_GET['table'];
$query = $_GET['k'];
//echo $table;

if(strcmp($table, "Project")==0) {
	//echo 'Connected to project';
    include 'projectSearch.php';
}
else if (strcmp($table, "Investor")==0) {
	//echo 'Connected to investor';
    include 'investorSearch.php';
} else {
	//echo 'Nothing';
}
//Call search
//$term = isset($_GET['query'])?$_GET['query']: '';
?>

<body>
<div class="container"> 
  <div style="background:#A3DDFC" class="jumbotron">
    <center><h1><img class="media-object" src="http://www.successionequitypartners.com/images/template/succession-equity-partners-logo.png" alt=""></h1>

      <br><p>Search from a large selection of projects and investors.</p></center></br>
    

  	<form action='./search3.php' method='get'>

		<center>

    <div class="input-group" style="width:50%">

     	<input type="text" name='k' class="form-control" id="usr" placeholder="Enter Search Criteria...">
     
      <span class="input-group-btn">
                    
        <select class="form-control" name="table">
                <option value="Project">Project</option>
                <option value="Investor">Investor</option>
            </select>
       
      <button type='submit' class="btn btn-info btn-lrg">
         <span class="glyphicon glyphicon-search" aria-hidden="true"></span>Search</button>


              </center>

            </span>
          </div>
			  </div>
  		</div>
		</form>
	</center>


<!--////////////////////////////////////////////////////////////////////////////////////////////////// -->
	
    <div class="container">


	<?php
		$search_results = search($query);
	
?>

	<?php if (!$search_results) {

		echo 'No results!';

	//  exit;
	}

	else {
		//Print project information
		
?>

<div class="panel panel-success">
	<div class="panel-heading">Results</div>
		<?php if(strcmp($table, "Project")==0){
			foreach ($search_results as $results) {
				echo ($results -> name)."<br />";
//				$url = $pics['data'][0]['images']['standard_resolution']['($results -> company_logo)'];
//				echo "<img src=\"".$url."\">"."<br />";
//				echo file_get_contents($results -> company_logo)."<br />";
//				echo ($results -> company_logo)."<br />";
				echo "Backers: ".($results -> backer_count)."<br />";
				echo "Current funding: ".($results -> current_funding)."<br />";
				echo "Target funding: ".($results -> target_funding)."<br />";
				echo "Minimum investment: ".($results -> min_invest)."<br />";
				echo "End date: ".($results -> end_date)."<br />";
				echo "<a href='".($results -> source)."'>'".($results -> source_site)."'</a>"."<br />";
				echo "<br />";
			}
		}
		//Print investor information
		else{
			foreach ($search_results as $results){
				echo ($results -> name)."<br />";
				echo "Location: ".($results -> location)."<br />";
				echo "Target market: ".($results -> target_market)."<br />";
				echo "Average investment: ".($results -> average_invested)."<br />";
				//Need to break these. Parallel arrays, profession is relation to company title
				echo ($results -> profession)."<br />";
				echo ($results -> company_title)."<br />";

				echo "<a href='".($results -> source_url)."'>'".($results -> source_site)."'</a>"."<br />";
				echo "<br />";
			}
		}
	}
	?></div>
</div>
	
</body>
</html>