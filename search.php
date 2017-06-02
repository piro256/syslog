<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Syslog - server">
        <meta name="author" content="piro256">
        <link rel="shortcut icon" href="favicon.ico">
        <title>Syslog - server</title>
        <link href="./css/bootstrap.min.css" rel="stylesheet">
    </head>
    <style>
        .table td {
            font-size: 14px;
        .table  td{
            width: 16px;
        }
    </style>
    <?php
	//Кажется пора внедрять smarty ^^
    include './parsing.php';
    include './config.php';
    //принимаем ip железки
    $serch = filter_input(INPUT_GET, 'ip', FILTER_VALIDATE_IP);
    $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
    //проверяем заполнение переменной, а то понаводят пустоты или ипов через запятую
    if (empty($serch)) {
        $serch = "IP не задан или задан не верно, попробуйте сново";
        echo $serch . '<br><a href="index.php"> <= Вернуться на главную</a>';
        //прекращаем выполнение скрипта
        exit();
    }
    //ищем хост в базе
    $long_serch = ip2long($serch);
	echo $long_serch;
    $host_found = mysqli_query($link_to_mysql, "SELECT FromHost FROM Syslog.SystemEvents WHERE FromHost = '$long_serch' LIMIT 0 , 1");
    if (mysqli_num_rows($host_found) == 1) {
        //printf("Select вернул %d строк.\n", mysqli_num_rows($host_found));//debug
        $serch_error = "<h4>Оборудования c IP $serch найдено, ищем логи...</h4>";
        
    } else {
        $serch_error = 'Оборудование не найдено, обратитесь к системному администратору для добавления его на сервер syslog\'s<br>';
        echo $serch_error . '<a href="index.php"> <= Вернуться на главную</a>';
        //прекращаем выполнение скрипта
        exit();
    }
    ?>
    <body>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <h3> <a href="index.php"><img src="ico/logo2.png"></a> Syslog - service v 0.5</h3>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <?php include 'menu.php'; ?>
                <form class="form-inline" method="GET" action="search.php">
                    <div class="form-group" style="">
                        <div class="input-group">
                            <div class="input-group-addon">IP</div>
                            <input type="text" class="form-control" name="ip" placeholder="Введите ip устройства">
                        </div>
                    </div>
                  <button type="submit" class="btn btn-primary">Получить логи</button>
                </form>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <div class="tab-content" style="padding-bottom: 150px">   
                    <?php
                    //рисуем ссылки на последние 7 дней от текущей даты
                    echo "<ol class=\"breadcrumb\">";
                    echo "<li><a href=\"search.php?ip=".$serch."&date=".date("Y-m-d",time()-(6*(24*60*60)))."\">".date("Y-m-d",time()-(6*(24*60*60)))."</a></li>";
                    echo "<li><a href=\"search.php?ip=".$serch."&date=".date("Y-m-d",time()-(5*(24*60*60)))."\">".date("Y-m-d",time()-(5*(24*60*60)))."</a></li>";
                    echo "<li><a href=\"search.php?ip=".$serch."&date=".date("Y-m-d",time()-(4*(24*60*60)))."\">".date("Y-m-d",time()-(4*(24*60*60)))."</a></li>";
                    echo "<li><a href=\"search.php?ip=".$serch."&date=".date("Y-m-d",time()-(3*(24*60*60)))."\">".date("Y-m-d",time()-(3*(24*60*60)))."</a></li>";
                    echo "<li><a href=\"search.php?ip=".$serch."&date=".date("Y-m-d",time()-(2*(24*60*60)))."\">".date("Y-m-d",time()-(2*(24*60*60)))."</a></li>";
                    echo "<li><a href=\"search.php?ip=".$serch."&date=".date("Y-m-d",time()-(1*(24*60*60)))."\">".date("Y-m-d",time()-(24*60*60))."</a>";
                    echo "<li><a href=\"search.php?ip=".$serch."&date=".date("Y-m-d")."\">".date("Y-m-d")."</a>";
                    echo "</ol>";
                    
                    //если дата пустая, то выводим за текущий день
                    if ($date == "") {
                        $date = date("Y-m-d");
                    }
                    $query_log = mysqli_query($link_to_mysql, "SELECT ReceivedAt,Message,SysLogTag  FROM Syslog.SystemEvents"
                            . " WHERE ReceivedAt BETWEEN STR_TO_DATE('$date 00:00:00', '%Y-%m-%d %H:%i:%s') "
                            . "AND STR_TO_DATE('$date 23:59:59', '%Y-%m-%d %H:%i:%s') "
                            . "AND FromHost = '$long_serch' ORDER BY ID DESC") or die();
                    echo "<table class=\"table\">";
                    //выводим логи
                    for ($log_count = 0; $log_count < mysqli_num_rows($query_log); $log_count ++) {
                        //складываем запись в массив
                        $log_result = mysqli_fetch_assoc($query_log);
                        //переводим Message в переменную string и кормим парсеру
                        $string = $log_result[SysLogTag].$log_result[Message];
                        //непосредственно парсинг файла, удаление, подстветка 
                        //принимает $string  и возвращает $p_string
                        include 'parsing.php';
                        echo $p_string;
                    }
                    ?>
                    </table>    
                </div>
            </div>
            <div class="navbar-fixed-bottom row-fluid container  col-lg-10 col-lg-offset-1" style="background-color: white ">
                <div class="navbar-inner">
                    <div class="container"> 
                        <h4>Обозначения в логах:</h4>
                        <ul class="list-inline">
                            <li class="bg-success"><img src="ico/link-up.png"> Порт поднялся</li>
                            <li class="bg-warning"><img src="ico/link-down.png"> Порт упал</li>
                            <li class="bg-danger"><img src="ico/loop.png"> Петля на порту</li>
                            <li class="bg-info"><img src="ico/coldr.png"> Перезагрузка из-за пропадения напряжения</li>
                            <li class="bg-info"><img src="ico/warmr.png"> Перезагрузка по требованию</li>
                        </ul>
                        <?php 
                        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                        printf('Лог обработан за %.3F сек..', $time);
                        printf(' Всего найдено %d записей.', mysqli_num_rows($query_log));
                        mysqli_close($link_to_mysql)
                        ?>
                        <br><br>
                    </div>
                </div>
            </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</html>