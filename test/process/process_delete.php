<?php

use PhpMyAdmin\Console;

use function PHPSTORM_META\type;

require_once '../lib/db.php';

$delArr = $_REQUEST['idx'];

for($i = 0; $i < count($delArr); $i++){
    $query = "DELETE FROM `board` WHERE `id`= $delArr[$i]";    
    $result = mysqli_query($conn, $query);
}



?>
