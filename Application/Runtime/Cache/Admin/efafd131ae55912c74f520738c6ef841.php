<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>轮播图修改</title>
    <link rel="stylesheet" href="/Public/Admin/css/zui.min.css">
    <script src="/Public/Admin/js/jquery.min.js"></script>
    <script src="/Public/Admin/js/zui.min.js"></script>

</head>
<body>
<div class="panel">
    <div class="panel-heading" style="height: 50px;">
        <a class="btn btn-primary pull-left" href="<?php echo U('Informationimg/add');?>">增加</a>
    </div>
    <div class="panel-body">
        <table class="table table-fixed table-hover table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>图片路径</th>
                <th>图片</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["url"]); ?></td>
                    <td>
                        <img src="<?php echo ($vo["url"]); ?>" width="88px" height="88px" class="img-thumbnail"
                             alt="缩略图">
                    </td>
                    <td><?php echo ($vo["create_time"]); ?></td>
                    <td>
                        <a class="btn btn-primary " href="<?php echo U('Informationimg/update');?>?id=<?php echo ($vo["id"]); ?>">修改</a>
                        <a class="btn btn-primary " href="<?php echo U('Informationimg/delete');?>?id=<?php echo ($vo["id"]); ?>">删除</a>
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

</script>
</html>