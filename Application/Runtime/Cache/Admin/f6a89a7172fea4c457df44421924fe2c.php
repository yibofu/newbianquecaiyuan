<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="height: 100%;">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>数据中心</title>
    <link rel="stylesheet" href="/Public/Admin/css/zui.min.css">
    <script src="/Public/Admin/js/jquery.min.js"></script>
    <script src="/Public/Admin/js/zui.min.js"></script>
    <link rel="stylesheet" href="/Public/Admin/css/public.css"/>
    <style>
        .panel {
            height: auto !important;
            border: none !important;
        }

        .text-muted {
            text-align: center;
        }

        .cards {
            background: white !important;
            margin: 0 30px;
        }

        .card {
            height: 150px;
        }

        .card > div h1,
        p {
            font-weight: normal !important;
            text-align: center;
            margin: 0 0 20px 0;
            font-size: 16px !important;
        }

        .card > div h1 {
            font-weight: normal !important;
            font-size: 30px !important;
            margin: 40px 0 10px 0;
        }

        .card > div p {
            font-size: 20px;
        }

        .card > div {
            height: 100%;
        }

        .dateDay7 a {
            width: 14%;
            text-align: center;
            display: inline-block;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
        }

        .dateDay7 a:hover {
            color: brown;
            cursor: pointer;
        }

        .dateDay7 a:active {
            color: brown;
        }

        .shuzhizc {
            height: 150px;
        }

        .shuzhizc p {
            font-weight: normal !important;
            width: 100%;
            text-align: center;
            margin: 40px 0 10px 0 !important;
            font-size: 20px;
        }

        .shuzhizc h1 {
            font-weight: normal !important;
            width: 100%;
            text-align: center;
            font-size: 36px;
            margin-top: 0;
        }

        .main .panel-body {
            padding: 0;
        }

        .main .dateDay7 {
            padding: 20px 15px !important;
        }

        .tabOnn h2 {
            font-weight: normal !important;
            padding: 0 !important;
            margin: 0 !important;
            line-height: 1;
        }

        table td h3 {
            font-weight: normal !important;
            color: #999;
        }

        .dateDayY {
            display: none;
        }

        .acccc {
            display: block;
        }

        .ac {
            color: brown;
        }
        .panel-primary {
            border-color: #3280fc;
        }
    </style>
</head>

<body style="height: 100%;background:white;">
    <div class="panel panel-primary" style="width: 600px; height: 300px;margin-top: 50px;margin-left: 50px;float: left">
        <div class="panel-heading" contenteditable="">我的个人信息</div>
        <div class="panel-body" style="height: 200px;">
            <div>您好：<?php echo ($adminname); ?></div>
            <div>所属角色：</div>
            <div>上次登录时间：</div>
            <div>上次登录IP：</div>
        </div>
    </div>
    <div class="panel panel-primary" style="width: 600px; height: 300px;margin-top: 50px;margin-left: 50px;float: left;">
        <div class="panel-heading" contenteditable="">系统信息</div>
        <div class="panel-body" style="height: 200px;">
            <div>PHP版本：</div>
            <div>操作系统：</div>
            <div>MySQL版本：</div>
        </div>
    </div>
</body>
<script>

</script>

</html>