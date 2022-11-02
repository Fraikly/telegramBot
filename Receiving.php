<?php

const TOKEN = '5666064256:AAEbqI8sO5H-Mpg0bEVKD6EithD9HMPNbE4';
$url = 'https://api.telegram.org/bot' . TOKEN . '/getUpdates';


//getting all last responses
$array =json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);


//getting the update_id of the most recent message
$last_id_message=$array['result'][count($array['result'])-1]['update_id'];

$last_chat_id=$array['result'][count($array['result'])-1]['message']['chat']['id'];


//getting only the most recent message
$options=[
    'offset' =>  $last_id_message
];
$url = $url . '?' . http_build_query($options);
$array =json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);


//show
echo '<pre>';
print_r($array);
echo '<pre>';