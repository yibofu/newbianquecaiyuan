<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>后台管理</title>
    <link href="/Public/Admin/css/mzui.css" rel="stylesheet">
    <link href="/Public/Admin/css/doc.min.css" rel="stylesheet">
    <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/zui.min.js"></script>
</head>
<body>
<div class="form">
    <div class="aa">
        <div class="img-box">
            <h1>扁鹊财院后台管理系统</h1>
        </div>
        <form method="post">
            <div class="form-group">
                <div class="input-group">
				    	<span class="input-group-addon">
				    		<i class="icon icon-user"></i>
				    	</span>
                    <input type="text" name="account" class="form-control" required="required">
                </div>

            </div>
            <div class="form-group">
                <div class="input-group">
				    	<span class="input-group-addon">
				    		<i class="icon icon-key"></i>
				    	</span>
                    <input type="password" name="password" class="form-control" required="required">
                </div>
            </div>
            <button type="button" name="sub" class="btn btn-primary btn-block" style="margin-top: 30px;">登录</button>
        </form>
    </div>
</div>
</body>
<script type="text/javascript">
    $(".btn-block").bind('click', function () {

        var account = $("input[name='account']").val().trim();
        var password = $("input[name='password']").val().trim();

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo U('Login/login');?>",
            data: {'account': account, 'password': password},
            success: function (data) {
                var row = eval(data);
                if (row.error == true) {
                    alert(data.msg);
                    window.location.href = "<?php echo U('Index/common');?>";
                } else {
                    alert(data.msg);
                    return false;
                }
            }
        })
    })
</script>
</html>