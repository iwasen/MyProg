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
	case 4:
	//jeon_start seq=ad1
	//	return 'WEBMONEY';
		return '楽天銀行';
	//jeon_start seq=ad1	
	case 5:
		return '義援金';
	}
}

// 入力項目チェック
$sql = "SELECT mp_curr_point FROM t_monitor_point WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
$curr_point = db_fetch1($sql);
$min_exchange_point = get_min_exchange_point($shouhin);
if ($ex_point == 0)
	$msg[] = '精算するポイント数を入力してください。';
elseif (!check_num($ex_point))
	$msg[] = '精算ポイント数が正しく入力されていません。';
elseif ($shouhin == 1 && $ex_point % 1000 != 0)
	$msg[] = "精算ポイント数は1000単位で入力してください。";
elseif ($shouhin == 4 && $ex_point != 200 && $ex_point != 500 && $ex_point % 1000 != 0)
	$msg[] = "精算ポイント数は200または500、それ以上は1000単位で入力してください。";
elseif ($shouhin == 5 && $ex_point != $min_exchange_point)
	$msg[] = "義援金は{$min_exchange_point}ポイントを入力してください。";
else {
	$charge_point = get_charge_point($shouhin);
	if ($ex_point + $charge_point > $curr_point)
		$msg[] = 'ポイント数が足りません。';
	elseif ($ex_point < $min_exchange_point)
		$msg[] = "精算できるポイント数は{$min_exchange_point}ポイント以上です。";
}

if ($shouhin == '')
	$msg[] = '交換したい商品を選択してください。';
// jeon_start seq=ad1
if (!$msg) {
	if ($shouhin == 4) {
		if($siten_name=='')
			$msg[] = '支店名を入力してください。';
		if($siten_no=='')
			$msg[] = '支店番号を入力してください。';
		else{
			$str = $siten_no;
			for($i =0;$i<strlen($str);$i++){
				echo $str{$i};
				if($str{$i}>=0 && $str{$i}<=9){	
					
				}else{
					$msg[] = '半角数字のみ、入力してください。';
					break;
				}
			
			}
	
		}
		if($kouza_no=='')
			$msg[] = '口座番号を入力してください。';
		if($kouza_name=='')
			$msg[] = '口座名義を入力してください。';
	// jeon_end seq=ad1					
	}elseif ($shouhin == 1){
		if ($name1 == '')
			$msg[] = '氏名の姓を入力してください。';

		if ($name2 == '')
			$msg[] = '氏名の名を入力してください。';

		if ($zip1 == '' || $zip2 == '')
			$msg[] = '郵便番号を入力してください。';
		elseif (!check_zip($zip1, $zip2))
			$msg[] = '郵便番号が正しく入力されていません。';

		if ($addr1 == '')
			$msg[] = '住所１（都道府県）を入力してください。';

		if ($addr2 == '')
			$msg[] = '住所２（市区町村）を入力してください。';

		if ($addr3 == '')
			$msg[] = '住所３（番地）を入力してください。';

		if ($tel1 == '' || $tel2 == '' || $tel3 == '')
			$msg[] = '電話番号を入力してください。';
		elseif (!check_tel($tel1, $tel2, $tel3))
			$msg[] = '電話番号が正しく入力されていません。';
	}
}
$sql = "select mn_name1,mn_name2 from t_monitor where mn_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
if (!$msg) {
	if ($shouhin == 4 || $shouhin == 5) {
		// WEBMONEYの場合
		$name1 = $fetch->mn_name1;
		$name2 = $fetch->mn_name2;
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
					<td><font color="black" size="2">精算内容をご確認ください。<br><br></font> 
						<table width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#9999ff">
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">精算ポイント</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=number_format($ex_point)?>ポイント</font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">交換商品</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=decode_shouhin($shouhin)?></font></td>
										</tr>
								<!-- jeon_start seq=ad1 -->		
								<?if ($shouhin == 4) {?>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">支店名</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($siten_name)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">支店番号</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=$siten_no?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">口座番号</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=$kouza_no?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">口座名義</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($kouza_name)?></font></td>
										</tr>
								<?} elseif ($shouhin == 1) {?>		
								<!-- jeon_end seq=ad1 -->	
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">お名前</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($name)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">郵便番号</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=format_zip($zip)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">ご住所</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=htmlspecialchars($addr)?></font></td>
										</tr>
										<tr>
											<td width="93" bgcolor="#ccccff"><font size="2">電話番号</font></td>
											<td width="307" bgcolor="white"><font size="2"><?=$tel?></font></td>
										</tr>
								<?}?>		
										<br>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<form name="form1" method="post" action="m_pcomp.php">
				<div align="center">
					<input type="button" value="ポイント精算をする" onclick="disabled=true;document.form1.submit()">
					<input type="button" value="　戻る　" onclick="history.back()">
					<input type="hidden" name="param" <?=value(serialize($_POST))?>>
				</div>
			</form>
<?
} else
	error_msg($msg);
?>
		</td>
	</tr>
</table>
