<?
/******************************************************
' System :メール配信サービス
' Content:配信メール更新
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/database.php");
include("$inc/mail.php");
include("inc.php");

define('CANCEL_URL', '%CANCEL_URL%');

// 二重起動チェック
if (!dup_check('lock_mail_send_sm'))
	exit;

// 最大実行時間（３分）
set_time_limit(180);

mail_send();
exit;

function mail_send() {
	$sql = "SELECT md_send_mail_id,md_plug_vars,sd_body"
			. " FROM t_mail_data"
			. " JOIN t_send_mail_data ON sd_send_mail_id=md_send_mail_id"
			. " WHERE sd_status=0 AND (md_send_date IS NULL OR md_send_date<=CURRENT_TIMESTAMP)";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$send_mail_id = $fetch->md_send_mail_id;

		$plug_vars = array();
		$ary = explode(',', $fetch->md_plug_vars);
		foreach ($ary as $plug_var) {
			$plug_var = trim($plug_var);
			if ($plug_var != '')
				$plug_vars[] = $plug_var;
		}
		$plug_count = count($plug_vars);

		$param = strpos($fetch->sd_body, CANCEL_URL);
		if ($param) {
			$fetch2 = get_system_info('sy_site_url');
			$cancel_url = "{$fetch2->sy_site_url}cancel.php";
		}

		db_begin_trans();

		$sql = "SELECT ml_sub_seq,ml_mail_addr,md_plug_data FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id AND ml_status=0";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		$count = 0;
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
			$sub_seq = $fetch2->ml_sub_seq;
			$mail_header = "X-SMAgent-Id: $send_mail_id-$sub_seq";

			if (send_mail_list($send_mail_id, $fetch2->ml_mail_addr, ($plug_count || $param) ? $sub_seq : '', $mail_header)) {
				if ($plug_count) {
					$plug_data = explode("\n", $fetch2->md_plug_data);
					foreach ($plug_vars as $k => $plug_var) {
						send_mail_embed($send_mail_id, $sub_seq, $plug_var, $plug_data[$k]);
					}
				}

				if ($param) {
					$key = ($send_mail_id + $sub_seq * 3 + 4240) % 10000;
					send_mail_embed($send_mail_id, $sub_seq, CANCEL_URL, "$cancel_url?c=$send_mail_id&s=$sub_seq&k=$key");
				}
			}

			$sql = "UPDATE t_mail_list SET ml_status=2 WHERE ml_send_mail_id=$send_mail_id AND ml_sub_seq=$sub_seq";
			db_exec($sql);

			$count++;
		}

		$sql = "UPDATE t_mail_count SET mc_count0=mc_count0-$count,mc_count2=mc_count2+$count WHERE mc_send_mail_id=$send_mail_id";
		db_exec($sql);

		send_mail_ok($send_mail_id);

		db_commit_trans();
	}
}
?>