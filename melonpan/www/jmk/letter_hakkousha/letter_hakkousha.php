<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:ȯ�Լ԰��ƥ᡼��
'******************************************************/

$title_text = $TT_letter_hakkousha;
$title_color = $TC_HAKKOUSHA;

//=== Main ===
$mt_id = "send_pub";
$sql = "SELECT * FROM M_MAIL_TEMPLATE WHERE MT_id='$mt_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$guide = $fetch->mtguide;
	$subject = $fetch->mt_subject;
	$from = $fetch->mt_from;
	$cc = $fetch->mt_cc;
	$bcc = $fetch->mt_bcc;
	$body = $fetch->mt_body;
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
function  ShowHide( id, show ) {
	var disp = document.all(id).style.display;
	if (show == 'all')
		document.all(id).style.display = "none";
	else
	if (show == 'choice')
		document.all(id).style.display = "block";
}

function OnClick_pub() {
  var win;
  win = window.open("../melmaga_detail/send_pub_list.php", "pub_list","scrollbars=yes,resizable=yes,width=800,height=500");
  win.focus();
}
function OnSubmit_form1() {
  fm = document.form1;
  if (fm.chk[0].checked == false && fm.chk[1].checked == false) {
    alert("��������򤷤Ƥ���������");
    return false;
  }
  if (fm.chk[1].checked == true && fm.mailto.value == "") {
    alert("��������Ϥ��Ƥ���������");
    fm.mailto.focus();
    return false;
  }
  if (fm.mailfrom.value == "") {
    alert("���������ɥ쥹�����Ϥ��Ƥ���������");
    fm.mailfrom.focus();
    return false;
  }
  if (fm.subject.value == "") {
    alert("��̾�����Ϥ��Ƥ���������");
    fm.subject.focus();
    return false;
  }
  if (fm.body.value == "") {
    alert("��ʸ�����Ϥ��Ƥ���������");
    fm.body.focus();
    return false;
  }
  return confirm("�᡼����������ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="letter_hakkousha2.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">��ȯ�Լ԰��ƥ᡼�����</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">����</td>
      <td class="n6">
				<input type="radio" name="chk" onclick="ShowHide('kind', 'all')" value="all">��ȯ�Լ�
				<input type="radio" name="chk" onclick="ShowHide('kind', 'choice')" value="choice">��������<br>
				<span ID="kind" style="display:none;">
					<font class="note">�������ȯ�Լ�ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font><br>
					<textarea name="mailto" cols="56" rows="4"></textarea>
					<input type="button" value="����" OnClick='JavaScript:OnClick_pub()'>
				</span>
			</td>
    </tr>
    <tr>
      <td class="m4">���������ɥ쥹</td>
      <td class="n6"><input type="text" name="mailfrom" size=80 value="<?= htmlspecialchars($from) ?>"></td>
    </tr>
    <tr>
      <td class="m4">��̾</td>
      <td class="n6"><input type="text" name="subject" size=80 value="<?= htmlspecialchars($subject) ?>"</td>
    </tr>
    <tr>
      <td class="m4">��ʸ</td>
			<td class="n6"><font class="note">��@hakkousha@�ˤ�ȯ�Լ�̾��@publisher_id@�ˤ�ȯ�Լ�ID��@point@�ˤϸ��ݥ���ȿ��������ޤ�ޤ���</font><br>
				<textarea cols=80 rows=30 name="body" class="np"><?= htmlspecialchars($body) ?></textarea></td>
    </tr>
  </table><br>
  <input type="submit" value=" ���� ">
  <input type="reset" value="�ꥻ�å�">
  <input type="button" value=" ��� " onclick="location.href='../menu.php'">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
