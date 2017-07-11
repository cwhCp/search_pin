<!DOCTYPE html>
<html>
<body>

<?php
$con = mysql_connect("localhost","root","cc1985");

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("searchpin", $con);

$curTime = time();
$value = $_REQUEST['data'];
$sql="INSERT INTO pininfo (time,data) VALUES ($curTime,'$value')";

// if (true)
// {
//     $myfile = fopen("data.txt", "w");
//     fwrite($myfile, $_REQUEST['data']);
//     fclose($myfile);
// }

if (!mysql_query($sql,$con))
{
    $myfile = fopen("errorfile.txt", "a") or die("Unable to open file!");
    fwrite($myfile, mysql_error());
    fclose($myfile);
    die('Error: ' . mysql_error());
}

mysql_close($con)
?>

</body>
</html>