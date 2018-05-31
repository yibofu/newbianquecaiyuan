<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>管理员权限规则</title>
		<link rel="stylesheet" href="/Public/Admin/css/zui.min.css">
		<link href="/Public/Admin/css/mzui.css" rel="stylesheet">
		<script src="/Public/Admin/js/jquery.min.js"></script>
		<script src="/Public/Admin/js/zui.min.js"></script>

	</head>
	<body>
			<div class="panel">
				<div class="panel-heading">
					<a class="btn btn-primary pull-left" href="<?php echo U('Authrules/add');?>">增加</a>
					<form id="search-form">
						<div class="col-sm-3">
							<select name="rulename" class="form-control search">
								<option value="0">--选择搜索项--</option>
								<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>"><?php echo ($voo["rule_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
    					</div>
					</form>
					<button class="btn btn-primary" onclick="searcha()">查询</button>
				</div>
				<div class="panel-body">
					<table class="table table-fixed table-hover table-bordered">
						<thead>
							<tr>
								<th>消息ID</th>
								<th>权限名称</th>
								<th>权限规则名称</th>
								<th>权限规则备注</th>
                				<th>控制器</th>
								<th>action</th>
								<th>创建时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td><?php echo ($vo["id"]); ?></td>
								<td><?php echo ($vo["auth_name"]); ?></td>
								<td><?php echo ($vo["rule_name"]); ?></td>
								<td><?php echo ($vo["remarks"]); ?></td>
								<td><?php echo ($vo["controller"]); ?></td>
                				<td><?php echo ($vo["action"]); ?></td>
								<td><?php echo ($vo["create_time"]); ?></td>
								<td>
									<a class="btn btn-primary " href="<?php echo U('Authrules/update');?>?id=<?php echo ($vo["id"]); ?>">修改</a>
									<a class="btn btn-primary " href="<?php echo U('Authrules/delete');?>?id=<?php echo ($vo["id"]); ?>">删除</a>
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- 确定删除弹框 -->
			<div class="modal fade">
				<div class="modal-dialog">
				  <div class="modal-content">
				    <div class="modal-header">
				      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
				      <h4 class="modal-title">标题</h4>
				    </div>
				    <div class="modal-body">
				      <p>确定删除么？</p>
				    </div>
				    <div class="modal-footer">
				      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				      <button type="button" class="btn btn-primary">保存</button>
				    </div>
				  </div>
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
	        var url = '/manage.php/Authrules/index';
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