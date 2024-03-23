<?php
declare(strict_types=1);

//==============Не редактировать
final class DataBase
{
    private bool $isConnected = false;

    public function connect(): bool
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
    public function connectAndFetch($id)
    {
        $dataBase = new DataBase();
        $dataBase->connect();
        $result = $dataBase->fetch($id);
        return $result;
    }

    public function connectAndInsert($id)
    {
        $dataBase = new DataBase();
        $dataBase->connect();
        $result = $dataBase->insert($id);
        return $result;
    }
}

function step1($dataToFetch)
{
    $dataBaseHelper = new DataBaseHelper();

    for ($i = 1; $i < count($dataToFetch); $i++) {
        print($dataBaseHelper->connectAndFetch($dataToFetch[$i]));
        print(PHP_EOL);
    }
}

function step2($dataToInsert)
{
    $dataBaseHelper = new DataBaseHelper();

    for ($i = 0; $i <= count($dataToInsert); $i++) {
        print($dataBaseHelper->connectAndInsert($dataToInsert[$i]));
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