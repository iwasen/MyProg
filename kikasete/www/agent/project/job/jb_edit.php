<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:����־��� ����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/decode.php");

// �᡼����������
function select_mail_format($default, $sel) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $sel), ">�ƥ�����</option>";
	echo "<option ", value_selected('1', $sel), ">HTML</option>";
}

// display����
function check_disp($sel1, $sel2) {
	if ($sel1 == $sel2)
		echo "block";
	else
		echo "none";
}

// �ƥ���������
function select_parent_job($default, $selected, $project_id) {
	if ($default)
		echo "<option value=''>$default</option>\n";
	$sql = "SELECT jb_job_id,jb_job_name FROM t_job WHERE jb_project_id=$project_id ORDER BY jb_job_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo "<option ", value_selected($fetch->jb_job_id, $selected), ">", htmlspecialchars($fetch->jb_job_name), "</option>";
	}
}

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '����־��󹹿�', BACK_TOP);

// �ƤӽФ�������¸
$sql = "SELECT pj_project_name FROM t_project WHERE pj_project_id=$pjt_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$pj_name = $fetch->pj_project_name;
}
if ($job_id) {
	$sql = "SELECT jb_job_name,jb_job_type,jb_mail_format,jb_parent_job_id,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_enquete_id,jb_cp_job_id,jb_category,en_enq_type,EXISTS (SELECT * FROM t_job AS jb2 WHERE jb1.jb_job_id=jb2.jb_cp_job_id) AS jb_parent"
		. " FROM t_job AS jb1 LEFT JOIN t_enquete ON en_enquete_id=jb_enquete_id WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	$en_id = $fetch->jb_enquete_id;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	parent_job1.style.display = "none";
	parent_job2.style.display = "none";
	if (f.job_type[1].checked)
		parent_job1.style.display = "block";
	if (f.job_type[2].checked)
		parent_job2.style.display = "block";
}
function OnChange_parent_job(flag) {
	if (flag)
		parent_job0.style.display = "block";
	else
		parent_job0.style.display = "none";
}
function OnChange_job_type(id, type) {
	parent_job1.style.display = "none";
	parent_job2.style.display = "none";
	if (type == 1)
		document.all(id).style.display = "block";
	else if (type == 2)
		document.all(id).style.display = "block";
}
function CopyJob(job_id) {
	location.replace("../project/jb_new.php?pjt_id=<?=$pjt_id?>&job_id=" + job_id);
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}

