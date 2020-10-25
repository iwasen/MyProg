<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���С��ꥹ���ɲó�ǧ
'******************************************************/

$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ����å��С��ꥹ��', '���С��ɲó�ǧ', BACK_TOP);

$sql = "SELECT mg_group_name FROM t_member_grp WHERE mg_group_id=$group_id";
$group_name = db_fetch1($sql);

$n = 0;
$ok = 0;
$data_ary = array();
$monitor_id_ary = array();

if ($monitor_list != '') {
	$ary = explode("\n", str_replace(',', "\n", $monitor_list));
	foreach ($ary as $addr) {
		$addr = trim($addr);

		if ($addr == '')
			continue;

		$data_ary[$n]['input'] = $addr;
		$data_ary[$n]['monitor'] = '-';

		$sql = '';
		if (strpos($addr, '@')) {
			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2"
					. " FROM t_monitor WHERE mn_mail_addr_low=" . sql_char(strtolower($addr)) . " AND mn_status=0";
		} elseif (is_numeric($addr)) {
			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2"
					. " FROM t_monitor WHERE mn_monitor_id=$addr AND mn_status=0";
		} else
			$data_ary[$n]['msg'] = '<font color="red">�����ʥǡ����Ǥ�</font>';

		if ($sql != '') {
			$result = db_exec($sql);
			if (pg_numrows($result) == 0)
				$data_ary[$n]['msg'] = '<font color="red">���������˥�����¸�ߤ��ޤ���</font>';
			else {
				$fetch = pg_fetch_object($result, 0);
				$monitor_id = $fetch->mn_monitor_id;

				$data_ary[$n]['monitor'] = "$fetch->mn_name1 $fetch->mn_name2 <$fetch->mn_mail_addr>";

				$sql = "SELECT COUNT(*) FROM t_member_list WHERE ml_group_id=$group_id AND ml_monitor_id=$monitor_id";
				if (db_fetch1($sql) != 0)
					$data_ary[$n]['msg'] = '<font color="red">���ˤ��Υ��С��ꥹ�Ȥ���Ͽ����Ƥ��ޤ�</font>';
				else {
					$data_ary[$n]['msg'] = '<font color="blue">��Ͽ�ϣ�</font>';
					$ok++;
					$monitor_id_ary[] = $monitor_id;
				}
			}
		}

		$n++;
	}
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function delete_member() {
	if (confirm("���򤷤����С��򤳤Υꥹ�Ȥ��������ޤ���������Ǥ�����"))
		document.form2.submit();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="add.php">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">��<?=htmlspecialchars($group_name)?></td>
		<td class="lb">
			�����ϡ�<?=number_format($n)?>���ϿOK��<?=number_format($ok)?>���ϿNG��<?=number_format($n - $ok)?>���
<?
if ($ok) {
?>
			<input type="submit" value="����Ͽ��">
<?
}
?>
			<input type="button" value="����롡" onclick="history.back()">
		</td>
	</tr>
</table>
<input type="hidden" name="group_id" <?=value($group_id)?>>
<input type="hidden" name="monitor_id_ary" <?=value(join(',', $monitor_id_ary))?>>
</form>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
<?
		sort_header(0, '���ϥǡ���');
		sort_header(0, '������˥���');
		sort_header(0, '��Ͽ����');
?>
	</tr>
<?
foreach ($data_ary as $data) {
?>
	<tr class="tc<?=$i++ % 2?>">
		<td align="center"><?=htmlspecialchars($data['input'])?></td>
		<td><?=htmlspecialchars($data['monitor'])?></td>
		<td align="center"><?=$data['msg']?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
