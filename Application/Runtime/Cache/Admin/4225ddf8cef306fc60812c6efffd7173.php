<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页轮播图</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/zui.min.js"></script>
    <script src="/Public/Admin/js/zui.uploader.min.js"></script>
    <link href="/Public/Admin/css/mzui.css" rel="stylesheet" />
    <link href="/Public/Admin/css/zui.uploader.css" rel="stylesheet" />
</head>
<body>
  <div class="container">
    <div class="panel">
      <div class="panel-heading">
        <h2 class="title">首页轮播图</h2>
      </div>
          <div id="uploaderExample" class="uploader">
              <div class="file-list" data-drag-placeholder="请拖拽文件到此处" value="<?php echo ($result['url']); ?>"></div>
              <button type="button" class="btn btn-primary uploader-btn-browse"><i class="icon icon-cloud-upload"></i> 选择文件</button>
              <button type="button" class="btn btn-primary" name="sub">确定</button>

          </div>
    </div>
      <div class="form-group">
          <div class="col-md-6 col-sm-10">
              <input type="hidden" name="id" value="<?php echo ($_GET['id']); ?>">
          </div>
      </div>
  </div>
</body>
<script type="text/javascript">
    var id = $("input[name='id']").val();
    $('#uploaderExample').uploader({
        type:"post",
        autoUpload: true,            // 当选择文件后立即自动进行上传操作
        url: '<?php echo U("Homepage/updateUpload");?>',// 文件上传提交地址
        multipart_params:{"id":id}
    });

//    $("button[name='sub']").bind('click',function(){
//        var id = $("input[name='id']").val();
//        $.ajax({
//            type:'post',
//            url:"<?php echo U('Homepage/updatelist');?>",
//            data:{"id":id},
//            success:function(data){
//                var data = eval("("+data+")");
//                if(data.error == true){
//                    new $.zui.Messager('提示消息：成功', {
//                        type:'success',
//                        placement: 'bottom-right' // 定义显示位置
//                    }).show();
//                    window.location.href = "<?php echo U('Homepage/banner');?>";
//                }else{
//                    new $.zui.Messager('提示消息：失败', {
//                        type:'danger',
//                        placement: 'bottom-right' // 定义显示位置
//                    }).show();
//                }
//            }
//        })
//    })
</script>
</html>