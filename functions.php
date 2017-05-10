<?php
require 'dbconfig.php';
function checkuser($fuid,$ffname,$femail)
{
	global $mysqli;
	$result = $mysqli->query("select * from Users where Fuid='$fuid'");
	$row_cnt = $result->num_rows;
	if ($row_cnt == 0)
	{
		$stmt = $mysqli->prepare("INSERT INTO Users (Fuid,Ffname,Femail) VALUES (?,?,?)");
		$stmt->bind_param("sss",$fuid,$ffname,$femail);
	}
	else
	{
		$stmt = $mysqli->prepare("UPDATE Users set Ffname = ?, Femail = ? where Fuid = ?");
		$stmt->bind_param("sss",$ffname,$femail,$fuid);
	}
	$result = $stmt->execute();
	$stmt->close();
}
?>


