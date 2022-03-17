<?php

/*
 * This is a short script that limits page views based on remote address.
 * With this script you can allow or deny access to any certain webpage or host based on the remote address.
 * Define the CIDR ranges you want to allow and the script will take care of the rest
 *
 * This can be used as a helper class by changing the boolean $helper to true
 *
 * Johannes Edgren [Arcada] 2022
 */

// Require the ipValidator class
use IPValidator\IPValidator;
use IPValidator\Logger;
use IPValidator\ReadCSV;

require "config.php";

// Read config file
$config = parse_ini_file(CONFIG_FILE);

$ipValidator = new IPValidator($config['debug'], $config['helper'], $config['ipv4'], $config['ipv6']);
$logger = new Logger($config['logfile']);
$attendanceLogger = new Logger($config['attendanceLog']);
$readCSV = new ReadCSV($config['attendanceLog']);
$template['validation'] = $ipValidator->checkIfUserIsOnEduroam();
$template['course'] = $_GET['course'];
$template['attendanceData'] = $readCSV->getCSV();
$template['uid'] = $_SERVER['MELLON_uid'];
$template['displayName'] = $_SERVER['MELLON_displayName'];
if ($config['helper'] && $template['validation']) {
    // Do stuff here if you have set the type to helper class and the ip validates correctly

    // Check visitors register status and save the array result if one exists
    $template['isRegistered'] = $ipValidator->checkIfUserIsRegistered($template['uid'],$template['course'], $template['attendanceData']);

    // If user presses register
    if(isset($_POST['submitAttendance'])){
        // Check that user isn't registered before sending a new registrar
        if(!$template['isRegistered']) {
            $attendanceLogger->registerAttendance($template['uid'], $template['displayName'], $template['course']);
            header("Refresh: 0");
        }
    }
    require "template.php";
    var_dump($template['attendanceData']);
} else {
    // Do evil stuff here if user is a skurk

    echo "<h1>You are not welcome here</h1>";
}