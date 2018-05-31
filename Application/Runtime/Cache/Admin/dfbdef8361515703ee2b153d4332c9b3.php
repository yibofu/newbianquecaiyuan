<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>管理角色权限</title>
		<link rel="stylesheet" href="/Public/Admin/css/zui.min.css">
		<script src="/Public/Admin/js/jquery.min.js"></script>
		<script src="/Public/Admin/js/zui.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="panel">
				<div class="panel-heading">
					<h2 class="title">删除管理员角色</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" style="margin: 50px 0;">
            <div class="" style="margin:0 auto">
              <h3>确定要删除这条信息么？删除之后将不能恢复！</h3></br>
              <a class="btn btn-primary" href="<?php echo U('AuthRules/deletelist');?>?id=<?php echo ($result["id"]); ?>">确定</a>
              <a class="btn btn-primary" href="javascript:history.back()">返回</a>
            </div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>