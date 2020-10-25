<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/login_log.php");
$inc = "$top/inc";
include("$inc/mk_header.php");

// セッションにマーケターIDを保存
function set_session($marketer_id, $owner_id, $marketer_type, $permission) {
	session_start();
	$_SESSION['ss_marketer_id'] = $marketer_id;
	$_SESSION['ss_owner_id'] = $owner_id;
	$_SESSION['ss_marketer_type'] = $marketer_type;
	$_SESSION['ss_permission'] = $permission;
}

if (isset($mail_addr)) {
	// メールアドレスをクッキーに保存
	setcookie('ck_marketer_mail_addr', $mail_addr, COOKIE_EXPIRE, '/');
	$_COOKIE['ck_marketer_mail_addr'] = $mail_addr;

	if ($mail_addr == '')
		$msg[] = 'メールアドレスが入力されていないようです。';

	if ($password == '')
		$msg[] = 'パスワードが入力されていないようです。';
} elseif ($marketer_id == '')
	$msg[] = 'ログインしていないか、またはログインがタイムアウトしました。もう一度ログインしてください。';

if (!$msg) {
	$sql = "SELECT mr_marketer_id,mr_password,mr_type,mr_parent_id,mr_permission"
			. " FROM t_marketer";
	if ($mail_addr != '')
			$sql .= " WHERE mr_mail_addr_low=" . sql_char(strtolower($mail_addr)) . " AND mr_status<>9";
	elseif ($marketer_id != '')
			$sql .= " WHERE mr_marketer_id=$marketer_id AND mr_status<>9";

	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		$msg[] = '登録が完了していないか、アドレスが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
	else {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mr_password != $password)
			$msg[] = 'パスワードが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
		else {
			switch ($fetch->mr_type) {
			case MK_FREE:
				set_session($fetch->mr_marketer_id, $fetch->mr_marketer_id, $fetch->mr_type, $fetch->mr_permission);
				login_log(2, $mail_addr, $password, $fetch->mr_marketer_id);
				$url = 'mku_index.php';
				redirect($ssl ? https_url($url) : http_url($url));
			case MK_PAY:
				login_log(2, $mail_addr, $password, $fetch->mr_marketer_id);
				set_session($fetch->mr_marketer_id, $fetch->mr_parent_id != '' ? $fetch->mr_parent_id : $fetch->mr_marketer_id, $fetch->mr_type, $fetch->mr_permission);
				if ($url == '')
					$url = 'mkk_index.php';
				redirect($ssl ? https_url($url) : http_url($url));
			case MK_ASSISTANT:
				login_log(2, $mail_addr, $password, $fetch->mr_marketer_id);
				set_session($fetch->mr_marketer_id, $fetch->mr_parent_id, $fetch->mr_type, $fetch->mr_permission);
				$url = 'mkk_index.php';
				redirect($ssl ? https_url($url) : http_url($url));
			default:
				$msg[] = '登録が完了していないか、アドレスが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
			}
		}
	}
}

login_log(2, $mail_addr, $password, null);

marketer_header('ログイン', PG_NULL);
?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="2" cellpadding="0" bgcolor="#ffffff">
  <tr> 
    <td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
    <td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
  </tr>
  <tr> 
    <td> 
      <form method="post" action="index.php">
        ログインできませんでした。
        <Hr Width="95%" Size="1" Color="#666666" Align="left" Noshade>
        <br>
        <?=disp_msg($msg, '・', '<br>')?>
        <br>

<input type="image" src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31">
      </form>
    </td>
  </tr>
  <tr>
    <td width="20">　</td>
    <td>　</td>
  </tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" 

border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>