<?php


namespace IPValidator;

/**
 * Class for logging messages to log file.
 * @property false|resource fh
 */
class Logger
{

    /**
     * @var false|resource
     */
    private $attendance;

    function __construct($file)
    {
        $this->fh = fopen($file, "a") or die("Unable to open: " . $file);
    }

    /**
     * Write message to log.
     * @param string String to write to logfile
     */
    function msg($str)
    {
        $d = date("Y-m-d H:i:s");
        fwrite($this->fh, $d . " " . $str . "\n");
    }

    function registerAttendance($uid,$displayName,$course){
        $file = "../log/attendance.log";
        $this->attendance = fopen($file, "a") or die($this->msg("Unable to open: " . $file  ) && "Unable to open file");
        $d = date("Y-m-d H:i:s");

        fwrite($this->attendance, $d . ";" . "$uid" . ";". $displayName . "$course" .";");
    }

}


