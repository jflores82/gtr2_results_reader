# gtr2_results_reader
GTR2 Results.txt File Parser.

Usage:
'''php
$results_file = 'your_results.txt';
$rf = new ResultsParser($results_file);
'''

Methods:
'''php
$results = $rf->RaceResults();
'''

'''php
$trackinformation = $rf->TrackInformation();
'''

'''php
$bestlap = $rf->BestLap();
'''

The methods returns associative arrays with the relevant information. 
RaceResults is ordered, from first to last.
BestLap is only the bestlap of the race.

You can format the result as anyway you please.



