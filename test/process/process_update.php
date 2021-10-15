<?php

$id = $_REQUEST['id'];
$name = $_REQUEST['name'];
$title = $_REQUEST['title'];
$memo = $_REQUEST['memo'];

$query = "UPDATE `board` SET `title` = '{$title}', `memo` = '{$memo}', `name` = '{$name}' WHERE `idx` = {$id}";
$result = mysqli_query($conn, $query);



?>
