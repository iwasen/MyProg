<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:送信メールテンプレート変更画面
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '送信メールテンプレート', BACK_TOP);

switch ($template_id) {
case 'present1':
	$msg = "<span class='note'>※差込文字が使えます。（会員氏名： %MEMBER_NAME% 、会員トップURL： %TOP_URL%）</span>
";
	break;
case 'present2':
	$msg = "<span class='note'>※差込文字が使えます。（タイトル： %TITLE% 、発送日： %SEND_DATE%）</span>
";
 break;
}

$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='$template_id'";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("template_idが不正", __FILE__);
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
	return confirm("送信メールテンプレートを更新します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■送信メールテンプレート情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">テンプレート名</td>
		<td class="n1">
			<input type="text" name="guide" size=70 <?=value($fetch->mt_guide)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">件名</td>
		<td class="n1">
			<input class="kanji" type="text" name="subject" size=70 maxlength=100 <?=value($fetch->mt_subject)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">Fromアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="from" size=70 maxlength=100 <?=value($fetch->mt_from)?>>
		</td>
	</tr>
<?
if ($template_id != 'present2') {
?>
	<tr>
		<td class="m1">Ccアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="cc" size=70 maxlength=100 <?=value($fetch->mt_cc)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">Bccアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="bcc" size=70 maxlength=100 <?=value($fetch->mt_bcc)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">Reply-Toアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="reply_to" size=70 maxlength=100 <?=value($fetch->mt_reply_to)?>>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">本文</td>
		<td class="n1"><?=$msg?><textarea class="kanji" name="body" cols=78 rows=20><?=htmlspecialchars($fetch->mt_body)?></textarea></td>
	</tr>
</table>
<br>
<input type="hidden" name="template_id" <?=value($fetch->mt_template_id)?>>
<input type="submit" value="　更新　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
