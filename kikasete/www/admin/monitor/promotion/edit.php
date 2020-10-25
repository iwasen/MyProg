<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ץ�⡼��������
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
include("$inc/encrypt.php");

//=== ��������� ===
function select_media($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT md_media_id,md_name FROM m_media WHERE md_media_id<>'03' ORDER BY md_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->md_media_id, $selected), '>', htmlspecialchars($fetch->md_name), '</option>', "\n";
	}
}

//=== ���𥿥�������� ===
function select_adv_type($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT at_adv_id,at_name FROM m_adv_type ORDER BY at_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->at_adv_id, $selected), '>', htmlspecialchars($fetch->at_name), '</option>', "\n";
	}
}

//=== ���ǡ�����ID���� ===
function get_prev_pr_id() {
	$sql = "SELECT MAX(pr_pr_id) FROM t_promotion";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		return $fetch[0];
	}
}

//=== ����PRID���� ===
function get_next_pr_id() {
	$sql = "SELECT MAX(pr_pr_id) FROM t_promotion";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		return $fetch[0] + 1;
	}
}

//=== �����å��������� ===
function CreateCheckSum($id) {
	$sum = 0;
	for ($i = 0; $i < strlen($id); $i++)
		$sum = ($sum + substr($id, i, 1)) % 10;

	return chr((10 - $sum) % 10 + ord('0'));
}

//=== �ģ¤��ǡ����ɤ߹��� ===
function read_data($id) {
	global $media_id, $pr_name, $adv_id, $contents_type, $contents_text, $url, $haishin_suu, $click_suu, $haishin_start_y;
	global $haishin_start_m, $haishin_start_d, $haishin_end_y, $haishin_end_m, $haishin_end_d, $cost, $affiliate, $bikou;
	global $contents_pict, $jump_url;

	if ($id) {
		$sql = "SELECT * FROM t_promotion WHERE pr_pr_id=$id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$media_id = $fetch->pr_media_id;
			$pr_name = $fetch->pr_pr_name;
			$adv_id = $fetch->pr_adv_id;
			$contents_type = $fetch->pr_contents_type;
			$contents_text = $fetch->pr_contents_text;
			$url = $fetch->pr_url;
			$haishin_suu = $fetch->pr_haishin_suu;
			$click_suu = $fetch->pr_click_suu;
			$haishin_start_y = get_datepart('Y', $fetch->pr_haishin_start);
			$haishin_start_m = get_datepart('M', $fetch->pr_haishin_start);
			$haishin_start_d = get_datepart('D', $fetch->pr_haishin_start);
			$haishin_end_y = get_datepart('Y', $fetch->pr_haishin_end);
			$haishin_end_m = get_datepart('M', $fetch->pr_haishin_end);
			$haishin_end_d = get_datepart('D', $fetch->pr_haishin_end);
			$cost = $fetch->pr_cost;
			$affiliate = $fetch->pr_affiliate;
			$bikou = $fetch->pr_bikou;
			$contents_pict = $fetch->pr_contents_pict;
			$jump_url = $fetch->pr_jump_url;
		}
	}
}

// �ᥤ�����
set_global('monitor', '��˥�������', '�ץ�⡼��������', BACK_TOP);

