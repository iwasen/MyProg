<?
/******************************************************
' System :ICPプロジェクトページ
' Content:プロジェクトメンバ一覧ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('プロジェクトメンバ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$pjt_id = $_SESSION[SCD]['pjt_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function member(member_id) {
	var f = document.goto2;
	f.member_id.value = member_id;
	f.submit();
}
//-->
	</script>
</head>

<body>
<div align="center">

<form method="post" name="goto2" action="member.php">
<input type="hidden" name="member_id">
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		プロジェクト情報
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		メンバーリスト
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right">
		<input type="button" value="メンバーエクスポート" onclick="location.href='export.php'">
		<input type="button" value="メンバーインポート" onclick="window.open('import.php')">
	</td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="1" cellspacing="0" cellpadding="2" width="760" class="list" frame="box">
		<tr>
			<th width="20" rowspan="2">ID</th>
			<th width="440" rowspan="2">ニックネーム</th>
			<th width="300" colspan="8">権限</th>
		</tr>
		<tr style="font-size:10px;">
			<th width="50">pjt管理</th>
			<th width="50">job管理</th>
			<th width="50">log DL</th>
			<th width="50">発言録DL</th>
			<th width="50">メンバーDL</th>
			<th width="50">発言集計DL</th>
		</tr>
<?
$sql = "SELECT pm_member_id,pm_org_id,pm_org_member_id,pm_name1||pm_name2 AS pm_name,pm_nickname,pm_mail_addr,pm_staff_auth"
		. " FROM t_pjt_member"
		. " WHERE pm_pjt_id=$pjt_id AND pm_status=1"
		. " ORDER BY pm_member_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$auth = $fetch->pm_staff_auth;
?>
		<tr class="line<?=$i % 2?>">
			<td align="center"><?=$fetch->pm_member_id?></td>
			<td><a href="javascript:member(<?=$fetch->pm_member_id?>)" title="このメンバーの情報を表示します"><?=htmlspecialchars($fetch->pm_nickname)?></a></td>
			<td align="center"><?=check_staff_auth('プロジェクトメンバ管理', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('ジョブ管理', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('発言ログエクスポート', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('発言録エクスポート', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('参加者メンバエクスポート', $auth) ? '○' : '-'?></td>
			<td align="center"><?=check_staff_auth('発言集計エクスポート', $auth) ? '○' : '-'?></td>

		</tr>
<?
}
?>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><a href="../index.php" title="ホームに戻る">ホームに戻る</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
