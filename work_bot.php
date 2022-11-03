<?php
include "const.php";
//const TOKEN='';
const URL = 'https://api.telegram.org/bot' . TOKEN . '/';
const Method_Update = 'getUpdates';
const Method_Send = 'sendMessage';

    //get all message and find the last one
    $array =json_decode(file_get_contents(URL . Method_Update), JSON_OBJECT_AS_ARRAY);
    $last_id_message=$array['result'][count($array['result'])-1]['update_id'];
    $last_chat_id=$array['result'][count($array['result'])-1]['message']['chat']['id'];
    //default text
    $text="Ошибка выполнения";

//main function
function useMethod($method,$options=[]){
    global $text;

    //the last message
    $url =URL . $method. '?' . http_build_query($options);
    $array= json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);

    //for update
    if($method == 'getUpdates'){

        //get last message
        $last_message_text=$array['result'][count($array['result'])-1]['message']['text'];

        //if this is a command
        if($last_message_text=='/start'){
            $text = "Добрый день, выберите нужную команду: \n/command1 - для подсчета дней \n/command2 - для подсчета даты";
        }
        else if($last_message_text=='/command1'){
            $text = "Введите дату, до которой нужно посчитать дни, в формате д.м.гггг";
        }
        else if($last_message_text=='/command2'){
            $text = "Введите кол-во дней";
        }
        //if this is no command
        else {
            //the previous message
            $previous_message_text=$array['result'][count($array['result'])-2]['message']['text'];

            //if the previous one is command
            if($previous_message_text=='/command1' and strtotime($last_message_text)){
                $text = command1($last_message_text);
            }

            else if($previous_message_text=='/command2' and is_numeric($last_message_text) and $last_message_text>-1){
               $text = command2($last_message_text);
            }

           //if the previous one is no command
           else $text = "Ошибка команды \nИспользуйте:\n/command1 - для подсчета дней \n/command2 - для подсчета даты";
        }
    }
    return $array;

}
//function for command1
function command1($date){
    $now=time();
    $date=strtotime($date);

    if($now>$date){
        $days = round(($now-$date)/(60*60*24))-1;

        if($days==1 or $days %10== 1)
            $text = "Прошел ". $days. " день";
        else if (($days >1 and $days < 5) or ( $days % 10 > 1 and $days % 10 < 5))
            $text = "Прошло ". $days. " дня";
        else
            $text = "Прошло ". $days. " дней";
    }

    else{
        $days =  round(($date-$now)/(60*60*24))+1;

        if($days==1 or $days%10==1)
            $text = "Остался ". $days. " день";
        else if (($days >1 and $days < 5) or ( $days % 10 > 1 and $days % 10 < 5))
            $text = "Осталось ". $days. " дня";
        else
            $text = "Осталось ". $days. " дней";
    }
    return $text;
}

//function for command2
function command2($days){
    $text="Через ". $days;

    if($days==1 or $days %10== 1 )
        $text.=" день будет " ;
    else if (($days >1 and $days < 5) or ( $days % 10 > 1 and $days % 10 < 5))
        $text.=" дня будет ";
    else
        $text.=" дней будет ";

    $now = time();
    $days='+ '. $days . ' day';
    return $text . date("d.m.Y", strtotime($days,$now));
}

echo '<pre>';
print_r("Последние полученные сообщения: \n");
print_r(useMethod(Method_Update,['offset'=>$last_id_message-1]));
print_r("Последнее отправленное сообщение: \n");
print_r(useMethod(Method_Send,[ 'chat_id' => $last_chat_id,'text' => $text]));
echo '<pre>';