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


<style>
  body
  {

    background: url(http://themiltonagency.com/wp-content/uploads/2013/10/Business-Insurance.jpg);
    background-size: 100%;
    background-attachment: fixed;
    background-position: 150% 0%; 
    background-repeat: no-repeat;


}
</style>







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
</body>
</html>