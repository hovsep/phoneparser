<?php

require_once 'db.php';

$name = empty($_POST['name']) ? '' : $_POST['name'];
$phone = empty($_POST['phone']) ? '' : $_POST['phone'];

//Store raw input
DB::getInstance()
    ->prepare('INSERT INTO raw_input (name, phone) VALUES (:name, :phone)')
    ->execute([
        ':name' => $name,
        ':phone' => $phone
    ]);