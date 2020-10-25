<?
/******************************************************
' System :ICPオペレータページ
' Content:オペレータ一覧
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

// 権限チェック
if (!$_SESSION[SCD]['admin_auth'])
	redirect('../top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレーターページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- 右コンテンツ -->
	<td valign="top">

		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td width="50%">■オペレーター一覧</td>
					<td width="50%" align="right"><input type="button" value="新規オペレーター登録" class="form" onclick="location.href='regist.php'"></td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list" frame="box">
				<tr>
					<th width="10%">オペレーターCD</th>
					<th width="20%">オペレーター名</th>
					<th width="10%">組織CD</th>
					<th width="20%">組織名</th>
					<th width="10%">ICP管理者権限</th>
					<th width="10%">状態</th>
					<th width="20%">登録者<br>（登録者所属組織CD）</th>
				</tr>
<?
$sql = "SELECT t1.op_operator_id,t1.op_name1||t1.op_name2 AS op_name,t1.op_org_id AS op_org_id1,t1.op_status,t1.op_admin_auth,t2.op_name1||t2.op_name2 AS regist_operator_name,t2.op_org_id AS op_org_id2,or_name"
		. " FROM t_operator t1"
		. " JOIN m_org ON or_org_id=t1.op_org_id"
		. " LEFT JOIN t_operator t2 ON t2.op_operator_id=t1.op_regist_operator_id"
		. " ORDER BY t1.op_operator_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr class="line<?=$i % 2?>">
					<td align="center"><?=$fetch->op_operator_id?></td>
					<td><a href="update.php?operator_id=<?=$fetch->op_operator_id?>"><?=htmlspecialchars($fetch->op_name)?></a></td>
					<td align="center"><?=$fetch->op_org_id1?></td>
					<td><?=htmlspecialchars($fetch->or_name)?></td>
					<td align="center"><?=$fetch->op_admin_auth ? '○' : '-'?></td>
					<td align="center"><?=decode_operator_status($fetch->op_status)?></td>
					<td><?=htmlspecialchars($fetch->regist_operator_name)?>（<?=$fetch->op_org_id2?>）</td>
				</tr>
<?
}
?>
				</table>

			</td>
		</tr>
		</table>

	</td>
	<!-- 右コンテンツ -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
