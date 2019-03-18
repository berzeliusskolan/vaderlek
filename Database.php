<?php

class Database
{
    // definiera inställningar
    protected $dbm;
    protected $stmt;

    /**
     * Database constructor.
     * @param Environment $env
     */
    public function __construct(Environment $env)
    {

        // skapa anslutningen och fånga fel
        try {
            $this->dbm = new PDO($env->dsn, $env->user, $env->pass, $env->settings);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @return string
     */
    public function migrate()
    {
        $createTableSQL = "
            DROP TABLE IF EXISTS weather;
            CREATE TABLE weather (
              id int(11) NOT NULL,
              time datetime ,
              intervall int(11) ,
              temp_in float ,
              humidity_in float ,
              temp_out float ,
              humidity_out float ,
              air_pressure_rel float ,
              air_pressure_abs float ,
              wind_speed float ,
              squall float ,
              wind_direction varchar(10) ,
              dew_point float ,
              wind_cooling float ,
              rain_h float ,
              rain_24h float ,
              rain_week float ,
              rain_month float ,
              rain_total float ,
              PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        if ($this->runQuery($createTableSQL)) {
            return "Data structure migrated successfully<br>";
        }
        return "Fail.";
    }

    /**
     * @param string $sql
     * @param null $data
     * @return bool
     */
    public function runQuery(string $sql, $data = null)
    {
        $this->stmt = $this->dbm->prepare($sql);
        if ($data) {
            return $this->stmt->execute($data);
        }
        return $this->stmt->execute();
    }

    /**
     * @param string $sql
     * @return mixed Assoc array with single row
     */
    public function fetch(string $sql)
    {
        $this->stmt = $this->dbm->prepare($sql);
        $this->stmt->execute();
        return $this->stmt->fetch();
    }

    /**
     * @param $rows Rows to store as array
     * @return int Nr of rows stored
     */
    public function storeRows($rows)
    {
        $lastId = $this->getLastId();
        $rowsStored = 0;
        foreach ($rows as $row) {
            if ($row[0] > $lastId) {
                $rowsStored++;
                $this->store($row);
            }
        }
        return $rowsStored;
    }

    /**
     * @param $row
     * @return bool
     */
    public function store($row)
    {
        $query = 'INSERT INTO weather VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        return $this->runQuery($query, $row);
    }

    /**
     * @return mixed
     */
    public function getLastId()
    {
        $query = 'SELECT MAX(id) from weather';
        $row = $this->fetch($query);
        return $row['MAX(id)'];
    }
}