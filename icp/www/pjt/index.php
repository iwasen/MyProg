<?
/******************************************************
' System :ICPプロジェクトページ
' Content:トップページ
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/status.php");
include("$inc/room.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="default.css">
	<script type="text/javascript">
<!--
function detail(job_id) {
	var f = document.detail;
	f.job_id.value = job_id;
	f.submit();
}
//-->
	</script>
</head>

<body>

<form method="post" name="detail" action="job/detail.php">
<input type="hidden" name="job_id">
</form>

<form>
<div align="center">

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="index.php" title="ホーム">ホーム</a></td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="<?=$top?>/img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right">
<?
if (check_staff_auth('ジョブ管理')) {
?>
		<input type="button" value="新規ジョブ登録" onclick="location.href='job/job_regist.php'">
<?
}
?>
	</td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="230" valign="top">
		<!-- 左メニュー -->
		<!-- プロジェクト情報 -->
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="230" class="title">
		<tr>
			<td><img src="<?=$top?>/img/midash_pjt.gif" alt="プロジェクト情報" width="230" height="26" border="0"></td>
		</tr>
		</table>
		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#FDE7A3" width="230" class="contents">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="100%" class="contents">
				<tr>
					<td>
<?
$sb1 = "SELECT COUNT(*) FROM t_pjt_member WHERE pm_pjt_id=pj_pjt_id AND pm_status=1";
$sql = "SELECT pj_pjt_cd,pj_name,pj_regist_date,pj_description,op_name1||op_name2 AS op_name,($sb1) AS member_num"
		. " FROM t_pjt"
		. " JOIN t_operator ON op_operator_id=pj_operator_id"
		. " WHERE pj_pjt_id={$_SESSION[SCD]['pjt_id']}";
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
?>
						<table border="0" cellspacing="1" cellpadding="0" bgcolor="#ffffff" width="100%" class="contents">
						<tr>
							<td width="40%" valign="top">プロジェクト名</td>
							<td width="60%" valign="top"><?=htmlspecialchars($fetch->pj_name)?></td>
						</tr>
						<tr>
							<td valign="top">プロジェクトCD</td>
							<td valign="top"><?=htmlspecialchars($fetch->pj_pjt_cd)?></td>
						</tr>
						<tr>
							<td valign="top">プロジェクト説明</td>
							<td valign="top"><?=nl2br(htmlspecialchars($fetch->pj_description))?></td>
						</tr>
						<tr>
							<td valign="top">登録オペレータ</td>
							<td valign="top"><?=htmlspecialchars($fetch->op_name)?></td>
						</tr>
						<tr>
							<td valign="top">登録日</td>
							<td valign="top"><?=format_date($fetch->pj_regist_date)?></td>
						</tr>
						<tr>
							<td valign="top">登録メンバー数</td>
							<td valign="top"><a href="pjt/list.php" title="プロジェクト参加者リスト一覧"><?=number_format($fetch->member_num)?></a></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="100%" class="contents">
						<tr>
							<td>
<?
if ($_SESSION[SCD]['operator_flag']) {
?>
								<a href="pjt/update.php" title="プロジェクト情報更新">更新</a>
<?
}
?>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<!-- プロジェクト情報 --><br>
		<!-- バックルーム -->
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="230" class="title">
		<tr>
			<td><img src="<?=$top?>/img/midash_sr.gif" alt="バックルーム" width="230" height="26" border="0"></td>
		</tr>
		</table>
		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#CECECF" width="230" class="contents">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="100%">
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="100%" class="contents">
<?
$sql = "SELECT jb_job_id,jb_name,jb_job_cd,jb_status"
		. " FROM t_job"
		. " WHERE jb_pjt_id={$_SESSION[SCD]['pjt_id']} AND jb_room_type=3"
		. " ORDER BY jb_job_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($i != 0) {
?>
						<tr>
							<td align="center"><img src="<?=$top?>/img/line_200.gif" width="200" height="3" border="0"></td>
						</tr>
<?
	}
?>
						<tr>
							<td>
<?
	if (check_staff_auth('ジョブ管理')) {
?>
								<a href="javascript:detail(<?=$fetch->jb_job_id?>)" title="このジョブの詳細を表示します"><?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）</a>
<?
	} else {
?>
								<?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）
<?
	}
?>
							</td>
						</tr>
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="100%" class="contents">
								<tr>
									<td width="50%"><?=decode_job_status($fetch->jb_status)?></td>
									<td width="25%" align="center"><a href="mailto:<?=get_room_mail_addr($_SESSION[SCD]['pjt_cd'], $fetch->jb_job_cd)?>"><img src="img/mail.gif" alt="メーラーが起動し、このルームにメールを送信できます" width="50" height="20" border="0"></a></td>
									<td width="25%" align="center">
<?
	if ($fetch->jb_status >= $g_job_status['実施中']) {
?>
										<a href="<?=$top?>/login/login_room.php?job_id=<?=$fetch->jb_job_id?>" target="room"><img src="img/ir.gif" alt="会議室を新しいウィンドウで表示します" width="50" height="20" border="0" align="left"></a>
<?
	}
?>
									</td>
								</tr>
								</table>
							</td>
						</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				</table>

			</td>
		</tr>
		</table>
		<!-- バックルーム -->
		<!-- 左メニュー -->
	</td>
	<td width="10"><img src="<?=$top?>/img/spacer.gif" width="10" height="1" border="0"></td>
	<td width="520" valign="top">
		<!-- 右コンテンツ -->
		<!-- ネットグルイン -->
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="520" class="title">
		<tr>
			<td><img src="<?=$top?>/img/midash_gi.gif" alt="ネットグルイン" width="230" height="26" border="0"></td>
		</tr>
		</table>
		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#AC9DCB" width="520" class="contents">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="100%" class="contents">
<?
$sql = "SELECT jb_job_id,jb_name,jb_job_cd,jb_start_date,jb_end_date,jb_status,jb_member_num"
		. " FROM t_job"
		. " WHERE jb_pjt_id={$_SESSION[SCD]['pjt_id']} AND jb_room_type=1"
		. " ORDER BY jb_job_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($i != 0) {
?>
				<tr>
					<td colspan="5" align="center"><img src="<?=$top?>/img/line_500.gif" width="500" height="3" border="0"></td>
				</tr>
<?
	}
?>
				<tr>
					<td>
<?
	if (check_staff_auth('ジョブ管理')) {
?>
						<a href="javascript:detail(<?=$fetch->jb_job_id?>)" title="このジョブの詳細を表示します"><?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）</a>
<?
	} else {
?>
						<?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）
<?
	}
?>
					</td>
					<td><?=format_date($fetch->jb_start_date)?>〜<?=format_date($fetch->jb_end_date)?></td>
					<td><?=decode_job_status($fetch->jb_status)?></td>
					<td align="center">
<?
	if ($fetch->jb_status >= $g_job_status['実施中']) {
?>
						<a href="<?=$top?>/login/login_room.php?job_id=<?=$fetch->jb_job_id?>" target="room"><img src="img/ir.gif" alt="会議室を新しいウィンドウで表示します" width="50" height="20" border="0" align="left"></a>
<?
	}
?>
					</td>
					<td><nobr><?=number_format($fetch->jb_member_num)?>人</nobr></td>
				</tr>
<?
}
?>
				</table>
			</td>
		</tr>
		</table>
		<!-- ネットグルイン --><br>
		<!-- 定性メールアンケート -->
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="520" class="title">
		<tr>
			<td><img src="<?=$top?>/img/midash_pi.gif" alt="定性メールアンケート" width="230" height="26" border="0"></td>
		</tr>
		</table>
		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#FFDADA" width="520" class="contents">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="100%" class="contents">
<?
$sql = "SELECT jb_job_id,jb_name,jb_job_cd,jb_start_date,jb_end_date,jb_status,jb_member_num"
		. " FROM t_job"
		. " WHERE jb_pjt_id={$_SESSION[SCD]['pjt_id']} AND jb_room_type=2"
		. " ORDER BY jb_job_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($i != 0) {
?>
				<tr>
					<td colspan="5" align="center"><img src="<?=$top?>/img/line_500.gif" width="500" height="3" border="0"></td>
				</tr>
<?
	}
?>
				<tr>
					<td>
<?
	if (check_staff_auth('ジョブ管理')) {
?>
						<a href="javascript:detail(<?=$fetch->jb_job_id?>)" title="このジョブの詳細を表示します"><?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）</a>
<?
	} else {
?>
						<?=htmlspecialchars($fetch->jb_name)?>（<?=htmlspecialchars($fetch->jb_job_cd)?>）
<?
	}
?>
					</td>
					<td><?=format_date($fetch->jb_start_date)?>〜<?=format_date($fetch->jb_end_date)?></td>
					<td><?=decode_job_status($fetch->jb_status)?></td>
					<td align="center">
<?
	if ($fetch->jb_status >= $g_job_status['実施中']) {
?>
						<a href="<?=$top?>/login/login_room.php?job_id=<?=$fetch->jb_job_id?>" target="room"><img src="img/ir.gif" alt="会議室を新しいウィンドウで表示します" width="50" height="20" border="0" align="left"></a>
<?
	}
?>
					</td>
					<td><nobr><?=number_format($fetch->jb_member_num)?>人</nobr></td>
				</tr>
<?
}
?>
				</table>
			</td>
		</tr>
		</table>
		<!-- 定性メールアンケート -->
		<!-- 右コンテンツ -->
</td>
<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<? pjt_footer() ?>

</div>
</form>
</body>

</html>
