<?php
$link = mysqli_connect("localhost", "mex", "mex162534", "mex");

/* check connection */ 
if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

printf("Host information: %s\n", mysqli_get_host_info($link));

/* close connection */
mysqli_close($link);
?>