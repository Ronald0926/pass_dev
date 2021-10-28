<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Log
 *
 * @author MANUEL ANZOLA JIMENEZ
 */
class Log extends CI_Controller{

    public function __construct($filename, $path) {
        parent::__construct();
        $this->path = ($path) ? $path : "/";
        $this->filename = ($filename) ? $filename : "log";
        $this->date = date("Y-m-d H:i:s");
        $this->ip = ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0;
    }

    public function insert($text, $dated, $clear, $backup) {
        if ($dated) {
            $date = "_" . str_replace(" ", "_", $this->date);
            $append = null;
        } else {
            $date = "";
            $append = ($clear) ? null : FILE_APPEND;
            if ($backup) {
                $result = (copy($this->path . $this->filename . ".log", $this->path . $this->filename . "_" . str_replace(" ", "_", $this->date) . "-backup.log")) ? 1 : 0;
                $append = ($result) ? $result : FILE_APPEND;
            }
        };
        $log = $this->date . " [ip] " . $this->ip . " [text] " . $text . PHP_EOL;
        
        $result = (file_put_contents($this->path . $this->filename . $date . ".log", $log, $append)) ? 1 : 0;

        return $result;
    }
    
    


}
