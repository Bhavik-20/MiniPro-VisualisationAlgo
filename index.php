<?php 
session_start();
 ?>
<html>
    <head>
        <title>MiniProject | Home Page</title>
        <link rel='stylesheet' type='text/css' href='page1.css' />
        <style>
/*body {background-color:black;}*/
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

</style>
    
</head>
<body>

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
  		<center> <img src="headd.png" alt="Title" style="width:80%;"></center>
	</div>
	<center>
   <div class="center">
   <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <input type="text" id="1" name="number" placeholder="Enter number of Processes"><div><?php echo $error_num; ?>
    <br>
    <em>Note: The Visualisation will Run Best for 15 - 20 Processes.</em>
    <br><br>
    <p>Select the algorithm to visualise:</p>
		<div class="radio" style="width:fit-content;text-align-last:left;">
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
</center>    
</body>
</html>