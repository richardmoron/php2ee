<?php session_start();?>
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="js/tabber.js"></script>
        <link rel="stylesheet" href="css/tabs.css" TYPE="text/css" MEDIA="screen">
        <link rel="stylesheet" href="css/rightside.css" TYPE="text/css" MEDIA="screen">
        <title></title>
    </head>
    <body>
    <?php 
        include_once 'connection.php';
        include_once 'write.php';
        include_once 'TOCGenerator.php';
        include_once 'BRCGenerator.php';
        include_once 'XAJAXGenerator.php';
        include_once 'HTMLGenerator.php';
     ?>
    <div>
        <span>
            <a href="right_side.php?content=dto" target="_self">Save DTO Class </a>
        </span>
        &nbsp;&nbsp;
        <span>
            <a href="right_side.php?content=dao" target="_self">Save DAO Class</a>
        </span>
        &nbsp;&nbsp;
        <span>
            <a href="right_side.php?content=xajax" target="_self">Save XAJAX Class</a>
        </span>
        &nbsp;&nbsp;
        <span>
            <a href="right_side.php?content=html" target="_self">Save HTML Class</a>
        </span>
        &nbsp;&nbsp;
        <span>
            <?php //echo CLASS_PATH; ?>
        </span>
        <?php
        if(isset ($_GET['content'])){
                $write = new write($_GET['content']);
            }
        ?>
    </div>
    <div class="tabber">
        <div class="tabbertab">
            <h2>DTO Class</h2>
            <p>
               <textarea cols="100%" rows="30%" >
                    <?php
                        if(isset($_GET['table'])){
                            $_SESSION['table'] = $_GET['table'];
                        }
                        if(isset($_SESSION['table']) && isset($_SESSION['database'])){
                            $generator = new TOCGenerator($_SESSION['database'],$_SESSION['table']);
                            echo $generator->generateClass();
                        }else{
                            echo '';
                        }
                    ?>
                </textarea>
            </p>
        </div>
        <div class="tabbertab">
            <h2>DML Class</h2>
            <p>
                <textarea cols="85%" rows="30%">
                    <?php
                        if(isset($_SESSION['table']) && isset($_SESSION['database'])){
                            $generator = new BRCGenerator($_SESSION['database'],$_SESSION['table']);
                            echo $generator->generateClass();
                        }else{
                            echo '';
                        }
                    ?>
                </textarea>
            </p>
        </div>
        <div class="tabbertab">
            <h2>XAJAX Class</h2>
            <p>
                <textarea cols="85%" rows="30%">
                    <?php
                        if(isset($_SESSION['table']) && isset($_SESSION['database'])){
                            $generator = new XAJAXGenerator($_SESSION['database'],$_SESSION['table']);
                            echo $generator->generateClass();
                        }else{
                            echo '';
                        }
                    ?>
                </textarea>
            </p>
        </div>
        <div class="tabbertab">
            <h2>HTML Class</h2>
            <p>
                <textarea cols="85%" rows="30%">
                    <?php
                        if(isset($_SESSION['table']) && isset($_SESSION['database'])){
                            $generator = new HTMLGenerator($_SESSION['database'],$_SESSION['table']);
                            echo $generator->generateClass();
                        }else{
                            echo '';
                        }
                    ?>
                </textarea>
            </p>
        </div>
    </div>
    </body>
</html>

