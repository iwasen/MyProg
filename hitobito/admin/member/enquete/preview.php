<?php
// フォーム情報取得
$title = $_REQUEST['title'];
$enq_text = $_REQUEST['enq_text'];
$quest_text = $_REQUEST['quest_text'];
$quest_type = $_REQUEST['quest_type'];
$quest_option = $_REQUEST['quest_option'];
$free_flag = $_REQUEST['free_flag'];
$enquete_id = $_REQUEST['enquete_id'];

for ($i = 0; $i < count($quest_text); $i++) {
	if (trim($quest_text[$i]) == '') {
		break;
	} else {
		$option_ary[$i] = $quest_option[$i];
	}
}

$qnum = count($option_ary);

?>
<html lang="ja">
<head>
<title>プレビュー</title>
<link rel="stylesheet" type="text/css" href="../../hitobito.css">
</head>
<table width="580" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3F7DF">
	<tr>
		<td width="1" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td width="15" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="15" height="1"></td>
		<td width="548" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="548" height="1"></td>
		<td width="15" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="15" height="1"></td>
		<td width="1" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td colspan="3"><img src="image/spacer.gif" width="1" height="10"></td>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td colspan="3"><img src="image/spacer.gif" width="1" height="6"></td>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
	</tr>
</table>
<table width="580" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3F7DF">
	<tr>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td><img src="image/spacer.gif" width="15" height="1"></td>
		<td colspan="5" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="548" height="1"></td>
		<td><img src="image/spacer.gif" width="15" height="1"></td>
		<td><img src="image/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
		<td width="1" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td width="15"><img src="image/spacer.gif" width="15" height="1"></td>
		<td width="1" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td width="15" bgcolor="#FFFFFF"><img src="image/spacer.gif" width="15" height="1"></td>
		<td bgcolor="#FFFFFF" width="516" align="center">
			<table width="516" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="image/spacer.gif" width="1" height="15"></td>
				</tr>
				<tr>
					<td bgcolor="#5FCA44" align="center" height="24"> <strong><font color="#FFFFFF" size="4">※※※※※　<?=$title?>　※※※※※</font></strong></td>
				</tr>
				<tr>
					<td><img src="image/spacer.gif" width="1" height="15"></td>
				</tr>
			</table>
			<table width="500" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="35"><img src="image/spacer.gif" width="35" height="1"></td>
					<td width="430" class="char120"><?=nl2br($enq_text)?></td>
					<td width="35"><img src="image/spacer.gif" width="35" height="1"></td>
				</tr>
			</table>
			<br>
			<form method="post">
<? for($i = 0; $i < $qnum; $i++) { ?>
			<table width="500" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left">
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="65" align="right" valign="top" class="char120">Q<?=($i + 1)?>.&nbsp;&nbsp;</td>
								<td class="char120"><?=$quest_text[$i]?></td>
							</tr>
						</table>
					</td>
					<td width="35"><img src="image/spacer.gif" width="35" height="1"></td>
				</tr>
				<tr>
					<td colspan="2"><img src="image/spacer.gif" width="1" height="15"></td>
				</tr>
				<tr>
					<td align="left">
						<table border="0" cellpadding="0" cellspacing="0">
<? if ($quest_type[$i] != 3) { ?><!-- // SA・MA -->
<? foreach(explode("\n", $quest_option[$i]) as $option) { ?>
<? if (trim($option) == '') break; ?>
							<tr>
								<td width="50"><img src="image/spacer.gif" width="65" height="1"></td>
								<td width="22" align="left" valign="middle"><input name="" type="<?=$quest_type[$i] == 1 ? 'radio' : 'checkbox'?>"></td>
								<td class="char120"><?=$option?></td>
							</tr>
							<tr>
								<td colspan="3"><img src="image/spacer.gif" width="1" height="5"></td>
							</tr>
<? } ?>
<? if ($free_flag[$i] == 1) { ?>
						</table>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="50"><img src="image/spacer.gif" width="1" height="1"><img src="image/spacer.gif" width="65" height="1"></td>
								<td align="left" valign="top">
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td width="22" align="left" valign="middle"><input name="" type="<?=$quest_type[$i] == 1 ? 'radio' : 'checkbox'?>"></td>
											<td class="char120">その他</td>
										</tr>
									</table>
								</td>
								<td width="10"><img src="image/spacer.gif" width="10" height="1"></td>
								<td class="char120"><input name="" type="text" size="33"></td>
							</tr>
<? } ?>
<? } ?>

<? if ($quest_type[$i] == 3) { ?><!-- // FA -->
							<tr>
								<td align="left">
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td width="50"><img src="image/spacer.gif" width="65" height="1"></td>
											<td align="left" valign="middle">
											<textarea name="" cols="50" rows="7"></textarea>
											</td>
										</tr>
									</table>
								</td>
								<td width="35"><img src="image/spacer.gif" width="35" height="1"></td>
							</tr>
<? } ?>
						</table>
					</td>
					<td width="35"><img src="image/spacer.gif" width="35" height="1"></td>
				</tr>
				<tr>
					<td colspan="2"><img src="image/spacer.gif" width="1" height="25"></td>
				</tr>
			</table>
<? } ?>
			<table width="500" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><input type="button" value="閉じる" onclick="window.close()"></td>
				</tr>
				<tr>
					<td width="500" colspan="3"><img src="image/spacer.gif" width="1" height="25"></td>
				</tr>
			</table>
			</form>
		</td>
		<td width="15" bgcolor="#FFFFFF"><img src="image/spacer.gif" width="15" height="1"></td>
		<td width="1" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td width="15"><img src="image/spacer.gif" width="15" height="1"></td>
		<td width="1" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td><img src="image/spacer.gif" width="15" height="1"></td>
		<td colspan="5" bgcolor="#CCCCCC"><img src="image/spacer.gif" width="548" height="1"></td>
		<td><img src="image/spacer.gif" width="15" height="1"></td>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
		<td colspan="7"><img src="image/spacer.gif" width="1" height="15"></td>
		<td bgcolor="#CCCCCC"><img src="image/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr bgcolor="#CCCCCC">
		<td colspan="9"><img src="image/spacer.gif" width="550" height="1"></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td colspan="9"><img src="image/spacer.gif" width="1" height="10"></td>
	</tr>
</table>
