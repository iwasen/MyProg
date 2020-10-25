<?
include ("../inc/com_db_func.inc");

if (($c + $s * 3 + 4240) % 10000 == $k) {
	$sql = "select CD_seq_no from M_CLEANING_DATA"
		. " where CD_seq_no=$c and to_char(now(), 'YYYYMMDD') between to_char(CD_start_date, 'YYYYMMDD') and to_char(CD_end_date, 'YYYYMMDD')";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$sql = "select CL_status from T_CLEANING_LIST where CL_seq_no=$c and CL_sub_seq=$s";
		$result = db_exec($sql);

		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$status = $fetch->cl_status;

			if ($status == "2" || $status == "3") {
				$sql = "update T_CLEANING_LIST set CL_status='4' where CL_seq_no=$c and CL_sub_seq=$s";
				db_exec($sql);
				$msg = "今後こちらからのメールが届きませんように、<br>リストからあなたさまのメールアドレスを削除させていただきました。";
			} elseif ($status == "4") {
				$msg = "あなたさまのメールアドレスはすでに削除されております。";
			} else
				$msg = "ＩＤが正しくありません。";
		} else
			$msg = "ＩＤが登録されていません。";
	} else
		$msg = "解除受け付けの期限を過ぎているため、メールアドレスの削除はできませんでした。";
} else
	$msg = "パラメータが正しくありません。";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000">
<p align="center">　</p>
<p align="center">　</p>
<p align="center">　</p>
<p align="center">
  <font color="#333333"><? echo $msg ?></font></p>
  <p align="center"><font color="#333333">またのご利用をお待ちいたしております。</font></p>
<TABLE border="0" cellpadding="3" cellspacing="5" align="center" width="400">
  <TR class="text"><TD  ALIGN="center"> 
    <FORM><INPUT TYPE="button" value="この画面を閉じる" onClick="window.close()"></FORM>
    </TD>
  </TR>
</TABLE>
</body>
</html>