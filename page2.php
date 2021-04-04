<?php
    $arrival_error = array();
    $burst_error = array();
    $arrival_value = array();
    $burst_value = array();
    for ($x = 1; $x <= 10; $x++) {
        $arrival_error['arrival'.$x] = '';
        $arrival_value['arrival'.$x] = '';
        $burst_error['burst'.$x] = '';
        $burst_value['burst'.$x] = '';
    }  

    if (isset($_POST['submit'])) {
        for ($x = 1; $x <= 10; $x++) {
            if (empty($_POST['arrival'.$x])) {
                $arrival_error['arrival'.$x] = 'This field cannot be empty';
            }
            else {
                $arrival_value['arrival'.$x] = $_POST['arrival'.$x];
            }
    
            if (empty($_POST['burst'.$x])) {
                $burst_error['burst'.$x] = 'This field cannot be empty';
            }
            else {
                $burst_value['burst'.$x] = $_POST['burst'.$x];
            }
        }
    
        if (!array_filter($arrival_error) && !array_filter($burst_error)) {
            echo "FORM IS CORRECT!!!";
        }
    } 
 
?>
<html>
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
<body>
    <form action="page2.php" method="POST">
        <div class="row">
            <div class="column">
                <h4>Arrival Time</h4>
                <?php for ($x = 1; $x <= 10; $x++) { 
                    ?>
                    <label>Process <?php echo $x?>:</label>
                    <input type="number" name="<?php echo "arrival".$x?>" min = "0" max = "15"
                     value="<?php echo $arrival_value['arrival'.$x]; ?>"><br>
                     <div><?php echo $arrival_error['arrival'.$x]; ?></div><br>
                <?php } ?>
            </div>
            <div class="column">
                <h4>Burst Time</h4>
                <?php for ($x = 1; $x <= 10; $x++) { ?>
                    <label>Process <?php echo $x?>:</label>
                    <input type="number" name="<?php echo "burst".$x?>" min = "0" max = "15"
                     value="<?php echo $burst_value['burst'.$x] ?>"><br>
                     <div><?php echo $burst_error['burst'.$x]; ?></div><br>
                <?php } ?>
            </div>
        </div>
        <div class="center">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>
</body>
</html>