<?php

namespace KDE\Controller;

use TBCore\Model\Model;

/**
 * Class DbinstallController
 * @package KDE\Controller
 */
class DbinstallController extends DefaultController
{
    public function start()
    {
        $database = "kde";
        $dbDummy = $this->dbManager->dbDummy();
        $tables = $dbDummy->getTables(null, $database);

        $dir = __DIR__ . "/../Model";
        $objects = scandir($dir);
        $prefix = "\KDE\Model\\";
        $list = [];
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . " / " . $object)) {

                } else {
                    $list[] = $prefix . explode('.php', $object)[0];
                }
            }
        }
        $list = ["\KDE\Model\User"];
        foreach ($list as $classname) {
            /** @var \TBCore\Model\Model $class */
            $class = new $classname();
            $tableName = $class->getTableName();
            if (strpos($tableName, ".") !== false) {
                $tableName = explode(".", $tableName)[1];
            }
            if (in_array($tableName, $tables)) {
                // update
                $sql = "";
                $fields = $dbDummy->getFieldsOfDbTableRaw($class->getTableName());

                $assoc = [];
                foreach ($fields as $value) {
                    $assoc[$value['Field']] = $value['Type'];
                }
                $this->createUpdate($classname, $assoc);
            } else {
                // create
                $sql = $this->createTable($classname);
                $dbDummy->q($sql);
                var_dump($sql);
            }
        }

        $dbDummy->close();
        die;
    }


    function createTable($classname)
    {

        $category = new $classname();
        $properties = $category->getProperties();
        $tableName = $category->getTableName();

        $sql = "CREATE TABLE $tableName (";

        foreach ($properties as $property => $value) {
            $annotation = $category->getAnnotation($property, "TBCore\Annotation\Type");
            $primary = $category->getAnnotation($property, "TBCore\Annotation\PrimaryKey");
            $sql .= $this->line($property, $annotation->value, $primary);
        }

        $sql = mb_substr($sql, 0, -1);
        $sql .= ");";

        return $sql;
    }

    function createUpdate($classname, $assoc)
    {
        $dbDummy = $this->dbManager->dbDummy();
        $category = new $classname();
        $properties = $category->getProperties();
        $tableName = $category->getTableName();

        foreach ($properties as $property => $value) {

            $annotation = $category->getAnnotation($property, "TBCore\Annotation\Type");
            if (!isset($assoc[$property])) {
                $sql = "ALTER TABLE $tableName ADD COLUMN $property " . $this->mapType($annotation->value) . "  NOT NULL;";
                $dbDummy->q($sql);
            }
        }
    }

    function mapType($raw)
    {
        switch ($raw) {
            case "int":
                return "int(11)";
                break;
            case "varchar":
                return "varchar(255)";
                break;
            case "text":
                return "text";
        }
        return "";
    }

    function line($property, $annotationValue, $primary)
    {
        $midfix = "";
        if ($primary !== null) {
            $midfix = " AUTO_INCREMENT PRIMARY KEY ";
        }
        return $property . ' ' . $this->mapType($annotationValue) . $midfix . " NOT NULL,";
    }
}
