<?php

component('navigation', 'global/navigation',array(),0,1);
component('main-content','pages/index',array(),0,0);
component('bottom-columns','utils/columns',array());
component('footer','global/footer',array(),0,1);

// Layout
$output = tpl('default',array(),0);

echo $output;