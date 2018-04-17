<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>平台后台管理</title>
    <link href="/Public/Admin/css/mzui.css" rel="stylesheet">
    <link href="/Public/Admin/css/doc.min.css" rel="stylesheet">
    <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/zui.min.js"></script>
    <style>
        html{height: 100%;}
        body{ width: 100%;height: 100%; background: url(/Public/Admin/img/bg.png) no-repeat center center;background-size: cover;}
        .form{   width:100%;
            height:450px;
            position:relative;
        }
        .aa {
            width:300px;
            height:300px;
            margin: auto;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
        }
        .aa .img-box{width: 100%;height: 200px;}
        .img-box img{display: block; margin: 0 auto;margin-bottom: 50px;}
        .img-box h1{text-align: center;color: white;display: block;}
        form{padding: 20px;margin-top: 10px;}
    </style>
</head>
<body>
<div class="form">
    <div class="aa">
        <div class="img-box">
            <img height="120" src="/Public/Admin/img/laobanqianbao.png" />
            <h1>扁鹊财院后台管理系统</h1>
        </div>
        <form method="post">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon icon-user"></i>
                    </span>
                    <input type="text" name="account" class="form-control" required="required"  >
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="icon icon-key"></i>
                    </span>
                    <input type="password" name="password" class="form-control" required="required"  >
                </div>
            </div>
            <button type="button" name="sub" class="btn btn-primary btn-block" style="margin-top: 30px;">登录</button>
        </form>
    </div>
</div>
</body>
<script type="text/javascript">
    $(".btn-block").bind('click',function(){

        var account = $("input[name='account']").val().trim();
        var password = $("input[name='password']").val().trim();

        $.ajax({
            type:"POST",
            dataType:'json',
            url:"<?php echo U('Login/login');?>",
            data:{'account':account,'password':password},
            success:function(data){
                var row = eval(data);
                if(row.error == true){
                    alert(data.msg);
                    window.location.href = "<?php echo U('Index/common');?>";
                }else{
                    alert(data.msg);
                    return false;
                }
            }
        })
    })
</script>
</html>