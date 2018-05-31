<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>管理角色权限</title>
		<link rel="stylesheet" href="/Public/Admin/css/zui.min.css">
		<script src="/Public/Admin/js/jquery.js"></script>
		<script src="/Public/Admin/js/zui.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="panel">
				<div class="panel-heading">
					<h2 class="title">管理角色权限增加</h2>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" style="margin: 50px 0;">
						<div class="form-group">
						<label class="col-sm-2">职位</label>
						<div class="form-group">
							<?php if(is_array($result)): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="radio-inline">
    							<input type="radio" name="role_name" value="<?php echo ($vo["id"]); ?>"> <?php echo ($vo["role_name"]); ?>
  							</label><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
						</div>
					  <div class="form-group">
					    <label for="Input1" class="col-sm-2">账号</label>
					    <div class="col-md-6 col-sm-10">
					      <input class="form-control" name="account" id="Input1">
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="Input2" class="col-sm-2">昵称</label>
					    <div class="col-md-6 col-sm-10">
					      <input class="form-control" name="nick_name" id="Input1">
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="Input3" class="col-sm-2">初始密码</label>
					    <div class="col-md-6 col-sm-10">
					    	<input type="password" name="password" class="form-control" id="Input3">
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="Input4" class="col-sm-2">权限</label>
							<div class="checkbox">
								<?php if(is_array($rs)): $i = 0; $__LIST__ = $rs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label>
	    							<input type="checkbox" name="auth_name" id="checkbox" value="<?php echo ($vo["id"]); ?>">
	    								<?php echo ($vo["auth_name"]); ?>
	  							</label><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
					  </div>
						<div class="form-group">
					    <label for="Input4" class="col-sm-2">用户状态</label>
					    <div class="col-md-6 col-sm-10">
					    	<select class="form-control" name="status">
					    		<option value="0">禁用</option>
					    		<option value="1">未禁用</option>
					    	</select>
					    </div>
					  </div>
						<div class="form-group">
					    <label for="Input4" class="col-sm-2">管理员角色</label>
					    <div class="col-md-6 col-sm-10">
					    	<select class="form-control" name="admin_type">
					    		<option value="1">超级管理员</option>
					    		<option value="2">普通管理员</option>
					    	</select>
					    </div>
					  </div>
					  <div class="form-group">
					    <div class="col-sm-offset-2 col-sm-10">
					      <button name="sub" class="btn btn-primary">添加角色</button>
						  <a name="sub" href="javascript:history.back()" class="btn btn-primary">返回</a>
					    </div>
					  </div>

					</form>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
	$("button[name='sub']").bind('click',function(){
		var role_name = $("input[name='role_name']:checked").val();
		var account = $("input[name='account']").val();
		var nick_name = $("input[name='nick_name']").val();
		var password = $("input[name='password']").val();
		var auth_name = [];
 			$('input[name=auth_name]').each(function() {
   			if(this.checked) {
     			auth_name.push($(this).val());
   				}
 			});
		var status = $("select[name='status']").val();
		var admin_type = $("select[name='admin_type']").val();
		if(role_name !== '' && account !== '' && nick_name !== '' && password !== ''){
				$("button").attr("disabled",true);
				$("button").attr("type","button");
		}
		$.ajax({
			type:'post',
			url:"<?php echo U('Admin/addlist');?>",
			data:{"role_name":role_name,"account":account,"nick_name":nick_name,"password":password,"auth_name":auth_name,"status":status,"admin_type":admin_type},
			success:function(data){
				var data = eval("("+data+")");
				if(data.error == true){
					new $.zui.Messager('提示消息：成功', {
					type:'success',
					placement: 'bottom-right' // 定义显示位置
					}).show();
					setTimeout(function(){
						window.location.href = "<?php echo U('Admin/index');?>";
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