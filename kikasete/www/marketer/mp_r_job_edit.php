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
<? marketer_header('My�ѡ��ȥʡ�', PG_NULL) ?>
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
			<!-- my�ѡ��ȥʡ� -->

			<div align="center">
			<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
			<table border=0 cellspacing=2 cellpadding=3 width="80%">
				<tr>
					<td colspan=2>�����󥱡��Ȥ�����</td>
				</tr>
				<tr>
					<td bgcolor="#eff7e8" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check1" onclick="" >�»ܳ���</td>
							</tr>
						</table>
					</td>
				</tr>
<!-- 	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
					<td  bgcolor="#eeeeee" width="15%">��������</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="start_date_y" size="4" maxlength="4" value="2004">ǯ
						<input class="number" type="text" name="start_date_m" size="2" maxlength="2" value="4">��
						<input class="number" type="text" name="start_date_d" size="2" maxlength="2" value="22">��
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">��λ����</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="end_date_y" size="4" maxlength="4" value="2004">ǯ
						<input class="number" type="text" name="end_date_m" size="2" maxlength="2" value="4">��
						<input class="number" type="text" name="end_date_d" size="2" maxlength="2" value="26">��
						<input class="number" type="text" name="end_date_h" size="2" maxlength="2" value="17">��
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">�ݥ���ȿ�</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="point" size="3" maxlength="3" value="10">�ݥ����
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">����ȯ����</td>
					<td bgcolor="#ffeecc">
						<input class="number" type="text" name="send_num" size="3" maxlength="3" value="300">��
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">���󥱡��ȥ����ȥ�</td>
					<td bgcolor="#ffeecc">
						<input class="kanji" type="text" name="title" size="50" maxlength="20" value="�֥���饯�����פ˴ؤ��륢�󥱡���">
					</td>
				</tr>
			</table>
		</td>
	</tr>
 -->
				<tr>
					<td bgcolor="#eff7e8">
						<input checked type="checkbox" name="check1" onclick="" >���������
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td  bgcolor="#eeeeee" width="15%" rowspan="3">�ѣ�</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">���ˤ����륭��饯�����Τ��������ʤ�����¸�ΤΥ���饯�����Ϥɤ�Ǥ������ΤäƤ��������Ƥ˥����å��򤷤Ƥ���������</textarea>
									<div class="small">�������λ���<br>
									<input type="radio" checked>�ʤ�<br>
									<input type="radio">URL����ꤹ�� ��<input type="text" name="" size="30"><br>
									<input type="radio">�����������ࡡ<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>ñ������(SA)
									<option value='2' selected>ʣ������(MA)
									<option value='4' >�ώĎ؎���(SA)
									<option value='5' >�ώĎ؎���(MA)
									<option value='6'>��ͳ����(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<strong>������20�Ĥޤ�)</strong><br>
									<textarea name="" cols="50" rows="10"></textarea>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td  bgcolor="#eeeeee" width="15%" rowspan="3">�ѣ�</td>
								<td bgcolor="#ffeecc">
									<textarea rows="3" cols="70" name="question_text1">�ʲ��β����Υ���饯�����Ϥ�¸�ΤǤ�����</textarea>
									<div class="small">�������λ���<br>
									<input type="radio" checked>�ʤ�<br>
									<input type="radio">URL����ꤹ�� ��<input type="text" name="" size="30"><br>
									<input type="radio">�����������ࡡ<input type="file" name="" size="30">
									</div>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type1">
									<option value='1'>ñ������(SA)
									<option value='2' >ʣ������(MA)
									<option value='4' >�ώĎ؎���(SA)
									<option value='5' selected>�ώĎ؎���(MA)
									<option value='6'>��ͳ����(FA)
									</select>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td width="50%"><strong>ɽ¦��10�Ĥޤǡ�</strong><br>
											<textarea name="" cols="32" rows="10"></textarea></td>
											<td width="50%"><strong>ɽƬ��10�Ĥޤǡ�</strong><br>
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
																	<td width="50%" align="center">ɽƬ</td>
																</tr>
																<tr>
																	<td align="center">ɽ¦</td>
																	<td>
																	</td>
																</tr>
															</table>
														</td>
														<td>
															<input type="button" name="" value="�ץ�ӥ塼" onclick="onclick_pre_matrix()">
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�����ν�ʣ������ʤ�</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#ffeecc">
									<input type="button" value="��������ɲá�">
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td bgcolor="#eff7e8">
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check1" onclick="" >��������</td>
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
					<td  bgcolor="#eeeeee" width="15%">�оݥ�˥���</td>
					<td width="85%" bgcolor="#ffeecc">
						<input type="radio" name="" value='1'>�ꥵ�����ѡ��ȥʡ��Τ�<br>
						<input type="radio" name="" value='2'>�ꥵ�����ѡ��ȥʡ�ͥ��<br>
						<input type="radio" name="" value='3'>�̾�Υ�˥���ͥ��<br>
						<input type="radio" name="" value='4'>�̾�Υ�˥����Τ�<br>
						<input type="radio" name="" value='5' checked>����
				</td>
				</tr>

				<tr>
					<td  bgcolor="#eeeeee" width="15%">����</td>
					<td width="85%" bgcolor="#ffeecc">
						<input type="checkbox" name="sex[]" value='1'>����&nbsp;
						<input type="checkbox" name="sex[]" value='2'>����
				</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">ǯ��</td>
					<td bgcolor="#ffeecc">
						<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='1'><font size=2 class='honbun2'>������̤��</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='2'><font size=2 class='honbun2'>��������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='3'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='4'><font size=2 class='honbun2'>��������Ⱦ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='5'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='6'><font size=2 class='honbun2'>��������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='7'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='8'><font size=2 class='honbun2'>��������Ⱦ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='9'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='10'><font size=2 class='honbun2'>��������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='11'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='12'><font size=2 class='honbun2'>��������Ⱦ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='13'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='14'><font size=2 class='honbun2'>�����Ͱʾ�</font></td>
