<?php 
session_start();
$burst = $_SESSION["burst_array"];
$arrival = $_SESSION["arrival_array"];
$num = $_SESSION["number_pro"];
$algo = $_SESSION["algo"];
$quantum = $_SESSION["quantum_value"];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MiniProject | Visualize</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="index.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #navbarSupportedContent{
                color: white;
        }
        table, th, td 
        {
          border: 1px solid black;
          border-collapse: collapse;
        }

        th,td{
          padding: 3px;
          text-align: left;
        }
        .hidden {
  display: none;
}
    </style>

    <!-- Canvas JS -->
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  </head>
  <body>
    <!-- Header -->
    <nav class="navbar sticky-top navbar-expand navbar-dark bg-dark" >
    <a class="navbar-brand" href="#" style="padding-left: 10px;"> Mini Project - Visualising CPU Scheduling Algorithms</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="index.php" >Select a different process</a>
        <!-- style="color:white;" -->
      </li>
  </div>
  </nav>

    <div class="container-fluid">
      <div class="row">
        <!-- Left Bar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
          <div class="position-sticky pt-3">
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="border-bottom" style="padding: 5px;">
              <h2> Performance </h2>
              </div>
              <div style="padding: 20px">
                Legend:
                <table style="width:100%"> 
                  <tr>
                    <td> PID </td>
                    <td> Process ID </td>
                  </tr>
                  <tr>
                    <td> AT </td>
                    <td> Arrival Time </td>
                  </tr>
                  <tr>
                    <td> BT </td>
                    <td> Burst Time </td>
                  </tr>
                  <tr>
                    <td> WT </td>
                    <td> Waiting Time </td>
                  </tr>
                  <tr>
                    <td> TAT </td>
                    <td> Turn Around Time </td>
                  </tr>

                </table>
              </div>
              
              <div style="padding: 10px">
              <button type="button" id="performance" class="btn btn-success">Show/Hide Performance</button>
            </div>
         
              <div id="displaytable" class="hidden" style="padding: 10px">
                <table style="width:100%;">
                    <tr>
                      <th>PID</th>
                      <th>AT</th> 
                      <th>BT</th>
                      <th>WT</th>
                      <th>TAT</th>
                    </tr>
                    <?php for($i=1;$i<=$num;$i++){ ?>
                    <tr>
                      <td id="<?php echo 'P'.$i; ?>"></td>
                      <td id="<?php echo 'at'.$i; ?>"></td>
                      <td id="<?php echo 'bt'.$i; ?>"></td>
                      <td id="<?php echo 'wt'.$i; ?>"></td>
                      <td id="<?php echo 'tat'.$i; ?>"></td>
                    </tr>
                  <?php } ?>
                </table>
                <br><p id="avg_wt"></p>
                <p id="avg_tat"></p><br>
              </div>
            </div>
          </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2" id="algo" >CPU Scheduling Algorithm</h1>
            <div class="btn-group me-1" id="tq">
                <!-- Time Quantum - 2 seconds -->
              </div>
            <div class="btn-toolbar" id="timer" style="padding-top: 5px;">
              Current Time - 0 seconds
              </div>
            <button type="button" id="regenerate" class="btn btn-success"> <i class="fa fa-play" style="margin-right: 5px"></i> Run</button>
                        
          </div>

          <div class="my-4 w-100" width="900" style='height: 700px;' id="chartContainer">
          </div>
        
        </main>
      </div>
    </div>
    <script >
      var chart, total, max, min, queue, processes, quant = 2;
      var script_arrival;
      var script_burst ;
      var labels = [];
      
        //Process class to store data of a process
        class Process{
            constructor(idx, arrival, burst){
                this.id             = idx;
                this.arrivalTime    = arrival;
                this.burstTime      = burst;
                this.consumedTime   = 0;
            }
        }

        //Process Queue to store all the processes
        //that are available at a given time for scheduling
        class Queue{
            constructor(){
                this.items = [];
            }

            isEmpty(){
                return this.items.length == 0;
            }

            enqueue(element){
                this.items.push(element);
            }

            dequeue(){
                if(this.isEmpty())
                    return -1;
                return this.items.shift();
            }

            front(){
                if(this.isEmpty())
                    return -1;
                return this.items[0];
            }
        }

        //Random Processes generator and Chart Initializer
        function generateProcesses() {
            document.getElementById("timer").innerHTML = "Current Time - " + 0 + " seconds";
            [min, max] = [15, 20];
            total = <?php echo $num ?>;
            script_arrival = <?php echo json_encode($arrival); ?>;
            script_burst = <?php echo json_encode($burst); ?>;
             // Math.floor(Math.random() * (max - min + 1)) + min;
            chart = new CanvasJS.Chart("chartContainer", {
                axisX:{
                    title: "---Arrival Time-->"
                },
                axisY:{
                    title: "---Burst Time-->",
                    minimum: 0,
                    maximum: 20,
                    interval: 1
                },
                data: [
                    {
                        type: "stackedColumn",
                        color: "blue",
                        legendText: "ToBeProcessed",
                        showInLegend: "true",
                        dataPoints: []
                    },
                    {
                        type: "stackedColumn",
                        color: "#33ff00",
                        legendText: "Processed",
                        showInLegend: "true",
                        dataPoints: []
                    }
                ]
            });
            chart.render();

            
            for(var i=1; i <=total; i++)
            {
                labels[i] = "P"+i;
            }

            [min, processes]   = [1, []];
            // Sort
            for(var i = 1 ; i < total  ; i++)
            {
              for(var j = i+1 ; j <= total ; j++)
              {
                  if (script_arrival[i] > script_arrival[j])
                { 
                  const temp = script_arrival[i];
                  script_arrival[i] = script_arrival[j];
                  script_arrival[j] = temp;

                  const temp2 = script_burst[i];
                  script_burst[i] = script_burst[j];
                  script_burst[j] = temp2;

                  const temp3 = labels[i];
                  labels[i] = labels[j];
                  labels[j]=temp3;
                }
              }
            }
            for(var i=1; i<=total; i++){
                var burst   = parseInt(script_burst[i],10);
                var arrival = parseInt(script_arrival[i],10);
                var process = new Process(i-1, arrival, burst);
                min         = arrival+1;

                processes.push(process);
                // chart.data[1].dataPoints.push({label: labels[i], y: 0});
                // chart.data[0].dataPoints.push({label: labels[i], y: burst});
                chart.data[1].dataPoints.push({label: labels[i]+"(" + arrival + ")", y: 0});
                chart.data[0].dataPoints.push({label: labels[i]+"(" + arrival + ")", y: burst});
            }
            chart.render();
            
        }

        function printStatsFCFS(){
            var wt=[];
            var tat=[];
            wt[1] = 0;
            for (var  i = 2; i <= total; i++ )
            {
              wt[i] =  parseInt(script_burst[i-1]) + parseInt(wt[i-1]) ;
            }
          for(var i = 1; i<=total;i++)
          {
            tat[i] = parseInt(script_burst[i]) + parseInt(wt[i]);
            document.getElementById("P"+i).innerHTML=labels[i];
            document.getElementById("at"+i).innerHTML=script_arrival[i];
            document.getElementById("bt"+i).innerHTML=script_burst[i];
            document.getElementById("wt"+i).innerHTML=wt[i];
            document.getElementById("tat"+i).innerHTML=tat[i];
          }
          const sum_wt = wt.reduce((a, b) => a + b, 0);
          const sum_tat = tat.reduce((a, b) => a + b, 0);
          var avg_wt=sum_wt/total;
          var avg_tat=sum_tat/total;
          avg_wt = avg_wt.toFixed(4);
          avg_tat = avg_tat.toFixed(4);// round to 4 dec places
          document.getElementById("avg_tat").innerHTML="Average TAT: "+avg_tat;
          document.getElementById("avg_wt").innerHTML="Average WT: "+avg_wt;
        }

        function printStatsRR()
        {
            var wt=[];
            var tat=[];
            wt[1] = 0;
            var rem_bt=[];
            for (var i = 1 ; i <=total ; i++)
            {
                rem_bt[i] = parseInt(script_burst[i]);
            }
          
            var t = 0; // Current time
          
            while (1)
            {
                var done = true;

                for (var i = 1 ; i <=total; i++)
                {
                    if (rem_bt[i] > 0)
                    {
                        done = false;
          
                        if (parseInt(rem_bt[i]) > parseInt(quant))
                        {
                            t += parseInt(quant);
                            rem_bt[i] -= parseInt(quant);
                        }

                        else
                        {
                            t = t + parseInt(rem_bt[i]);
                            wt[i] = t - parseInt(script_burst[i]);
                            rem_bt[i] = 0;
                        }
                    }
                }
                if (done == true)
                  break;
            }
    
          
            for (var i = 1; i <= total ; i++)
            {
                tat[i] = parseInt(script_burst[i]) + parseInt(wt[i]);
            }

            for(var i = 1; i<=total;i++)
            {
              tat[i] = parseInt(script_burst[i]) + parseInt(wt[i]);
              document.getElementById("P"+i).innerHTML=labels[i];
              document.getElementById("at"+i).innerHTML=script_arrival[i];
              document.getElementById("bt"+i).innerHTML=script_burst[i];
              document.getElementById("wt"+i).innerHTML=wt[i];
              document.getElementById("tat"+i).innerHTML=tat[i];
            }
            const sum_wt = wt.reduce((a, b) => a + b, 0);
            const sum_tat = tat.reduce((a, b) => a + b, 0);
            var avg_wt=sum_wt/total;
            var avg_tat=sum_tat/total;
            avg_wt = avg_wt.toFixed(4);
            avg_tat = avg_tat.toFixed(4);// round to 4 dec places
            document.getElementById("avg_tat").innerHTML="Average TAT: "+avg_tat;
            document.getElementById("avg_wt").innerHTML="Average WT: "+avg_wt; 

        }



        //A helper function for FCFS and round-robin alortihm
        function fcfsHelper(){
            var front = queue.front();
            if(front.burstTime > front.consumedTime){
                wait = Math.ceil(1000/quant);
                front.consumedTime += quant;
                front.consumedTime  = (front.consumedTime > front.burstTime ? front.burstTime :front.consumedTime);
                chart.data[0].dataPoints[front.id].y = front.burstTime-front.consumedTime;
                chart.data[1].dataPoints[front.id].y = front.consumedTime;
                chart.render();
            }
            queue.front().consumedTime = front.consumedTime;
            return queue.front();
        }

        function printStatsSJF(){
             var rt=[];
             var wt=[];
             var tat=[];
             for (var i = 1; i <=total; i++)
             {
                rt[i] = parseInt(script_burst[i]);
             }
             var complete = 0;
             var t = 0;
             var minm = 9999;
             var shortest = 0;
             var finish_time;
             var check = false;
             while (complete != total) {
                for (var j = 1; j <= total; j++) {
                   if ((parseInt(script_arrival[j]) <= t) && (rt[j] < minm) && rt[j] > 0) {
                      minm = rt[j];
                      shortest = j;
                      check = true;
                   }
                }
                if (check == false) {
                   t=t+1;
                   continue;
                }
                // decrementing the remaining time
                rt[shortest]=rt[shortest]-1;
                minm = rt[shortest];
                if (minm == 0)
                   minm = 9999;
                   // If a process gets completely
                   // executed
                   if (rt[shortest] == 0) {
                      complete=complete+1;
                      check = false;
                      finish_time = t + 1;
                      // Calculate waiting time
                      wt[shortest] = finish_time - parseInt(script_burst[shortest]) - parseInt(script_arrival[shortest]);
                      if (wt[shortest] < 0)
                         wt[shortest] = 0;
                   }
                   // Increment time
                   t++;
             }
            
            for(var i = 1; i<=total;i++)
            {
              tat[i] = parseInt(script_burst[i]) + parseInt(wt[i]);
              document.getElementById("P"+i).innerHTML=labels[i];
              document.getElementById("at"+i).innerHTML=script_arrival[i];
              document.getElementById("bt"+i).innerHTML=script_burst[i];
              document.getElementById("wt"+i).innerHTML=wt[i];
              document.getElementById("tat"+i).innerHTML=tat[i];
            }
            const sum_wt = wt.reduce((a, b) => a + b, 0);
            const sum_tat = tat.reduce((a, b) => a + b, 0);
            var avg_wt=sum_wt/total;
            var avg_tat=sum_tat/total;
            avg_wt = avg_wt.toFixed(4);
            avg_tat = avg_tat.toFixed(4);// round to 4 dec places
            document.getElementById("avg_tat").innerHTML="Average TAT: "+avg_tat;
            document.getElementById("avg_wt").innerHTML="Average WT: "+avg_wt;  
        }

        function printStatsSRTF(){
            var wt=[];
            var tat=[];
            
            var rt=[];
               
                
              for (var i = 0; i <=total ; i++)
                  rt[i] = parseInt(script_burst[i]);
             
              var complete = 0;
              var t = 0;
              var minm = 9999;
              var shortest = 0;
              var finish_time;
              var check = false;
             
              while (complete != total) {
                  for (var j = 0; j <=total; j++) 
                  {
                      if ((parseInt(script_arrival[j]) <= t) &&(rt[j] < minm) && rt[j] > 0) {
                          minm = rt[j];
                          shortest = j;
                          check = true;
                      }
                  }
             
                  if (check == false) {
                      t++;
                      continue;
                  }
             
                  rt[shortest]--;
             
                  minm = rt[shortest];
                  if (minm == 0)
                      minm = 9999;
             
                  if (rt[shortest] == 0) {
             
                      complete++;
                      check = false;
             
                      finish_time = t + 1;
             
                      
                      wt[shortest] = finish_time - parseInt(script_burst[shortest]) - parseInt(script_arrival[shortest]);
             
                      if (wt[shortest] < 0)
                          wt[shortest] = 0;
                  }
                  
                  t++;
              }
               
            
                
                for (var i = 1; i <= total; i++)
                    tat[i] = parseInt(script_burst[i]) + wt[i];
            

             for(var i = 1; i<=total;i++)
            {
              document.getElementById("P"+i).innerHTML=labels[i];
              document.getElementById("at"+i).innerHTML=script_arrival[i];
              document.getElementById("bt"+i).innerHTML=script_burst[i];
              document.getElementById("wt"+i).innerHTML=wt[i];
              document.getElementById("tat"+i).innerHTML=tat[i];
            }
            const sum_wt = wt.reduce((a, b) => a + b, 0);
            const sum_tat = tat.reduce((a, b) => a + b, 0);
            var avg_wt=sum_wt/total;
            var avg_tat=sum_tat/total;
            avg_wt = avg_wt.toFixed(4);
            avg_tat = avg_tat.toFixed(4);// round to 4 dec places
            document.getElementById("avg_tat").innerHTML="Average TAT: "+avg_tat;
            document.getElementById("avg_wt").innerHTML="Average WT: "+avg_wt;
        }


        //Implementation of First Come First Serve Algortihm
        function firstComeFirstServe(){
            
            quant = 2;
            queue = new Queue();
            queue.enqueue(processes[0]);
            var [i, time] = [1, processes[0].arrivalTime];
            document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";
            (function traverse() {
                if(!queue.isEmpty()){
                    var wait = Math.ceil(1000/quant);
                    setTimeout(function() {
                        var front = queue.front();
                        time += Math.min(front.burstTime-front.consumedTime, quant);
                        document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";

                        while(i<processes.length && processes[i].arrivalTime<=time)
                            queue.enqueue(processes[i++]);

                        front = fcfsHelper();
                        if(front.burstTime == front.consumedTime){
                            wait = 0;
                            queue.dequeue();
                        }
                        traverse();
                    }, wait);
                }
            })();
            
        }

      
        //Implementation of Shortest Job First Algortihm
        function shortestJobFirst(){
            quant = 2;
            var time        = 0;
            var processed   = new Set();
            queue           = new Queue;
            while(processed.size<processes.length){
                var i   = 0;
                var min = -1;
                while(i<processes.length && processes[i].arrivalTime<=time){
                    if((min==-1 || min.burstTime>processes[i].burstTime) && !processed.has(processes[i].id))
                        min = processes[i];
                    i++;
                }
                if(min==-1)
                    time+=quant;
                else{
                    time = Math.max(time, min.arrivalTime) + min.burstTime;
                    processed.add(min.id);
                    queue.enqueue(min);
                }
            }
            time = queue.front().arrivalTime;
            document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";
            (function traverse() {
                if(!queue.isEmpty()){
                    var wait = Math.ceil(1000/quant);
                    setTimeout(function(){
                        time += Math.min(queue.front().burstTime-queue.front().consumedTime, quant);
                        document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";

                        var front = fcfsHelper();
                        if(front.burstTime == front.consumedTime){
                            wait = 0;
                            queue.dequeue();
                        }
                        traverse();
                    }, wait);
                }
            })();
        }

                //Implementation of Shortest Remaining Time First Algortihm
        function shortestRemainingTimeFirst(){
            var [ctr, time] = [0, 0];
            quant = 1;
            document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";
            (function traverse() {
                if(ctr<processes.length){
                    var wait = Math.ceil(250/quant);
                    setTimeout(function() {
                        var i   = 0;
                        var min = -1;
                        while(i<processes.length && processes[i].arrivalTime<=time){
                            if((min==-1 || (min.burstTime-min.consumedTime)>(processes[i].burstTime-processes[i].consumedTime)) && processes[i].burstTime>processes[i].consumedTime)
                                min = processes[i];
                            i++;
                        }
                        if(min!=-1){
                            processes[min.id].consumedTime     += quant;
                            processes[min.id].consumedTime      = (processes[min.id].consumedTime > processes[min.id].burstTime ? processes[min.id].burstTime : processes[min.id].consumedTime);
                            chart.data[0].dataPoints[min.id].y  = processes[min.id].burstTime-processes[min.id].consumedTime;
                            chart.data[1].dataPoints[min.id].y  = processes[min.id].consumedTime;
                            chart.render();

                            if(processes[min.id].burstTime == processes[min.id].consumedTime)
                                ctr++;
                        }
                        time += quant;
                        document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";
                        traverse();
                    }, wait);
                }
            })();
        }

        //Implementation of Round Robin Algortihm
        function roundRobin(){
            quant = "<?php echo $quantum ?>";
            quant = parseInt(quant);
            queue           = new Queue;
            // [quant, queue] = [2, new Queue()];
            queue.enqueue(processes[0]);
            var [i, time] = [1, processes[0].arrivalTime];
            document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";
            (function traverse() {
                if(!queue.isEmpty()){
                    var wait = Math.ceil(1000/quant);
                    setTimeout(function() {
                        var front = queue.front();
                        time += Math.min(front.burstTime-front.consumedTime, quant);
                        document.getElementById("timer").innerHTML = "Current Time - " + time + " seconds";

                        while(i<processes.length && processes[i].arrivalTime<=time)
                            queue.enqueue(processes[i++]);

                        fcfsHelper();
                        front = queue.dequeue();
                        if(front.burstTime > front.consumedTime){
                            wait = 0;
                            queue.enqueue(front);
                        }
                        traverse();
                    }, wait);
                }
            })();
        }

        var script_algo = "<?php echo $algo; ?>";
        script_algo = String(script_algo);
        //Calling of above Functions
        window.onload                                   = generateProcesses();
        document.getElementById("regenerate").onclick   = function() {generateProcesses();
        
        if(script_algo === "fcfs")
        {
          document.getElementById("algo").innerHTML = "First Come First Serve";
          firstComeFirstServe();
         // printStatsFCFS();
        }
        else if(script_algo === "srjf")
        {
          document.getElementById("algo").innerHTML = "Shortest Job First";
          shortestJobFirst();
          //printStatsSJF();
        }
        else if(script_algo === "srtf")
        {
          document.getElementById("algo").innerHTML = "Shortest Remaining Time First";
          shortestRemainingTimeFirst();
         // printStatsSRTF();
        }
        else if(script_algo === "round-robin")
        {
          document.getElementById("algo").innerHTML = "Round Robin";
          document.getElementById("tq").innerHTML = "Time Quantum: <?php echo $quantum ?>";
          roundRobin();
         // printStatsRR();
        }
        };

        document.getElementById("performance").onclick = function() {
        var tablewrap = document.getElementById('displaytable');
        tablewrap.classList.toggle('hidden')

        if(script_algo === "fcfs")
        {
          document.getElementById("algo").innerHTML = "First Come First Serve";
          printStatsFCFS();
        }
        else if(script_algo === "srjf")
        {
          document.getElementById("algo").innerHTML = "Shortest Job First";
          printStatsSJF();
        }
        else if(script_algo === "srtf")
        {
          document.getElementById("algo").innerHTML = "Shortest Remaining Time First";
          printStatsSRTF();
        }
        else if(script_algo === "round-robin")
        {
          document.getElementById("algo").innerHTML = "Round Robin";
          document.getElementById("tq").innerHTML = "Time Quantum: <?php echo $quantum ?>";
          printStatsRR();
        }
        };
        
        
        // document.getElementById("fcfs").onclick         = function() {firstComeFirstServe();};
        // document.getElementById("srjf").onclick         = function() {shortestJobFirst();};
        // document.getElementById("srtf").onclick         = function() {shortestRemainingTimeFirst();};
        // document.getElementById("round-robin").onclick  = function() {roundRobin();}; 

    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
  </body>
