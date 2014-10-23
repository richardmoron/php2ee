<?php session_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/default.css" type="text/css" rel="stylesheet" />
        <title></title>
    </head>
    <body>
        <?php include_once 'connection.php';?>
        <iframe src="left_side.php" height="100%" width="25%" frameborder="0"></iframe>
        
        <iframe src="right_side.php" name="rightside" height="100%" width="74%" frameborder="0" scrolling="no"></iframe>
    </body>
</html>

