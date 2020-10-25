<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ɼԾ�����ƥʥ�
'******************************************************/

$title_text = $TT_dokusha_mainte;
$title_color = $TC_DOKUSHA;

$mail_addr = replace_single($mail_addr);

//'=== ��������� ===
function shokugyou($reader_occup) {
	$sql2 = "SELECT occup_id, occup FROM M_MAG_OCCUP";
	$result = db_exec($sql2);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$occup_id = $fetch->occup_id;
		$occup = $fetch->occup;
		if ($occup_id == $reader_occup) {
			print "<option value=\"$occup_id\" selected>". $occup ."</option>\n";
		} else {
			print "<option value=\"$occup_id\">". $occup ."</option>\n";
		}
	}
}

//'=== Main ===
$sql ="SELECT mail_addr, reader_id, reader_pswd"
	. ", TO_CHAR(reader_birth, 'YYYY') as birth_year"
	. ", TO_CHAR(reader_birth, 'MM') as birth_month"
	. ", TO_CHAR(reader_birth, 'DD') as birth_day"
	. ", reader_sex, reader_mrg, reader_zip1, reader_zip2, reader_occup, pt_melonpan"
	. ", TO_CHAR(modify_dt, 'YYYY') as modi_year"
	. ", TO_CHAR(modify_dt, 'MM') as modi_month"
	. ", TO_CHAR(modify_dt, 'DD') as modi_day"
	. ", TO_CHAR(modify_dt, 'hh24:mi:ss') as modi_time"
	. " FROM M_MELONPA"
	. " WHERE reader_id=$reader_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$reader_id = $fetch->reader_id;
	$old_mail_addr = $fetch->mail_addr;
	$reader_pswd = $fetch->reader_pswd;
	$birth_year = $fetch->birth_year;
	$birth_month = $fetch->birth_month;
	$birth_day = $fetch->birth_day;
	$reader_sex = $fetch->reader_sex;
	$reader_mrg = $fetch->reader_mrg;
	$reader_zip1 = $fetch->reader_zip1;
	$reader_zip2 = $fetch->reader_zip2;
	$reader_occup = $fetch->reader_occup;
	$pt_melonpan = $fetch->pt_melonpan;
	$modi_year = $fetch->modi_year;
	$modi_month = $fetch->modi_month;
	$modi_day = $fetch->modi_day;
	$modi_time = $fetch->modi_time;

	$male = ""; $female = ""; $mikon = ""; $kikon = "";
	if ($reader_sex == 1) {
		$male = " checked";
	} elseif ($reader_sex == 2) {
		$female = " checked";
	}
	if ($reader_mrg == 1) {
		$mikon = " checked";
	} elseif ($reader_mrg == 2) {
		$kikon = " checked";
	}
	$y = $birth_year;
	$m = $birth_month;
	$d = $birth_day;
} else {
	header ("location: ../admin/err_msg.php?id=2108");
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
    if (mail_addr.value == "") {
      alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
      mail_addr.focus();
      return false;
    }
		if (!isA(mail_addr.value)) {
			alert("�᡼�륢�ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			mail_addr.focus();
			return false;
		}
    if (password.value == "") {
      alert("�ѥ���ɤ����Ϥ��Ƥ���������");
      password.focus();
      return false;
    }
    if (zip1.value == "") {
      alert("͹���ֹ�����Ϥ��Ƥ���������");
      zip1.focus();
      return false;
    }
    if (zip2.value == "") {
      alert("͹���ֹ�����Ϥ��Ƥ���������");
      zip2.focus();
      return false;
    }
		if (!num_chk(zip1.value)){
			alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			zip1.focus();
			return(false);
		}
		if (!num_chk(zip2.value)){
			alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			zip2.focus();
			return(false);
		}
  }
	return confirm("�������ޤ���������Ǥ�����");
}
function OnClick_sakujo(reader_id) {
	if (confirm("�ɼ���Ͽ�������ޤ���������Ǥ�����")) {
	location.href = "dokusha_mainte4.php?reader_id=" + reader_id;
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="dokusha_mainte3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���ɼԾ�����ƥʥ�</td>
		</tr>
	</table>
	<table border=0 cellspacing=1 cellpadding=3 width="700">
		<tr>
			<td class="m5" width="25%">�᡼�륢�ɥ쥹</td>
			<td class="n5"><input size=50 name=mail_addr maxlength=50 <?= value($old_mail_addr) ?>> <font class="note">��Ⱦ�ѱѿ���</font><BR></td>
		</tr>
		<tr>
			<td class="m5">�ѥ����</td>
			<td class="n5"><input size=20 name=password maxlength=14 <?= value($reader_pswd) ?>> <font class="note">��Ⱦ�ѱѿ���4ʸ���ʾ�8ʸ�������</font></td>
		</tr>
		<tr>
			<td class="m5">��ǯ����</td>
			<td class="n5">
				<select name="year"><? select_birth_year(1900, '', $birth_year); ?></select>ǯ
				<select name="month"><? select_month('' ,$birth_month); ?></select>��
				<select name="day"><? select_day('', $birth_day); ?></select>��
			</td>
		</tr>
		<tr>
			<td class="m5">����</font></td>
			<td class="n5">
				<input type="radio" name="reader_sex" value="1" <?= $male ?>> ����
				<input type="radio" name="reader_sex" value="2" <?= $female ?>> ����
				</td>
    </tr>
		<tr>
			<td class="m5">	̤����</font></td>
      <td class="n5">
      	<input type="radio" name="reader_mrg" value="1" <?= $mikon?>> �ȿ�
        <input type="radio" name="reader_mrg" value="2" <?= $kikon ?>>����
				</td>
		</tr>
		<tr>
			<td class="m5">͹���ֹ�</td>
			<td class="n5"><input size=3 name=zip1 maxlength=3 value="<?= $reader_zip1 ?>">-<input size=4 name=zip2 maxlength=4 value="<?= $reader_zip2 ?>"> <font class="note">��Ⱦ�ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">����</td>
			<td class="n5">
				<select name="reader_occup"><? shokugyou($reader_occup); ?></select>
			</td>
		</tr>
		<tr>
			<td class="m5">�ݥ����</td>
			<td class="n5"><?= $pt_melonpan ?> �ݥ����</td>
		</tr>
		<tr>
			<td class="m5">�ǽ�������</td>
			<td class="n5" name="modify_dt"><? print $modi_year . "ǯ" . sprintf("%2d", $modi_month) . "��" . sprintf("%2d", $modi_day) . "�� " . $modi_time; ?></td>
		</tr>
	</table><br>
	<input type="hidden" name="old_mail_addr" value="<?= $old_mail_addr ?>">
	<input type="hidden" name="reader_id" value=<?= $reader_id ?>>
	<input type="submit" value=" ���� ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="OnClick_sakujo('<?= $reader_id ?>')">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
