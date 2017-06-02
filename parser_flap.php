<?php
//обрезаем дату
$received_date = substr($log_result[ReceivedAt], 10);
//Присваиваем пустое значение $switch_flag если не будет изменена ни одним условием, 
//выведится пустая строка
$switch_flag = "";
//****************************
$flapping = strpos($string, "flapping");
//меняем флаг tgrad на 1 (true)
if  ($flapping === false){
    //строка не найдена
    //оставляем switch_flag пустым
} else {
    //строка найдена
    $switch_flag = "flapping";
    $count_flapping++;
}

//Начинаем перепиливать строчку в соответствии с совпадением $log_result[FromHost]
//на основе $switch_flag проводим изменение строки
switch ($switch_flag) {
    case "flapping":
        $p_string = "<tr ><td>". $received_date ."</td><td><a href=\"search.php?ip=". long2ip($log_result[FromHost])."&date=". $date ."\">". long2ip($log_result[FromHost]) ."</a></td><td>" . $string . "</td></tr>";
        break;
    //если срабатыаний нету, выводим исходную строку !!! ДОЛЖНО БЫТЬ ПОСЛЕДНИМ УСЛОВИЕМ !!!
    case "":
        $p_string = "<tr><td></td><td>". $received_date ."</td><td><a href=\"search.php?ip=". long2ip($log_result[FromHost])."&date=". $date ."\">". long2ip($log_result[FromHost]) ."</a></td><td>" . $string . "</td></tr>";
        break;
}
