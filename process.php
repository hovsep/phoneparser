<?php

require_once 'db.php';
require_once 'parser.php';

$db = DB::getInstance();

$name = empty($_POST['name']) ? '' : $_POST['name'];
$phone = empty($_POST['phone']) ? '' : $_POST['phone'];

//Store raw input
$db->prepare('INSERT INTO raw_input (name, phone) VALUES (:name, :phone)')
    ->execute([
        ':name' => $name,
        ':phone' => $phone
    ]);


$phoneNumbers = parse($phone);

if (!empty($phoneNumbers)) {
    //Search client by numbers
    $stmt = $db->prepare('SELECT client_id FROM phone_numbers WHERE number IN (:numbers) LIMIT 1');
    $stmt->execute([
            ':numbers' => implode(',', $phoneNumbers)
        ]);
    $data = $stmt->fetchAll();

    if (!empty($data)) {
        echo 'Returning client!';
    } else {
        //Store new client
        try {
            $db->beginTransaction();
            $db->prepare('INSERT INTO clients (name) VALUES (:name)')
                ->execute([
                    ':name' => $name
                ]);
            $clientId = $db->lastInsertId();
            foreach ($phoneNumbers as $phoneNumber) {
                $db->prepare('INSERT INTO phone_numbers (number, client_id) VALUES (:number, :client_id)')
                    ->execute([
                        ':number' => $phoneNumber,
                        ':client_id' => $clientId
                    ]);
            }
            $db->commit();
            echo 'New client stored!';
        } catch (\Exception $e) {
            echo 'Oops! We f*cked up';
            $db->rollBack();
        }
    }

} else {
    echo 'No phone numbers detected :(', '<br>But we have saved your input :)';
}
