<?php  
 session_start();
?>
<html>
<style>
* {
  box-sizing: border-box;
  color: white;
}
body
{
	background-image:linear-gradient(black, #01012A);
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  align-content: center;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  clear: both;
  margin-left:60px;
  align-items: center;
}
 input[type=number], select {
  color: white;
  width: 10%;
  padding: 12px 20px;
  margin: 8px 0;
  border-radius: 10px;
  background-color:#5E6075;
  border-color:#5E6075;
  opacity: 75%;
}
input[type=submit] {
  width: 10%;
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
.heading
{
    align-content: center;
    margin-top: 5px;
    margin-left: 40px;
    padding-top: 30px;
    padding-right: 20px;
    padding-bottom: 10px;
    padding-left: 20px;
    
}

input[type=submit]:hover {
  background-color: white;
  color:grey;
}
</style>
<body>
     <div class="heading">
    <img src="headd.png" alt="Girl in a jacket" style="width:1200px;height:100px;">
    <?php
    $arrival_error = array();
    $burst_error = array();
    $arrival_value = array();
    $burst_value = array();
    $num=$_SESSION["number_pro"];
    for ($x = 1; $x <= $num; $x++) {
        $arrival_error[$x] = '';
        $arrival_value[$x] = '';
        $burst_error[$x] = '';
        $burst_value[$x] = '';
    }  
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
    if (isset($_POST['submit'])) {
    	
        for ($x = 1; $x <= $num; $x++) {
            if (empty($_POST["arrival".$x])) {
                $arrival_error[$x] = 'This field cannot be empty';
            }
            else {
                $arrival_value[$x] = $_POST["arrival".$x];
            }
    
            if (empty($_POST["burst".$x])) {
                $burst_error[$x] = 'This field cannot be empty';
            }
            else {
                $burst_value[$x] = $_POST["burst".$x];
            }
        }
        // echo $burst_value["burst1"];
        if (!array_filter($arrival_error) && !array_filter($burst_error)) {
        	$_SESSION["arrival_array"]=$arrival_value;
        	$_SESSION["burst_array"]=$burst_value;
            header("Location: index.php");
        }
    }
    } 
 
?>
</div>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <div class="row">
            <div class="column">
                <center><h4>Arrival Time</h4>
                <?php for ($x = 1; $x <= $num; $x++) { 
                    ?>
                    <label>Process <?php echo $x?>:</label>
                    <input type="number" name="<?php echo "arrival".$x?>" min = "0" max = "15"
                     value="<?php echo $arrival_value[$x]; ?>"><br>
                     <div><?php echo $arrival_error[$x]; ?></div><br>
                <?php } ?>
                </center>
            </div>
            <div class="column">
                <center><h4>Burst Time</h4>
                <?php for ($x = 1; $x <= $num; $x++) { ?>
                    <label>Process <?php echo $x?>:</label>
                    <input type="number" name="<?php echo "burst".$x?>" min = "0" max = "15"
                     value="<?php echo $burst_value[$x] ?>"><br>
                     <div><?php echo $burst_error[$x]; ?></div><br>
                <?php } ?>
                </center>
            </div>
        </div>
            <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>