<?php  
 session_start();
?>
<html>
<head>
  <link rel='stylesheet' type='text/css' href='page1.css' />
  <title>MiniProject | Enter Time</title>
<style>
* {
  box-sizing: border-box;
  color: white;
}

 input[type=number], select {
  color: white;
  width: 20%;
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

input[type=submit]:hover {
  background-color: white;
  color:grey;
}
</style>
</head>
<body>
     <div class="heading">
      <center><img src="headd.png" alt="Title" style="width:80%"></center>
    </div>
    <?php
    $arrival_error = array();
    $burst_error = array();
    $arrival_value = array();
    $burst_value = array();
    $quant_err="";
    $quant_value="";
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
            if(empty($_POST["quantum"]))
            {
              $quant_err = "This field cannot be empty";
            } 
            else
            {
              $quant_value=$_POST["quantum"];
            }
        }
        // echo $burst_value["burst1"];
        if (!array_filter($arrival_error) && !array_filter($burst_error)) {
        	$_SESSION["arrival_array"]=$arrival_value;
        	$_SESSION["burst_array"]=$burst_value;
          $_SESSION["quantum_value"]=$quant_value;
            header("Location: visualize.php");
        }
    }
    } 
 
?>
<br><br>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <center>
          <div class="row" style="padding-left:50%; width:1000px;">
            <div class="column">
                <center><h2>Arrival Time</h2> 

                  <label>Process 1:</label>
                    <input type="number" name="arrival1" min = "0" max = "15"
                     value="<?php echo $arrival_value[1]; ?>"> <span style="color:white;"><em>secs</em></span>
                     <br>
                     <div><?php echo $arrival_error[1]; ?></div>
                    <!--  Checkbox -->
                  <input type="checkbox" name="cpya" onclick="CopyAllA(this.form,<?php echo $num?>)">
                  <em>Check this box if all Arrival times are same.</em><br>
                  <br>
                <?php for ($x = 2; $x <= $num; $x++) { 
                    ?>
                    <label>Process <?php echo $x?>:</label>
                    <input type="number" name="<?php echo "arrival".$x?>" id="<?php echo 'a'.$x?>" min = "0" max = "15"
                     value="<?php echo $arrival_value[$x]; ?>"> <span style="color:white;"><em>secs</em></span>
                     <br>
                     <div><?php echo $arrival_error[$x]; ?></div><br>
                <?php } ?>
                </center>
            </div>
            <div class="column">
                <center><h2>Burst Time</h2>
                  <label>Process 1:</label>
                    <input type="number" name="burst1" min = "0" max = "15"
                     value="<?php echo $burst_value[1]; ?>"> <span style="color:white;"><em>secs</em></span>
                     <br>
                     <div><?php echo $burst_error[1]; ?></div>
                    <!--  Checkbox -->
                  <input type="checkbox" name="cpyb" onclick="CopyAllB(this.form,<?php echo $num?>)">
                  <em>Check this box if all Burst times are same.</em><br>
                    <br>
                <?php for ($x = 2; $x <= $num; $x++) { ?>
                    <label>Process <?php echo $x?>:</label>
                    <input type="number" name="<?php echo "burst".$x?>" id="<?php echo 'b'.$x?>" min = "0" max = "15"
                     value="<?php echo $burst_value[$x] ?>"> <span style="color:white;"><em>secs</em></span> <br>
                     <div><?php echo $burst_error[$x]; ?></div><br>
                <?php } ?> 
                </center>
            </div>
            <?php if($_SESSION["algo"] == "round-robin") {?>
            <center>
              <label>Time Quantum:</label>
              <input type="number" name="quantum" min = "0" max = "15" value="<?php echo $quant_value ?>">
              <div><?php echo $quant_err; ?></div><br>
              <br>
            </center>
            <?php } ?>
            <input type="submit" name="submit" value="Submit">
          </div>
            </center>
            <br>
    </form>
</body>
<script>
  
  function CopyAllA(f,n) {
  if(f.cpya.checked == true) {
    for(var i =2; i<=n;i++)
    {
      document.getElementById("a"+i).setAttribute('value',f.arrival1.value); 
    }
  }
}
function CopyAllB(f,n) {
  if(f.cpyb.checked == true) {
    for(var i =2; i<=n;i++)
    {
      document.getElementById("b"+i).setAttribute('value',f.burst1.value); 
    }
  }
}
</script>
</html>