<?php   
    //обрезаем дату
    $date = substr($log_result[ReceivedAt], 10);
    //Присваиваем пустое значение $switch_flag если не будет изменена ни одним условием, 
    //выведится пустая строка
    $switch_flag = "";
    //*************************************************************
    //ВЫЧИЩАЕМ ЛИШНЕЕ ИЗ ЛОГОВ
    //*************************************************************
    //ищем вхождение tgrad
    $tgrad = strpos($string, "tgrad");
    //меняем флаг tgrad на 1 (true)
    if  ($tgrad === false){
        //строка не найдена
        //оставляем switch_flag пустым
    } else {
        //строка найдена
        $switch_flag = "tgrad";
    }
    //ищем неверные SNMP запросы
    $snmp = strpos($string, "SNMP request received from");
    if  ($snmp === false){
        //строка не найдена
        //оставляем switch_flag пустым
    } else {
        //строка найдена
        $switch_flag = "snmp";
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //*************************************************************
    //РАБОТА С MES
    //*************************************************************
    //ищем перезагрузку питанию
    $cold_start_M = strpos($string, "Cold Startup");
    if ($cold_start_M == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "cold-start";
    }
    //ищем перезагрузку из консоли или мониторинга
    $warm_start_M = strpos($string, "Warm Startup");
    if ($warm_start_M == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "warm-start";
    }
    //ищем события поднятия порта в MES
    $linkUP_M = strpos($string, "LINK-I-Up");
    if ($linkUP_M == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "LinkUP";
    }
    //*************************************************************
    //ищем события падения порта в MES
    $linkDOWN_M = strpos($string, "LINK-W-Down");
    if ($linkDOWN_M == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "LinkDOWN";
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //*************************************************************
    //РАБОТА С DLINK
    //*************************************************************
    //ищем перезагрузку из-за пропадания напряжения
    $cold_start_D = strpos($string, "System cold start");
    if ($cold_start_D == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "cold-start";
    }
    //ищем перезагрузку из консоли или мониторинга
    $warm_start_D = strpos($string, "System warm start");
    if ($warm_start_D == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "warm-start";
    }
    //ищем события поднятия порта в D-link
    $linkUP_D = strpos($string, "link up");
    if ($linkUP_D == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "LinkUP";
    }
    //ищем срабатывание loop 
    $loop_D = strpos($string, "LBD loop occurred");
    if ($loop_D == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "loop";
    }
    //*************************************************************
    //ищем события падение порта в D-link
    $linkDOWN_D = strpos($string, "link down");
    if ($linkDOWN_D == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "LinkDOWN";
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //*************************************************************
    //РАБОТА С SNR
    //*************************************************************
    //ищем события поднятия порта в SNR
    $linkUP_SNR = strpos($string, "changed state to UP");
    if ($linkUP_SNR == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "LinkUP";
    }
    //*************************************************************
    //ищем события падение порта в SNR
    $linkDOWN_SNR = strpos($string, "changed state to DOWN");
    if ($linkDOWN_SNR == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "LinkDOWN";
    }
    //ищем перезагрузку из-за пропадания напряжения
    $cold_start_S = strpos($string, "System cold restart");
    if ($cold_start_S == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "cold-start";
    }
    //ищем перезагрузку из консоли или мониторинга
    $warm_start_S = strpos($string, "System warm restart");
    if ($warm_start_S == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "warm-start";
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //*************************************************************
    //Прочие
    //*************************************************************
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //ищем срабатывание loop на MES3124
    $loop_D = strpos($string, "Loopback Detection.");
    if ($loop_D == false){
        //строка не найдена
        //оставляем $switch_flag пустым
    } else {
        //подстрока найдена
        $switch_flag = "loop";
    }
    //Начинаем перепиливать строчку в соответствии с совпадением
    //на основе $switch_flag проводим изменение строки
    switch ($switch_flag) {
        case "tgrad":
            //если в switch_flag значение tgrad удаляем строку полностью
            $p_string = "";
            break;
        case "snmp":
            //убиваем с длинка неправельные snm запросы
            $p_string = "";
            break;
        case "cold-start":
            //подсвечиваем холодный рестарт
            $p_string = "<tr class=\"bg-info\"> <td style=\"width: 15px;\"> <img src=\"ico/coldr.png\"> </td><td>". $date ."</td><td>" . $string . "</td></tr>";
            break;
        case "warm-start":
            //подсвечиваем запланированный рестарт
            $p_string = "<tr class=\"bg-info\"> <td style=\"width: 15px;\">  <img src=\"ico/warmr.png\"> </td><td>". $date ."</td><td>" . $string . "</td></tr>";
            break;
        case "LinkUP":
            //если LinkUP меняем цвет строки на зеленый и подставляем иконку поднятия линка
            $p_string =  "<tr class=\"bg-success\"> <td style=\"width: 15px;\">  <img src=\"ico/link-up.png\"> </td><td>". $date ."</td><td>" . $string . " </td></tr>";
            break;
        case "LinkDOWN":
            $p_string = "<tr class=\"bg-warning\"> <td style=\"width: 15px;\">  <img src=\"ico/link-down.png\"> </td><td>". $date ."</td><td>" . $string . "</td></tr>";
            break;
        case "loop":
            $p_string = "<tr class=\"bg-danger\"> <td style=\"width: 15px;\">  <img src=\"ico/loop.png\"> </td><td>". $date ."</td><td>" . $string . "</td></tr>";;
            break;
        case "":
            //если срабатыаний нету, выводим исходную строку !!! ДОЛЖНО БЫТЬ ПОСЛЕДНИМ УСЛОВИЕМ !!!
            $p_string = "<tr><td></td><td>". $date ."</td><td>" . $string . "</td></tr>";
            break;
    }
?>