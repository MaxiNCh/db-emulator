<?php
declare(strict_types=1);

//==============Не редактировать
final class DataBase
{
    private bool $isConnected = false;

    public function connect(): string
    {
        sleep(1);
        $this->isConnected = true;
        return 'connected';
    }

    public function random()
    {
        $this->isConnected = rand(0, 3) ? $this->isConnected : false;
    }

    public function fetch($id): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(100000);
        return 'fetched - ' . $id;
    }

    public function insert($data): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(900000);
        return 'inserted - ' . $data;
    }


    public function batchInsert($data): string
    {
        $this->random();
        if (!$this->isConnected) {
            throw new Exception('No connection');
        }
        usleep(900000);
        return 'batch inserted';
    }
}
//==============

class DataBaseHelper
{
    const FETCH_ACTION = 'fetch';
    const INSERT_ACTION = 'insert';
    const BATCH_INSERT = 'batchInsert';

    const MAX_RECONNECTS = 10;

    private DataBase $dataBase;

    private function __construct()
    {
        $this->dataBase = new DataBase();
        $this->connect();
    }

    public static function getInstance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new static();
        }

        return $instance;
    }

    public function connect()
    {
        $this->dataBase->connect();
    }

    public function fetch($id)
    {
        return $this->requestWithRecconnection(self::FETCH_ACTION, $id);
    }

    public function insert($id)
    {
        return $this->requestWithRecconnection(self::INSERT_ACTION, $id);
    }

    private function requestWithRecconnection(string $action, $id): string
    {
        if (!is_callable([$this->dataBase, $action])) {
            throw new Exception('Not allowed action');
        }
        $attempts = 1;

        do {
            try {
                return $this->dataBase->$action($id);
            } catch (Exception $e) {
                $attempts++;

                if ($e->getMessage() !== 'No connection' || $attempts > self::MAX_RECONNECTS) {
                    throw $e;
                }

                $this->dataBase->connect();
            }
        } while ($attempts <= self::MAX_RECONNECTS);
    }
}

function step1($dataToFetch)
{
    $dataBaseHelper = DataBaseHelper::getInstance();

    for ($i = 0; $i < count($dataToFetch); $i++) {
        print($dataBaseHelper->fetch($dataToFetch[$i]));
        print(PHP_EOL);
    }
}

function step2($dataToInsert)
{
    $dataBaseHelper = DataBaseHelper::getInstance();

    for ($i = 0; $i < count($dataToInsert); $i++) {
        print($dataBaseHelper->insert($dataToInsert[$i]));
        print(PHP_EOL);
    }
}

//==============Не редактировать
$dataToFetch = [1, 2, 3, 4, 5, 6];
$dataToInsert = [7, 8, 9, 10, 11, 12];

step1($dataToFetch);
step2($dataToInsert);
print("Success");
//==============
