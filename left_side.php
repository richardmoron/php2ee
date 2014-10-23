<?php session_start();?>
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/vmenu.css" type="text/css" rel="stylesheet" />
        <title></title>
        <script type="text/javascript">
            function goto(select) {
                var index=select.selectedIndex
                if (select.options[index].value != "0") {
                    location=select.options[index].value;
                }
            }
        </script>
    </head>
    <body>
        <div id="leftside">
            <?php include_once 'connection.php';?>
            <?php
                if(isset($_GET['database'])){
                    $_SESSION['database'] = $_GET['database'];
                }
                $_SESSION['table'] = null;
                include 'menu/left_menu.php';
            ?>
        </div>
    </body>
</html>

