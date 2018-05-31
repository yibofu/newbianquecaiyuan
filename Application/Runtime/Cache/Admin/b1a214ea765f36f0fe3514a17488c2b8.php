<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>网站关键字修改</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
		<script type="text/javascript" src="/Public/Admin/js/zui.min.js"></script>
    <link href="/Public/Admin/css/mzui.css" rel="stylesheet" />
</head>
<body>
  <div class="container">
    <div class="panel">
      <div class="panel-heading">
        <h2 class="title">网站关键字修改</h2>
      </div>
      <form class="form-horizontal" style="margin-top:50px;">
    <div class="form-group">
      <label for="exampleInputAccount4" class="col-sm-2">页面标题</label>
      <div class="col-md-6 col-sm-10">
        <input type="text" name="title" class="form-control" required id="exampleInputAccount4" value="<?php echo ($result["title"]); ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="exampleInputPassword5" class="col-sm-2">页面关键字</label>
      <div class="col-md-6 col-sm-10">
        <input type="text" name="keywords" class="form-control" required id="exampleInputPassword5" value="<?php echo ($result["keywords"]); ?>">
      </div>
    </div>
        <div class="form-group">
          <label for="exampleInputPassword6" class="col-sm-2">页面的描述</label>
          <div class="col-md-6 col-sm-10">
            <input type="text" name="description" class="form-control" required id="exampleInputPassword6" value="<?php echo ($result["description"]); ?>">
          </div>
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
  $("button[name='sub']").bind('click',function(){
    var title = $("input[name='title']").val();
    var keywords = $("input[name='keywords']").val();
    var description = $("input[name='description']").val();
    var id = $("input[name='id']").val();
    if(title !== '' && keywords !== '' && description !== ''){
      $("button").attr("disabled",true);
      $("button").attr("type","button");
    }
    $.ajax({
      type:'post',
      url:"<?php echo U('Keywords/updatelist');?>",
      data:{"title":title,"keywords":keywords,"description":description,"id":id},
      success:function(data){
        var data = eval("("+data+")");
        if(data.error == true){
          new $.zui.Messager('提示消息：成功', {
          type:'success',
          placement: 'bottom-right' // 定义显示位置
          }).show();
          window.location.href = "<?php echo U('Keywords/index');?>";
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