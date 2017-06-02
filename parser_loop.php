<?php
//обрезаем дату
$received_date = substr($log_result[ReceivedAt], 10);
//Присваиваем пустое значение $switch_flag если не будет изменена ни одним условием, 
//выведится пустая строка
$switch_flag = "";
//****************************
//ищем вернувшиеся сообщения от eltex ma4000 т.к. в них присутствует %loop%
$ma4000 = strpos($string, "messages are looping back");
if  ($ma4000 === false){
    //строка не найдена
    //оставляем switch_flag пустым
} else {
    //строка найдена
    $switch_flag = "ma4000";
}
// порт или влан заблокированы
$blocked = strpos($string, "blocked");
//меняем флаг tgrad на 1 (true)
if  ($blocked === false){
    //строка не найдена
    //оставляем switch_flag пустым
} else {
    //строка найдена
    $switch_flag = "blocked";
}
//порт или влан разблокированы по таймауту
$recovered = strpos($string, "recovered");
//меняем флаг tgrad на 1 (true)
if  ($recovered === false){
    //строка не найдена
    //оставляем switch_flag пустым
} else {
    //строка найдена
    $switch_flag = "recovered";
}

//Начинаем перепиливать строчку в соответствии с совпадением $log_result[FromHost]
//на основе $switch_flag проводим изменение строки
switch ($switch_flag) {
    //убиваем сообщения с ma4000
    case "ma4000":
        $p_string = "";
        break;
    case "blocked":
        $p_string = "<tr class=\"bg-danger\"><td>". $received_date ."</td><td><a href=\"search.php?ip=". long2ip($log_result[FromHost])."&date=". $date ."\">". long2ip($log_result[FromHost]) ."</a></td><td>" . $string . "</td></tr>";
        break;
    //*
    case "recovered":
        $p_string = "<tr class=\"bg-success\"><td>". $received_date ."</td><td><a href=\"search.php?ip=". long2ip($log_result[FromHost])."&date=". $date ."\">". long2ip($log_result[FromHost]) ."</a></td><td>" . $string . "</td></tr>";
        break;
    //если срабатыаний нету, выводим исходную строку !!! ДОЛЖНО БЫТЬ ПОСЛЕДНИМ УСЛОВИЕМ !!!
    case "":
        $p_string = "<tr><td>". $received_date ."</td><td><a href=\"search.php?ip=". long2ip($log_result[FromHost])."&date=". $date ."\">". long2ip($log_result[FromHost]) ."</a></td><td>" . $string . "</td></tr>";
        break;
}
