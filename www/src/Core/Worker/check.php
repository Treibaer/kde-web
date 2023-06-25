<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 07.01.17
 * Time: 01:45
 */

require '../vendor/autoload.php';
// checks all class properties

$folder = 'Models';

function getModelClasses() {
    $dir = 'src/Models';
    $dh = opendir($dir);
    $m = [];
    while (false !== ($filename = readdir($dh))) {
        if (strlen($filename) > 2 && $filename !== 'Model.php') {
            $m[] = $dir . '/' . $filename;
        }
    }
    closedir($dh);
    return $m;
}

function getPropertiesOfAClass($className) {
    /** @var \Models\Model $v */
    $v = new $className();
    return array_keys($v->getProperties());
}

// start
$status = [];
$classes = getModelClasses();
$dbManager = new \database\DbManager();
$db = new \database\Database($dbManager);
$tables = $db->getTables();
$globalCorrectClassDb = 0;
$globalErrorClassDb = 0;
$globalCorrectDbClass = 0;
$globalErrorDbClass = 0;
$done = [];
echo "Starting with the classes:<br>";
for ($i = 0; $i < count($classes); $i++) {
    $class = str_replace('/', '\\', str_replace('.php', '', $classes[$i]));
    $properties = getPropertiesOfAClass($class);
    $tableName = explode("\\", $class)[1];
    $fields = $db->getFieldsOfDbTable($tableName);
    $errors = compareClassDb($tableName, $properties, $fields);
    if (count($errors) == 0) {
        interpretError("Table $tableName complete",'green');
    }
    interpretErrors($errors, 'class');
    $errors = compareDbClass($tableName, $properties, $fields);
    //var_dump($errors);
    interpretErrors($errors, 'table');
    $done[] = $tableName;
    //break;
}
echo "Starting with the tables:<br>";
for ($i = 0; $i < count($tables); $i++) {
    $tableName = $tables[$i];
    $fc = substr($tableName, 0, 1);
    if (strtoupper($fc) != $fc) {
        continue;
    }
    if (!in_array($tableName, $done)) {
        $globalErrorDbClass++;
        interpretError("Class $tableName not found", 'red');
    } else {
        $globalCorrectDbClass++;
        interpretError("Class $tableName found", 'green');
    }
    $done[] = $tableName;
}
echo "Done.<br>";
echo "Results:<br>";
echo "<b>compareClassDb</b> correct: " . $globalCorrectClassDb . " (" . round($globalCorrectClassDb / ($globalCorrectClassDb + $globalErrorClassDb) * 100, 2) . "%), Error: " . $globalErrorClassDb . "<br>";
echo "<b>compareDbClass</b> correct: " . $globalCorrectDbClass . " (" . round($globalCorrectDbClass / ($globalCorrectDbClass + $globalErrorDbClass) * 100, 2) . "%), Error: " . $globalErrorDbClass . "<br>";
function interpretError($error, $mode) {
    if ($mode === 'red') {
        echo "<font color=red>" . $error . "</font>";
    } else {
        echo "<font color=green>" . $error . "</font>";
    }
    echo "<br>";
}

function interpretErrors($errors, $start) {
    for ($i = 0; $i < count($errors); $i++) {
        if ($start === 'table') {
            echo "<font color=red>" . $errors[$i] . "</font>";
        } else {
            echo "<font color=#ffaa00>" . $errors[$i] . "</font>";
        }
        echo "<br>";
    }

}

function compareClassDb($name, $classProperties, $tableFields) {
    global $globalCorrectClassDb, $globalErrorClassDb;
    $errors = [];
    for ($i = 0; $i < count($classProperties); $i++) {
        $cp = $classProperties[$i];
        if (!in_array($cp, $tableFields)) {
            $globalErrorClassDb++;
            $errors[] = "Table $name doesn't have a property named $cp";
        } else {
            $globalCorrectClassDb++;
        }
    }
    return $errors;
}

function compareDbClass($name, $classProperties, $tableFields) {
    global $globalCorrectDbClass, $globalErrorDbClass;
    $errors = [];
    for ($i = 0; $i < count($tableFields); $i++) {
        $cp = $tableFields[$i];
        if (!in_array($cp, $classProperties)) {
            $globalErrorDbClass++;
            $errors[] = "Class $name doesn't have a property named $cp";
        } else {
            $globalCorrectDbClass++;
        }
    }
    return $errors;
}
