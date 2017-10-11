<?PHP 
	// GTR2 Results Parser // 
	// by tibone //
	// v1 - 20170517 //
	

class ResultsParser {
	
	var $results_file; // This is the name of the file. (The parameter that is passed on to the construct //
	var $ini_array; // This is the file that will get processed by the ini parser.
	
	public function __construct($result_file) {
		$this->results_file = $result_file; // Creates a new instance with the following result file name //
	}
	
	// Search function with wildcard //
	private function like($needle, $haystack) {
		$needle = strtolower($needle);
		$haystack = strtolower($haystack);
		$regex = '/' . str_replace('%', '.*?', $needle) . '/';
		return preg_match($regex, $haystack) > 0;
	}
	
	// Deletes the first line of the file, because it's a gmotor2 header //
	private function DeleteFirstLine() {
		$handle = fopen($this->results_file, "r");
		$first = fgets($handle,2048); 
		$outfile="temp.txt";
		$o = fopen($outfile,"w");
		while (!feof($handle)) {
			$buffer = fgets($handle,2048);
			fwrite($o,$buffer);
		}
		fclose($handle);
		fclose($o);
	}
	
	// Parse the ini into an array //
	private function ParseFile() {
		$this->ini_array = parse_ini_file($this->$results_file, true); //Load the results into a array //
	}

	
	// Returns a array with the race results, ordered //
	public function raceResults() {
		$drivers = array(); // Create a new array for the drivers //
		
		$this->DeleteFirstLine(); // Removes the first line //
		$this->ParseFile(); // Puts the results into an array //
		
		foreach($this->ini_array as $k => $i) {
			if($this->like("slot%", $k) == "true") {  // Look only into the [slotXXX] sections.
				$driver_line = array(); // Used to create a new multidimensional array to store the results.
				foreach($i as $section => $v) {
					$driver_line[$section] = $v; // Everysingle subsection of slotXXX is put here.
				}
				array_push($drivers, $driver_line); // Put the line with the drivers into the drivers array.
			}
		}

		$drivers_sort = array(); // This will be used to sort the results, by number of laps and racetime //
		foreach($drivers as $k=>$v) {
			$drivers_sort['Laps'][$k] = $v['Laps']; 
			$drivers_sort['RaceTime'][$k] = $v['RaceTime'];
		}

		array_multisort($drivers_sort['Laps'], SORT_DESC, $drivers_sort['RaceTime'], SORT_ASC,$drivers);
		return $drivers;
	}
	
	// Returns a array with information about the track, unordered //
	public function TrackInformation() {
		$track = array();
		
		$this->DeleteFirstLine(); // Removes the first line //
		$this->ParseFile(); // Puts the results into an array //
		
		foreach($this->ini_array as $k => $i) {
			if($this->like("%race%", $k) == "true") { // Look only into the [race] section //
				$track_line = array(); // Used to create a multidimensional array //
				foreach($i as $section => $v) {
					$track_line[$section] = $v; // We'll store every single subsection of the race section
				}
				array_push($track, $track_line);
			}
		}
		return $track; // Returns everything.
	}
	
	// Returns a simple array, with the best lap information //
	public function BestLap() {
		$bestlap = array();
			
		$this->DeleteFirstLine(); // Removes the first Line //
		$this->ParseFile(); // Puts the results into an array //
		
		foreach($this->ini_array as $k => $i) {
			if($this->like("%slot%", $k) == "true") { // Look only into the [slotXXX] section //
				$bestlap_line = array();  // Used to create a multidimensional array //
				foreach($i as $section => $v) {
					if($section == "Driver" || $section == "BestLap" || $section == "Team") {   // We need the driver information and the bestlap information //
						$bestlap_line[$section] = $v;
					}
				}
				array_push($bestlap, $bestlap_line); // Store the information ino the array //
			}
		}
		
		$bestlaps_sort = array(); // We need only the best lap.
		foreach($bestlap as $k=>$v) {
			$bestlaps_sort['BestLap'][$k] = $v['BestLap'];  
			
		}

		array_multisort($bestlaps_sort['BestLap'], SORT_ASC,$bestlap);
		return $bestlap[0]; // Returns a single-line array with the information about the best lap.
	}

// EOClass //
}


?>