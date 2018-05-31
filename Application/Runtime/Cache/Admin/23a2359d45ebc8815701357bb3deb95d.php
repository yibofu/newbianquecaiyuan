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
              <div class="file-list" data-drag-placeholder="请拖拽文件到此处"></div>
              <button type="button" class="btn btn-primary uploader-btn-browse"><i class="icon icon-cloud-upload"></i> 选择文件</button>
              <a href="<?php echo U('Homepage/banner');?>"><button type="button" class="btn btn-primary">确定</button></a>

          </div>
    </div>
  </div>
</body>
<script type="text/javascript">
    $('#uploaderExample').uploader({
        autoUpload: true,            // 当选择文件后立即自动进行上传操作
        url: '<?php echo U("Homepage/upload");?>'// 文件上传提交地址
    });
</script>
</html>