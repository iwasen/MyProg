<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ȿ�����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net ���ڥ졼�����ڡ���</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- ������ƥ�� -->
	<td valign="top">

		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td width="50%">���ȿ�����</td>
					<td width="50%" align="right"><input type="button" value="�����ȿ���Ͽ" class="form" onclick="location.href='regist.php'"></td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list" frame="box">
				<tr>
					<th width="5%">�ȿ�CD</th>
					<th width="25%">�ȿ�̾</th>
					<th width="5%">���ȿ�CD</th>
					<th width="25%">���ȿ�̾</th>
					<th width="10%">����</th>
					<th width="30%">��Ͽ��<br>����Ͽ�Խ�°�ȿ�CD��</th>
				</tr>
<?
// �ȿ�������ɽ��
$sql = "SELECT t1.or_org_id,t1.or_parent_org_id,t1.or_status,t1.or_name,t2.or_name AS parent_name,op_name1,op_operator_id,op_name2,op_org_id"
		. " FROM m_org t1"
		. " LEFT JOIN m_org t2 ON t2.or_org_id=t1.or_parent_org_id"
		. " JOIN t_operator ON op_operator_id=t1.or_operator_id";
if (!$_SESSION[SCD]['admin_auth'])
	$sql .= " WHERE op_org_id=(SELECT op_org_id FROM t_operator WHERE op_operator_id=$g_operator_id)";
$sql .= " ORDER BY t1.or_org_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr class="line<?=$i % 2?>">
					<td align="center"><?=$fetch->or_org_id?></td>
					<td>
<?
	// ICP�����Ԥޤ��Ͻ�ͭ���ڥ졼���Τ߹�����ǽ
	if ($_SESSION[SCD]['admin_auth'] || $fetch->op_operator_id == $g_operator_id) {
?>
						<a href="update.php?org_id=<?=$fetch->or_org_id?>" title="�����ȿ��򹹿����ޤ�"><?=htmlspecialchars($fetch->or_name)?></a>
<?
	} else {
?>
						<?=htmlspecialchars($fetch->or_name)?>
<?
	}
?>
					</td>
					<td align="center"><?=$fetch->or_parent_org_id ? $fetch->or_parent_org_id : '-'?></td>
					<td><?=$fetch->or_parent_org_id ? $fetch->parent_name : '-'?></td>
					<td align="center"><?=decode_org_status($fetch->or_status)?></td>
					<td><?=htmlspecialchars($fetch->op_name1)?><?=htmlspecialchars($fetch->op_name2)?>��<?=$fetch->op_org_id?>��</td>
				</tr>
<?
}
?>
				</table>

			</td>
		</tr>
		</table>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
