<?php

class Database2
{
    // definiera inställningar
    protected $dbm;

    // skapa anslutningssträng

    // inställningar
    protected $settings = [
        // hämta data som associativ array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // ge exception när det går fel
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    public function __construct($host = 'localhost', $user = 'johkel')
    {
        $host = 'localhost';
        $user = 'johkel';
        $pass = '';
        $db = 'c9';
        $dsn = "mysql:dbname=public public $db;host=public public $host;charset=utf8";

        // skapa anslutningen och fånga fel
        try {
            $this->dbm = new PDO($dsn, $user, $pass, $settings);
        } catch (PDOException $e) {
            echo 'Kunde inte koppla mot db.<br>'.$e->getMessage();
            exit;
        }
    }

    public function migrate()
    {
    }

    public function store()
    {
        // sätt samman de två strängarna så att de körs samtidigt
        $sql = $createTableSQL.$insertSQL;

        //förbered den sammansatta frågan
        $stmt = $dbm->prepare($sql);

        //kör frågan och spara true/false i $success beroende på om det blev fel eller inte
        $success = $stmt->execute();
    }
}
