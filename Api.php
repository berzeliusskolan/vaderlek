<?php
/**
 * Created by PhpStorm.
 * User: johkel
 * Date: 2019-03-26
 * Time: 09:44
 */

class Api
{
    public function json(array $data)
    {
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($data);
    }

}