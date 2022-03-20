<?php

require_once('helpers.php');

session_start();

$_SESSION = [];
header("Location: index.php");