<tr>
<td colspan="2"> Ǥ�դ�ǯ��������ǽ�ʣͣ����󥱡��Ȥν�����ȿ��</td>
</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td  bgcolor="#eeeeee">̤����</td>
					<td bgcolor="#ffeecc">
						<input type="checkbox" name="mikikon[]" value='1'>�ȿ�&nbsp;
						<input type="checkbox" name="mikikon[]" value='2'>����
					</td>
				</tr>
				<tr>
				<td  bgcolor="#eeeeee">�ｻ�ϰ�</td>
				<td bgcolor="#ffeecc">
					<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='1'><font size=2 class='honbun2'>�̳�ƻ</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='2'><font size=2 class='honbun2'>�Ŀ���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='3'><font size=2 class='honbun2'>��긩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='4'><font size=2 class='honbun2'>�ܾ븩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='5'><font size=2 class='honbun2'>���ĸ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='6'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='7'><font size=2 class='honbun2'>ʡ�縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='8'><font size=2 class='honbun2'>��븩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='9'><font size=2 class='honbun2'>���ڸ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='10'><font size=2 class='honbun2'>���ϸ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='11'><font size=2 class='honbun2'>��̸�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='12'><font size=2 class='honbun2'>���ո�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='13'><font size=2 class='honbun2'>�����</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='14'><font size=2 class='honbun2'>�����</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='15'><font size=2 class='honbun2'>���㸩</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='16'><font size=2 class='honbun2'>�ٻ���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='17'><font size=2 class='honbun2'>���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='18'><font size=2 class='honbun2'>ʡ�温</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='19'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='20'><font size=2 class='honbun2'>Ĺ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='21'><font size=2 class='honbun2'>���츩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='22'><font size=2 class='honbun2'>�Ų���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='23'><font size=2 class='honbun2'>���θ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='24'><font size=2 class='honbun2'>���Ÿ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='25'><font size=2 class='honbun2'>���츩</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='26'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='27'><font size=2 class='honbun2'>�����</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='28'><font size=2 class='honbun2'>ʼ�˸�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='29'><font size=2 class='honbun2'>���ɸ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='30'><font size=2 class='honbun2'>�²λ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='31'><font size=2 class='honbun2'>Ļ�踩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='32'><font size=2 class='honbun2'>�纬��</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='33'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='34'><font size=2 class='honbun2'>���縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='35'><font size=2 class='honbun2'>������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='36'><font size=2 class='honbun2'>���縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='37'><font size=2 class='honbun2'>���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='38'><font size=2 class='honbun2'>��ɲ��</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='39'><font size=2 class='honbun2'>���θ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='40'><font size=2 class='honbun2'>ʡ����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='41'><font size=2 class='honbun2'>���츩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='42'><font size=2 class='honbun2'>Ĺ�긩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='43'><font size=2 class='honbun2'>���ܸ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='44'><font size=2 class='honbun2'>��ʬ��</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='45'><font size=2 class='honbun2'>�ܺ긩</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='46'><font size=2 class='honbun2'>�����縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='47'><font size=2 class='honbun2'>���츩</font></td>
