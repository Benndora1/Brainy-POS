<?php 
header('Content-type: text/html; charset=ISO-8859-1');
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!-- //need to change -->
    <title>iRestora PLUS | Next Gen Restaurant Software</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <link href="css/custom.css" rel="stylesheet" type="text/css" />
    <link href="css/edit.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
    <!-- //need to change -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    
    <style type="text/css">
        html{ height: 100%; }
        form { margin:0; }
        .main_header{
             height:100vh;
             background:radial-gradient(ellipse at center, #a6eff352 0%, #b5d6f6 100%);
             background-repeat:no-repeat;
        }
        .button_1{
            background-color: #0c5889;
        }
    </style>
</head>
<body>
    <div class="main_header">
        <div id="install-header">
            <!-- //need to change -->
            <img style="width: 15%; height: 15%;" src="img/main_logo.png"/>
        </div>
        <div class="install">
            <?php
            require("install.php");
            ?>
        </div>
    </div>

    <script src="js/v1.9.1-jquery.js"></script>
    <script type="text/javascript" src="js/Bootstrap v4.3.1 .js"></script>   
</body>
</html>