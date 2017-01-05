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




$con = mysqli_connect($db_server, $db_user, $db_pass, $db_name );
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$sql = "SELECT distinct(subjecttitle) FROM `ratings` ORDER BY `datum` DESC";

$result = mysqli_query($con,$sql);


echo "<option>-Kurs w&auml;hlen-</option>";
while($row = mysqli_fetch_array($result)) {
    echo "<option value=\"" .$row['subjecttitle'] . "\">" . $row['subjecttitle'] . "</option>";
}

mysqli_close($con);
?>
</body>
</html>