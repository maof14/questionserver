<?php

/**
 *
 * Display errors
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 *
 * Include autoloader for classes
 *
 */
include __DIR__."/../autoloader.php";

/** 
 *
 * MySQL DSN.
 * See manual for SQLite use.
 *
 */

$database['dsn'] = 'mysql:host=localhost;dbname=various_stuff';
$database['username'] = 'root';
$database['password'] = 'root';
$database['options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
$database['fetchStyle'] = PDO::FETCH_OBJ;