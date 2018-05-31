<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="height: 100%;">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>平台管理</title>
		<link href="/Public/Admin/css/mzui.css" rel="stylesheet">
		<link href="/Public/Admin/css/doc.min.css" rel="stylesheet">
		<style id="themeStyle"></style>
		<script type="text/javascript" src="http://zui.sexy/dist/lib/prettify/prettify.js"></script>
		<script type="text/javascript" src="/Public/Admin/js/jquery.min.js"></script>
		<script type="text/javascript" src="http://zui.sexy/dist/js/zui.min.js"></script>
		<script type="text/javascript" src="/Public/Admin/js/tree.js"></script>
		<link rel="stylesheet" href="/Public/Admin/css/public.css" />
		<style media="screen">
			.icon,
			[class*=" icon-"],
			[class^=icon-] {
				line-height: 1.7;
			}

			.icon-chevron-right {
				right: 3px!important;
			}

			.zong .nav li a {
				padding: 0!important;
				height: 40px;
			}

			.nav-parent>a {
				font-weight: 700;
			}


			.nav>li>a:focus, .nav>li>a:hover, .nav>li>a:active{
				background-color: #03b8cf;
				border-color: #03b8cf;
			}


			.userSelect {
				box-shadow: 0 0 2px 2px #DDDDDD;
				display: none;
				overflow: hidden;
				position: absolute;
				top: 50px;
				left: -15px;
				background: white;
				width: 80px;
			}

			.userSelect span {
				cursor: pointer;
				text-align: center;
				display: block;
				width: 100%;
				margin: 0;
				text-align: center;
				line-height: 30px;
			}

			.userSelect span:hover {
				background: #E9E9E9;
				color: #8BC34A;
				text-decoration: none;
			}
			.panel-body li{border-bottom: 1px #eee  solid;}
			.panel-heading{padding: 0!important;}
			.panel-heading>h3{display:inline-block;width: 100%;height: 100%;}
			.panel-heading>h3>a{display:inline-block;width: 100%;height: 100%;padding: 4px 15px;}
			.panel-heading>h3>a:focus, .panel-heading>h3>a:hover {
			    background-color: #03b8cf;
			    border-color: #03b8cf;
			    color: white;
			}
		</style>

	</head>

	<body style="height: 100%;">
		<header>
			<nav class="navbar" style="margin-bottom: 0;background: white;border-bottom: 1px #d4d4d4 solid;" role="navigation">
				<div class="container-fluid">
					<!-- 导航头部 -->
					<div class="navbar-header" style="width: 150px;">
						<!-- 移动设备上的导航切换按钮 -->
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-example">
									<span class="sr-only">切换导航</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
						<!-- 品牌名称或logo -->
						<a class="navbar-brand navbar-inverse" href="#" style="background: none;padding: 10px 0 10px  50px;">
						</a>
					</div>
					<!-- 导航项目 -->
					<div class="collapse navbar-collapse navbar-collapse-example">
						<!-- 一般导航项目 -->
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a target="links" href="<?php echo U('Statistics/index');?>" class="links dropdown-toggle">首页</a>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right" style="position:relative;">
							<li class="user">
								<a href="">
									<i class="icon icon-user" style="font-size: 16px;"></i>
								</a>
							</li>
							<li>
								<a href="<?php echo U('Index/loginout');?>">
									<i class="icon icon-off" style="font-size: 16px;" title="退出"></i>
								</a>
							</li>
							<div class="userSelect">
								<span>管理员：<?php echo ($adminname); ?></span>
							</div>
						</ul>
					</div>
					<!-- END .navbar-collapse -->
				</div>
			</nav>
		</header>
		<div class="zong" style="width: 100%;height: 170%;">
			<nav class="menu" data-ride="menu" style="width: 10%;height:100%;background: white;float: left;">
				<ul id="treeMenu" class="tree tree-menu" data-ride="tree">
					<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<a href="#"><i class="icon icon-plus"></i><?php echo ($vo["auth_name"]); ?></a>
							<?php if(is_array($vo["row"])): $i = 0; $__LIST__ = $vo["row"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rulename): $mod = ($i % 2 );++$i;?><ul>
									<li><a class="links" target="links" href="<?php echo U($rulename['path']);?>"><?php echo ($rulename["rule_name"]); ?></a></li>
								</ul><?php endforeach; endif; else: echo "" ;endif; ?>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</nav>
			<div class="main" style="float: left;height: 100%!important;">
				<iframe src="<?php echo U('Statistics/index');?>" name="links" height="100%" width="100%">
				</iframe>
			</div>
		</div>
	</body>
	<script>
        $('#treeMenu').on('click', 'a', function() {
            $('#treeMenu li.active').removeClass('active');
            $(this).closest('li').addClass('active');
        });
	</script>

</html>