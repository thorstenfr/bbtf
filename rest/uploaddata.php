<?php 	

/**
 * 1) ratings in jjjjmmtt_ratings umbenannten (alter table rename)
 * 2) neue ratings erstellen (create table)
 * 3) Daten in ratings einfügen (insert)
 */
	function cors() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

     // echo "You have CORS!";
}
 
	// CORS aktivieren	
	cors();
		
	
	ini_set('max_execution_time', 120); //120 seconds 
	$start = time();
	$anzahl_ds = 0;
	$mysqli = new mysqli("localhost", "root", "", "ngbbtf");
	if ($mysqli->connect_error) {
		fwrite($handle,  "Fehler bei der Verbindung: " . mysqli_connect_error());
		exit();
	}
	if (!$mysqli->set_charset("utf8")) {
		fwrite($handle, "Fehler beim Laden von UTF8 ". $mysqli->error);
	}	
	$bkp_db = date("Ymd_His") . "_ratings";
	// 1) ratings in jjjjmmtt_ratings umbenennen
	$sql = "CREATE TABLE ". $bkp_db . " LIKE ratings;" ;
	$result = $mysqli->query($sql);
	
    $sql = "INSERT " . $bkp_db . " SELECT * FROM ratings;";
	$result = $mysqli->query($sql);	
	
	
	// 2) ratings leeren	
	$sql = "TRUNCATE TABLE `ratings`";
	
	if( $result = $mysqli->query($sql) )
	{
		// echo "Tabelle geleert\n";
	
	
	}		
	else {
		echo "Problem beim Leeren der Tabellen\n";
	}
	

    
	
	// Get Post Data
	$postdata = file_get_contents("php://input");
	$json = json_decode($postdata,true);
	foreach($json['subjects'] as $subject) {
		//echo $subject['title'];
		foreach($subject['pupils'] as $pupil) {
			//echo $pupil['name'];
			foreach($pupil['ratings'] as $rating) {
				$seconds = $rating['datum'] / 1000;
				$datum =  date("Y-m-d", $seconds);
								
				$sql = "INSERT INTO ratings (datum, datum_native, pupilname, subjecttitle, rating) 
					VALUES('$datum','$rating[datum]','$pupil[name]', '$subject[title]', 1);";
				
				// Datensatz einfügen
				if( $result = $mysqli->query($sql) )
				{
					$anzahl_ds++;					
				}				
			}
			foreach($pupil['teufelchen'] as $rating) {
				$seconds = $rating['datum'] / 1000;
				$datum =  date("Y-m-d", $seconds);
								
				$sql = "INSERT INTO ratings (datum, datum_native, pupilname, subjecttitle, rating) 
					VALUES('$datum','$rating[datum]','$pupil[name]', '$subject[title]', -1);";
				
				// Datensatz einfügen
				if( $result = $mysqli->query($sql) )
				{
					$anzahl_ds++;					
				}				
			}
			
		}
		
	}
	
	$sekunden = time() - $start;
	echo $anzahl_ds++ . " Datensätze in " . $sekunden . " Sekunde eingefügt!";
	$mysqli->close();
	 
	
	
	
	
 ?>