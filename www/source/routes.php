<?php

$router->get('', 'PageController@main');
$router->get('getUserList', 'HandleController@getUserList');
$router->post('delete', 'HandleController@delete');
$router->post('saveUser', 'HandleController@addUser');
//$router->post('');