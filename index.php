<?PHP

// GTR2 Results.txt parser //
// Sample file for use //
require_once('class.resultparser.php'); // Loads the class //

$results_file = "results.txt"; // Results File //

$rr = new ResultsParser($results_file); // Instantiates the object //
$resultados = $rr->RaceResults(); // RaceResults into array //
echo "Resultado da Corrida: "; var_dump($resultados); echo "<br><br><br>";

$pista = $rr->TrackInformation(); // TrackInformation into array //
echo "Informa&ccedil;&otilde;es da Pista: "; var_dump($pista); echo "<br><br><br>";

$best_laps = $rr->BestLap(); // Best Lap information into array. //
echo "Best Lap: "; var_dump($best_laps); echo "<br><br><br>";

?>