function update_check(f) {
	if (f.job_id.value == "") {
		alert("�����ID�����Ϥ��Ƥ���������");
		f.job_id.focus();
		return false;
	}
	if (f.job_name.value == "") {
		alert("�����̾�����Ϥ��Ƥ���������");
		f.job_name.focus();
		return false;
	}
/*
	if (f.mail_format.value == "") {
		alert("�᡼����������򤷤Ƥ���������");
		f.mail_format.focus();
		return false;
	}
*/
	if (f.job_type[1].checked) {
		if (f.parent_job_id1.value == "") {
			alert("�ƥ���֤���ꤷ�Ƥ���������");
			f.parent_job_id1.focus();
			return false;
		}
	} else if (f.job_type[2].checked) {
		if (f.parent_job_id2.value == "") {
			alert("�ƥ���֤���ꤷ�Ƥ���������");
			f.parent_job_id2.focus();
			return false;
		}
	}
	return confirm("����־���򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
<?
if ($fetch->jb_parent == 't') {
?>
		alert("���Υ���֤ˤϻҥ���֤�¸�ߤ��뤿�ᡢ����Ǥ��ޤ���");
<?
} else {
?>
	if (confirm("����֤������ޤ���������Ǥ�����"))
		return confirm("����֤Υǡ��������ƾõ��ޤ����ޤ���������Ǥ�����");
<?
}
?>
	return false;
}

//-->
</script>
<? menu_script() ?>
</head>
<body onload="onLoad_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="jb_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">���ץ������Ⱦ���</td>
	</tr>
	<tr>
		<td class="m1" width=140>�ץ�������ID</td>
		<td class="n1"><?=$pjt_id?></td>
	</tr>
	<tr>
		<td class="m1">�ץ�������̾</td>
		<td class="n1"><?=htmlspecialchars($pj_name)?></td>
	</tr>
</table><br>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2 align="left">������־���</td>
	</tr>
	<tr>
		<td class="m1" width=140>�����ID</td>
		<td class="n1"><?=htmlspecialchars($job_id)?></td>
	</tr>
	<tr>
		<td class="m1" width=140>�����̾</td>
		<td class="n1"><input class="kanji" type="text" name="job_name" size=50 maxlength=50 <?=value($fetch->jb_job_name)?>>
		<span class="note">�����ѣ���ʸ���ޤǡ�</span></td>
	</tr>
	<tr>
		<td class="m1">�ۿ����ƥ���</td>
		<td class="n1"><?=decode_job_category($fetch->jb_category)?></td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȼ���</td>
		<td class="n1"><?=decode_enq_type($fetch->en_enq_type)?></td>
	</tr>
	<tr>
		<td class="m1">�ƥ���ֻ���</td>
		<td class="n1">
<?
if ($fetch->jb_cp_job_id) {
	$sql = "SELECT jb_job_name FROM t_job WHERE jb_job_id=$fetch->jb_cp_job_id";
	echo "$fetch->jb_cp_job_id " . htmlspecialchars(db_fetch1($sql));
} else
	echo '̵��';
?>
		</td>
	</tr>
	<tr>
		<td class="m1">����ּ���</td>
		<td class="n1">
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><input type="radio" name="job_type" onclick="OnChange_job_type('', 0)" value=0 <?=value_checked(0, $fetch->jb_job_type)?>>ȯ��JOB<br></td>
					<td></td>
				</tr>
				<tr>
					<td><nobr><input type="radio" name="job_type" onclick="OnChange_job_type('parent_job1', 1)" value=1 <?=value_checked(1, $fetch->jb_job_type)?>>���󥱡��ȥ쥹�ݥ�JOB</nobr></td>
					<td><span ID="parent_job1">
						<nobr><font size=-1 class="note">����JOB���ꡧ</font><select name="parent_job_id1"><? select_parent_job('- ���򤷤Ʋ����� -',$fetch->jb_parent_job_id,$pjt_id)?></select></nobr>
						</span>
					</td>
				</tr>
				<tr>
					<td><nobr><input colspan=2 type="radio" name="job_type" onclick="OnChange_job_type('parent_job2', 2)" value=2 <?=value_checked(2, $fetch->jb_job_type)?>>����å��쥹�ݥ�JOB</nobr></td>
					<td><span ID="parent_job2">
						<nobr><font size=-1 class="note">����JOB���ꡧ</font><select name="parent_job_id2"><? select_parent_job('- ���򤷤Ʋ����� -',$fetch->jb_parent_job_id,$pjt_id)?></select></nobr>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">ȯ�������襢�ɥ쥹</td>
		<td class="n1">
<?
if ($job_id) {
	$sql = "SELECT sn_mail_adr FROM t_send_notice WHERE sn_job_id='$job_id'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$send_adr[$i] = $fetch->sn_mail_adr;
	}
}
?>
		<input class="kanji" type="text" name="sn_mail_adr[0]" size=50 maxlength=50 <?=value($send_adr[0])?>><br>
		<input class="kanji" type="text" name="sn_mail_adr[1]" size=50 maxlength=50 <?=value($send_adr[1])?>><br>
		<input class="kanji" type="text" name="sn_mail_adr[2]" size=50 maxlength=50 <?=value($send_adr[2])?>><br>
		<input class="kanji" type="text" name="sn_mail_adr[3]" size=50 maxlength=50 <?=value($send_adr[3])?>>
		<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span></td>
	</tr>
</table>
<br>
<table width=700>
	<div align="center">
	<input type="hidden" name="pj_no" <?=value($pj_no)?>>
	<input type="hidden" name="job_id" <?=value($job_id)?>>
	<input type="hidden" name="en_id" <?=value($en_id)?>>
	<input type="hidden" name="next_action">
	<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
	<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value="����롡" onclick='location.href="list.php"'>
	</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
