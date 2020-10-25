<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者達成メール設定
'******************************************************/

$title_text = $TT_tassei;
$title_color = $TC_OTHER;


$sql = "SELECT * FROM M_TASSEI_MAIL WHERE TM_STATUS='1' ORDER BY tm_seq_no DESC";
$result = db_exec($sql);

if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$tm_from = $fetch->tm_from;
	$tm_header = $fetch->tm_header;
	$tm_footer = $fetch->tm_footer;
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
  document.form1.tm_num.focus();
}

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}

function OnSubmit_form1() {
  with (document.form1) {
    if (tm_num.value == "") {
      alert("達成人数を入力してください。");
      tm_num.focus();
      return false;
    }
		if (!num_chk(tm_num.value)){
			alert("達成人数は半角の数値で入力してください。");
			tm_num.focus();
			return(false);
		}
    if (tm_subject.value == "") {
      alert("件名を入力してください。");
      tm_subject.focus();
      return false;
    }
    if (tm_from.value == "") {
      alert("送信元メールアドレスを入力してください。");
      tm_from.focus();
      return false;
    }
    if (tm_body.value == "") {
      alert("本文を入力してください。");
      tm_body.focus();
      return false;
    }
  }
  return confirm("送信メールテンプレートを更新します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="update.php" name="form1" onSubmit="return OnSubmit_form1();" style="margin:0">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■メール情報</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4" width="20%">達成人数</td>
      <td class="n6"><input type="text" name="tm_num" size=8 maxlength=6>人</td>
    </tr>
    <tr>
      <td class="m4">件名</td>
      <td class="n6"><input type="text" name="tm_subject" size=80></td>
    </tr>
    <tr>
      <td class="m4">送信元アドレス</td>
      <td class="n6"><input type="text" name="tm_from" size=80 <?= value($tm_from) ?>></td>
    </tr>
    <tr>
      <td class="m4">ヘッダ</td>
      <td class="n6"><textarea cols=80 rows=6 name="tm_header" class="np"><?= htmlspecialchars($tm_header) ?></textarea></td>
    </tr>
    <tr>
      <td class="m4">本文</td>
      <td class="n6">
			<font class="note">※差込文字（発行者名： @pub_name@ 、発行者ﾒｰﾙｱﾄﾞﾚｽ： @pub_mail@ 、ﾒﾙﾏｶﾞ名： @mag_name@ ）</font><br>
			<textarea cols=80 rows=16 name="tm_body" class="np"></textarea></td>
    </tr>
    <tr>
      <td class="m4">フッタ</td>
      <td class="n6"><textarea cols=80 rows=6 name="tm_footer" class="np"><?= htmlspecialchars($tm_footer) ?></textarea></td>
    </tr>
  </table><br>
	<input type="hidden" name="next_action" value="new">
  <input type="submit" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
