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
    private $fileName;
    function __construct($file)
    {
        $this->fileName = $file;
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
        if(strpos($this->fileName, "attendance")){
            $d = date("Y-m-d H:i:s");
            fwrite($this->fh, $d . "," . "$uid" . ",". $displayName .",". "$course" ."\n");
        }else{
            $this->msg("Wrong log for operation");
        }
    }
}


