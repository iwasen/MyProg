<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");
include("$inc/format.php");
include("$inc/mail.php");
include("$inc/decode.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mk_header_sem.php");

// マーケター登録処理
function regist_marketer($fetch) {
	db_begin_trans();

	// マーケター登録情報
	$marketer_id = $fetch->wr_marketer_id;
	if ($marketer_id == '') {
		$sql = "INSERT INTO t_marketer (mr_type,mr_name1,mr_name2,mr_name1_kana,mr_name2_kana,mr_birthday,mr_sex,mr_mail_addr,mr_mail_addr_low,mr_password,mr_shokugyou_cd,mr_gyoushu_cd,mr_shokushu_cd,mr_kinmu_zip,mr_kinmu_area,mr_kinmu_addr1,mr_kinmu_addr2,mr_kinmu_addr3,mr_kinmu_tel,mr_kinmu_fax,mr_kinmu_name,mr_kinmu_unit,mr_kinmu_post,mr_agent_id,mr_permission) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
	} else {
		$sql = "UPDATE t_marketer SET mr_type=%s,mr_name1=%s,mr_name2=%s,mr_name1_kana=%s,mr_name2_kana=%s,mr_birthday=%s,mr_sex=%s,mr_mail_addr=%s,mr_mail_addr_low=%s,mr_password=%s,mr_shokugyou_cd=%s,mr_gyoushu_cd=%s,mr_shokushu_cd=%s,mr_kinmu_zip=%s,mr_kinmu_area=%s,mr_kinmu_addr1=%s,mr_kinmu_addr2=%s,mr_kinmu_addr3=%s,mr_kinmu_tel=%s,mr_kinmu_fax=%s,mr_kinmu_name=%s,mr_kinmu_unit=%s,mr_kinmu_post=%s,mr_agent_id=%s,mr_permission=%s WHERE mr_marketer_id=$marketer_id";
	}
	$sql = sprintf($sql,
			sql_number($fetch->wr_type),
			sql_char($fetch->wr_name1),
			sql_char($fetch->wr_name2),
			sql_char($fetch->wr_name1_kana),
			sql_char($fetch->wr_name2_kana),
			sql_date($fetch->wr_birthday),
			sql_number($fetch->wr_sex),
			sql_char($fetch->wr_mail_addr),
			sql_char($fetch->wr_mail_addr_low),
			sql_char($fetch->wr_password),
			sql_number($fetch->wr_shokugyou_cd),
			sql_number($fetch->wr_gyoushu_cd),
			sql_number($fetch->wr_shokushu_cd),
			sql_char($fetch->wr_kinmu_zip),
			sql_number($fetch->wr_kinmu_area),
			sql_char($fetch->wr_kinmu_addr1),
			sql_char($fetch->wr_kinmu_addr2),
			sql_char($fetch->wr_kinmu_addr3),
			sql_char($fetch->wr_kinmu_tel),
			sql_char($fetch->wr_kinmu_fax),
			sql_char($fetch->wr_kinmu_name),
			sql_char($fetch->wr_kinmu_unit),
			sql_char($fetch->wr_kinmu_post),
			sql_char($fetch->wr_agent_id),
			sql_char(PMR_DEF_MARKETER));
	db_exec($sql);

	if ($marketer_id == '')
		$marketer_id = get_currval('t_marketer_mr_marketer_id_seq');

	// 希望メールアカウント登録
	//choice_email($marketer_id, 1, $fetch->wr_choice_email1);
	//choice_email($marketer_id, 2, $fetch->wr_choice_email2);
	//choice_email($marketer_id, 3, $fetch->wr_choice_email3);

	// マーケター仮登録データ削除
	$sql = "DELETE FROM w_marketer WHERE wr_seq_no=$fetch->wr_seq_no";
	db_exec($sql);

	db_commit_trans();

	return $marketer_id;
}

// 希望メールアカウント登録
function choice_email($marketer_id, $choice_no, $account) {
	if ($account != '') {
		$sql = sprintf("INSERT INTO w_choice_email (we_member_id,we_member_kind,we_choice_no,we_account) VALUES (%s,2,%s,%s)",
				sql_number($marketer_id),
				sql_number($choice_no),
				sql_char($account));
		db_exec($sql);
	}
}

