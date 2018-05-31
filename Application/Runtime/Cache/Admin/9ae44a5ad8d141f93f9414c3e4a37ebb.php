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
        <a class="btn btn-primary pull-left" href="<?php echo U('Curriculum/add');?>">增加</a>
    </div>
    <div class="panel-body">
        <table class="table table-fixed table-hover table-bordered">
            <thead>
            <tr>
                <th>课程ID</th>
                <th>课程名称</th>
                <th>授课地址</th>
                <th>授课月份</th>
                <th>是否显示</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["coursename"]); ?></td>
                    <td><?php echo ($vo["address"]); ?></td>
                    <td><?php echo ($vo["month"]); ?>月</td>
                    <td><?php echo ($vo["is_show"]); ?></td>
                    <td><?php echo ($vo["create_time"]); ?></td>
                    <td>
                        <a class="btn btn-primary " href="<?php echo U('Curriculum/is_show');?>?id=<?php echo ($vo["id"]); ?>&is_show=<?php echo ($vo["is_show"]); ?>">是否显示</a>
                        <a class="btn btn-primary " href="<?php echo U('Curriculum/update');?>?id=<?php echo ($vo["id"]); ?>">修改</a>
                        <a class="btn btn-primary " href="<?php echo U('Curriculum/delete');?>?id=<?php echo ($vo["id"]); ?>">删除</a>
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
        var url = '/manage.php/Curriculum/index';
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