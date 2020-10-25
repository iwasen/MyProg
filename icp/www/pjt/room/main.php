<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>テーマを表示</title>
</head>

<frameset rows="40%, 50%" frameborder="1" title="">
	<frame src="subject_top.php<? if (isset($_GET['disp_member_id'])) echo "?disp_member_id={$_GET['disp_member_id']}" ?>" title="" name="subject">
	<frame src="contents_top.php" title="" name="contents">

	<noframes>
		<body>
		このページをご覧いただくにはフレーム対応のブラウザが必要です。
		</body>
	</noframes>
</frameset>

</html>
