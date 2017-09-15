<?php
session_start();

if (isset($_SESSION["UserCode"]))
    echo '1';
else
    echo '0';
?>