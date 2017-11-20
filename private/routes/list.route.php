<?php

component('navigation', 'global/navigation',array(),'public',30);
component('main-content','pages/list',array(),'private');
component('bottom-columns','utils/columns',array());
component('footer','global/footer',array());
component('footer','utils/columns',array());

// Layout
echo tpl('default',array(),0);
