<?php

class LogService
{

    private string $filename;

    private bool $debug = false;
    private bool $trace = false;

    public function __construct($logger)
    {
        $folder = $_SERVER['DOCUMENT_ROOT'] . "/logs/";
        $this->filename = $folder . $logger . ".log";
        if (!is_dir($folder)) {
            mkdir($folder, 0700);
            file_put_contents($folder . ".htaccess", "Deny from all");
        }
    }

    public function Trace(string $message) {
        if ($this->debug and $this->trace) {
            $this->WriteToFile("DEBUG", $message);
        }
    }

    public function Debug(string $message) {
        if ($this->debug) {
            $this->WriteToFile("DEBUG", $message);
        }
    }

    public function Info(string $message) {
        $this->WriteToFile("INFO", $message);
    }

    public function Warning(string $message) {
        $this->WriteToFile("WARN", $message);
    }

    public function Error(string $message) {
        $this->WriteToFile("ERROR", $message);
    }

    private function WriteToFile(string $severity, string $content) {
        $c = "";
        if (is_file($this->filename)) {
            $c = file_get_contents($this->filename);
        }
        $date = new DateTime();
        $dstr = $date->format("Y-m-d H:i:s");
        $content = "$dstr $severity $content";
        $c .= "$content\n";
        file_put_contents($this->filename, $c);
    }

}