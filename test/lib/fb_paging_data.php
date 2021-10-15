<?php
require_once '../lib/db.php';

$dataView = 10;  // 데이터를 보여줄 개수 (10개)

if( !isset($_REQUEST['page']) ){

    $sql = " SELECT * FROM `board` ORDER BY `idx` DESC LIMIT 0, {$dataView} "; 
    $result = mysqli_query($conn, $sql);

} else {

    $fb_page = $_REQUEST['page'];

    $fb_pageView = ($fb_page - 1) * $dataView;
    $sql = " SELECT * FROM `board` ORDER BY `idx` DESC LIMIT {$fb_pageView}, {$dataView} "; 
    $result = mysqli_query($conn, $sql);
    
}

?>