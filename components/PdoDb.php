<?php
namespace Component;

class PdoDb
{
    public static $db;

    public static function connecting($db_config)
    {
        $dsn = "mysql:host=".$db_config['dbhost'].";dbname=".$db_config['dbname'].";charset=".$db_config['dbcharset'];
        $opt = [
            $db_config['dbtype']::ATTR_ERRMODE            => $db_config['dbtype']::ERRMODE_EXCEPTION,
            $db_config['dbtype']::ATTR_DEFAULT_FETCH_MODE => $db_config['dbtype']::FETCH_ASSOC,
            $db_config['dbtype']::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$db = new $db_config['dbtype']($dsn, $db_config['dbuser'], $db_config['dbpassword'], $opt);
            return new PdoDb();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    public function findAll($arguments)
    {   
        $stmt = self::$db->prepare($arguments[0]);
        $stmt->execute($arguments[1]);

        return $stmt->fetchAll();
    }

    public function findOne($arguments)
    {
        $stmt = self::$db->prepare($arguments[0]);
        $stmt->execute($arguments[1]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($arguments)
    {
        $stmt = self::$db->prepare($arguments[0]);
        $stmt->execute($arguments[1]);

        return self::$db->lastInsertId();

    }

    public function update($arguments)
    {
        $stmt = self::$db->prepare($arguments[0]);
        $stmt->execute($arguments[1]);

    }

    public function delete($arguments)
    {
        $stmt = $this->db->prepare($arguments[0]);
        $stmt->execute($arguments[1]);
    }
}
