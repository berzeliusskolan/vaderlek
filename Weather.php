<?php
/**
 * Created by PhpStorm.
 * User: johkel
 * Date: 2019-03-26
 * Time: 10:18
 */

class Weather
{
    public function get($begin = null, $end = null)
    {
        $begin = '2019-03-01 00:00:00';
        $end = '2019-03-01 04:00:00';
        $query = "select * from weather where time between '$begin' and '$end'";
        return $this->fetchAll($query);
    }
}