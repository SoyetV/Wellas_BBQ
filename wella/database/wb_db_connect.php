<?php

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wb_db";
    $conn = null;

    try {
        $conn = mysqli_connect($hostname, $username, $password, $dbname);
    } catch(Exception $e) {
        echo `<script>console.log(Error: $e);</script>`;
    }
?>