</html>



















<!-- var start_time=[];
          var completion_time=[];
          var is_completed=[];
          var burst_remaining=[]; // remaining time
          is_completed[0]=0;
           for(var i=1; i<=total; i++)
            {
              is_completed[i]=0;
              burst_remaining[i]= parseInt(script_burst[i]);
            }
            var current_time = 0;
            var completed = 0;
            var prev = 0;

            while(completed != total) {
                var idx = -1;
                var mn = 9999;
                for(var i = 1; i <= total; i++) 
                {
                    if(parseInt(script_arrival[i]) <= current_time && is_completed[i] == 0) {
                        if(parseInt(burst_remaining[i]) < mn) {
                            mn = parseInt(burst_remaining[i]);
                            idx = i;
                        }
                        if(parseInt(burst_remaining[i]) == mn) {
                            if(parseInt(script_arrival[i]) < parseInt(script_arrival[idx])) {
                                mn = parseInt(burst_remaining[i]);
                                idx = i;
                            }
                        }
                    }
                }

                if(idx != -1) {
                    if(parseInt(burst_remaining[idx]) == parseInt(script_burst[idx])) 
                    {
                        start_time[idx] = current_time;
                        total_idle_time = total_idle_time+start_time[idx] - prev;
                    }
                    burst_remaining[idx] = burst_remaining[idx] - 1;
                    current_time=current_time+1;
                    prev = current_time;
                    
                    if(parseInt(burst_remaining[idx]) == 0) {
                        completion_time[idx] = current_time;
                        tat[idx] = completion_time[idx] - parseInt(script_arrival[idx]);
                        wt[idx] = tat[idx] - parseInt(script_burst[idx]);                

                        is_completed[idx] = 1;
                        completed=completed+1;
                    }
                }
                else {
                     current_time=current_time+1;
                }  
            } -->
















