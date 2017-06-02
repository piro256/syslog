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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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
        <?php
        include './config.php';

        $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_SPECIAL_CHARS);

        echo "<ol class=\"breadcrumb\">";
        echo "<li><a href=\"report.php?date=".date("Y-m-d",time()-(6*(24*60*60)))."\">".date("Y-m-d",time()-(6*(24*60*60)))."</a></li>";
        echo "<li><a href=\"report.php?date=".date("Y-m-d",time()-(5*(24*60*60)))."\">".date("Y-m-d",time()-(5*(24*60*60)))."</a></li>";
        echo "<li><a href=\"report.php?date=".date("Y-m-d",time()-(4*(24*60*60)))."\">".date("Y-m-d",time()-(4*(24*60*60)))."</a></li>";
        echo "<li><a href=\"report.php?date=".date("Y-m-d",time()-(3*(24*60*60)))."\">".date("Y-m-d",time()-(3*(24*60*60)))."</a></li>";
        echo "<li><a href=\"report.php?date=".date("Y-m-d",time()-(2*(24*60*60)))."\">".date("Y-m-d",time()-(2*(24*60*60)))."</a></li>";
        echo "<li><a href=\"report.php?date=".date("Y-m-d",time()-(1*(24*60*60)))."\">".date("Y-m-d",time()-(24*60*60))."</a>";
        echo "<li><a href=\"report.php?date=".date("Y-m-d")."\">".date("Y-m-d")."</a>";
        echo "</ol>";
        //если дата пустая, то выводим за текущий день
        if ($date == "") {
            $date = date("Y-m-d");
        }
        echo "<table class=\"table table-condensed\">";
	    echo "
<tr>\n
	<td>Улица</td>
	<td>Дом</td>
	<td>Квартира</td>
	<td>IP</td>
	<td>Model</td>
	<td>Port</td>
	<td>Падений</td>
</tr>";    
        $query_log = mysqli_query($link_to_mysql, "SELECT FromHost,COUNT(*) AS Counts FROM SystemEvents WHERE ((Message LIKE \"%down%\" OR SysLogTag LIKE \"%down%\") AND (ReceivedAt BETWEEN STR_TO_DATE('$date 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('$date 23:59:59', '%Y-%m-%d %H:%i:%s'))) GROUP BY FromHost ORDER BY COUNT(*) DESC") or die();
		//echo "SELECT FromHost,COUNT(*) AS Counts FROM SystemEvents WHERE ((Message LIKE \"%down%\" OR SysLogTag LIKE \"%down%\") AND (ReceivedAt BETWEEN STR_TO_DATE('$date 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('$date 23:59:59', '%Y-%m-%d %H:%i:%s'))) GROUP BY FromHost ORDER BY COUNT(*) DESC";
        for ($log_count = 0; $log_count < mysqli_num_rows($query_log); $log_count ++) {
            //складываем запись в массив
            $log_result = mysqli_fetch_assoc($query_log);
            //переводим Message в переменную string и кормим парсеру
            if ($log_result[Counts] > 48 ) {
                $ip = long2ip($log_result[FromHost]);
                //запрашиваем геоданные от мишки
                $query=mysqli_query($link_to_mysql_sms, "
                SELECT *  FROM `device_data`  
                INNER JOIN `home_data` on `device_data`.`id_device` = `home_data`.`id_device` 
                WHERE `ip` LIKE '$ip'
                ") or die("Оборудование не найдено");
                $result_device = mysqli_fetch_array($query);
                $porch = $result_device[porch];
                $addr = "$result_device[street], $result_device[home] ($porch)";
                $street = $result_device[street];
				$home = $result_device[home];
				$ip_ip = $log_result[FromHost];
				$model = $result_device[model];
				//выводим
                //echo "<tr><td><a href=\"search.php?ip=$ip\">$ip || $addr || $model</a>";
				
				//echo " <br/>";
		//	формируем запрос на поиск совпадающих записей по данному свитчу.
				$query_find_ports = mysqli_query($link_to_mysql, "SELECT *,COUNT(Message) AS `Counters` FROM SystemEvents WHERE ((Message LIKE \"%down%\" OR SysLogTag LIKE \"%down%\") AND (ReceivedAt BETWEEN STR_TO_DATE('$date 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('$date 23:59:59', '%Y-%m-%d %H:%i:%s') AND (FromHost = $ip_ip))) GROUP BY `Message` ORDER BY `ReceivedAt` ASC") or die(mysqli_error());
				
					for ($port_count = 0; $port_count < mysqli_num_rows($query_find_ports); $port_count ++) 
					{
						$port_result = mysqli_fetch_assoc($query_find_ports); 

						$re = '/\d+/';
						
						
						
						switch ($model) {
							case "3526":
							preg_match_all($re, $port_result[Message], $matches);
							$port = $matches[0][0];
							break;
							case "3200":
							preg_match_all($re, $port_result[Message], $matches);
							$port = $matches[0][0];
							break;
							case "1210":
							preg_match_all($re, $port_result[Message], $matches);
							$port = $matches[0][0];
							break;

							case "1124":
							preg_match_all($re, $port_result[Message], $matches);
							$port = $matches[0][2];
							break;
						}							
						
						/*
							if ($model = "3526") 
							{
								$port = $matches[0][0];
								
								
							} elseif ($model = "1124")
							{
								$port = $matches[0][2];
							}
							*/
						if ( $port_result[Counters] > 30){
									// делаем запрос к смс-у за информацией об абоненте
									$query_abon=mysqli_query($link_to_mysql_sms, "
									SELECT * FROM `user_data` INNER JOIN 
									`device_data` on `user_data`.`id_device` = `device_data`.`id_device` 
									WHERE `ip`='$ip' AND `port`='$port'
									") or die("Оборудование не найдено");
									$result_abon = mysqli_fetch_array($query_abon);
									$abon = $result_abon[description];
									$count_port_down = $port_result[Counters];
									/*
									*	попробуем рисовать таблицу
									*/
									echo "
<tr>
	<td>$street</td>\n
	<td>$home</td>\n
	<td>$abon</td>\n
	<td>$ip</td>\n 
	<td>$model</td>\n 
	<td>$port</td>\n 
	<td>$count_port_down</td>\n 
</tr>

									";
	//							echo "[$abon - $port_result[Counters]] ";
								}
						//echo $port_result[Message];
					}
//				echo "</td><td>$log_result[Counts]
//				</td></tr>";
                include "parsing_port.php";
            }
            }
        echo "</table>";
        ?>
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

<script src="js/bootstrap.min.js"></script>
</html>