</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td  bgcolor="#eeeeee">����</td>
				<td bgcolor="#ffeecc">
					<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='1'><font size=2 class='honbun2'>��Ұ�</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='2'><font size=2 class='honbun2'>��ҷбġ����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='3'><font size=2 class='honbun2'>��̳��</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='4'><font size=2 class='honbun2'>���Ķ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='5'><font size=2 class='honbun2'>�ѡ��ȡ�����Х���</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='6'><font size=2 class='honbun2'>���翦���۸�Ρ���ա���׻�����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='7'><font size=2 class='honbun2'>���翦��SOHO���ǥ����ʡ������ꥨ����������</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='8'><font size=2 class='honbun2'>����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='9'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='10'><font size=2 class='honbun2'>��ȼ��ء�����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='11'><font size=2 class='honbun2'>̵��</font></td>
</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td  bgcolor="#eeeeee">����ӥ�</td>
				<td bgcolor="#ffeecc">
					<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='1'><font size=2 class='honbun2'>���֥󥤥�֥�</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='2'><font size=2 class='honbun2'>������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='3'><font size=2 class='honbun2'>�ե��ߥ꡼�ޡ���</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='4'><font size=2 class='honbun2'>���󥯥�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='5'><font size=2 class='honbun2'>�ߥ˥��ȥå�</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='6'><font size=2 class='honbun2'>am.pm.</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='7'><font size=2 class='honbun2'>��ޥ���</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='8'><font size=2 class='honbun2'>���������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='9'><font size=2 class='honbun2'>���꡼����</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='10'><font size=2 class='honbun2'>����¾</font></td>
</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td  bgcolor="#eeeeee">�����ѡ�</td>
				<td bgcolor="#ffeecc">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='1'><font size=2 class='honbun2'>���ȡ��衼���ɡ�</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='2'><font size=2 class='honbun2'>��������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='3'><font size=2 class='honbun2'>���㥹��</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='4'><font size=2 class='honbun2'>��ͧ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='5'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='6'><font size=2 class='honbun2'>�ޥ�����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='7'><font size=2 class='honbun2'>�ޥ륨��</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='8'><font size=2 class='honbun2'>��ޥ��ȥ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='9'><font size=2 class='honbun2'>��ˡ�</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='10'><font size=2 class='honbun2'>����¾</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">��̣������</td>
		<td bgcolor="#ffeecc">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='1'><font size=2 class='honbun2'>����ᡦ����</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='2'><font size=2 class='honbun2'>����ʪ�ʤ���ʳ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='3'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='4'><font size=2 class='honbun2'>�ե��å����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='5'><font size=2 class='honbun2'>�ӥ��ͥ�</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='6'><font size=2 class='honbun2'>��������ߡ�����ݸ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='7'><font size=2 class='honbun2'>�ع������</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='8'><font size=2 class='honbun2'>���󥿡��ƥ������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='18'><font size=2 class='honbun2'>ι�ԡ��쥸�㡼</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='9'><font size=2 class='honbun2'>����ԥ塼��</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='10'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='11'><font size=2 class='honbun2'>���ݡ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='12'><font size=2 class='honbun2'>��</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='13'><font size=2 class='honbun2'>��ʡ��</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='14'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='15'><font size=2 class='honbun2'>����ƥꥢ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='16'><font size=2 class='honbun2'>��ư�֡��Х���</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='17'><font size=2 class='honbun2'>�����ʡ�������</font></td>
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
					<td><input checked type="checkbox" name="check1" onclick="" >�ɲþ�������</td>
					<td>
						<input type="radio" name="" value='1'>and���
						<input type="radio" name="" value='2' checked>or���
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
				<tr>
	<td  bgcolor="#eeeeee" width="15%">��ļ�</td>
	<td width='85%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>���ȡݥ衼���ɡ���ļ�</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>����ӥ˲�ļ�</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�����ʲ�ļ�</font>
	</td>
