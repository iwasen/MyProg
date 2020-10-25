<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:���åץ���ô��������Ͽ����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// ô������Ź
function tantou_dealer() {
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
					echo "<input type='checkbox' name='dealer_cd[]' value='$fetch->dlr_dealer_cd'" . disabled($dealer_ary[$fetch->dlr_dealer_cd] != '') . ">$fetch->dlr_dealer_cd-$fetch->dlr_short_name";
					$i++;
				}
			}
			echo "</td>\n";
		}

		echo "</tr>\n";
	}
	echo "</table>\n";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
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
	return confirm("���åץ���ô������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.upload_cd.focus()">

<? center_header('�ޥ������ƥʥ󥹡å��åץ���ô���ޥ����ÿ�����Ͽ') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ͽ���륢�åץ���ô����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���åץ���ô��������<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="upload_cd" size=10 maxlength=10>
			<span class="note">��Ⱦ�ѡ����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ư����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="active" value='1' checked>��ư
			<input type="radio" name="active" value='0'>���ư
		</td>
	</tr>
	<tr>
		<td class="m1">�������ݡ��Ȳ����طʿ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="bgcolor" size=20 maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ô������Ź</td>
		<td class="n1"><? tantou_dealer() ?></td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
