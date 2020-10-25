<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->

			<div align="center">
			<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
			<table border=0 cellspacing=2 cellpadding=3 width="80%">
				<tr>
					<td colspan=2>■アンケートの設定</td>
				</tr>
				<tr>
					<td bgcolor="#eff7e8" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check1" onclick="" >実施概要</td>
							</tr>
						</table>
					</td>
				</tr>
<!-- 	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td  bgcolor="#eeeeee" width="15%">開始日時</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="start_date_y" size="4" maxlength="4" value="2004">年
						<input class="number" type="text" name="start_date_m" size="2" maxlength="2" value="4">月
						<input class="number" type="text" name="start_date_d" size="2" maxlength="2" value="22">日
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">終了日時</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="end_date_y" size="4" maxlength="4" value="2004">年
						<input class="number" type="text" name="end_date_m" size="2" maxlength="2" value="4">月
						<input class="number" type="text" name="end_date_d" size="2" maxlength="2" value="26">日
						<input class="number" type="text" name="end_date_h" size="2" maxlength="2" value="17">時
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">ポイント数</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="point" size="3" maxlength="3" value="10">ポイント
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">最大発信数</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="send_num" size="3" maxlength="3" value="300">人
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">アンケートタイトル</td>
					<td bgcolor="#ffeecc">
						<input class="kanji" type="text" name="title" size="50" maxlength="20" value="「キャラクター」に関するアンケート">
					</td>
				</tr>
			</table>
		</td>
	</tr>
 -->
				<tr>
					<td bgcolor="#eff7e8">
						<input checked type="checkbox" name="check1" onclick="" >設問の設定
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td  bgcolor="#eeeeee" width="15%" rowspan="3">Ｑ１</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">次にあげるキャラクターのうち、あなたがご存知のキャラクターはどれですか？知っているもの全てにチェックをしてください。</textarea>
									<div class="small">■画像の指定<br>
									<input type="radio" checked>なし<br>
									<input type="radio">URLを指定する 　<input type="text" name="" size="30"><br>
									<input type="radio">画像を埋め込む　<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>単一選択(SA)
									<option value='2' selected>複数選択(MA)
									<option value='4' >ﾏﾄﾘｸｽ(SA)
									<option value='5' >ﾏﾄﾘｸｽ(MA)
									<option value='6'>自由回答(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<strong>選択肢（20個まで)</strong><br>
									<textarea name="" cols="50" rows="10"></textarea>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>最後の選択肢でフリー回答を取得</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td  bgcolor="#eeeeee" width="15%" rowspan="3">Ｑ２</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">以下の画像のキャラクターはご存知ですか？</textarea>
									<div class="small">■画像の指定<br>
									<input type="radio" checked>なし<br>
									<input type="radio">URLを指定する 　<input type="text" name="" size="30"><br>
									<input type="radio">画像を埋め込む　<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>単一選択(SA)
									<option value='2' >複数選択(MA)
									<option value='4' >ﾏﾄﾘｸｽ(SA)
									<option value='5' selected>ﾏﾄﾘｸｽ(MA)
									<option value='6'>自由回答(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td width="50%"><strong>表側（10個まで）</strong><br>
											<textarea name="" cols="32" rows="10"></textarea></td>
											<td width="50%"><strong>表頭（10個まで）</strong><br>
											<textarea name="" cols="32" rows="10"></textarea></td>
										</tr>
										<tr>
											<td align="left">
												<table>
													<tr>
														<td>
															<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
																<tr>
																	<td width="50%" align="center"></td>
																	<td width="50%" align="center">表頭</td>
																</tr>
																<tr>
																	<td align="center">表側</td>
																	<td>
																	</td>
																</tr>
															</table>
														</td>
														<td>
															<input type="button" name="" value="プレビュー" onclick="onclick_pre_matrix()">
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>回答の重複を許さない</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#ffeecc">
									<input type="button" value="　質問を追加　">
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td bgcolor="#eff7e8">
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check1" onclick="" >条件の設定</td>
							</tr>
						</table>
					</td>
				</tr>
<!--
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
<!--
				<tr>
					<td  bgcolor="#eeeeee" width="15%">対象モニター</td>
					<td width="85%" bgcolor="#ffeecc">
						<input type="radio" name="" value='1'>リサーチパートナーのみ<br>
						<input type="radio" name="" value='2'>リサーチパートナー優先<br>
						<input type="radio" name="" value='3'>通常のモニター優先<br>
						<input type="radio" name="" value='4'>通常のモニターのみ<br>
						<input type="radio" name="" value='5' checked>全て
				</td>
				</tr>

				<tr>
					<td  bgcolor="#eeeeee" width="15%">性別</td>
					<td width="85%" bgcolor="#ffeecc">
						<input type="checkbox" name="sex[]" value='1'>男性&nbsp;
						<input type="checkbox" name="sex[]" value='2'>女性
				</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">年代</td>
					<td bgcolor="#ffeecc">
						<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='1'><font size=2 class='honbun2'>１０才未満</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='2'><font size=2 class='honbun2'>１０代前半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='3'><font size=2 class='honbun2'>１０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='4'><font size=2 class='honbun2'>２０代前半</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='5'><font size=2 class='honbun2'>２０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='6'><font size=2 class='honbun2'>３０代前半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='7'><font size=2 class='honbun2'>３０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='8'><font size=2 class='honbun2'>４０代前半</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='9'><font size=2 class='honbun2'>４０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='10'><font size=2 class='honbun2'>５０代前半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='11'><font size=2 class='honbun2'>５０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='12'><font size=2 class='honbun2'>６０代前半</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='13'><font size=2 class='honbun2'>６０代後半</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='14'><font size=2 class='honbun2'>７０才以上</font></td>
<tr>
<td colspan="2"> 任意の年代設定も可能（Ｍｙアンケートの修正を反映</td>
</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">未既婚</td>
					<td bgcolor="#ffeecc">
						<input type="checkbox" name="mikikon[]" value='1'>独身&nbsp;
						<input type="checkbox" name="mikikon[]" value='2'>既婚
					</td>
				</tr>
				<tr>
				<td  bgcolor="#eeeeee">居住地域</td>
				<td bgcolor="#ffeecc">
					<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='1'><font size=2 class='honbun2'>北海道</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='2'><font size=2 class='honbun2'>青森県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='3'><font size=2 class='honbun2'>岩手県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='4'><font size=2 class='honbun2'>宮城県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='5'><font size=2 class='honbun2'>秋田県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='6'><font size=2 class='honbun2'>山形県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='7'><font size=2 class='honbun2'>福島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='8'><font size=2 class='honbun2'>茨城県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='9'><font size=2 class='honbun2'>栃木県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='10'><font size=2 class='honbun2'>群馬県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='11'><font size=2 class='honbun2'>埼玉県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='12'><font size=2 class='honbun2'>千葉県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='13'><font size=2 class='honbun2'>東京都</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='14'><font size=2 class='honbun2'>神奈川県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='15'><font size=2 class='honbun2'>新潟県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='16'><font size=2 class='honbun2'>富山県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='17'><font size=2 class='honbun2'>石川県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='18'><font size=2 class='honbun2'>福井県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='19'><font size=2 class='honbun2'>山梨県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='20'><font size=2 class='honbun2'>長野県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='21'><font size=2 class='honbun2'>岐阜県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='22'><font size=2 class='honbun2'>静岡県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='23'><font size=2 class='honbun2'>愛知県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='24'><font size=2 class='honbun2'>三重県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='25'><font size=2 class='honbun2'>滋賀県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='26'><font size=2 class='honbun2'>京都府</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='27'><font size=2 class='honbun2'>大阪府</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='28'><font size=2 class='honbun2'>兵庫県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='29'><font size=2 class='honbun2'>奈良県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='30'><font size=2 class='honbun2'>和歌山県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='31'><font size=2 class='honbun2'>鳥取県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='32'><font size=2 class='honbun2'>島根県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='33'><font size=2 class='honbun2'>岡山県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='34'><font size=2 class='honbun2'>広島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='35'><font size=2 class='honbun2'>山口県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='36'><font size=2 class='honbun2'>徳島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='37'><font size=2 class='honbun2'>香川県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='38'><font size=2 class='honbun2'>愛媛県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='39'><font size=2 class='honbun2'>高知県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='40'><font size=2 class='honbun2'>福岡県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='41'><font size=2 class='honbun2'>佐賀県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='42'><font size=2 class='honbun2'>長崎県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='43'><font size=2 class='honbun2'>熊本県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='44'><font size=2 class='honbun2'>大分県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='45'><font size=2 class='honbun2'>宮崎県</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='46'><font size=2 class='honbun2'>鹿児島県</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='47'><font size=2 class='honbun2'>沖縄県</font></td>
</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td  bgcolor="#eeeeee">職業</td>
				<td bgcolor="#ffeecc">
					<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='1'><font size=2 class='honbun2'>会社員</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='2'><font size=2 class='honbun2'>会社経営・役員</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='3'><font size=2 class='honbun2'>公務員</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='4'><font size=2 class='honbun2'>自営業</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='5'><font size=2 class='honbun2'>パート・アルバイト</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='6'><font size=2 class='honbun2'>専門職（弁護士・医師・会計士等）</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='7'><font size=2 class='honbun2'>専門職（SOHO・デザイナー・クリエイター等）</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='8'><font size=2 class='honbun2'>教職</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='9'><font size=2 class='honbun2'>学生</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='10'><font size=2 class='honbun2'>専業主婦・主夫</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='11'><font size=2 class='honbun2'>無職</font></td>
</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td  bgcolor="#eeeeee">コンビニ</td>
				<td bgcolor="#ffeecc">
					<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='1'><font size=2 class='honbun2'>セブンイレブン</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='2'><font size=2 class='honbun2'>ローソン</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='3'><font size=2 class='honbun2'>ファミリーマート</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='4'><font size=2 class='honbun2'>サンクス</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='5'><font size=2 class='honbun2'>ミニストップ</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='6'><font size=2 class='honbun2'>am.pm.</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='7'><font size=2 class='honbun2'>ヤマザキ</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='8'><font size=2 class='honbun2'>サークルＫ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='9'><font size=2 class='honbun2'>スリーエフ</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='10'><font size=2 class='honbun2'>その他</font></td>
</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td  bgcolor="#eeeeee">スーパー</td>
				<td bgcolor="#ffeecc">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='1'><font size=2 class='honbun2'>イトーヨーカドー</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='2'><font size=2 class='honbun2'>ダイエー</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='3'><font size=2 class='honbun2'>ジャスコ</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='4'><font size=2 class='honbun2'>西友</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='5'><font size=2 class='honbun2'>生協</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='6'><font size=2 class='honbun2'>マイカル</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='7'><font size=2 class='honbun2'>マルエツ</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='8'><font size=2 class='honbun2'>東急ストア</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='9'><font size=2 class='honbun2'>ユニー</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='10'><font size=2 class='honbun2'>その他</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">興味ジャンル</td>
		<td bgcolor="#ffeecc">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='1'><font size=2 class='honbun2'>グルメ・料理</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='2'><font size=2 class='honbun2'>飲み物（お酒以外）</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='3'><font size=2 class='honbun2'>お酒</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='4'><font size=2 class='honbun2'>ファッション</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='5'><font size=2 class='honbun2'>ビジネス</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='6'><font size=2 class='honbun2'>お金（貯蓄・投資・保険）</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='7'><font size=2 class='honbun2'>学校・資格</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='8'><font size=2 class='honbun2'>エンターテインメント</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='18'><font size=2 class='honbun2'>旅行・レジャー</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='9'><font size=2 class='honbun2'>コンピュータ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='10'><font size=2 class='honbun2'>アート</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='11'><font size=2 class='honbun2'>スポーツ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='12'><font size=2 class='honbun2'>健康</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='13'><font size=2 class='honbun2'>介護・福祉</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='14'><font size=2 class='honbun2'>住宅</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='15'><font size=2 class='honbun2'>インテリア</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='16'><font size=2 class='honbun2'>自動車・バイク</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='17'><font size=2 class='honbun2'>新製品・新商品</font></td>
</tr>
</table>
		</td>
	</tr>
</table>

<!--
<table>
	<tr>
		<td class="m1">
			<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td><input checked type="checkbox" name="check1" onclick="" >追加条件の設定</td>
					<td>
						<input type="radio" name="" value='1'>and条件
						<input type="radio" name="" value='2' checked>or条件
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
	<td  bgcolor="#eeeeee" width="15%">会議室</td>
	<td width='85%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>イト−ヨーカドー会議室</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>コンビニ会議室</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>新商品会議室</font>
	</td>
</tr>

<tr>
	<td  bgcolor="#eeeeee" width="15%">Ｍｙモニター</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>家にスパイスを３０種類以上ストックしている人</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>５０歳以上の女性で、毎日惣菜を買っている人</font>
	</td>
</tr>

<tr>
	<td  bgcolor="#eeeeee">除外Ｍｙモニター</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>家にスパイスを３０種類以上ストックしている人</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>５０歳以上の女性で、毎日惣菜を買っている人</font>
	</td>
</tr>
<tr>
	<td  bgcolor="#eeeeee">過去Ｍｙパートナー</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>○○会議室</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>××会議室</font>
	</td>
</tr>

<tr>
	<td  bgcolor="#eeeeee">除外Ｍｙパートナー</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>××会議室</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>××会議室</font>
	</td>
</tr>
</table>
</td>

</tr> -->

				<tr>
					<td bgcolor="#eff7e8" colspan=2>■アンケートＷＥＢ画面とメール文のカスタマイズ</td>
				</tr>
				<tr>
					<td class="m1" colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee"  width="30%">アンケートＷＥＢ画面のカスタマイズ</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>しない：設定内容から自動的にＷＥＢ画面を作ります。<br>
									<input type="radio" name="" value='2' checked>する　：設定されたＷＥＢ画面に、修正を加えたい場合はこちらをお選びください。
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td  colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee" width="30%">メール文のカスタマイズ</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>しない：設定内容から自動的にメール文を作ります。<br>
									<input type="radio" name="" value='2' checked>する　：設定されたメール文に、修正を加えたい場合はこちらをお選びください。
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<br>
			<input type="button" value="ＷＥＢ画面の手動修正" onclick="location.href='edit_web.php'">
			<input type="button" value="メール文面の手動修正" onclick="location.href='edit_mail.php'">
			<input type="button" value="ＷＥＢプレビュー" onclick="location.href='preveiw_web.php'">
			<input type="button" value="メールプレビュー" onclick="location.href='preveiw_mail.php'">
			<input type="button" value="　修正　" onclick="">
			<input type="button" value="　戻る　" onclick="history.back()">

			<input type="hidden" name="next_action">
			<input type="hidden" name="marketer_id" value="1047">
			<input type="hidden" name="my_enq_no" value="2">
			</form>
			</div>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
