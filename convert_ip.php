<?php
include './config.php';
$query = mysqli_query($link_to_mysql, "SELECT ID,FromHost FROM Syslog.SystemEvents") or die();
for ($log_count = 0; $log_count < mysqli_num_rows($query); $log_count ++) {
    $result = mysqli_fetch_assoc($query);
    $ip = ip2long($result[FromHost]);
    $query_update = mysqli_query($link_to_mysql, "UPDATE SystemEvents SET FromHost = $ip WHERE ID =$result[ID]") or die();
}