</tr>

<tr>
	<td  bgcolor="#eeeeee" width="15%">�ͣ���˥���</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�Ȥ˥��ѥ����򣳣�����ʾ她�ȥå����Ƥ����</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�����аʾ�ν����ǡ������ںڤ���äƤ����</font>
	</td>
</tr>

<tr>
	<td  bgcolor="#eeeeee">�����ͣ���˥���</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�Ȥ˥��ѥ����򣳣�����ʾ她�ȥå����Ƥ����</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�����аʾ�ν����ǡ������ںڤ���äƤ����</font>
	</td>
</tr>
<tr>
	<td  bgcolor="#eeeeee">���ͣ��ѡ��ȥʡ�</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>������ļ�</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�ߡ߲�ļ�</font>
	</td>
</tr>

<tr>
	<td  bgcolor="#eeeeee">�����ͣ��ѡ��ȥʡ�</td>
	<td width='10%'>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�ߡ߲�ļ�</font><br>
		<input type='checkbox' name='' value=''><font size=2 class='honbun2'>�ߡ߲�ļ�</font>
	</td>
</tr>
</table>
</td>

</tr> -->

				<tr>
					<td bgcolor="#eff7e8" colspan=2>�����󥱡��ȣףţ²��̤ȥ᡼��ʸ�Υ������ޥ���</td>
				</tr>
				<tr>
					<td class="m1" colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee"  width="30%">���󥱡��ȣףţ²��̤Υ������ޥ���</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>���ʤ����������Ƥ��鼫ưŪ�ˣףţ²��̤���ޤ���<br>
									<input type="radio" name="" value='2' checked>���롡�����ꤵ�줿�ףţ²��̤ˡ�������ä��������Ϥ���������Ӥ���������
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td  colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td bgcolor="#eeeeee" width="30%">�᡼��ʸ�Υ������ޥ���</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="" value='1'>���ʤ����������Ƥ��鼫ưŪ�˥᡼��ʸ����ޤ���<br>
									<input type="radio" name="" value='2' checked>���롡�����ꤵ�줿�᡼��ʸ�ˡ�������ä��������Ϥ���������Ӥ���������
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<br>
			<input type="button" value="�ףţ²��̤μ�ư����" onclick="location.href='edit_web.php'">
			<input type="button" value="�᡼��ʸ�̤μ�ư����" onclick="location.href='edit_mail.php'">
			<input type="button" value="�ףţ¥ץ�ӥ塼" onclick="location.href='preveiw_web.php'">
			<input type="button" value="�᡼��ץ�ӥ塼" onclick="location.href='preveiw_mail.php'">
			<input type="button" value="��������" onclick="">
			<input type="button" value="����롡" onclick="history.back()">

			<input type="hidden" name="next_action">
			<input type="hidden" name="marketer_id" value="1047">
			<input type="hidden" name="my_enq_no" value="2">
			</form>
			</div>
			<!-- my�ѡ��ȥʡ� -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
