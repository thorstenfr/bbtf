<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
  require_once '../config.php';
// $q = intval($_GET['q']);
$q = $_GET['q'];

$con = mysqli_connect($db_server, $db_user, $db_pass, $db_name );
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

// mysqli_select_db($con,"ajax_demo");
$sql="SELECT distinct (pupilname) FROM ratings WHERE subjecttitle = '".$q."'";
$result = mysqli_query($con,$sql);


echo "<option value=\"-1\">-Alle-</option>";
while($row = mysqli_fetch_array($result)) {
    echo "<option value=\"" .$row['pupilname'] . "\">" . $row['pupilname'] . "</option>";
}

mysqli_close($con);
?>
</body>
</html>