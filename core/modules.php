<?php
// Array with name module's folder
$modules = array('');

foreach ($modules as &$module) {
    if(file_exists('modules/'.$module.'/index.php'))
        include('modules/'.$module.'/index.php');
}
