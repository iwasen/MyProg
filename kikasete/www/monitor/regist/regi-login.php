<?
$top = './..';
$inc = "$top/../inc";
include("$inc/encrypt.php");
include("$inc/mail.php");
include("$inc/point.php");
include("$inc/affiliate.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");

// モニター登録処理
function regist_monitor($fetch) {
	// モニター登録情報
	$sql = sprintf("INSERT INTO t_monitor (mn_type,mn_name1,mn_name2,mn_name1_kana,mn_name2_kana,mn_birthday,mn_sex,mn_mikikon,mn_mail_addr,mn_mail_addr_low,mn_password,mn_jitaku_zip,mn_jitaku_area,mn_shokugyou_cd,mn_gyoushu_cd,mn_shokushu_cd,mn_kinmu_zip,mn_kinmu_area,mn_pr_id,mn_have_child,mn_housing_form,mn_have_car,mn_html_mail,mn_spouse_flg,mn_spouse_other,mn_spouse_birthday,mn_spouse_shokugyo_cd,mn_senior_flg,mn_haimail_flag) VALUES (1,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			sql_char($fetch->wm_name1),
			sql_char($fetch->wm_name2),
			sql_char($fetch->wm_name1_kana),
			sql_char($fetch->wm_name2_kana),
			sql_date($fetch->wm_birthday),
			sql_number($fetch->wm_sex),
			sql_number($fetch->wm_mikikon),
			sql_char($fetch->wm_mail_addr),
			sql_char($fetch->wm_mail_addr_low),
			sql_char($fetch->wm_password),
			sql_char($fetch->wm_jitaku_zip),
			sql_number($fetch->wm_jitaku_area),
			sql_number($fetch->wm_shokugyou_cd),
			sql_number($fetch->wm_gyoushu_cd),
			sql_number($fetch->wm_shokushu_cd),
			sql_char($fetch->wm_kinmu_zip),
			sql_number($fetch->wm_kinmu_area),
			sql_number($fetch->wm_pr_id),
			sql_number($fetch->wm_have_child),
			sql_number($fetch->wm_housing_form),
			sql_number($fetch->wm_have_car),
			sql_bool($fetch->wm_html_mail),
			sql_number($fetch->wm_spouse_flg),
			sql_char($fetch->wm_spouse_other),
			sql_date($fetch->wm_spouse_birthday),
			sql_number($fetch->wm_spouse_shokugyo_cd),
			sql_number($fetch->wm_senior_flg),
			sql_bool($fetch->wm_haimail_flag));
	db_exec($sql);

	// モニターID取得
	$monitor_id = get_currval('t_monitor_mn_monitor_id_seq');

	// 子供情報
	$child_ary = unserialize($fetch->wm_child_info);
	if (is_array($child_ary)) {
		$child_no = 1;
		foreach ($child_ary as $child) {
			$sql = sprintf("INSERT INTO t_monitor_child (mc_monitor_id,mc_child_no,mc_sex,mc_birthday) VALUES (%s,%s,%s,%s)",
					sql_number($monitor_id),
					sql_number($child_no++),
					sql_number($child->sex),
					sql_date2($child->birthday_y, $child->birthday_m, $child->birthday_d));
			db_exec($sql);
		}
	}

	// よく行くコンビニ
	if ($fetch->wm_conveni != '') {
		$sql = "INSERT INTO t_conveni (dc_monitor_id,dc_conveni_cd)"
				. " SELECT $monitor_id,cv_conveni_cd FROM m_conveni WHERE cv_conveni_cd IN ($fetch->wm_conveni)";
		db_exec($sql);
	}

	// よく行くスーパー
	if ($fetch->wm_super != '') {
		$sql = "INSERT INTO t_super (ds_monitor_id,ds_super_cd)"
				. " SELECT $monitor_id,sp_super_cd FROM m_super WHERE sp_super_cd IN ($fetch->wm_super)";
		db_exec($sql);
	}

	// 興味ジャンル
	if ($fetch->wm_genre != '') {
		$sql = "INSERT INTO t_genre (dk_monitor_id,dk_genre_cd)"
				. " SELECT $monitor_id,kg_genre_cd FROM m_genre WHERE kg_genre_cd IN ($fetch->wm_genre)";
		db_exec($sql);
	}

	// 追加プロファイル
	if ($fetch->wm_ma_profile != '') {
		$sql = "INSERT INTO t_monitor_profile (mnp_monitor_id,mnp_profile_cd)"
				. " SELECT $monitor_id,pfs_profile_cd FROM t_profile_sel WHERE pfs_profile_cd IN ($fetch->wm_ma_profile)";
		db_exec($sql);
	}

	// ポイント情報作成
	$fetch2 = get_system_info('sy_point_validity_period');
	$point_validity_period = $fetch2->sy_point_validity_period + 1;
	$sql = "INSERT INTO t_monitor_point (mp_monitor_id,mp_point_period) VALUES ($monitor_id,date_trunc('month', current_date) + interval '$point_validity_period month')";
	db_exec($sql);

	// モニター数加算
	$sql = "UPDATE t_sum_data SET su_sum_data=su_sum_data+1 WHERE su_sum_id=1";
	db_exec($sql);

	// モニター仮登録データ削除
	$sql = "DELETE FROM w_monitor WHERE wm_seq_no=$fetch->wm_seq_no";
	db_exec($sql);

	// バージンメール送信対象に追加
	$sql = "INSERT INTO w_virgin_mail (vm_monitor_id) VALUES ($monitor_id)";
	db_exec($sql);

	// アフィリエイト処理
	$affiliate_id = $fetch->wm_affiliate_id;
	if ($affiliate_id) {
		// アフィリエイト申請者のモニターID取得
		$sql = "SELECT af_monitor_id,af_affiliate_mgr_id FROM t_affiliate WHERE af_affiliate_id=$affiliate_id AND af_status=1";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch2 = pg_fetch_object($result, 0);
			$affiliate_monitor_id = $fetch2->af_monitor_id;
			$affiliate_mgr_id = $fetch2->af_affiliate_mgr_id;

			// アフィリエイト登録
			$rec['afr_affiliate_id'] = sql_number($affiliate_id);
			$rec['afr_monitor_id'] = sql_number($monitor_id);
			$rec['afr_point'] = sql_number(GP_AFFILIATE);
			db_insert('t_affiliate_reg', $rec);

			// 総登録数更新
			$rec['afm_regist_count'] = 'afm_regist_count+1';
			db_update('t_affiliate_mgr', $rec, "afm_affiliate_mgr_id=$affiliate_mgr_id");

			// 登録数更新
			$rec['afc_kari_count'] = 'afc_kari_count-1';
			$rec['afc_hon_count'] = 'afc_hon_count+1';
			db_update('t_affiliate_cnt', $rec, "afc_affiliate_id=$affiliate_id");

			// 子の階層数更新
			for ($child_tree = 1, $parent_affiliate_id = $affiliate_id; $parent_affiliate_id; $child_tree++) {
				$sql = "UPDATE t_affiliate SET af_child_tree=$child_tree WHERE af_affiliate_id=$parent_affiliate_id AND af_child_tree<$child_tree";
				db_exec($sql);

				$sql = "SELECT af_parent_affiliate_id FROM t_affiliate WHERE af_affiliate_id=$parent_affiliate_id";
				$parent_affiliate_id = db_fetch1($sql);
			}

			// メールの場合、子にアフィリエイトURLを付与
			$sql = "SELECT afm_affiliate_type FROM t_affiliate_mgr WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
			if (db_fetch1($sql) == AFFILIATE_MAIL) {
				// アフィリエイト付与
				$rec['af_monitor_id'] = sql_number($monitor_id);
				$rec['af_affiliate_mgr_id'] = sql_number($affiliate_mgr_id);
				$rec['af_status'] = sql_number(1);
				$rec['af_accept_date'] = 'current_timestamp';
				$rec['af_parent_affiliate_id'] = sql_number($affiliate_id);
				db_insert('t_affiliate', $rec);
				$affiliate_id = get_currval('t_affiliate_af_affiliate_id_seq');

				// アフィリエイトカウント
				$rec['afc_affiliate_id'] = sql_number($affiliate_id);
				db_insert('t_affiliate_cnt', $rec);
			}

			// ポイント付与
			monitor_point($affiliate_monitor_id, PT_AFFILIATE, GP_AFFILIATE);
			monitor_point($monitor_id, PT_AFFILIATE, GP_AFFILIATE);
		}
	}
}

