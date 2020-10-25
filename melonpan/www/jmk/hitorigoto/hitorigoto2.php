<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「今日のひとりごと」入力
'******************************************************/

$title_text = $TT_hitorigoto;
$title_color = $TC_MASTER;

$sql = "SELECT * FROM T_ADMIN_MSG WHERE sqmsg_id=$sqmsg_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$access_day = $fetch->access_day;
	$admin_msg = $fetch->admin_msg;
	$info_year = substr($access_day, 0, 4);
	$info_month = substr($access_day, 4, 2);
	$info_day = substr($access_day, 6, 2);
	$info_date = $info_year . "年" . sprintf("%2d", $info_month) . "月" . sprintf("%2d", $info_day) . "日";
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
  document.form1.admin_msg.focus();
}
function OnSubmit_form1() {
  with (document.form1) {
    if (admin_msg.value == "") {
      alert("メッセージを入力してください。");
      admin_msg.focus();
      return false;
    }
		str=admin_msg.value;
		if (str.length > 100) {
			alert("メッセージが長すぎます。");
			document.form1.admin_msg.focus();
			return(false);
		}
  }
  return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("削除します。よろしいですか？")) {
    location.href = "hitorigoto5.php?sqmsg_id=<?= $sqmsg_id ?>";
	}
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="hitorigoto4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■「今日のひとりごと」情報</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4" width="20%">表示指定日</td>
      <td class="n6"><?= $info_date ?></td>
    </tr>
    <tr>
      <td class="m4">メッセージ<br><font size="-1">（100文字以内）</font></td>
      <td class="n6"><textarea cols=60 rows=4 name="admin_msg" class="np"><?= htmlspecialchars($admin_msg) ?></textarea></td>
    </tr>
  </table><br>
	<input type="hidden" name="sqmsg_id" value="<?= $sqmsg_id ?>">
	<input type="hidden" name="access_day" value="<?= $access_day ?>">
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 削除 " onclick="OnClick_sakujo()">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
