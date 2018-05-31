<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>管理员权限</title>
		<link rel="stylesheet" href="/Public/Admin/css/zui.min.css">
		<script src="/Public/Admin/js/jquery.min.js"></script>
		<script src="/Public/Admin/js/zui.min.js"></script>
	</head>
	<body>
			<div class="panel">
				<div class="panel-heading">
					<a class="btn btn-primary pull-left" href="<?php echo U('Auths/add');?>">增加</a>
				<form id="search-form">
					<div class="col-sm-3">
						<select name="authname" class="form-control search sear">
							<option value="0">--选择搜索项--</option>
							<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>"><?php echo ($voo["auth_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
    				</div>
				    <button class="btn btn-primary" onclick="searcha()">查询</button>
				</form>
				</div>
				<div class="panel-body">
					<table class="table table-fixed table-hover table-bordered" name="table_name">
						<thead>
							<tr>
								<th>消息ID</th>
								<th>权限名称</th>
								<th>权限备注</th>
								<th>创建时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td><?php echo ($vo["id"]); ?></td>
								<td><?php echo ($vo["auth_name"]); ?></td>
								<td><?php echo ($vo["remarks"]); ?></td>
								<td><?php echo ($vo["create_time"]); ?></td>
								<td>
									<a class="btn btn-primary " href="<?php echo U('Auths/update');?>?id=<?php echo ($vo["id"]); ?>">修改</a>
									<a class="btn btn-primary " href="<?php echo U('Auths/delete');?>?id=<?php echo ($vo["id"]); ?>">删除</a>
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- 分页样式 -->
			<div class="col-md-offset-5">
				<ul class="pager">
				<?php echo ($page); ?>
				</ul>
			</div>
	</body>
	<script type="text/javascript">

	function searcha(){
	        var url = '/manage.php/Auths/index';
	        var query  = $('#search-form').find('.search').serialize();
	        if( url.indexOf('?')>0 ){
	            url += '&' + query;
	        }else{
	            url += '?' + query;
	        }
        window.location.href = url;
	}
	</script>
</html>