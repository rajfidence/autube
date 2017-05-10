<?php
session_start();
$logoutUrl = $_SESSION['LOGOUT'];
$_SESSION['FBID'] = NULL;
$_SESSION['FULLNAME'] = NULL;
$_SESSION['EMAIL'] =  NULL;
session_destroy();
header("Location: index.php");
?>
