<?php
    $serverName = "localhost";
    $userName = "root";
    $password = "quyetboit";
    $databaseName = "ql_nhac";

    $conn = new mysqli($serverName, $userName, $password, $databaseName);

    if($conn->connect_error) {
        die("Kết nối không thành công" . $conn -> connect_error);
    }