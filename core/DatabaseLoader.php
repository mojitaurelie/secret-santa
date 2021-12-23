<?php

$ROOT = $_SERVER['DOCUMENT_ROOT'];
include_once($ROOT . "/core/LogService.php");

class DatabaseLoader
{

    private PDO $PDO;
    private LogService $logger;

    public function __construct()
    {
        $this->Initialize();
    }


    public function Initialize() {
        $this->logger = new LogService("server");
        try {
            $this->PDO = new PDO("mysql:host=localhost;dbname=secret-santa", "root");
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->logger->Error($e->getMessage());
        }
    }

    public function Query(string $sql, Array $params): Array
    {
        $array = [];
        try {
            $stmt = $this->PDO->prepare($sql);
            $this->logger->Debug("Querying '" . $sql . "' with parameters " . implode(", ", $params));
            if ($stmt != false) {
                $stmt->execute($params);
                $this->logger->Debug("Entities found : " . $stmt->rowCount());
                while($row = $stmt->fetch())
                {
                    array_push($array, $row);
                }
                $stmt->closeCursor();
            }
        } catch (PDOException $e) {
            $this->logger->Error($e->getMessage());
        }
        return $array;
    }

    public function FindOne(string $sql, Array $params): mixed
    {
        $obj = null;
        try {
            $stmt = $this->PDO->prepare($sql);
            $this->logger->Debug("Finding one with '" . $sql . "' and parameters " . implode(", ", $params));
            if ($stmt != false) {
                $stmt->execute($params);
                $this->logger->Debug("Entities found : " . $stmt->rowCount());
                if ($stmt->rowCount() > 0) {
                    $obj = $stmt->fetch();
                }
                $stmt->closeCursor();
            }
        } catch (PDOException $e) {
            $this->logger->Error($e->getMessage());
        }
        return $obj;
    }

    public function Execute(string $sql, Array $params): void
    {
        try {
            $stmt = $this->PDO->prepare($sql);
            $this->logger->Debug("Executing '" . $sql . "' with parameters " . implode(", ", $params));
            if ($stmt != false) {
                $stmt->execute($params);
                $stmt->closeCursor();
            }
        } catch (PDOException $e) {
            $this->logger->Error($e->getMessage());
        }
    }

}