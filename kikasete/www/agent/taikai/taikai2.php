<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:退会処理
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/check.php");

//メイン処理
set_global('退会処理', BACK_TOP);

$sql = "SELECT ag_agent_id FROM t_agent WHERE ag_agent_id=$agent_login_id AND ag_password='$password'";
$result = db_exec($sql);
if (pg_numrows($result) != 0) {
	$sql = "UPDATE t_agent SET ag_status=9 WHERE ag_agent_id=$agent_login_id";
	db_exec($sql);

	session_start();
	session_destroy();

	$msg = "退会処理を実行しました。";
	$back = "location.href='../index.php'";

} else {
	$msg = "パスワードが正しくありません。";
	$back = "location.href='javascript:history.back()'";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
				<tr>
					<td align="center"><br><br>
						<p class="msg"><?=$msg?></p>
						<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
