<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�����᡼��ƥ�ץ졼��
'******************************************************/

$title_text = $TT_mail_template;
$title_color = $TC_MASTER_MAINTE;

$sql = "SELECT * FROM M_MAIL_TEMPLATE WHERE MT_id='$mt_id'";
$result = db_exec($sql);

if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$guide = htmlspecialchars(trim($fetch->mt_guide));
	$subject = value($fetch->mt_subject);
	$from = value($fetch->mt_from);
	$cc = value($fetch->mt_cc);
	$bcc = value($fetch->mt_bcc);
	$body = htmlspecialchars($fetch->mt_body);
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
function OnSubmit_form1() {
  with (document.form1) {
    if (subject.value == "") {
      alert("��̾�����Ϥ��Ƥ���������");
      subject.focus();
      return false;
    }
    if (body.value == "") {
      alert("��ʸ�����Ϥ��Ƥ���������");
      body.focus();
      return false;
    }
  }
  return confirm("�����᡼��ƥ�ץ졼�Ȥ򹹿����ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="mail_template2.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">���᡼�����</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4" width="20%">����</td>
      <td class="n6"><?= $guide ?></td>
    </tr>
    <tr>
      <td class="m4">��̾</td>
      <td class="n6"><input type="text" name="subject" size=80 <?= $subject ?>></td>
    </tr>
    <tr>
      <td class="m4">���������ɥ쥹</td>
      <td class="n6"><input type="text" name="from" size=80 <?= $from ?>></td>
    </tr>
    <tr>
      <td class="m4">cc���ɥ쥹</td>
      <td class="n6"><input type="text" name="cc" size=80 <?= $cc ?>></td>
    </tr>          <tr>
      <td class="m4">bcc���ɥ쥹</td>
      <td class="n6"><input type="text" name="bcc" size=80 <?= $bcc ?>></td>
    </tr>
    <tr>
      <td class="m4">��ʸ</td>
      <td class="n6"><textarea cols=80 rows=30 name="body" class="np"><?= $body ?></textarea></td>
    </tr>
  </table><br>
  <input type="submit" value=" ���� ">
  <input type="reset" value="�ꥻ�å�">
  <input type="button" value=" ��� " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
