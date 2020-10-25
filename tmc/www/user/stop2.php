<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// ユーザID取得
$sql = "SELECT ulg_user_id"
		. " FROM t_user_login"
		. " JOIN t_user_status ON ust_user_id=ulg_user_id"
		. " WHERE ulg_login_id='{$_POST['login_id']}' AND (ust_status=1 OR ust_status=2)";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$user_id = pg_fetch_result($result, 0, 0);

	db_begin_trans();

	// ユーザステータス更新
	$rec['ust_status'] = 8;
	$rec['ust_handover_date'] = 'current_timestamp';
	$rec['ust_cancel_cd'] = sql_number($_POST['cancel_cd'], 0);
	$rec['ust_cancel_text'] = sql_char($_POST['cancel_text']);
	$rec['ust_cancel_date'] = 'current_timestamp';
	db_update('t_user_status', $rec, "ust_user_id=$user_id");

	// ユーザ対応結果登録
	$rec['urs_kecchaku'] = sql_number(6);
	$rec['urs_rank'] = sql_char('E');
	$sql = "SELECT COUNT(*) FROM t_user_result WHERE urs_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_fetch_result($result, 0, 0) == 0) {
		$rec['urs_user_id'] = sql_number($user_id);
		db_insert('t_user_result', $rec);
	} else {
		db_update('t_user_result', $rec, "urs_user_id=$user_id");
	}

	$rec['urg_handover_date'] = 'current_timestamp';
	db_update('t_user_regist', $rec, "urg_user_id=$user_id");

	db_commit_trans();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="content-style-type" content="text/css">
<link rel="stylesheet" href="stop/ccs/default.css">
<title>おクルマ選び　お手伝いサービス</title>

</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" background="stop/img/sidebg.gif" text="#666666">
<div align="center" class="text12b"> 
  <table border="0" cellspacing="0" cellpadding="0" width="800" height="30">
    <tr bgcolor="#FFFFFF"> 
      <td class="text12"><img src="stop/img/ttl.gif" width="800" height="70" alt="おクルマ選びお手伝いサービス" border="0"><br>
        <img src="stop/img/spacer.gif" width="10" height="10" alt="*"> </td>
	</tr>
	</table>


  <table border="0" cellspacing="0" cellpadding="0" width="800">
    <tr> 
      <td align="center" valign="top" class="text12" width="800" bgcolor="#FFFFFF"> 
        <img src="stop/img/spacer.gif" width="10" height="10" alt="*"> <br>
        <img src="stop/img/bar.gif" width="525" height="5" alt="bar"><br>
        メール配信解除<br>
        <img src="stop/img/bar.gif" width="525" height="5" alt="bar"> 
		<form>        
<table border="0" cellspacing="0" cellpadding="2" align="center" width="548" class="text12">
          <tr> 
            <td width="20"><img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"></td>
          </tr>
          <tr align="center"> 
            <td class="text12"> メールの配信を解除いたしました。</td>
          </tr>
          <tr align="center"> 
            <td class="text14b"><img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"></td>
          </tr>
          <tr align="center"> 
            <td class="text14b"><img src="stop/img/arrow_blue.gif" width="14" height="8"></td>
          </tr>
          <tr> 
            <td align="center" class="text12"> 
              <input type="button" value="閉じる" onclick="window.close()">
              <br>
              <img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"> 
            </td>
          </tr>
        </table>
</form>
        <br>
      </td>
    </tr>
  </table>


<table border="0" cellspacing="0" cellpadding="0" width="800">
    <tr bgcolor="#002589" valign="middle"> 
      <td align="center" height="20"> 
        <div id="copyright" class="copyright">copyright 2003 e-okuruma.info All Rights Reserved.</div>
      </td>
</tr>
</table>


</div>
</body>

</html>
