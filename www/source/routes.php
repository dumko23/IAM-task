<?php

$router->get('', 'PageController@main');
$router->get('getUserList', 'HandleController@getUserList');
$router->post('deleteOne', 'HandleController@deleteOne');
//$router->post('');