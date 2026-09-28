<?php
require_once __DIR__ . '/../vendor/autoload.php';

use models\Config\Conn;

function connection()
{
    $conn = new Conn("localHost", "root", "", "portfolio");
    if ($conn) {
        return   $conn->getConnect();
    }
}

$connect = connection();