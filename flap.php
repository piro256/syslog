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
    <body>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <h3> <a href="index.php"><img src="ico/logo2.png"></a> Syslog - service v 0.5</h3>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <?php 
                    include './menu.php'; 
                ?>

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
                <div class="col-lg-10 col-lg-offset-1" style="padding-bottom: 50px">
                <table class="table table-condensed">
                <?php
                include './config.php';
                
                $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
                
                echo "<ol class=\"breadcrumb\">";
                echo "<li><a href=\"flap.php?date=".date("Y-m-d",time()-(6*(24*60*60)))."\">".date("Y-m-d",time()-(6*(24*60*60)))."</a></li>";
                echo "<li><a href=\"flap.php?date=".date("Y-m-d",time()-(5*(24*60*60)))."\">".date("Y-m-d",time()-(5*(24*60*60)))."</a></li>";
                echo "<li><a href=\"flap.php?date=".date("Y-m-d",time()-(4*(24*60*60)))."\">".date("Y-m-d",time()-(4*(24*60*60)))."</a></li>";
                echo "<li><a href=\"flap.php?date=".date("Y-m-d",time()-(3*(24*60*60)))."\">".date("Y-m-d",time()-(3*(24*60*60)))."</a></li>";
                echo "<li><a href=\"flap.php?date=".date("Y-m-d",time()-(2*(24*60*60)))."\">".date("Y-m-d",time()-(2*(24*60*60)))."</a></li>";
                echo "<li><a href=\"flap.php?date=".date("Y-m-d",time()-(1*(24*60*60)))."\">".date("Y-m-d",time()-(24*60*60))."</a>";
                echo "<li><a href=\"flap.php?date=".date("Y-m-d")."\">".date("Y-m-d")."</a>";
                echo "</ol>";
                //если дата пустая, то выводим за текущий день 
                if ($date == "") {
                    $date = date("Y-m-d");
                }

                $query_log = mysqli_query($link_to_mysql, "SELECT * FROM SystemEvents WHERE ReceivedAt BETWEEN "
                        . "STR_TO_DATE('$date 00:00:00', '%Y-%m-%d %H:%i:%s') AND "
                        . "STR_TO_DATE('$date 23:59:59', '%Y-%m-%d %H:%i:%s') AND "
                        . "Message LIKE '%flap%' ORDER BY ID DESC ") or die();
                echo "<h5>";
                for ($log_count = 0; $log_count < mysqli_num_rows($query_log); $log_count ++) {
                    //складываем запись в массив
                    $log_result = mysqli_fetch_assoc($query_log);
                    //переводим Message в переменную string и кормим парсеру
                    $string = $log_result[SysLogTag].$log_result[Message];
                    include './parser_flap.php';
                    echo $p_string;
                    //echo "<tr><td>".$log_result[ReceivedAt] ." </td><td> ". $log_result[FromHost] ." </td><td> ". $log_result[Message]. "</td></tr>";    
                }
                echo "</h5>";
                ?>
                </table>
            </div>
            <div class="navbar-fixed-bottom row-fluid container  col-lg-10 col-lg-offset-1" style="background-color: whitesmoke; border-radius: 15px 15px 0px 0px;">
                <div class="navbar-inner">
                    <div class="container"> 
                        <?php 
                        //$time = microtime(true) - $start; 
                        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                        printf('<br>Лог обработан за %.3F сек.', $time); 
                        printf(" || Всего найдено %d записей,", mysqli_num_rows($query_log));
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