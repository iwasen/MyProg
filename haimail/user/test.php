<?
include('inc/common.php');
include('inc/database.php');
include('inc/get_profile.php');
include('inc/member.php');
include('inc/point.php');
include('inc/reginfo.php');
include('inc/sel_profile1.php');
include('inc/sel_profile2.php');
include('inc/shoukai.php');
//include('inc/show_profile.php');



?>
<html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>test</title>
</head>
<body>

<form method="post">
<input type="text" name="ttt">
<input type="text" name="ttt">
<input type="submit" value="‘—M">
</form>

<?
echo $HTTP_RAW_POST_DATA;
?>

</body>
</html>