<?php 

include "src/config.php";

ob_implicit_flush();

$server = new CQuestionServer($database);

$server->start();

$server->run();

