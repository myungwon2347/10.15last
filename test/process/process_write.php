<?php

require_once '../lib/db.php';

$name = mysqli_real_escape_string($conn, $_REQUEST['name']);
$title = mysqli_real_escape_string($conn, $_REQUEST['title']);
$memo = mysqli_real_escape_string($conn, $_REQUEST['memo']);
$date = date('Y-m-d H:i:s');

$query = "INSERT INTO `board`(`name`, `title`, `memo`, `date`)
    values('{$name}', '{$title}', '{$memo}', '{$date}')";
$result = mysqli_query($conn, $query);

?>
