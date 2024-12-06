<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1); // Disable error display
ini_set('log_errors', 1); // Log errors instead
error_reporting(E_ALL);

class db
{
    public $con;

    function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=test";
        $uname = "root";
        $pwd = "";

        try {
            $this->con = new PDO($dsn, $uname, $pwd);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->routeRequest();
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            exit;
        }
    }

    private function routeRequest()
    {
        $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $path = explode('/', trim($pathInfo, '/'));

        $method = $_SERVER['REQUEST_METHOD'];
        if ($path[0] == 'phpdemo') {
            switch ($method) {
                case 'GET':
                    $this->getAllUsers();
                    break;
                default:
                    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid route"]);
        }
    }

    function getAllUsers()
    {
        try {
            $query = "SELECT * FROM phpdemo";
            $stmt = $this->con->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return only JSON response
            echo json_encode(["status" => "success", "data" => $result]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}

new db();
