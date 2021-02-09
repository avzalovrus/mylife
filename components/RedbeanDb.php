<?php
namespace Component;

class RedbeanDb{
    public static $db;
    public static function connecting($db_config)
    {
        $db_config['dbtype']::setup('mysql:host='.$db_config['dbhost'].';dbname='.$db_config['dbname'].', '.$db_config['dbuser'].', '.$db_config['dbpassword']);
        $db = $db_config['dbtype'];
        if(!$db_config['dbtype']::testConnection()){
            throw new \Exception("Ошибка: Проблема с подключением к базе данных!");
        }
        return new RedbeanDb();
    }

    public function findAll($arguments)
    {
        return self::$db::getAll($arguments[0], $arguments[1]);
    }

    public function findOne($arguments)
    {
        return self::$db::getRow($arguments[0], $arguments[1]);
    }

    public function insert($arguments)
    {
        return self::$db::exec($arguments[0], $arguments[1]);
    }

    public function update($arguments)
    {
        return self::$db::exec($arguments[0], $arguments[1]);

    }

    public function delete($arguments)
    {
        return self::$db::exec($arguments[0], $arguments[1]);

    }


}