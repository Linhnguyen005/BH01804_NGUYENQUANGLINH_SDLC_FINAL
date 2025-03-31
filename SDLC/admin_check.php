<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["is_admin"] !== true) {
    header("location: login.php");
    exit;
}
?>
