<?php

$g_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

$config['languages'] = array(
    'en' => 'English',
    'es' => 'Español',
    'fr' => 'French',
    'pt' => 'Portuguese',
    'it' => 'Italiano'
);

$config['default_language'] = 'es';