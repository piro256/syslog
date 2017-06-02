<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Syslog - server">
        <meta name="author" content="piro256">
        <link rel="shortcut icon" href="../ico/favicon.ico">
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
                <form class="form-inline" method="GET" action="serch.php">
                    <div class="form-group" style="">
                        <div class="input-group">
                            <div class="input-group-addon">IP</div>
                            <input type="text" class="form-control" name="ip" placeholder="Введите ip устройства">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Получить логи</button>
                </form>
                <br>
                <h4>О системе:</h4>
                <p>Система Syslog - service предназначена для облегчения работы с log файлами собираемыми сервером rsyslog.</p>
                <p>Смежные права на элементы дизайна:</p>
                <p><li>Icons made by <a href="http://www.flaticon.com/authors/pavel-kozlov" title="Pavel Kozlov">Pavel Kozlov</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a>             is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></li></p>
                <br>
                <h4>Changelog:</h4>
                <p>Version 0.5</p>
                <ul>
                    <li>Мы случайно переехали на MySQL...</li>
                </ul>
                <br>
                <p>Version 0.4</p>
                <ul>
                    <li>Добавлен вывод петель за текущий и вчерашний день</li>
                </ul>
                <br>
                <p>Version 0.3</p><h6>*Moscow edition</h6>
                <ul>
                    <li>Добавлена подсветка события перезагрузки по требованию для свичей марки SNR</li>
                    <li>Изменен вывод имени файла. обрезано окончание -log.log</li>
                    <li>Изменен вывод списка лог файлов. Теперь он в строчку и занимает меньше места</li>
                    <li>Изменен формат вывода лог файла. Теперь между строчками нет расстояния, а сами строчки шире и лучше читаемы.</li>
                    <li>Подсветка обнаружения петли на порту.</li>
                </ul>
                <br>
                <p>Version 0.2</p>
                <ul>
                    <li>Изменение в WebGUI</li>
                    <li>Добавление страницы с информацией о проекте и лицензии</li>
                    <li>Добавлен парсер логов с подсветкой событий и вырезанием лишних событий <br>
                        (события поднятия и падения портов, перезагрузка оборудования, вырезаны сообщения tgrad / error snmp)</li>
                </ul>
                <br>
                <p>Version 0.1</p>
                <ul>
                    <li>Запуск сервиса</li>
                    <li>Общий функционал системы</li>
                </ul>
                <br>
                <address>
                    Вопросы, сообщения об ошибках, <br>предложения присылать на почту.
                    <br>
                    <a href="mailto:piro256@yandex.ru">piro256@yandex.ru</a>
                </address>
                <br>
                2015 (c) Sidorov A.A.
            </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</html>