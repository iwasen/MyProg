<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:HTMLメールテンプレート
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', 'アンケートメールテンプレート', BACK_TOP);

$sql = "SELECT * FROM m_enq_template WHERE et_template_id='$template_id'";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	db_exec("INSERT INTO m_enq_template (et_template_id) VALUES ('$template_id')");
else
	$fetch = pg_fetch_object($result, 0);

$fetch2 = get_system_info(sy_url_top);
$top_url = $fetch2->sy_url_top;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1() {
	var f = document.form1;
	f.next_action.value='update';
	return confirm("アンケートメールテンプレートを更新します。よろしいですか？");
}
function onclick_css() {
	var f = document.form1;
	f.next_action.value='css';
	f.submit();
}
function onclick_image() {
	var f = document.form1;
	f.next_action.value='image';
	f.submit();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class="m0" colspan=2>■アンケートメールテンプレート情報を入力してください。</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=800>
				<tr>
					<td class="m1">件名</td>
					<td class="n1"><input class="kanji" type="text" name="header" size=80 maxlength=100 <?=value($fetch->et_header)?>><span class="note">（全角１００文字まで）</span></td>
				</tr>
				<tr>
					<td class="m1">CSSの指定</td>
					<td class="n1">
						<table border=0 cellspacing=2 cellpadding=3 width="100%">
							<tr>
								<td bgcolor="#cccccc" width="30%">ファイル名</td>
								<td bgcolor="#cccccc" width="65%">cssファイルのURL</td>
								<td width="10%"><input type="button" value="設定" onclick="onclick_css()"></td>
							</tr>
<?
$sql = "SELECT htc_css_id,htc_file_name FROM t_html_css WHERE htc_job_id=0 ORDER BY htc_css_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch2 = pg_fetch_object($result, $i);
?>
							<tr>
								<td bgcolor="#ffcc00"><?=htmlspecialchars($fetch2->htc_file_name)?></td>
								<td bgcolor="#ffcc00"><?=htmlspecialchars("{$top_url}css.php?id={$fetch2->htc_css_id}")?></td>
								<td></tr>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m1">画像の指定</td>
					<td class="n1">
						<table border=0 cellspacing=2 cellpadding=3 width="100%">
							<tr>
								<td bgcolor="#cccccc" width="30%">ファイル名</td>
								<td bgcolor="#cccccc" width="65%">画像のURL</td>
								<td width="10%"><input type="button" value="設定" onclick="onclick_image()"></td>
							</tr>
<?
$sql = "SELECT hti_image_id,hti_file_name FROM t_html_image WHERE hti_job_id=0 ORDER BY hti_image_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch2 = pg_fetch_object($result, $i);
?>
							<tr>
								<td bgcolor="#ffcc00"><?=htmlspecialchars($fetch2->hti_file_name)?></td>
								<td bgcolor="#ffcc00"><?=htmlspecialchars("{$top_url}img.php?id={$fetch2->hti_image_id}")?></td>
								<td></tr>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m1" width=140>HTMLソース</td>
					<td class="n1">
					<textarea name="body" cols=80 rows=30><?=$fetch->et_body?></textarea></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="template_id" value="<?=$template_id?>">
<input type="hidden" name="next_action">
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
