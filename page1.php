<?php 
session_start();
 ?>
<html>
    <head>
        <title>Home Page</title>
        <style>
/*body {background-color:black;}*/
body {
 background-image:linear-gradient(black, #01012A);
 color: white;
}
.heading
	{
	    align-content: center;
	    margin-top: 5px;
	    margin-left: 70px;
	    padding-top: 30px;
	    padding-right: 20px;
	    padding-bottom: 10px;
	    padding-left: 20px;
	    
	}
  input[type=text], select {
  color: white;
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
/*  display: inline-block;*/
  border-radius: 10px;
 /* box-sizing: border-box;*/
  background-color:#5E6075;
  border-color:#5E6075;
  opacity: 75%;
	}

.center {
  margin: auto;
  width: 30%;
  padding: 50px;
  margin-top: 60px
}

input[type=submit] {
  width: 20%;
  background-color: #181B29;
  border: 2px solid #4CAF50; 
  align-content: center;
  color:white;
  padding: 14px 20px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  margin: auto;
  margin-top: 15px;
  display: flex;
  justify-content: center;
  text-align: center;
  font-weight: 850;
}

input[type=submit]:hover {
  background-color: #BEC7FF;
  color:#00001E;
}

form {
  border-radius: 20px;
 padding-bottom: 50px
  width: 600px;
  height: 400px;
  margin-top: -20px;


}
video {
  object-fit: cover;
  width: 100vw;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
}
::placeholder
{
  color: white;
}
input[type='radio']:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #d1d3d1;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
        color: white;
    }

    input[type='radio']:checked:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #5E6075;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }
    .radio
    {
      color: white;
    }
    p
    {
    	color: white;
    	font-size: 20;
    }
</style>
    
</head>
<body>

<!-- <video id="videoBG" loop autoplay muted>
  <source src="video.webm" type="video/webm">
  <source src="video.ogg" type="video/ogg">
  <source src="video.mp4" type="video/mp4">
</video> -->
<?php 
  $error_num="";
  if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
    	$num=$_POST['number'];
    	if (!empty($num))
    	{
    		$num = $_POST['number'];
        $_SESSION["number_pro"]=$num;
        $_SESSION["algo"]=$_POST["algo"];  
    		// setcookie("number_pro", $num, time() + (86400 * 30), "/");

    		header("Location: page2.php");
    	}
    	else
    	{
    		$error_num = " Please enter number of processes";
    	}
    	
    }

 ?>
   <div class="heading">
   <img src="headd.png" alt="Girl in a jacket" style="width:1200px;height:100px;">
   <div class="center">
   <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <input type="text" id="1" name="number" placeholder="Enter number of Processes"><div><?php echo $error_num; ?>
    <br><br>
    <p>Select the algorithm to visualise:</p>
		<div class="radio">
			<input type="radio" id="fcfs" name="algo" value="fcfs" checked="true">
			<label for="fcfs">First Come First Serve</label>
			<br><br>
			<input type="radio" id="sjf" name="algo" value="srjf">
			<label for="sjf">Shortest Job First</label>
			<br><br>
			<input type="radio" id="srtf" name="algo" value="srtf">
			<label for="srtf">Shortest Remaining Time First</label>
			<br><br>
			<input type="radio" id="rr" name="algo" value="round-robin">
			<label for="rr">Round Robbin</label>
			<br><br>
		</div>
 	<input type="submit" value="SUBMIT">
	</form>
</div>      
</body>
</html>