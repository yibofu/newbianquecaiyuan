<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>关键字管理</title>
    <link rel="stylesheet" href="/Public/Admin/css/zui.min.css">
    <script src="/Public/Admin/js/jquery.min.js"></script>
    <script src="/Public/Admin/js/zui.min.js"></script>
</head>
<body>
<div class="panel">
    <div class="panel-heading" style="height: 50px;">
        <a class="btn btn-primary pull-left" href="<?php echo U('Keywords/add');?>">增加</a>
    </div>
    <div class="panel-body">
        <table class="table table-fixed table-hover table-bordered">
            <thead>
            <tr>
                <th>账号ID</th>
                <th>标题</th>
                <th>关键字</th>
                <th>描述</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["title"]); ?></td>
                    <td><?php echo ($vo["keywords"]); ?></td>
                    <td><?php echo ($vo["description"]); ?></td>
                    <td><?php echo ($vo["create_time"]); ?></td>
                    <td><?php echo ($vo["update_time"]); ?></td>
                    <td>
                        <a class="btn btn-primary " href="<?php echo U('Keywords/update');?>?id=<?php echo ($vo["id"]); ?>">修改</a>
                        <a class="btn btn-primary " href="<?php echo U('Keywords/delete');?>?id=<?php echo ($vo["id"]); ?>">删除</a>
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
    function searcha() {
        var url = '/manage.php/Keywords/index';
        var query = $('#search-form').find('.search').serialize();

        if (url.indexOf('?') > 0) {
            url += '&' + query;
        } else {
            url += '?' + query;
        }
        window.location.href = url;
    }

</script>
</html>