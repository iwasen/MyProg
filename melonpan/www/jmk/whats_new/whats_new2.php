<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「What's New」入力
'******************************************************/

$title_text = $TT_whats_new;
$title_color = $TC_MASTER;

$sql = "SELECT * FROM T_WHATS_NEW WHERE msg_id='$msg_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, $i);
	$msg_id = $fetch->msg_id;
	$new_msg = $fetch->new_msg;
	$msg_url = $fetch->msg_url;
	$target = $fetch->target;
	if ($target == "_self") { $self = "checked"; }
	if ($target == "_top") { $top = "checked"; }
	if ($target == "_parent") { $parent = "checked"; }
	if ($target == "_blank") { $blank = "checked"; }

}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.new_msg.focus();
}
function OnClick_info() {
  var win;
  win = window.open(document.form1.msg_url.value, "info_mag", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
  win.focus();
}
function OnSubmit_form1() {
	with (document.form1) {
    if (new_msg.value == "") {
      alert("メッセージを入力してください。");
      new_msg.focus();
      return false;
    }
		str=new_msg.value;
		if (str.length > 100) {
			alert("コメントが長すぎます。");
			new_msg.focus();
			return(false);
		}
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("削除します。よろしいですか？")) {
    location.href = "whats_new5.php?msg_id=<?= $msg_id ?>";
	}
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="whats_new4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">■「What's New」更新</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="80%">
		<tr>
			<td class="m4"><nobr>メッセージ</nobr></td>
			<td class="n6">
				<textarea name="new_msg" cols=80 rows=5><?= htmlspecialchars($new_msg) ?></textarea><font class="note">（100文字以内）</font>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>リンクするURL</nobr></td>
			<td class="n6">
				<input size=80 name=msg_url maxlength=200 value="<?= htmlspecialchars($msg_url) ?>"><font class="note">（半角英数）</font>
				<input type="button" value="確認" onclick="OnClick_info()">
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>ターゲット</nobr></td>
			<td class="n6">
				<input type="radio" name="target" value="_self" <?= $self ?>>_self　
				<input type="radio" name="target" value="_top" <?= $top ?>>_top　
				<input type="radio" name="target" value="_parent" <?= $parent ?>>_parent　
				<input type="radio" name="target" value="_blank" <?= $blank ?>>_blank　
			</td>
		</tr>
	</table>
	<br>
	<input type="hidden" name="msg_id" value="<?= $msg_id ?>">
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
  <input type="button" value=" 削除 " onclick="OnClick_sakujo()">
	<input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
