<?php

class Environment
{
    public $user, $pass, $db, $host, $settings, $dsn;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->user = 'root';
        $this->pass = '';
        $this->db = 'weather19';
        $this->dsn = "mysql:dbname=$this->db;host=$this->host;charset=utf8";

        $this->settings = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
    }
}

