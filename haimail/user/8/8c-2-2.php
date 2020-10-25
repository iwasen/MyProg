<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/point.php');

//=== メイン処理 ===
session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$param = $_POST['param'];
if (!$param)
	redirect('../index.php');
$dt = unserialize($param);

$member = new CMember;

// シーケンス番号取得
$seq_no = GetNextVal('PE_seq_no');

// 入力データ保存
$sql = sprintf("INSERT INTO T_POINT_EXCHANGE (PE_seq_no,PE_member_id,PE_date,PE_status,PE_exchange_type,PE_point,PE_bank,PE_bank_branch,PE_bank_kana,PE_bank_branch_kana,PE_acc_kind,PE_acc_number,PE_acc_name1,PE_acc_name2,PE_acc_name1_kana,PE_acc_name2_kana,PE_name1,PE_name2,PE_zip,PE_addr_pref,PE_addr_city,PE_addr_number,PE_addr_building) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		SqlNum($seq_no),
		SqlStr($member->member_id),
		'SYSDATE',
		SqlStr('0'),
		SqlStr($dt['exchange_type']),
		SqlNum($dt['point']),
		SqlStr($dt['bank']),
		SqlStr($dt['bank_branch']),
		SqlStr($dt['bank_kana']),
		SqlStr($dt['bank_branch_kana']),
		SqlStr($dt['acc_kind']),
		SqlStr($dt['acc_number']),
		SqlStr($dt['acc_name1']),
		SqlStr($dt['acc_name2']),
		SqlStr($dt['acc_name1_kana']),
		SqlStr($dt['acc_name2_kana']),
		SqlStr($dt['name1']),
		SqlStr($dt['name2']),
		SqlStr($dt['zip1'] . $dt['zip2']),
		SqlStr($dt['addr_pref']),
		SqlStr($dt['addr_city']),
		SqlStr($dt['addr_number']),
		SqlStr($dt['addr_building']));
ExecuteSQL($sql);
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY ポイント交換確認</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table cellspacing="0" cellpadding="0" border="0" height="58">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table><br>
  <table border="0" cellpadding="0" cellspacing="0" width="632">
			<tr height="36">
				<td width="20" height="36"><img height="16" width="20" src="../img/space.gif"></td>
				
      <td height="36" align="left" valign="top"><font color="#2f2180"><b>ポイント交換申請を受けつけました。</b></font></td>
			</tr>
			<tr>
				<td width="20"></td>
				
      <td><font size="2">締め日は毎月20日です。21日以降は翌月処理とさせていただきます。<br><br>
        銀行振込での申請の場合は、翌月末に振込みとなります。<br>
        商品券での交換申請の場合は、翌月中頃に郵送にてお送りします。</font><br>
      </td>
			</tr>
    <tr> 
      <td>　</td>
      <td><br>
        <br>
        <font size="2"> 
        <hr>
        </font><a href="index.php"><img height="17" width="80" src="img/mypage.gif" alt="my pageに戻る" border="0"></a><br>
        <br>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <br>
	</body>

</html>