// 登録完了メール送信
function mail_send($marketer_id) {
	// マーケター登録情報取得
	$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$marketer_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// 登録者宛てメール
		get_mail_template('reg_mkp2', $subject, $from, $cc, $bcc, $repty_to, $body);
		$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
		$body = str_replace('%PASSWORD%', $fetch->mr_password, $body);
		send_mail($subject, $fetch->mr_mail_addr, $from, $body, $cc, $bcc, $reply_to);

		// 事務局宛てメール
		get_mail_template('reg_mkp3', $subject, $from, $cc, $bcc, $repty_to, $body);
		$body = str_replace('%REGIST_DATE%', format_datetime($fetch->mr_regist_date), $body);
		$body = str_replace('%NAME1%', $fetch->mr_name1, $body);
		$body = str_replace('%NAME2%', $fetch->mr_name2, $body);
		$body = str_replace('%NAME1_KANA%', $fetch->mr_name1_kana, $body);
		$body = str_replace('%NAME2_KANA%', $fetch->mr_name2_kana, $body);
		$body = str_replace('%BIRTHDAY%', format_date($fetch->mr_birthday), $body);
		$body = str_replace('%SEX%', decode_sex($fetch->mr_sex), $body);
		$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
		$body = str_replace('%SHOKUGYOU%', decode_shokugyou($fetch->mr_shokugyou_cd), $body);
		$body = str_replace('%GYOUSHU%', decode_gyoushu($fetch->mr_gyoushu_cd), $body);
		$body = str_replace('%SHOKUSHU%', decode_shokushu($fetch->mr_shokushu_cd), $body);
		$body = str_replace('%KINMU_NAME%', $fetch->mr_kinmu_name, $body);
		$body = str_replace('%KINMU_UNIT%', $fetch->mr_kinmu_unit, $body);
		$body = str_replace('%KINMU_POST%', $fetch->mr_kinmu_post, $body);
		$body = str_replace('%KINMU_ZIP%', format_zip($fetch->mr_kinmu_zip), $body);
		$body = str_replace('%KINMU_AREA%', decode_area($fetch->mr_kinmu_area), $body);
		$body = str_replace('%KINMU_ADDR1%', $fetch->mr_kinmu_addr1, $body);
		$body = str_replace('%KINMU_ADDR2%', $fetch->mr_kinmu_addr2, $body);
		$body = str_replace('%KINMU_ADDR3%', $fetch->mr_kinmu_addr3, $body);
		$body = str_replace('%KINMU_TEL%', $fetch->mr_kinmu_tel, $body);
		$body = str_replace('%KINMU_FAX%', $fetch->mr_kinmu_fax, $body);
		$body = str_replace('%AGENT%', decode_agent($fetch->mr_agent_id), $body);
		$fetch2 = get_system_info('sy_marketer_reg_to');
		if ($fetch2->sy_marketer_reg_to != '')
			send_mail($subject, $fetch2->sy_marketer_reg_to, $from, $body, $cc, $bcc, $reply_to);

		// エージェント宛てメール
		$sql = "SELECT ag_mail_addr,ag_bcc_addr FROM t_agent WHERE ag_agent_id=$fetch->mr_agent_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			send_mail($subject, $fetch->ag_mail_addr, $from, $body, '', $fetch->ag_bcc_addr);
		}
	}
}

// メイン処理
$seq_no = decrypt_number($key);
if (!$seq_no)
	$msg = '登録に失敗しました。';
else {
	$sql = "SELECT * FROM w_marketer WHERE wr_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		$msg = 'すでに登録済みです。';
	else {
		$fetch = pg_fetch_object($result, 0);
		$marketer_id = regist_marketer($fetch);
		mail_send($marketer_id);
	}
}
?>
<? marketer_header('登録完了', PG_REGIST) ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
    <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb">        
          <td valign="top">        
          <td valign="top">&nbsp;</td>
          <td valign="top"><img src="../../images/common/spacer.gif" width="20" height="15"></td>
        </tr>
        <tr>
          <td width="170" valign="top" bgcolor="#e5eaf0">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" bgcolor="#FFFFFF"><img src="../../images/regist/side_regist.gif" alt="ご登録" width="57" height="18"></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mypage_on.gif" alt="Myページ登録" width="170" height="31"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mail" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image59','','../../images/regist/side_mailnews_on.gif',1)"><img src="../../images/regist/side_mailnews.gif" alt="メールニュース登録" name="Image59" width="170" height="31" border="0"></a></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　<a href="mk_regist.php">登録</a>　＞　Myページ登録</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td><img src="../../images/regist/title.gif" alt="ご登録について" width="114" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td width="578"><img src="../../images/regist/header1.gif" alt="Myページ登録" width="578" height="38"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <?
if ($msg == '') {
?>
              <tr> 
                <td align="center"><img src="../../images/regist/finish.gif"></td>
              </tr>
              <tr> 
                <td> 
                  <p><br>
                    ご登録ありがとうございました。 </p>
                  <p>Ｍｙページへログインするには、ご登録のメールアドレスとパスワードを入力して「ログイン」ボタンを押して下さい。 <br>
                    Ｔｏｐページでご覧頂いた情報は、Ｍｙページからも見ることができます。</p>
                </td>
              </tr>
              <?
} else {
?>
              <tr> 
                <td>
                  <?=$msg?>
                </td>
              </tr>
              <?
}
?>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

</body>
</html>
