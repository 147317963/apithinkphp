<?php /*a:1:{s:81:"D:\BtSoft\WebSoft\wwwroot\www\lunhui\application\admin\view\bjsc\auto_bjsc_2.html";i:1525437491;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style type="text/css">
        body,td,th {
            font-size: 12px;
        }
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style type="text/css">
        body,td,th {
            font-size: 12px;
        }
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>
<body>

<table width="200" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left">
            <input type=button name=button value="刷新" onClick="window.location.reload()">
            北京赛车PK拾2<br>
            <?php if(isset($data)): ?>
            <?php echo htmlentities($data['number']); ?>期:(<?php echo htmlentities($data['data']); ?>)
            <?php endif; ?>
           <br /><span id="timeinfo"></span>
        </td>

    </tr>
</table>
<script type="application/javascript">
    var number=Math.floor(Math.random()*15+1);
    var curtime='';
    function beginrefresh() {
        number--;
        if(number!=0) {

            document.getElementById("timeinfo").innerText =number + "秒后自动获取!";
            window.setTimeout(beginrefresh,1000);
        }else{
            document.getElementById("timeinfo").innerText='';
            window.location.href="<?php echo url('/Admin/Bjsc/auto_bjsc_2'); ?>";
        }
    }



    beginrefresh();

</script>

</body>
</html>