<?php
require_once '../lib/db.php';

    
if( !isset($_REQUEST['page']) ){

    $sql = " SELECT * FROM `board` ORDER BY `idx` DESC LIMIT 0, 100 ";  // 단지 데이터 개수를 가져올 뿐
    $result = mysqli_query($conn, $sql);
    
}


$sheetView = 10;  // 시트를 보여줄 데이터 개수 (100개)
$dataCount = " SELECT COUNT(*)/$sheetView FROM `board` ";
$result = mysqli_query($conn, $dataCount);
$dataCount = mysqli_fetch_array($result);
$sheetCount = $dataCount[0]; // 시트 총 개수 24.4개
// $sql = " SELECT * FROM `board` ORDER BY `id` DESC LIMIT 10, 10 "; 
// $result = mysqli_query($conn, $sql);
?>