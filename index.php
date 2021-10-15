<?php
    namespace util;
   
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    // 방문자 로깅
    Visit::log();
    
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/index.php";
    // require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/head.php";
?>