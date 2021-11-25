<?php
$json = file_get_contents("php://input");
$request = json_decode($json, true);
file_put_contents('./logs/log.txt', var_export($request, true), FILE_APPEND);

$data = str_replace('data:image/jpeg;base64,', '', $request['data']);
$data = str_replace(' ', '+', $data);
$image = base64_decode($data);

//file_put_contents('./logs/test.png', $image);
file_put_contents('./logs/test.jpg', $image);

echo json_encode(['result' => './logs/test.jpg?' . time()]);
