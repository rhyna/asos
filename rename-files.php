<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

function moveFiles($conn, $id)
{
    $sql = "select image from category where id = $id";

    $result = $conn->query($sql);

    $path = $result->fetchColumn();

    if ($path) {
        $nameArr = explode('/', $path);

        $i = 0;

        $path1 = '';

        foreach ($nameArr as $item) {
            $path1 = $path1 . $item . '/';

            $i++;

            if ($i > (substr_count($path, '/') - 1)) {
                break;
            }
        }

        $dir = scandir(__DIR__ . '/../' . $path1);

        $extensions = ['.png', '.jpg', '.jpeg'];

        $file = '';

        foreach ($dir as $item) {
            foreach ($extensions as $extension) {
                if (strpos($item, $extension) !== false) {
                    $file = $item;
                }
            }
        }

        $newPath = __DIR__ . '/..' . '/upload/category/';

        copy(__DIR__ . '/..' . $path, $newPath . $file);

        $newFile = '/upload/category/' . $file;

        return $newFile;
    }

    return null;
}

function updateTable($conn, $newFile, $id)
{

    $sql = "update category set image = :newFile where id = :id";

    $statement = $conn->prepare($sql);

    $statement->bindValue(':newFile', $newFile, PDO::PARAM_STR);

    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    $statement->execute();
}

function getIds($conn)
{
    $sql = "select id from category";

    $result = $conn->query($sql);

    $ids = $result->fetchAll();

    $idArr = [];

    foreach ($ids as $id) {
        $idArr[] = $id['id'];
    }

    return $idArr;
}

$ids = getIds($conn);

//foreach ($ids as $id) {
//    $newFile = moveFiles($conn, $id);
//
//    if ($newFile) {
//        updateTable($conn, $newFile, $id);
//    }
//}