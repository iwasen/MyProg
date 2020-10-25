<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ޡ������������Խ���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �������
function select_month2($selected) {
	echo '<option ', value_selected('0', $selected), ">����</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">${i}��</option>\n";
}

set_global('marketer', '�ޡ�����������', '�ޡ������������Խ���', BACK_TOP);

$height = 300;
$color_bg = '#eeffee';
$color_nyuukai = 'royalblue';
$color_taikai = 'darkmagenta';

// ����ǯ��
if ($year == '')
	$year = date('Y');

if ($month == '')
	$month = date('m');

if ($month == 0) {
	$ndata = 12;
	$bar_width = 15;

	$ymd = "$year/1/1";

	// ����Կ�����
	$sql = "SELECT DATE_PART('month',mr_regist_date) AS month,COUNT(*) AS cnt"
			. " FROM t_marketer"
			. " WHERE mr_status<>9 AND DATE_TRUNC('year',mr_regist_date)='$ymd'"
			. " GROUP BY month";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$nyuukai[$fetch->month] = $fetch->cnt;
		$max = max($max, $fetch->cnt);
	}

	// ���Կ�����
	$sql = "SELECT DATE_PART('month',mr_taikai_date) AS month,COUNT(*) AS cnt"
			. " FROM t_marketer"
			. " WHERE mr_status=9 AND DATE_TRUNC('year',mr_taikai_date)='$ymd'"
			. " GROUP BY month";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$taikai[$fetch->month] = $fetch->cnt;
		$max = max($max, $fetch->cnt);
	}

	// ��̾
	for ($i = 1; $i <= $ndata; $i++) {
		$hs[$i] = $i . '<font class="small">��</font>';
	}
} else {
	// ���׷����������
	$ndata = date('d', mktime(0, 0, 0, $month + 1, 0 , $year));

	$bar_width = 10;

	$ymd = "$year/$month/1";

	// ����Կ�����
	$sql = "SELECT DATE_PART('day',mr_regist_date) AS day,COUNT(*) AS cnt"
			. " FROM t_marketer"
			. " WHERE mr_status<>9 AND DATE_TRUNC('month',mr_regist_date)='$ymd'"
			. " GROUP BY day";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$nyuukai[$fetch->day] = $fetch->cnt;
		$max = max($max, $fetch->cnt);
	}

	// ���Կ�����
	$sql = "SELECT DATE_PART('day',mr_taikai_date) AS day,COUNT(*) AS cnt"
			. " FROM t_marketer"
			. " WHERE mr_status=9 AND DATE_TRUNC('month',mr_taikai_date)='$ymd'"
			. " GROUP BY day";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$taikai[$fetch->day] = $fetch->cnt;
		$max = max($max, $fetch->cnt);
	}

	// ���ˤ�
	$ary = array('<font color="red">��</font>', '��', '��', '��', '��', '��', '<font color="blue">��</font>');
	for ($i = 1; $i <= $ndata; $i++) {
		$w = date('w', strtotime("$year/$month/$i"));
		$hs[$i] = $i . '<br><span class="small">' . $ary[date('w', strtotime("$year/$month/$i"))] . '</span>';
	}
}

// ��������Ĵ��
if ($max > 0) {
	$div = pow(10, floor(log10($max)));
	if ($div >= 10) {
		if ($max < $div * 2)
			$div /= 5;
		elseif ($max < $div * 5)
			$div /= 2;
	}
} else
	$div = 1;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">

<form name="form1">
<table width="90%" border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td></td>
		<td>
			<table width="100%" border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td class="m0">
						<select name="year" onchange="document.form1.submit()"><? select_year(2000, '', $year) ?></select>ǯ
						<select name="month" onchange="document.form1.submit()"><? select_month2($month) ?></select>
						�����Կ����
					</td>
					<td align="right">
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
								<td>����Կ���</td>
								<td><table width=20 height=10 border=1 cellspacing=0 cellpadding=0 bgcolor="<?=$color_nyuukai?>" frame="box"><tr><td></td></tr></table></td>
								<td>�����Կ���</td>
								<td><table width=20 height=10 border=1 cellspacing=0 cellpadding=0 bgcolor="<?=$color_taikai?>" frame="box"><tr><td></td></tr></table></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border=0 cellspacing=0 cellpadding=0>
<?
for ($i = 0; $i < 10; $i++) {
?>
				<tr><td align='right' valign='top' height=30 style="padding-right:4"><?=(10 - $i) * $div?></td></tr>
<?
}
?>
			</table>
		</td>
		<td width="100%" valign="top">
			<table height=7 border=0 cellspacing=0 cellpadding=0 frame="box"><tr><td></td></tr></table>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 bgcolor="<?=$color_bg?>" style="border:1 solid black">
				<tr>
					<td width=6>
						<table width="100%" border=0 cellspacing=0 cellpadding=0 frame="box">
<?
for ($i = 0; $i < 10; $i++) {
?>
							<tr><td height=30 style="border-top:1 solid gray"></td></tr>
<?
}
?>
						</table>
					</td>
					<td>
						<table width="100%" border=0 cellspacing=0 cellpadding=0>
							<tr>
<?
for ($i = 1; $i <= $ndata; $i++) {
?>
								<td width="3%" height=<?=$height?> align="center" valign="bottom">
									<table border=0 cellspacing=0 cellpadding=0>
										<tr>
											<td valign="bottom">
												<table width=<?=$bar_width?> height=<?=floor($nyuukai[$i] / ($div * 10) * $height)?> border=1 cellspacing=0 cellpadding=0 frame="box" bgcolor="<?=$color_nyuukai?>"><tr><td style="border-bottom:0"></td></tr></table>
											</td>
											<td valign="bottom">
												<table width=<?=$bar_width?> height=<?=floor($taikai[$i] / ($div * 10) * $height)?> border=1 cellspacing=0 cellpadding=0 frame="box" bgcolor="<?=$color_taikai?>"><tr><td style="border-bottom:0"></td></tr></table>
											</td>
										</tr>
									</table>
								</td>
<?
}
?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%" border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td width=6></td>
					<td>
						<table width="100%" border=0 cellspacing=0 cellpadding=0>
							<tr>
<?
for ($i = 1; $i <= $ndata; $i++) {
?>
								<td width="3%" align="center" height=30><?=$hs[$i]?></td>
<?
}
?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellspacing=0 cellpadding=2>
				<tr><td align="right"><nobr><font size="-1">����</font></nobr></td></tr>
				<tr><td align="right"><nobr><font size="-1">���</font></nobr></td></tr>
			</table>
		</td>
		<td>
			<table width="100%" border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td width=6></td>
					<td>
						<table width="100%" border=1 cellspacing=0 cellpadding=0 frame="box">
							<tr>
<?
for ($i = 1; $i <= $ndata; $i++) {
?>
								<td width="3%" align="center"><font size="-1" color="<?=$color_nyuukai?>"><b><?=(int)$nyuukai[$i]?></b></font></td>
<?
}
?>
							</tr>
							<tr>
<?
for ($i = 1; $i <= $ndata; $i++) {
?>
								<td width="3%" align="center"><font size="-1" color="<?=$color_taikai?>"><b><?=(int)$taikai[$i]?></b></font></td>
<?
}
?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
