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
    </head
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
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</html>