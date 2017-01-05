<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
  require_once '../config.php';

$kurs = $_POST["kurs"];
$schueler = $_POST["schueler"];
$von = $_POST["von"];
$bis = $_POST["bis"];
$sortierung =$_POST["sortierung"];

if ($von == "") {$von="1970-01-01";}
if ($bis == "") {$bis="2099-12-30";}

$con = mysqli_connect($db_server, $db_user, $db_pass, $db_name );
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
// Übersicht über alle Schüler anzeigen
if ($schueler=="-1") {
	
	if ($sortierung == "-1") {$sortierung = "pupilname"; $dir="ASC";}
	elseif  ($sortierung == "pupilname") {$dir="ASC";}
	else {$dir="DESC";}
	
	$sql="SELECT sum(rating) as ratings, pupilname FROM ratings WHERE subjecttitle = '".$kurs."' and datum BETWEEN '" .$von. "' AND '" .$bis. "' group by pupilname order by " .$sortierung. " ".$dir;
	$result = mysqli_query($con,$sql);
	echo "<tr><th>Name</th><th>Ratings</th></tr>";
	while($row = mysqli_fetch_array($result)) {
		echo "<tr><td>" . $row['pupilname'] . "</td><td>" . $row['ratings'] . "</td></tr>";
	}	
	
}
else {
	
	if ($sortierung == "-1") {$sortierung = "datum";$dir="ASC";}
	elseif  ($sortierung == "datum") {$dir="ASC";}
	else {$dir="DESC";}
	$sql = "SELECT sum(rating) as ratings, datum, pupilname \n"
    . "FROM `ratings` \n"
    . "WHERE subjecttitle='" .$kurs. "' and pupilname='" .$schueler. "'\n"
	. "AND datum BETWEEN '" .$von. "' AND '" .$bis. "'\n"
    . "group by datum\n"
    . "ORDER BY " .$sortierung. " ".$dir;

$result = mysqli_query($con,$sql);



echo "<tr><th>Datum</th><th>Ratings</th></tr>";
while($row = mysqli_fetch_array($result)) {
    echo "<tr><td>" . $row['datum'] . "</td><td>" . $row['ratings'] . "</td></tr>";
}
}

mysqli_close($con);

?>
</body>
</html>