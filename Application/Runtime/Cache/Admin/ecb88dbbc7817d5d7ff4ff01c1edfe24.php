<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>资讯</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/zui.min.js"></script>
    <link href="/Public/Admin/css/mzui.css" rel="stylesheet" />
    <script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/Public/Admin/ueditor/lang/zh-cn/zh-cn.js"></script>
</head>
<body>
  <div class="container">
    <div class="panel">
      <div class="panel-heading">
        <h2 class="title">网站资讯内容增加</h2>
      </div>
      <form class="form-horizontal" style="margin-top:50px;">
    <div class="form-group">
      <label for="exampleInputAccount4" class="col-sm-2">资讯标题</label>
      <div class="col-md-6 col-sm-10">
        <input type="text" name="title" class="form-control" required id="exampleInputAccount4" placeholder="请输入资讯的标题">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2">资讯内容</label>
      <div class="col-md-6 col-sm-10">
          <script id="editor" name="condition" type="text/plain">
          </script>
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
      var ue = UE.getEditor('editor');
  $("button[name='sub']").bind('click',function(){
    var title = $("input[name='title']").val();
    var condition = UE.getEditor('editor').getContent();
    if(title !== '' && condition !== ''){
      $("button").attr("disabled",true);
      $("button").attr("type","button");
    }
    $.ajax({
      type:'post',
      url:"<?php echo U('Information/addlist');?>",
      data:{"title":title,"condition":condition},
      success:function(data){
        var data = eval("("+data+")");
        if(data.error == true){
          new $.zui.Messager('提示消息：成功', {
          type:'success',
          placement: 'bottom-right' // 定义显示位置
          }).show();
          setTimeout(function(){
              window.location.href = "<?php echo U('Information/index');?>";
          },1500);

        }else{
          new $.zui.Messager('提示消息：失败', {
          type:'danger',
          placement: 'bottom-right' // 定义显示位置
          }).show();
        }
      }
    })
  })
</script>
</html>