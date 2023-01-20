<?php

/*
 * This is a short script that limits page views based on remote address.
 * With this script you can allow or deny access to any certain webpage or host based on the remote address.
 * Define the CIDR ranges you want to allow and the script will take care of the rest
 *
 * This can be used as a helper class by changing the boolean $helper to true
 *
 * This is an extended version of the PHPIPValidator project to provide attendance monitoring capability.
 *
 * Johannes Edgren [Arcada] 2022
 */


// Require our config file
require 'config.php';

// Import Classes
use IPValidator\IPValidator;
use IPValidator\Logger;
use IPValidator\ReadCSV;


// Read config file
$config = parse_ini_file(CONFIG_FILE);


// Create one general logger
$logger = new Logger($config['logfile']);

// Create class objects
$ipValidator = new IPValidator($config['debug'], $config['helper'], $config['ipv4'], $config['ipv6'], $logger, $config['logging']);

// Create a specific logger for attendance logging
$attendanceLogger = new Logger($config['attendanceLog']);

// Create a csv file reader
$readCSV = new ReadCSV($config['attendanceLog']);

// Create templates for courses, validation and user input
$template['courses'] = $config['courses'];
$template['validation'] = $ipValidator->checkIfUserIsOnEduroam();
$template['course'] = $_GET['course'];
$template['attendanceData'] = $readCSV->getCSV();
$template['uid'] = $_SERVER['MELLON_uid'];
$template['displayName'] = $_SERVER['MELLON_displayName'];


/**
 * Main functionality input.
 * If config is se to helper, ip validation passes and input course key exists proceed
 */

if ($config['helper'] && $template['validation'] && array_key_exists($template['course'], $template['courses'])) {
    // Do stuff here if you have set the type to helper class and the ip validates correctly

    // Check visitors register status and save the array result if one exists
    $template['isRegistered'] = $ipValidator->checkIfUserIsRegistered($template['uid'], $template['course'], $template['attendanceData']);

    // If user presses register
    if (isset($_POST['submitAttendance'])) {
        // Check that user isn't registered before sending a new registrar
        if (!$template['isRegistered']) {
            $attendanceLogger->registerAttendance($template['uid'], $template['displayName'], $template['course']);
            header("Refresh: 0");
        }
    }
    /**
     * Require template.php that builds the page
     */

    require "template.php";
} else {
    // Do evil stuff here if user is a skurk
    // If any one of the validations fails

    echo "<h1>You are not welcome here</h1>";
}