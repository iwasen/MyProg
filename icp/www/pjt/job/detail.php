<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブ基本設定表示ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

// ジョブ情報取得
$sql = "SELECT * FROM t_job WHERE jb_job_id=" . sql_number($job_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	$status = $fetch->jb_status;
} else
	program_err(__FILE__, __LINE__);
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
function goto(page) {
	var f = document.goto1;
	f.action = page;
	f.submit();
}
function order(status) {
	var f = document.goto2;
	f.status.value = status;
	f.submit();
}
function set_mail_status(kind, status) {
	var kind_s, st;
	switch (kind) {
	case "guide":
		kind_s = "案内";
		break;
	case "start":
		kind_s = "開始";
		break;
	case "end":
		kind_s = "終了";
		break;
	}
	switch (status) {
	case 1:
		st = confirm(kind_s + "メールを作成中状態に戻しますか？");
		break;
	case 2:
		st = confirm(kind_s + "メールを作成完了に設定しますか？");
		break;
	}
	if (st)
		location.href = "mail_status.php?job_id=<?=$job_id?>&kind=" + kind + "&status=" + status;
}
//-->
	</script>
</head>

<body>
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<form method="post" name="goto2" action="order.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="status">
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		<?=htmlspecialchars($job_name_cd)?>情報
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right">
<?
if ($fetch->jb_reorder_flag == DBFALSE) {
	if ($status == $g_job_status['申請前']) {
?>
		<input type="button" value="ジョブ実施申請" onclick="order(<?=$g_job_status['申請中']?>)">
<?
	} elseif ($status == $g_job_status['申請中']) {
?>
		<input type="button" value="ジョブ申請取消" onclick="order(<?=$g_job_status['申請前']?>)">
<?
	} elseif ($status == $g_job_status['承認済']) {
?>
		<input type="button" value="設定完了" onclick="order(<?=$g_job_status['待機中']?>)">
<?
	} elseif ($status == $g_job_status['待機中']) {
?>
		<input type="button" value="設定完了解除" onclick="order(<?=$g_job_status['承認済']?>)">
<?
	} elseif ($status == $g_job_status['実施中']) {
?>
		<input type="button" value="会議室休止" onclick="order(<?=$g_job_status['休止中']?>)">
<?
	} elseif ($status == $g_job_status['休止中']) {
?>
		<input type="button" value="会議室再開" onclick="order(<?=$g_job_status['実施中']?>)">
<?
	}
	if ($status == $g_job_status['承認済']
			|| $status == $g_job_status['待機中']
			|| $status == $g_job_status['実施中']
			|| $status == $g_job_status['休止中']
			|| $status == $g_job_status['猶予中']) {
?>
	<input type="button" name="" value="ジョブ再申請" onclick="goto('reapply_update.php')"></td>
<?
	}
} else {
?>
	<input type="button" name="" value="ジョブ再申請取消" onclick="goto('reorder_cancel.php')"></td>
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
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">■ジョブ基本設定</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['終了']) {
?>
				<a href="javascript:goto('basic_update.php')" title="ジョブの基本設定を更新します">更新</a>
<?
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">ジョブ名（ジョブID）</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_name)?>（<?=$fetch->jb_job_id?>）</td>
		</tr>
		<tr>
			<th>ジョブ詳細</th>
			<td><?=nl2br(htmlspecialchars($fetch->jb_description))?></td>
		</tr>
		<tr>
			<th>会議室タイプ</th>
			<td><?=decode_room_type($fetch->jb_room_type)?></td>
		</tr>
		<tr>
			<th>会議室名</th>
			<td><?=htmlspecialchars($fetch->jb_room_name)?></td>
		</tr>
		<tr>
			<th>実施期間</th>
			<td><?=format_date_j($fetch->jb_start_date)?> 〜 <?=format_date_j($fetch->jb_end_date)?></td>
		</tr>
		<tr>
			<th>最大使用人数</th>
			<td><?=$fetch->jb_member_max ? number_format($fetch->jb_member_max)  . '人' : '制限なし'?></td>
		</tr>
		<tr>
			<th>ジョブコード</th>
			<td><?=htmlspecialchars($fetch->jb_job_cd)?></td>
		</tr>
		<tr>
			<th>メールアドレス開示</th>
			<td><?=$fetch->jb_open_mail_addr == DBFALSE ? 'メールアドレスを開示しない（置換する）' : 'メールアドレスを開示する（置換しない）'?></td>
		</tr>
		<tr>
			<th>添付ファイル</th>
			<td><?=$fetch->jb_attachment_file == DBFALSE ? '使用しない' : '使用する'?></td>
		</tr>
		<tr>
			<th>インポート済み人数</th>
				<td>
				<table border="0" cellspacing="0" cellpadding="0" width="100%" class="card">
				<tr>
					<td width="50%"><a href="javascript:goto('list.php')" title="このジョブに関連づいているメンバーのリストを表示します"><?=number_format($fetch->jb_member_num)?>人</a></td>
					<td align="right" width="50%">
<?
if (check_staff_auth('参加者メンバエクスポート')) {
?>
						<a href="javascript:goto('member_export.php')" title="参加者リストダウンロード">参加者リストダウンロード</a>
<?
}
?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>状態</th>
			<td><?=decode_job_status($status)?><?=$fetch->jb_reorder_flag == DBTRUE ? ' （再申請中）' : ''?></td>
		</tr>
			</table>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">■案内メール設定</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['終了']) {
	if ($fetch->jb_guide_status <= 1) {
?>
				<a href="javascript:goto('guidemail_update.php')" title="ジョブの案内メールの設定を更新します">更新</a>
<?
	}
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">サブジェクト</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_guide_subject)?></td>
		</tr>
		<tr>
			<th>差出人名称</th>
			<td><?=htmlspecialchars($fetch->jb_guide_from_name)?></td>
		</tr>
		<tr>
			<th>本文</th>
			<td><pre><?=htmlspecialchars($fetch->jb_guide_content)?></pre></td>
		</tr>
		<tr>
			<th>送信日</th>
			<td><?=format_datetime_j($fetch->jb_guide_send_date)?></td>
		</tr>
		<tr>
			<th>同報先</th>
			<td><?=str_replace(' ', '<br>', htmlspecialchars($fetch->jb_guide_add_mail))?></td>
		</tr>
		<tr>
			<th>ステータス</th>
			<td><?=decode_mail_status($fetch->jb_guide_status)?>
<?
if ($status != $g_job_status['終了']) {
	if ($fetch->jb_guide_status == 1) {
?>
				&nbsp;&nbsp;<input type="button" value="作成完了" onclick="set_mail_status('guide', 2)">
<?
	} elseif ($fetch->jb_guide_status != 0) {
?>
				&nbsp;&nbsp;<input type="button" value="作成中に戻す" onclick="set_mail_status('guide', 1)">
<?
	}
}
?>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">■開始メール設定</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['終了']) {
	if ($fetch->jb_start_status <= 1) {
?>
				<a href="javascript:goto('startmail_update.php')" title="ジョブの開始メールの設定を更新します">更新</a>
<?
	}
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">サブジェクト</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_start_subject)?></td>
		</tr>
		<tr>
			<th>差出人名称</th>
			<td><?=htmlspecialchars($fetch->jb_start_from_name)?></td>
		</tr>
		<tr>
			<th>本文</th>
			<td><pre><?=htmlspecialchars($fetch->jb_start_content)?></pre></td>
		</tr>
		<tr>
			<th>送信日</th>
			<td><?=format_datetime_j($fetch->jb_start_send_date)?></td>
		</tr>
		<tr>
			<th>同報先</th>
			<td><?=str_replace(' ', '<br>', htmlspecialchars($fetch->jb_start_add_mail))?></td>
		</tr>
		<tr>
			<th>ステータス</th>
			<td><?=decode_mail_status($fetch->jb_start_status)?>
<?
if ($status != $g_job_status['終了']) {
	if ($fetch->jb_start_status == 1) {
?>
				&nbsp;&nbsp;<input type="button" value="作成完了" onclick="set_mail_status('start', 2)">
<?
	} elseif ($fetch->jb_start_status != 0) {
?>
				&nbsp;&nbsp;<input type="button" value="作成中に戻す" onclick="set_mail_status('start', 1)">
<?
	}
}
?>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">■終了メール設定</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['終了']) {
	if ($fetch->jb_end_status <= 1) {
?>
				<a href="javascript:goto('endmail_update.php')" title="ジョブの終了メールの設定を更新します">更新</a>
<?
	}
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">サブジェクト</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_end_subject)?></td>
		</tr>
		<tr>
			<th>差出人名称</th>
			<td><?=htmlspecialchars($fetch->jb_end_from_name)?></td>
		</tr>
		<tr>
			<th>本文</th>
			<td><pre><?=htmlspecialchars($fetch->jb_end_content)?></pre></td>
		</tr>
		<tr>
			<th>送信日</th>
			<td><?=format_datetime_j($fetch->jb_end_send_date)?></td>
		</tr>
		<tr>
			<th>同報先</th>
			<td><?=str_replace(' ', '<br>', htmlspecialchars($fetch->jb_end_add_mail))?></td>
		</tr>
		<tr>
			<th>ステータス</th>
			<td><?=decode_mail_status($fetch->jb_end_status)?>
<?
if ($status != $g_job_status['終了']) {
	if ($fetch->jb_end_status == 1) {
?>
				&nbsp;&nbsp;<input type="button" value="作成完了" onclick="set_mail_status('end', 2)">
<?
	} elseif ($fetch->jb_end_status != 0) {
?>
				&nbsp;&nbsp;<input type="button" value="作成中に戻す" onclick="set_mail_status('end', 1)">
<?
	}
}
?>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">■署名設定</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['終了']) {
?>
				<a href="javascript:goto('sign_update.php')" title="メールに付加される署名の設定を更新します">更新</a>
<?
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">ヘッダ</th>
			<td width="560"><?=nl2br(format_text($fetch->jb_room_header, '付けない'))?></td>
		</tr>
		<tr>
			<th>フッタ</th>
			<td><?=nl2br(format_text($fetch->jb_room_footer, '付けない'))?></td>
		</tr>
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
