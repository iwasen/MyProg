<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:システムマスタ登録変更画面
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

function password($str) {
	return str_repeat('*', strlen($str));
}

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', 'システムマスタ', BACK_TOP);

$sys_id = 1;

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="edit.php">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■システム情報</td>
	</tr>
	<tr>
		<td class="m1" width=35%>システムID</td>
		<td class="n1" width=65%><?=$fetch->sy_system_id?></td>
	</tr>
	<tr>
		<td class="m1">システム名称</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_system_name)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■ＷｅｂＵＲＬ設定</td>
	</tr>
	<tr>
		<td class="m1">サイトトップURL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_top)?></td>
	</tr>
	<tr>
		<td class="m1">モニターページURL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_monitor)?></td>
	</tr>
	<tr>
		<td class="m1">スタッフページURL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_staff)?></td>
	</tr>
	<tr>
		<td class="m1">事務局ページURL</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_url_admin)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■全配信設定</td>
	</tr>
	<tr>
		<td class="m1">全配信送信元アドレス</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mail_from)?></td>
	</tr>
	<tr>
		<td class="m1">全配信送信元名称</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mail_name)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■通知先アドレス設定</td>
	</tr>
	<tr>
		<td class="m1">Ｍｙアンケート申請通知先アドレス</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_myenq_to)?></td>
	</tr>
	<tr>
		<td class="m1">メール送信完了通知先アドレス</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_mail_notify_to)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メールサーバ設定</td>
	</tr>
	<tr>
		<td class="m1">送信メールエンベロープアカウント</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_envelope_addr)?></td>
	</tr>
	<tr>
		<td class="m1">POP3サーバのホスト名</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_pop_server)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■アンケートメール設定</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信ドメイン</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_domain)?></td>
	</tr>
	<tr>
		<td class="m1">アンケート受信アカウント</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_account)?></td>
	</tr>
	<tr>
		<td class="m1">Webアンケート送信元名称</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_enq_web)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　修正　">
<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
