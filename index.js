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