<?php
include "const.php";
const Method_Update = 'getUpdates';
const Method_Send = 'sendMessage';


    $array =json_decode(file_get_contents(URL . Method_Update), JSON_OBJECT_AS_ARRAY);
    $last_id_message=$array['result'][count($array['result'])-1]['update_id'];
    $last_chat_id=$array['result'][count($array['result'])-1]['message']['chat']['id'];


function useMethod($method,$options=[]){
    $url =URL . $method. '?' . http_build_query($options);
    return  json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);

}

echo '<pre>';
print_r("Последнее полученное сообщение: \n");
print_r(useMethod(Method_Update,['offset'=>$last_id_message]));
print_r("Последнее отправленное сообщение: \n");
print_r(useMethod(Method_Send,[ 'chat_id' => $last_chat_id,'text' => "Проверка1"]));
echo '<pre>';