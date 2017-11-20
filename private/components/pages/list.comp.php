<?php

$result = db_query('select * from user');

while($row = db_fetch($result)){
    $data[] = $row['username'];
}

db_free($result);


tpl('pages/user_list',array('data'=>$data));