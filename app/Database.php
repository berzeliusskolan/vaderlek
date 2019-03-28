<?php

class Database
{
    // definiera inställningar
    protected $dbm;
    protected $stmt;
    protected $lastInsertId;

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
     * Migrate. Set up tables.
     * @return string
     */
    public function migrate()
    {
        $createTableSQL = "
            DROP TABLE IF EXISTS weather;
            CREATE TABLE weather (
              id int(11) NOT NULL auto_increment,
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
     * @param mixed[] $data
     * @return int 0 or last id inserted
     */
    public function runQuery(string $sql, array $data = null)
    {
        $this->stmt = $this->dbm->prepare($sql);
        if ($data) {
            $success = $this->stmt->execute($data);
            if ($success) {
                $this->lastInsertId = $this->dbm->lastInsertId();
                return $this->lastInsertId;
            }
            return false;
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
     * @param string $sql
     * @param array $params
     * @return mixed Assoc array with multiple rows of arrays
     */
    public function fetchAll(string $sql, array $params = [])
    {
        $this->stmt = $this->dbm->prepare($sql);
        $this->stmt->execute($params);
        return $this->stmt->fetchAll();
    }

    /**
     * @param mixed[] $rows
     * @return int Nr of rows stored
     */
    public function storeRows(array $rows)
    {

        $rowsStored = 0;
        foreach ($rows as $row) {
            if ($row[0] > $this->lastInsertId) {
                $rowsStored++;
                $this->store($row);
            }
//            if($rowsStored == 10) { break; } // enbart för testning så att det blir hanterbart många
        }
        return $rowsStored;
    }

    /**
     * @param mixed[] $row
     * @return int 0 or lastInsertId
     */
    public function store(array $row)
    {
        $query = 'INSERT INTO weather VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        return $this->runQuery($query, $row);
    }

    /**
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    /**
     * @param string $begin Startdatum
     * @param string $end Slutdatum
     * @return mixed Indexed array of weather data
     */
    public function getWeather(string $begin, string $end)
    {
//        $query = "select * from weather where time between '$begin' and '$end'";
        $query = "select * from weather where time between ? and ?";
        return $this->fetchAll($query, [$begin,$end]);
    }

}