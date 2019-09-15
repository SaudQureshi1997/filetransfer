<?php

$router->post('/uploads', 'FileTransferController@store');
$router->delete('/uploads', 'FileTransferController@destroy');
$router->get('/assets/{path:.+}', 'FileTransferController@show');