if (!$pr_id) {
	$pr_id = get_next_pr_id();
	If ($cp == '') {
		$haishin_start_m = 1;
		$haishin_start_d = 1;
		$haishin_end_m = 1;
		$haishin_end_d = 1;
	} else
		read_data(get_prev_pr_id());

	$contents_text = "";
	$fetch = get_system_info('sy_url_top');
	$url = "{$fetch->sy_url_top}pr.php?id=" . encrypt_number($pr_id, 8);
	$new_pr = true;
} else
	read_data($pr_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function InputCheck() {
	with (document.form1) {
		if (media_id.value == "") {
			alert("����̾�����򤷤Ƥ���������");
			media_id.focus();
			return false;
		}
		if (pr_name.value == "") {
			alert("�ץ�⡼�����̾�����Ϥ��Ƥ���������");
			pr_name.focus();
			return false;
		}
		if (adv_id.value == "") {
			alert("���𥿥��פ����򤷤Ƥ���������");
			adv_id.focus();
			return false;
		}
	}
	return true;
}
function onClick_regist() {
	if (InputCheck()) {
		if (confirm("�ץ�⡼��������Ͽ���ޤ���������Ǥ�����")) {
			document.form1.next_action.value = "new";
			document.form1.submit();
		}
	}
}
function onClick_update() {
	if (InputCheck()) {
		if (confirm("�ץ�⡼�����򹹿����ޤ���������Ǥ�����")) {
			document.form1.next_action.value = "update";
			document.form1.submit();
		}
	}
}
function onClick_delete() {
	if (InputCheck()) {
		if (confirm("�ץ�⡼�����������ޤ���������Ǥ�����")) {
			document.form1.next_action.value = "delete";
			document.form1.submit();
		}
	}
}
function onClick_showpict() {
	if (document.form1.contents_pict.value != "") {
		document.all.pict.style.width = "";
		document.all.pict.style.height = "";
		document.all.pict.src = document.form1.contents_pict.value;
	}
}
function onClick_copy() {
	location.href = "edit.php?cp=1";
}
function disp_jump_url() {
	var f = document.form1;
	f.jump_url.style.display = f.jump_url_flag[1].checked ? '' : 'none';
}
//-->
</script>
</head>

<body onload="disp_jump_url()">
<? page_header() ?>

<center>
<form method="post" action="update.php" name="form1" ENCtype="multipart/form-data">
	<table border=0 cellspacing=0 cellpadding=0 width='75%'>
		<tr>
			<td class="m0">���ץ�⡼��������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m1" width="22%">PRID</td>
						<td class="n1"><?=$pr_id?><input type="hidden" name="pr_id" <?=value($pr_id)?>></td>
					</tr>
					<tr>
						<td class="m1">����̾</td>
						<td class="n1"><select name="media_id"><? select_media('- ���򤷤Ƥ������� -', $media_id) ?></select></td>
					</tr>
					<tr>
						<td class="m1">�ץ�⡼�����̾</td>
						<td class="n1">
							<input class="kanji" type="text" name="pr_name" size=50 maxlength=25 <?=value($pr_name)?>>
							<font class="note">�����ѣ���ʸ���ޤǡ�</font>
						</td>
					</tr>
					<tr>
						<td class="m1">���𥿥���</td>
						<td class="n1"><select name="adv_id"><? select_adv_type('- ���򤷤Ƥ������� -', $adv_id) ?></select></td>
					</tr>
					<tr>
						<td class="m1">����ƥ�ġʥƥ����ȡ�</td>
						<td class="n1"><textarea name="contents_text" cols=78 rows=5 class="np"><?=$contents_text?></textarea></td>
					</tr>
					<tr>
						<td class="m1">����ƥ�ġʲ�����</td>
						<td class="n1"><img src="pict.php?id=<?=$contents_pict?>" id="pict" alt="����"<? if (!$contents_pict) echo ' style="width:0;height:0"' ?>><br>
							<input type="file" name="contents_pict" size=50>
							<input type="button" value="ɽ��" onclick="onClick_showpict()"></td>
					</tr>
					<tr>
						<td class="m1">����å�������URL</td>
						<td class="n1">
							<?=$url?>
							<input class="alpha" type="hidden" name="url" <?=value($url)?>>
							<input type="button" value="���؎��̎ߎΎގ��Ďޤ؎��ˎߎ�" onclick="clipboardData.setData('Text','<?=$url?>')">
						</td>
					</tr>
					<tr>
						<td class="m1">������URL</td>
						<td class="n1">
							<input type="radio" name="jump_url_flag" <?=value_checked('1', $jump_url == '' ? '1' : '2')?> onclick="disp_jump_url()">��˥����ȥåץڡ���
							<input type="radio" name="jump_url_flag" <?=value_checked('2', $jump_url == '' ? '1' : '2')?> onclick="disp_jump_url()">����¾�Υڡ���
							<input class="alpha" type="text" name="jump_url" size=50 <?=value($jump_url)?>>
						</td>
					</tr>
					<tr>
						<td class="m1">�ۿ���</td>
						<td class="n1">
							<input class="number" type="text" name="haishin_suu" maxlength=8 <?=value($haishin_suu)?>>
							<font class="note">�ʿ�������ޤǡ�</font>
							</td>
					</tr>
					<tr>
						<td class="m1">�ݾڥ���å���</td>
						<td class="n1">
							<input class="number" type="text" name="click_suu" maxlength=8 <?=value($click_suu)?>>
							<font class="note">�ʿ�������ޤǡ�</font>
						</td>
					</tr>
					<tr>
						<td class="m1">�ۿ�������</td>
						<td class="n1">
							<input class="number" type="text" name="haishin_start_y" size=4 maxlength=4 <?=value($haishin_start_y)?>>ǯ&nbsp;
							<select name="haishin_start_m"><? select_month('', $haishin_start_m) ?></select>��&nbsp;
							<select name="haishin_start_d"><? select_day('', $haishin_start_d) ?></select>��
						</td>
					</tr>
					<tr>
						<td class="m1">�ۿ���λ��</td>
						<td class="n1">
							<input class="number" type="text" name="haishin_end_y" size=4 maxlength=4 <?=value($haishin_end_y)?>>ǯ&nbsp;
							<select name="haishin_end_m"><? select_month('', $haishin_end_m) ?></select>��&nbsp;
							<select name="haishin_end_d"><? select_day('', $haishin_end_d) ?></select>��
						</td>
					</tr>
					<tr>
						<td class="m1">������</td>
						<td class="n1">
							<input class="number" type="text" name="cost" maxlength=8 <?=value($cost)?>>
							<font class="note">�ʿ�������ޤǡ�</font>
						</td>
					</tr>
					<tr>
						<td class="m1">���ե��ꥨ����ñ��</td>
						<td class="n1">
							<input class="number" type="text" name="affiliate" maxlength=8 <?=value($affiliate)?>>
							<font class="note">�ʿ�������ޤǡ�</font>
						</td>
					</tr>
					<tr>
						<td class="m1">����</td>
						<td class="n1">
							<input class="kanji" type="text" name="bikou" size=80 <?=value($bikou)?>>
							<font class="note">�����ѣ�����ʸ���ޤǡ�</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
<?
if ($new_pr) {
?>
	<input type="button" name="copy" value="���Îގ������ˎߎ�" Onclick="onClick_copy()">
	<input type="button" name="regist" value=" ��Ͽ " Onclick="onClick_regist()">
<?
} else {
?>
	<input type="button" name="update" value=" ���� " Onclick="onClick_update()">
	<input type="button" name="delete" value=" ��� " Onclick="onClick_delete()">
<?
}
?>
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value="����롡" onclick="history.back()">
	<input type="hidden" name="next_action">

</form>
</center>

<? page_footer() ?>
</body>
</html>