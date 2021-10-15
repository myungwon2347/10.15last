<?php
    if(!empty($conn)){
        $sql = "SELECT * FROM `board`";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_array($result);
    }
?>