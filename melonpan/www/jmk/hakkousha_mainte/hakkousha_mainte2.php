<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:ȯ�ԼԾ�����ƥʥ�
'******************************************************/

$title_text = $TT_hakkousha_mainte;
$title_color = $TC_HAKKOUSHA;

//'=== Main ===
$sql ="SELECT publisher_id, mail_add_main, mail_add_sub, owner_pswd, owner_nm_kanj, owner_nm_kana"
	. ", owner_zip1, owner_zip2, owner_prefec, owner_add, owner_tel, owner_riba_flg, pt_melonpan"
	. ", TO_CHAR(modefy_dt, 'YYYY') as modi_year"
	. ", TO_CHAR(modefy_dt, 'MM') as modi_month"
	. ", TO_CHAR(modefy_dt, 'DD') as modi_day"
	. ", TO_CHAR(modefy_dt, 'hh24:mi:ss') as modi_time"
	. ", TO_CHAR(regist_dt, 'YYYY') as reg_year"
	. ", TO_CHAR(regist_dt, 'MM') as reg_month"
	. ", TO_CHAR(regist_dt, 'DD') as reg_day"
	. ", TO_CHAR(regist_dt, 'hh24:mi:ss') as reg_time"
	. " FROM M_PUBLISHER"
	. " WHERE publisher_id=$publisher_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, $i);
	$publisher_id = $fetch->publisher_id;
	$mail_add_main = $fetch->mail_add_main;
	$mail_add_sub = $fetch->mail_add_sub;
	$owner_pswd = $fetch->owner_pswd;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$owner_nm_kana = $fetch->owner_nm_kana;
	$owner_zip1 = $fetch->owner_zip1;
	$owner_zip2 = $fetch->owner_zip2;
	$owner_prefec = $fetch->owner_prefec;
	$owner_add = $fetch->owner_add;
	$owner_tel = $fetch->owner_tel;
	$owner_riba_flg = $fetch->owner_riba_flg;
	$pt_melonpan = $fetch->pt_melonpan;
	$modi_year = $fetch->modi_year;
	$modi_month = $fetch->modi_month;
	$modi_day = $fetch->modi_day;
	$modi_time = $fetch->modi_time;
	$reg_year = $fetch->reg_year;
	$reg_month = $fetch->reg_month;
	$reg_day = $fetch->reg_day;
	$reg_time = $fetch->reg_time;
	if ($owner_riba_flg == '1') {
		$riba = " checked";
	} if ($owner_riba_flg <> '1') {
		$no_riba = " checked";
	}
} else {
	header ("location: ../admin/err_msg.php?id=0908");
	exit;
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
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
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
    if (mail_add_main.value == "") {
      alert("���ܥ᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
      mail_add_main.focus();
      return false;
    }
		if (!isA(mail_add_main.value)) {
			alert("���ܥ᡼�륢�ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			mail_add_main.focus();
			return false;
		}
    if (mail_add_sub.value == "") {
      alert("���ܥ᡼�륢�ɥ쥹�������Ϥ��Ƥ���������");
      mail_add_sub.focus();
      return false;
    }
		if (!isA(mail_add_sub.value)) {
			alert("���ܥ᡼�륢�ɥ쥹����Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			mail_add_sub.focus();
			return false;
		}
    if (owner_pswd.value == "") {
      alert("�ѥ���ɤ����Ϥ��Ƥ���������");
      owner_pswd.focus();
      return false;
    }
    if (owner_zip1.value == "") {
      alert("͹���ֹ�����Ϥ��Ƥ���������");
      owner_zip1.focus();
      return false;
    }
    if (owner_zip2.value == "") {
      alert("͹���ֹ�����Ϥ��Ƥ���������");
      owner_zip2.focus();
      return false;
    }
		if (!num_chk(owner_zip1.value)){
			alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			owner_zip1.focus();
			return(false);
		}
		if (!num_chk(owner_zip2.value)){
			alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			owner_zip2.focus();
			return(false);
		}
  }
	return confirm("�������ޤ���������Ǥ�����");
}
function OnClick_sakujo(publisher_id) {
//	if (confirm("ȯ�Լ���Ͽ�������ޤ���������Ǥ�����")) {
	location.href = "hakkousha_mainte4.php?pub_id=" + publisher_id;
//	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="hakkousha_mainte3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">��ȯ�ԼԾ�����ƥʥ�</td>
		</tr>
	<table border=0 cellspacing=1 cellpadding=3 width="700">
		<tr>
			<td class="m5" width="25%">���ܥ᡼�륢�ɥ쥹</td>
			<td class="n5"><input size=50 name=mail_add_main maxlength=50 value="<?= $mail_add_main ?>"><font class="note">��Ⱦ�ѱѿ���</font><BR></td>
		</tr>
		<tr>
			<td class="m5" width="25%">���ܥ᡼�륢�ɥ쥹��</td>
			<td class="n5"><input size=50 name=mail_add_sub maxlength=50 value="<?= $mail_add_sub ?>"><font class="note">��Ⱦ�ѱѿ���</font><BR></td>
		</tr>
		<tr>
			<td class="m5">�ѥ����</td>
			<td class="n5"><input size=20 name=owner_pswd maxlength=20 value="<?= $owner_pswd ?>"><font class="note">��Ⱦ�ѱѿ���4ʸ���ʾ�8ʸ�������</font></td>
		</tr>
		<tr>
			<td class="m5">ȯ�Լ�[����]</td>
			<td class="n5"><input size=40 name=owner_nm_kanj maxlength=25 <?= value($owner_nm_kanj) ?>><font class="note">�����ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">ȯ�Լ�[����]</td>
			<td class="n5"><input size=40 name=owner_nm_kana maxlength=25 value="<?= $owner_nm_kana ?>"><font class="note">�����ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">͹���ֹ�</td>
			<td class="n5"><input size=3 name=owner_zip1 maxlength=3 value="<?= $owner_zip1 ?>">-<input size=4 name=owner_zip2 maxlength=4 value="<?= $owner_zip2 ?>"> <font class="note">��Ⱦ�ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">Ϣ���轻��</td>
			<td class="n5"><select name="owner_prefec"><? SelectKen($owner_prefec); ?></select>
				<input size=50 name=owner_add maxlength=100 value="<?= $owner_add ?>"><font class="note">�����ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">Ϣ���������ֹ�</td>
			<td class="n5"><input size=20 name=owner_tel maxlength=14 value="<?= $owner_tel ?>"><font class="note">��Ⱦ�ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">��Фå���</td>
			<td class="n5">
				<input type="radio" name="owner_riba_flg" value="1" <?= $riba ?>> ����
				<input type="radio" name="owner_riba_flg" value="" <?= $no_riba ?>> �ʤ�
			</td>
		</tr>
		<tr>
			<td class="m5">�ݥ����</td>
			<td class="n5"><?= number_format($pt_melonpan) ?> �ݥ����</td>
		</tr>
		<tr>
			<td class="m5">��Ͽ��</td>
<? if ($reg_year == "") { ?>
			<td class="n5" name="modify_dt">-</td>
<? } else { ?>
			<td class="n5" name="modify_dt"><? print $reg_year . "ǯ" . sprintf("%2d", $reg_month) . "��" . sprintf("%2d", $reg_day) . "�� " . $reg_time; ?></td>
<? } ?>
		</tr>
		<tr>
			<td class="m5">�ǽ�������</td>
			<td class="n5" name="reg_dt"><? print $modi_year . "ǯ" . sprintf("%2d", $modi_month) . "��" . sprintf("%2d", $modi_day) . "�� " . $modi_time; ?></td>
		</tr>
	</table><br>
	<input type="hidden" name="old_mail_add_main" value=<?= $mail_add_main ?>>
	<input type="hidden" name="publisher_id" value=<?= $publisher_id ?>>
	<input type="submit" value=" ���� ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="OnClick_sakujo('<?= $publisher_id ?>')">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
