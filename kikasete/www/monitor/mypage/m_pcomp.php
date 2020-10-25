<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
include("$inc/format.php");
include("$inc/point.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_chara.php");
include("$inc/mn_error.php");

// 交換商品デコード
function decode_shouhin($code) {
	switch ($code) {
	case 1:
		return 'VISAギフト券';
	case 2:
		return '図書券';
	case 3:
		return 'WEBMONEY';		
	//jeon_start seq=ad1
	case 4:		
		return '楽天銀行';	
	//jeon_end seq=ad1	
	case 5:
		return '義援金';	
	}
}

// パラメータをグローバル変数に展開
extract(unserialize($param));

if ($shouhin == 4 || $shouhin == 5) {
	// WEBMONEYの場合
//	$name1 = '-';
//	$name2 = ' ';
	$name = '-';
	$addr = '-';
	$zip = '-';
	$tel = '-';
} else {
	// WEBMONEY以外の場合
	$name = "$name1 $name2";
	$addr = trim("$addr1$addr2$addr3 $addr4");
	$zip = "$zip1$zip2";
	$tel = format_tel($tel1, $tel2, $tel3);
}

db_begin_trans();
// jeon_start seq=ad1

$sql = sprintf("INSERT INTO t_seisan (si_member_kind,si_member_id,si_point,si_item,si_name1,si_name2,si_addr,si_zip,si_tel,si_siten_name,si_siten_no,si_kouza_no,si_kouza_name)"
		. " VALUES (1,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			sql_number($_SESSION['ss_monitor_id']),
			sql_number($ex_point),
			sql_number($shouhin),
			sql_char($name1),
			sql_char($name2),
			sql_char($addr),
			sql_char($zip),
			sql_char($tel),
			sql_char($siten_name),			
			sql_char($siten_no),
			sql_char($kouza_no),
			sql_char($kouza_name));	

// jeon_end seq=ad1
// 精算情報書き込み
db_exec($sql);

// モニターポイント更新
monitor_point($_SESSION['ss_monitor_id'], PT_SEISAN, -($ex_point + get_charge_point($shouhin)));

db_commit_trans();

$sql = "SELECT mp_curr_point,mp_enq_history"
		. " FROM t_monitor_point"
		. " WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$curr_point = number_format($fetch->mp_curr_point);
	get_chara_condition($fetch->mp_enq_history, $chara_text);
}
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　ポイント</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td colspan="3">
<?
if (!$msg) {
?>
			<table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td><font color="black" size="2">ポイント精算承りました。発送予定日は<a href="m_point_list.php">ポイント履歴</a>よりご確認ください。</font> </td>
				</tr>
			</table>
<?
} else
	error_msg($msg);
?>
		</td>
	</tr>
</table>