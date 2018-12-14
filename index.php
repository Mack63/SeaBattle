<?php
require("Controllers/Controller.php");

$playGame = new Controller();
$playGame->Action($_GET);

