<?php
require_once dirname(__FILE__)."/order/order_action.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap-3.3.5-dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>更新已支付订单</h1>
        <div name="form">
            <form action="order/order_action.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">选择文件</label>
                    <input type="file" id="file" name="file">
                    <p class="help-block">请传入需要更新的支付宝导出文件（csv格式）.</p>
                </div>
                <button type="submit" class="btn btn-default">确认</button>
            </form>
        </div>
    </div>
</body>
</html>