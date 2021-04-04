var chart, total, max, min, queue, processes, quant = 2;

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
    total = Math.floor(Math.random() * (max - min + 1)) + min;
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

    [min, processes]   = [1, []];
    for(var i=0; i<total; i++){
        var label_  = String.fromCharCode(65+i);
        var burst   = Math.floor(Math.random() * (20 - 5 + 1)) + 5;
        var arrival = Math.floor(Math.random() * 3) + min - 1;
        var process = new Process(i, arrival, burst);
        min         = arrival+1;

        processes.push(process);
        chart.data[1].dataPoints.push({label: label_+"(" + arrival + ")", y: 0});
        chart.data[0].dataPoints.push({label: label_+"(" + arrival + ")", y: burst});
    }
    chart.render();
}