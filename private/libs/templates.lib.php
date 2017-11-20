<?php

/* Functions */


function component(
        $region,
        $name, 
        $params, 
        $type = 0 /* 0 = public or private */,
        $expira = 1, 
        $crc = NULL)
{
    global $path;
    global $g_components;
    global $g_crc;
    global $g_ajax;
    $new = false;

    $cache_path = $path['cache'].($type == 0 ? '' : session_id().'-');
    $cache_filename = $cache_path.$_SESSION['lang'].'-'.slash($name).'.cache.php';
    $cache_info_filename = $cache_path.$_SESSION['lang'].'-'.slash($name).'.info.php';

    if (file_exists($cache_info_filename)) {
        $info = file_get_contents($cache_info_filename);
        list($creation, $expiration,$crc) = explode('|',$info);
        $current_datetime = date('YmdHis');
        if ($current_datetime < $expiration){
            $content = file_get_contents($cache_filename);
        }
        else {
            $new = true;
        }
    }
    else {
        $new = true;
    }

    if ($new){
        ob_start();
        require $path['components'].$name.'.comp.php';
        $content = ob_get_contents();
        ob_end_clean();
        $crc = crc32($content);

        // Storing the cache file
        file_put_contents($cache_filename,$content);

        // Storing file information
        $expiration = date('YmdHis',mktime(date('H'),date('i')+$expira,date('s'),date('m'),date('d'),date('Y')));
        $creation = date('YmdHis');
        file_put_contents($cache_info_filename, $creation.'|'. $expiration.'|'.$crc);
    }
    if (!isset($g_components[$region])){
        $g_components[$region] = array();
        $g_crc[$region] = array();
    }

    $g_components[$region][slash($name)] = '<div class="component component-' . slash($name) . '">';
    $g_components[$region][slash($name)] .= $content;
    $g_components[$region][slash($name)] .= '</div>';
    $g_crc[$region][slash($name)] = $crc;

    if ($g_ajax) {
        if (isset($_COOKIE['page_information'])){
            $page_information = json_decode($_COOKIE['page_information'],true);
            reset($page_information);
            $layout = key($page_information);
            if ($page_information[$layout][$region][slash($name)] == $crc){
                $g_components[$region][slash($name)] = '<null>';
            }
        }
    }


    // return $g_components[$region][slash($name)];
}


function region($name)
{
    global $g_components;
    global $g_crc;


    $output = '';
    $crc = 0;
    if (isset($g_components[$name])) {
        if (isset($g_components[$name]) && count($g_components[$name]) > 0){
            foreach($g_components[$name] as $block_name => $block){
                $output .= $block;
            }
        }
        // $crc = crc32($output);
        // $g_crc[$name]['_crc_region'] = $crc;
        echo '<div class="region region-'.slash($name).'">';
        echo $output;
        echo '</div>';
    }
}



function tpl($name,$params, $type = 1){

    global $path;
    global $g_components;
    global $g_crc;
    global $g_ajax;

    // Types definition
    $types = array();
    $types[0] = 'layout';
    $types[1] = 'components';
    
    if ($type == 0){

        // Ajax output
        if ($g_ajax){

            // Saving the layout name
            $tmp = $g_crc;
            $g_crc = array();
            $g_crc[$name] = $tmp;

            // Setting the cookie master value
            setcookie('page_information', json_encode($g_crc), time() + (86400 * 30), "/"); // 86400 = 1 day
            
            echo json_encode($g_components);
            exit;
        }

        // Normal output
        ob_start();
        require $path['templates'].$types[$type].'/'.$name.'.tpl.php';
        $content = ob_get_contents();
        ob_end_clean();

        // Saving the layout name
        $tmp = $g_crc;
        $g_crc = array();
        $g_crc[$name] = $tmp;
        //$g_crc[$name]['crc_layout'] = crc32($content);

        // Setting the cookie master value
        setcookie('page_information', json_encode($g_crc), time() + (86400 * 30), "/"); // 86400 = 1 day
        
        return $content;
    }
    else {
        require $path['templates'].$types[$type].'/'.$name.'.tpl.php';
    }
 
}
