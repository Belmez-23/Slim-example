<?php
namespace App;
use PDO;

Class Connection
{
    private $conn;
    private $error;
    public function __construct()
    {
        try
        {
            $this->conn = new PDO('mysql:host=localhost;dbname=MYDB08', 'a1', '111111');
        }
        catch(PDOException $e)
        {
            $this->error = $e->getMessage();
        }
    }
    public function getConnection()
    {
        return $this->conn;
    }
}