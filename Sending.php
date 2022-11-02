<?php

const TOKEN = '5666064256:AAEbqI8sO5H-Mpg0bEVKD6EithD9HMPNbE4';
$url = 'https://api.telegram.org/bot' . TOKEN . '/sendMessage';


//getting only the most recent message
$options=[
    'chat_id' => $last_chat_id,
    'text' => "Проверка1"
];
$url = $url . '?' . http_build_query($options);
$array =json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);


//show
echo '<pre>';
print_r($array);
echo '<pre>';
