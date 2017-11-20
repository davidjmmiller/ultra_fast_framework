<?php

// Utils
function pre($var){
    echo '<pre>'.print_r($var,true).'</pre>';
}


function slash($t){
    return str_replace('/','-',$t);
}

function route_exists($g_path,$routes){
    global $g_parameters;
    global $g_languages;
    global $config;
    $size = 0;
    $selected_route = "";

    // Detecting language
    $part = explode('/',$g_path);
    if (isset($config['languages'][$part[0]])){
        $_SESSION['lang'] = $part[0];
        $g_path = substr($g_path,3);
    }

    $g_path = (trim($g_path) == '' ? 'index': $g_path);

    foreach($routes as $route){
        if($route == substr($g_path,0,strlen($route))){
            if ($size < strlen($route)){
                $size = strlen($route);
                $selected_route = $route;

                // Extracting parameters
                $g_parameters = explode('/',substr($g_path,strlen($route)+1));
            }

        }
    }

    if ($size == 0){
        return false;
    }
    else {
        return $selected_route;
    }
    // return array_key_exists($g_path,$routes);
}

function url($path){
    return '/'.$_SESSION['lang'].'/'.$path;
}

function t($key){
    global $g_translate;
    return $g_translate[$key][$_SESSION['lang']];
}

function lang($key,$en,$es,$fr = ''){
    global $g_translate;
    $g_translate[$key]['en'] = $en;
    $g_translate[$key]['es'] = $es;
    $g_translate[$key]['fr'] = $fr;
}