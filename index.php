<?php
session_start();
require_once "controller/controller.php";
require_once "models/model.php";
$mvc = new controllersEnlaces();
$mvc->platilla();
?>
