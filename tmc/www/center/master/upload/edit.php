<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:���åץ���ô���ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// ô������Ź
function tantou_dealer($upload_cd) {
	// ��Ͽ�Ѥߤ�ô������Ź����
	$sql = "SELECT upd_dealer_cd,upd_upload_cd FROM t_upload_dealer";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	$dealer_ary = array();
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$dealer_ary[$fetch->upd_dealer_cd] = $fetch->upd_upload_cd;
	}

	$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name,dlr_dealer_cd,dlr_short_name"
			. " FROM t_dealer"
			. " JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
			. " ORDER BY sch_sales_channel_cd,dlr_dealer_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);

	echo "<table border=0 cellspacing=0 cellpadding=1 width='100%' class='small'>\n";

	for ($i = 0; $i < $nrow; ) {
		$fetch = pg_fetch_object($result, $i);

		if ($sales_channel_cd != $fetch->sch_sales_channel_cd) {
			echo "<tr class='subhead'>\n";
			echo "<td colspan=5>$fetch->sch_sales_channel_name</td>\n";
			echo "</tr>\n";
		}

		$sales_channel_cd = $fetch->sch_sales_channel_cd;

		echo "<tr>\n";

		for ($j = 0; $j < 5; $j++) {
			echo "<td width='20%'>";
			if ($i < $nrow) {
				$fetch = pg_fetch_object($result, $i);
				if ($fetch->sch_sales_channel_cd == $sales_channel_cd) {
					echo "<input type='checkbox' name='dealer_cd[]' value='$fetch->dlr_dealer_cd'" . checked($dealer_ary[$fetch->dlr_dealer_cd] == $upload_cd) . disabled($dealer_ary[$fetch->dlr_dealer_cd] != '' && $dealer_ary[$fetch->dlr_dealer_cd] != $upload_cd) . ">$fetch->dlr_dealer_cd-$fetch->dlr_short_name";
					$i++;
				}
			}
			echo "</td>\n";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}

// ���ϥѥ�᡼��
$upload_cd = $_GET['upload_cd'];

// ��Ͽ�Ѥߥ��åץ���ô���������
$sql = "SELECT * FROM t_upload_tantou WHERE upt_upload_cd=" . sql_char($upload_cd);
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
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
	if (f.upload_cd.value == "") {
		alert("���åץ���ô�������ɤ����Ϥ��Ƥ���������");
		f.upload_cd.focus();
		return false;
	}
	if (f.name.value == "") {
		alert("����̾�Τ����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	return confirm("���åץ���ô���򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("���åץ���ô���������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡å��åץ���ô���ޥ�������Ͽ�ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�����åץ���ô����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���åץ���ô��������</td>
		<td class="n1">
			<?=htmlspecialchars($fetch->upt_upload_cd)?>
			<input type="hidden" name="upload_cd" <?=value($upload_cd)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=50 maxlength=50 <?=value($fetch->upt_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ư����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="active" value='1' <?=checked($fetch->upt_active == DBTRUE)?>>��ư
			<input type="radio" name="active" value='0' <?=checked($fetch->upt_active == DBFALSE)?>>���ư
		</td>
	</tr>
	<tr>
		<td class="m1">�������ݡ��Ȳ����طʿ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="bgcolor" size=20 maxlength=20 <?=value($fetch->upt_bgcolor)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ô������Ź</td>
		<td class="n1"><? tantou_dealer($upload_cd) ?></td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
</form>
</div>

<? center_footer() ?>

</body>
</html>
