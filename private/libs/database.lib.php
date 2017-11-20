<?php

// Database
function db_connect(){
    global $config;
    static $link;
    if (isset($link)) {
        // Connection already exists
        return $link;
    }
    $link = mysqli_connect(
        $config['database']['host'],
        $config['database']['username'],
        $config['database']['password'],
        $config['database']['name'],
        $config['database']['port']
    );

    if (!$link) {
        return false;
    }

    // New connection
    return $link;
}

function db_query($sql){
    $link = db_connect();
    if ($result = mysqli_query($link, $sql)){
        return $result;
    }
    return false;
}

function db_fetch($result){
    return mysqli_fetch_assoc($result);
}

function db_free($result){
    mysqli_free_result($result);
}

function db_num_rows($result){
    return mysqli_num_rows($result);
}
