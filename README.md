# MiniPro-VisualisationAlgo
Mini Project Sem 6 : CPU Scheduling Algorithms Visualisation

**This project involves development of a visualization technique for CPU scheduling algorithms.** Scheduling is a fundamental operating-system function. In a single-processor system, only one process can run at a time; any other must wait until the CPU is free and can be rescheduled. Thus, its scheduling is central to operating-system design
This project has been developed as a comprehensive tool which runs a simulation in real time, and generates useful data to be used for evaluation. A user-friendly and mouse driven graphical user interface has been integrated by making use of the Canvas JS library which helps in visualization the processes. 

This visualization tool can be used for measuring performance of different scheduling algorithms, preemptive and non-preemptive, which helps in better understanding and training of students in domains like Operating System where there is very little practical visualization available, of the underlying system.

Our main aim is to make the understanding process of these algorithms easy for users which will be done by visualizing the working of each algorithm by making use of the CanvasJS library’s stacked column graphs.

In this project we have created visualizations of 4 CPU Scheduling algorithms:
1. First Come First Serve
2. Shortest Job First
3. Shortest Remaining Time First
4. Round Robin

Apart from visualization we have also calculated the performance metrics of each execution which can be seen in a tabular format. The table displays each process's arrival time, burst time, waiting time and turn around time. 

The scope of the mini-project is limited to 15 processes for better visualisation and understanding, but this can be increased. The arrival and burst time inputs are restricted to a maximum of 15 seconds for the same reason.

Examples:

1. FCFS

   | Process | AT | BT | WT | TAT |
   |---------|----|----|----|-----|
   |   P1    | 12 |  5 |  5 |  10 |
   |   P2    | 10 |  5 |  2 |   7 |
   |   P3    |  7 |  5 |  0 |   5 |

2. SJF

   | Process | AT | BT | WT | TAT |
   |---------|----|----|----|-----|
   |   P1    |  2 |  4 |  2 |   6 |
   |   P2    |  2 |  7 |  6 |  13 |
   |   P3    |  2 |  2 |  0 |   2 |
   
3. SRTF
   
   | Process | AT | BT | WT | TAT |
   |---------|----|----|----|-----|
   |   P1    |  1 | 14 | 18 |  32 |
   |   P2    |  4 |  8 |  1 |   9 |
   |   P3    |  6 |  9 |  7 |  16 |
   |   P4    | 10 |  1 |  0 |   1 |

4. RR

    Time quantum: 2 secs

   | Process | AT | BT | WT | TAT |
   |---------|----|----|----|-----|
   |   P1    |  1 | 14 | 18 |  32 |
   |   P2    |  4 |  8 |  1 |   9 |
   |   P3    |  6 |  9 |  7 |  16 |
   |   P4    | 10 |  1 |  0 |   1 |