// 登録完了メール送信
function mail_send($mail_addr, $password) {
	get_mail_template('reg_mn2', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%PASSWORD%', $password, $body);

	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

$seq_no = decrypt_number($key);
if (!$seq_no) {
	$msg = '登録に失敗しました。';
} else {
	db_begin_trans();

	db_exec('LOCK TABLE w_monitor IN EXCLUSIVE MODE');

	$sql = "SELECT * FROM w_monitor WHERE wm_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$sql = "SELECT * FROM t_monitor WHERE mn_mail_addr_low=" . sql_char($fetch->wm_mail_addr_low) . " AND mn_status<>9";
		$result2 = db_exec($sql);
		if (pg_numrows($result2) == 0) {
			regist_monitor($fetch);
			mail_send($fetch->wm_mail_addr, $fetch->wm_password);
		} else
			$msg = 'すでに登録済みです。';
	} else
		$msg = 'すでに登録済みです。';

	db_commit_trans();
}
?>
<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_reg.gif" width=590 height=30 border=0 alt="モニター登録"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD align="left"><IMG src="../image/reg7_m.gif" width=600 height=36 border=0 alt="登録確認"></TD>
</TR>
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD colspan="2">

	<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>

<?
if ($msg == '') {
?>

	<TR>
	<TD align="left">
<SPAN class=fs12_lh120>
<font color="#666666">
<B>ご登録ありがとうございました。</B><BR>
<BR>
ただいまご登録頂きましたメールアドレス宛てに、【登録確認メール】を送らせて頂きました。<BR>
このメールをもちまして登録完了となります。<BR>
<BR>
左メニューのテキスト欄にメールアドレスとパスワードを入力し、モニターMyページへログインしてください。<br>
<BR>
</font></SPAN>
	</TD>
	</TR>

<?
} else {
?>

	<TR>
	<TD align="left">
<SPAN class=fs12_lh120>
<font color="#CC0000">
<B>登録エラー</B><BR>
<BR>
<?=$msg?><br>
<BR>
</font></SPAN>
	</TD>
	</TR>

<?
}
?>

	</TBODY>
	</TABLE>
<DIV align="left"><img height="75" width="600" src="../image/reg_flow07.gif" border="0" alt="登録確認" vspace="15"></DIV>

<!--
<a href="https://mile.netmile.co.jp/ctrl/user/uMilePublishInput.do?param1=3580&param2=44259&param3=4b948bb157df4e7a5f62cf58e7b9ad5d&earnsite_key=&mile=&tran_id=" target="_blank"><img src="https://mile.netmile.co.jp/image/mileget.gif" border="0"></a>
-->

</TD>
</TR>
</TBODY>
</TABLE>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
