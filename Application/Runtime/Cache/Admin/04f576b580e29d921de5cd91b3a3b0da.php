<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>课程安排修改</title>
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/zui.min.js"></script>
    <link href="/Public/Admin/css/mzui.css" rel="stylesheet"/>
</head>
<body>
<div class="container">
    <div class="panel">
        <div class="panel-heading">
            <h2 class="title">课程安排修改</h2>
        </div>
        <form class="form-horizontal" style="margin-top:50px;">
            <div class="form-group">
                <label for="exampleInputAccount4" class="col-sm-2">课程名称</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" name="coursename" class="form-control" required id="exampleInputAccount4"
                           value="<?php echo ($result["coursename"]); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword5" class="col-sm-2">授课地址</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" name="address" class="form-control" required id="exampleInputPassword5"
                           value="<?php echo ($result["address"]); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2">授课月份</label>
                <select name="month" class="form-control search" style="width: 200px;">
                    <option value="<?php echo ($result["month"]); ?>"><?php echo ($result["month"]); ?></option>
                    <option value="1">一月</option>
                    <option value="2">二月</option>
                    <option value="3">三月</option>
                    <option value="4">四月</option>
                    <option value="5">五月</option>
                    <option value="6">六月</option>
                    <option value="7">七月</option>
                    <option value="8">八月</option>
                    <option value="9">九月</option>
                    <option value="10">十月</option>
                    <option value="11">十一月</option>
                    <option value="12">十二月</option>
                </select>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-sm-10">
                    <input type="hidden" name="id" value="<?php echo ($_GET['id']); ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button name="sub" class="btn btn-primary">提交</button>
                    <a name="sub" href="javascript:history.back()" class="btn btn-primary">返回</a>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
<script type="text/javascript">
    $("button[name='sub']").bind('click', function () {
        var coursename = $("input[name='coursename']").val();
        var address = $("input[name='address']").val();
        var month = $("select[name='month']").val();
        var id = $("input[name='id']").val();
        if (coursename !== '' && address !== '' && month !== '') {
            $("button").attr("disabled", true);
            $("button").attr("type", "button");
        }
        $.ajax({
            type: 'post',
            url: "<?php echo U('Curriculum/updatelist');?>",
            data: {"coursename": coursename, "address": address, "month": month, "id": id},
            success: function (data) {
                var data = eval("(" + data + ")");
                if (data.error == true) {
                    new $.zui.Messager('提示消息：成功', {
                        type: 'success',
                        placement: 'bottom-right' // 定义显示位置
                    }).show();
                    window.location.href = "<?php echo U('Curriculum/index');?>";
                } else {
                    new $.zui.Messager('提示消息：失败', {
                        type: 'danger',
                        placement: 'bottom-right' // 定义显示位置
                    }).show();
                }
            }
        })
    })
</script>
</html>