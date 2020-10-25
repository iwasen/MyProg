<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// セッション処理
if ($my_enq_no) {
	$staff_id = $_SESSION['ss_staff_id'];
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($staff_id, $my_enq_no);
} else
	redirect('myenq.php');

$enquete = &$myenq->enquete;
$search = &$myenq->search;
?>
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪作成したアンケートの詳細≫</font></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<br>
			<form method="" action="myenq_adj.php">
			<table width="680" border="1" cellspacing="0" cellpadding="3" bordercolor="#ffffff" bordercolorlight="#ffffff" bordercolordark="#ffffff">
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center"><font size="2" class="honbun2">概要設定内容</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">アンケート種類</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_enq_type($enquete->enq_type)?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">開始日時</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$enquete->start_date_y?>年<?=$enquete->start_date_m?>月<?=$enquete->start_date_d?>日</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">終了日時</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$enquete->end_date_y?>年<?=$enquete->end_date_m?>月<?=$enquete->end_date_d?>日　<?=$enquete->end_date_h?>時</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">ポイント数</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$enquete->point?>ポイント</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">最大発信数</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=$myenq->send_num?>人</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#eff7e8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center"><font size="2" class="honbun2">条件設定内容</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">性別</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_sex($search->sex, '<br>')?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">年代</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">未既婚</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_mikikon($search->mikikon, '<br>')?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">子供の有無</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><?=decode_child($search->child, '<br>')?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">居住地域</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_area($search->jitaku_area) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">職業</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_shokugyou($search->shokugyou) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="150" valign="top" align="left" bgcolor="#eeeeee">
						<table width="140" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2">チェーン</font></td>
							</tr>
						</table>
					</td>
					<td width="530" align="right" bgcolor="#ffeecc">
						<table width="520" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font size="2" class="honbun2"><? disp_chain($search->chain) ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#dcfafd">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="50%" align="left"><font size="2" class="honbun2">メールプレビュー</font></td>
								<td width="25%" align="right">
									<input type="button" value="　メールプレビュー　" onclick="location.href='myenq_mailprev.php?my_enq_no=<?=$my_enq_no?>'">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#dcfafd">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="50%" align="left"><font size="2" class="honbun2">WEBプレビュー</font></td>
								<td width="50%" align="right">
									<input type="button" value="　WEBプレビュー　" onclick="location.href='myenq_webprev.php?my_enq_no=<?=$my_enq_no?>'">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2" bgcolor="#dcfafd">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="50%" align="left"><font size="2" class="honbun2">対象人数検索</font></td>
								<td width="50%" align="right">
									<input type="button" value="　対象人数検索　" onclick="location.href='myenq_matest2.php?my_enq_no=<?=$my_enq_no?>'">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<table width="700" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td width="100%" align="center">
						<input type="button" value="　戻　る　" onclick="history.back()">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<? staff_footer() ?>
