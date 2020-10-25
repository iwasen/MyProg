<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:トップメニュー画面
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('charara', '', 'きゃららFactory 管理メニュー', BACK_LOGOFF);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>
<table align="center" border=0 cellpadding=8 cellspacing=0 width="90%">
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="nigaoe_m" colspan=3 align="left">■似顔絵情報管理</th></tr>
				<tr>
					<td width="33%"><a href="nigaoe/order_list/list.php">似顔絵受付リスト</a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="staff_m" colspan=3 align="left">■スタッフ情報管理</a></th></tr>
				<tr>
					<td width="33%"><a href="staff/check/list.php">似顔絵チェックリスト</a></td>
					<td width="33%"><a href="staff/list/list.php">スタッフ登録リスト</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="user_m" colspan=3 align="left">■ユーザ情報管理</th></tr>
				<tr>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="mail_m" colspan=3 align="left">■メール配信管理</th></tr>
				<tr>
					<td width="33%"><a href="mail/mail_template/list.php">送信メールテンプレート</a></td>
					<td width="33%"><a href="mail/send_mail/list.php">配信メール設定</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="master_m" colspan=3 align="left">■マスタ管理</th></tr>
				<tr>
					<td width="33%"><a href="master/nigaoe/menu.php">似顔絵関連マスタ</a></td>
					<td width="33%"><a href="master/staff/menu.php">スタッフ関連マスタ</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc_m" colspan=3 align="left">■その他管理</th></tr>
				<tr>
					<td width="33%"><a href="etc/server/menu.php">サーバ別応募状況</a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
