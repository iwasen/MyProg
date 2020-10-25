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

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', 'システムマスタ', BACK_TOP);

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('システムＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.sy_system_name.value == "") {
		alert("システム名称を入力してください。");
		f.sy_system_name.focus();
		return false;
	}
	if (f.sy_url_monitor.value == "") {
		alert("モニターページURLを入力してください。");
		f.sy_url_monitor.focus();
		return false;
	}
	if (f.sy_url_staff.value == "") {
		alert("スタッフページURLを入力してください。");
		f.sy_url_staff.focus();
		return false;
	}
	if (f.sy_url_admin.value == "") {
		alert("事務局ページURLを入力してください。");
		f.sy_url_admin.focus();
		return false;
	}
	if (f.sy_mail_from.value == "") {
		alert("全配信送信元アドレスを入力してください。");
		f.sy_mail_from.focus();
		return false;
	}
	return confirm("システムマスタ情報を更新します。よろしいですか？");
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■システムマスタ情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width=35%>システムID<?=MUST_ITEM?></td>
		<td class="n1" width=65%><?=$fetch->sy_system_id?></td>
	</tr>
	<tr>
		<td class="m1">システム名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_system_name" size=70 maxlength=50 <?=value($fetch->sy_system_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■ＷｅｂＵＲＬ設定</td>
	</tr>
	<tr>
		<td class="m1">サイトトップURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_top" size=70 maxlength=100 <?=value($fetch->sy_url_top)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">モニターページURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_monitor" size=70 maxlength=100 <?=value($fetch->sy_url_monitor)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">スタッフページURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_staff" size=70 maxlength=100 <?=value($fetch->sy_url_staff)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">事務局ページURL<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_url_admin" size=70 maxlength=100 <?=value($fetch->sy_url_admin)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■全配信設定</td>
	</tr>
	<tr>
		<td class="m1">全配信送信元アドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_from" size=70 maxlength=100 <?=value($fetch->sy_mail_from)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">全配信送信元名称</td>
		<td class="n1">
			<input class="kanji" type="text" name="sy_mail_name" size=70 maxlength=50 <?=value($fetch->sy_mail_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■通知先アドレス設定</td>
	</tr>
	<tr>
		<td class="m1">Ｍｙアンケート申請通知先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_myenq_to" size=70 maxlength=100 <?=value($fetch->sy_myenq_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メール送信完了通知先アドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_mail_notify_to" size=70 maxlength=100 <?=value($fetch->sy_mail_notify_to)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メールサーバ設定</td>
	</tr>
	<tr>
		<td class="m1">送信メールエンベロープアカウント</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_envelope_addr" size=70 maxlength=100 <?=value($fetch->sy_envelope_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">POP3サーバのホスト名</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_pop_server" size=70 maxlength=100 <?=value($fetch->sy_pop_server)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■アンケートメール設定</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信ドメイン</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_domain" size=70 maxlength=100 <?=value($fetch->sy_enq_domain)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">アンケート受信アカウント</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_account" size=70 maxlength=100 <?=value($fetch->sy_enq_account)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Webアンケート送信元名称</td>
		<td class="n1">
			<input class="alpha" type="text" name="sy_enq_web" size=70 maxlength=100 <?=value($fetch->sy_enq_web)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="submit" value="　更新　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
