//**********************************************************************
//		MCDB:�V��:����BOOK TIMES �A���P�[�g���W�V�X�e��
//
//		Title:		�o�X�P�b�g�V�X�e����{�N���X
//		FileName:	input01.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/19
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.util.StringTokenizer;
import java.util.Date;
import java.util.GregorianCalendar;
import java.sql.Statement;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.HttpSession;

public class input01 extends BaseServlet {

	// "GET"���\�b�h����
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ContentType�ݒ肨���PrintWriter�擾
			PrintWriter pw = GetPrintWriter(response);

			Html(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// "GET"���\�b�hhtml�o��
	public void Html(PrintWriter pw) 
			throws ServletException, IOException, SQLException {

		pw.println("<HTML>");
		pw.println("<HEAD>");
		pw.println("<TITLE>BOOK TIMES�A���P�[�g</TITLE>");
		Script(pw);
		pw.println("</HEAD>");
		pw.println("<BODY BGCOLOR='#ffffff' TEXT='042255' LEFTMARGIN='0' TOPMARGIN='5' MARGINWIDTH='0' MARGINHEIGHT='5'>");
		pw.println("<CENTER>");
		pw.println("<FORM method='post' name='form1'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=10 CELLPADDING=0 width='600'>");
		pw.println("<tr><td align=center><IMG SRC='/img/ttle01.gif' width=600 height=42 alt='BOOK TIMES 5'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td>");
		pw.println("�A���P�[�g�ɂ����͂��������A���肪�Ƃ��������܂��B<br>");
		pw.println("<b>���̃y�[�W�́����V���uBOOK TIMES�@�T�����v�̃A���P�[�g��t�t�H�[���ł��B</b><br>");
		pw.println("���L�A���P�[�g�ɂ����������A<b>�U���P�U���i���j�܂ł�</b>���M���ĉ������B");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</TABLE>");
		pw.println("<br>");
		pw.println("<!---------�A���P�[�g�ɂ������ɂȂ���ɂ���------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR><TD BGCOLOR='ffffff'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR>");
		pw.println("<TD colspan=2 BGCOLOR='4288FF'>");
		pw.println("<font size=+1 color='#ffffff'><b>�� �v���[���g����]�̏��Ж����������������B�i���]�ŏЉ�ꂽ���Ђ̒����炨�I�щ������B�j</b></font>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='BFD7FF' colspan=2 align=center>");
		pw.println("<INPUT TYPE=Text name='present' SIZE='50'><br>���I�łQ�P���l�Ƀv���[���g");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<TR>");
		pw.println("<TD width='5%'>");
		pw.println("<IMG SRC='/img/d.gif'>");
		pw.println("</td>");
		pw.println("<td width='95%' BGCOLOR='FFffff'>");
		pw.println("<br><font size=+1><b>Q1�F�A���P�[�g�ɂ������ɂȂ���ɂ��ċ����Ă��������B</b></font><br>");
		pw.println("*��͕K�{���ڂł��B");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</TABLE>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>�����O*�i�S�p�j</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>��<INPUT TYPE=Text name='shimeisei' SIZE='20'>�@��<INPUT TYPE=Text name='shimeimei' SIZE='20'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�t���K�i�i�S�p�J�i�j</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>��<INPUT TYPE=Text name='furiganasei' SIZE='20'>�@��<INPUT TYPE=Text name='furiganamei' SIZE='20'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>���[���A�h���X*�i���p�j</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'><INPUT TYPE=Text name='mailaddr' SIZE='50'><br>");
		pw.println("<nobr><input type='radio' name='mailkind' value='0'>�d���p�@</nobr>");
		pw.println("<nobr><input type='radio' name='mailkind' value='1'>����p�i�v���C�x�[�g�j�@</nobr>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>���Z��*�i�ܕi���t��j</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>");
		pw.println("��<input type=text size='3' name='zip1'>-<input type=text size='4' name='zip2'>�i�����͔��p�j<br>");
		TodouhukenSelect(pw, "todoufuken", "", "�s���{����", null);
		pw.println("<input type='text' name='address' size='40'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#FFFf00' size=+0><b>�d�b�ԍ�*�i���p�j</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'><INPUT TYPE=Text name='tel1' SIZE='5'>-<INPUT TYPE=Text name='tel2' SIZE='5'>-<INPUT TYPE=Text name='tel3' SIZE='5'></TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>����</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'><input type='radio' name='seibetsu' value='0'>�j���@<input type='radio' name='seibetsu' value='1'>����</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>���N�����i���p�j</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>�P�X<INPUT TYPE=Text name='seinen' SIZE='2' maxlength=2>�N<INPUT TYPE=Text name='seitsuki' SIZE='2' maxlength=2>��<INPUT TYPE=Text name='sexxx' SIZE='2' maxlength=2>��</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�E��</b></font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>");
		ShokugyouSelect(pw, "shokugyou", "", null, null);
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD NOWRAP BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>���A�������邱��</b><br>�i�����񓚉j</font>");
		pw.println("</TD>");
		pw.println("<TD BGCOLOR='BFD7FF'>");
		pw.println("�����y��<br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='0'>J-POP�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='1'>�m�yROCK&POPS�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='2'>�m�yDANCE&SOUL�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='3'>�W���Y�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='4'>�N���V�b�N�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='5'>���́E�̗w�ȁ@</nobr><br><br>");
		pw.println("���X�|�[�c��<br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='6'>�싅�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='7'>�T�b�J�[�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='8'>�S���t�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='9'>�e�j�X�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='10'>����@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='11'>�}�����X�|�[�c�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='12'>�E�B���^�[�X�|�[�c�i�X�L�[���j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='13'>���o�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='14'>�v�����X�E�i���Z�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='15'>���[�^�[�X�|�[�c�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='16'>�X�|�[�c�F���̑��@</nobr><br><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='17'>�f��@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='18'>�A�[�g�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='19'>���w�E���|�i�Ǐ��j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='20'>�}���K�E�A�j���@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='21'>�p�\�R���@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='22'>�Q�[���@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='23'>�ԁE�I�[�g�o�C�@</nobr><br><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='24'>�������s�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='25'>�C�O���s�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='26'>�h���C�u�E�c�[�����O�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='27'>�L�����v�E�A�E�g�h�A�@</nobr><br><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='28'>���e�N�E�}�l�[�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='29'>���N�E�t�B�b�g�l�X�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='30'>�y�b�g�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='31'>�����E�O�����@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='32'>�t�@�b�V�����@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='33'>�V���b�s���O�@</nobr><br>");
		pw.println("<nobr><input type='checkbox' name='q1check' value='34'>���̑�<font size=-0>����̓I�ɂ��L�����������B</font>�i<input type=text name='q1text' size='10'>�j</nobr>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("<!---------�^�A���P�[�g�ɂ������ɂȂ���ɂ���------------->");
		pw.println("<br>");
		pw.println("<!---------Q2�FBOOK TIMES���ǂ��ǂ܂ꂽ�������Ă��������B------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='ffffFF'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='FFffff'>");
		pw.println("<font size=+1><b>Q2�FBOOK TIMES���ǂ��ǂ܂ꂽ�������Ă��������B</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�P�jBOOK TIMES�͖����P����j���Ɍf�ڂ���Ă���̂͂����m�ł����H</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<input type='radio' name='q21radio' value='0'>�͂��@<input type='radio' name='q21radio' value='1'>������");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�Q�j����܂ł�BOOK TIMES��ǂ񂾂��Ƃ͂���܂����H</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='radio' name='q22radio' value='0'>�����ǂ�ł���@</nobr>");
		pw.println("<nobr><input type='radio' name='q22radio' value='1'>���X�ǂ�ł���@</nobr>");
		pw.println("<nobr><input type='radio' name='q22radio' value='2'>���߂ēǂ񂾁@</nobr>");
		pw.println("<nobr><input type='radio' name='q22radio' value='3'>���̑��i<input type=text size='10' name='q22text'>�j�@</nobr>");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�R�jBOOK TIMES�ɂ��Ă̊��z���������Ă��������B</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<textarea rows='2' cols='70' name='q23text'></textarea>");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�S�jBOOK TIMES�����ǂ݂ɂȂ��ċ������������ɂȂ�{�͂���܂����H</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='radio' name='q24radio' value='0'>��������@</nobr>");
		pw.println("<nobr><input type='radio' name='q24radio' value='1'>���X����@</nobr>");
		pw.println("<nobr><input type='radio' name='q24radio' value='2'>�Ȃ��@</nobr>");
		pw.println("<nobr><input type='radio' name='q24radio' value='3'>���̑��i<input type=text size='10' name='q24text'>�j</nobr>�@");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>��L�́i�S�j�ŁA�u��������v���́u���X����v�Ƃ������ɂȂ������Ɏf���܂��B�@���̖{���w");
		pw.println("�����܂������H</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='radio' name='q25radio' value='0'>�w���������Ƃ�����@</nobr>");
		pw.println("<nobr><input type='radio' name='q25radio' value='1'>�w���������Ƃ��Ȃ��@</nobr>");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�U�j����ABOOK TIMES�Ŋ�悵�ė~�����R�[�i�[�͉��ł����H</b></font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<textarea rows='2' cols='70' name='q26text'></textarea>");
		pw.println("</td></tr></table>");
		pw.println("</td></tr></table>");
		pw.println("<!---------�^Q2�FBOOK TIMES���ǂ��ǂ܂ꂽ�������Ă��������B------------->");
		pw.println("<br><br>");
		pw.println("<!---------Q3�F���ЂɊւ���A���P�[�g�@�i3�`5��j------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='FFFFFF'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='FFffff'>");
		pw.println("<font size=+1><b>Q3�F���ЂɊւ���A���P�[�g</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�P�j�D���Ȗ{�̃W�����������m�点���������B</b><br>�i�����񓚉j</font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td>");
		pw.println("<td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='0'>�����i�����E�C�O�A����E���̑��j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='1'>�m���t�B�N�V�����@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='2'>���j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='3'>�n�E�c�[�i���p���j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='4'>�I�s���́@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='5'>�r�W�l�X�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='6'>�G�b�Z�C�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='7'>�^�����g���́@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='8'>�������@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='9'>�G�{�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='10'>�R�~�b�N�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='11'>�f�W�^���֘A�@</nobr>");
		pw.println("<br>");
		pw.println("<nobr><input type='checkbox' name='q31check' value='12'>���̑�<font size=-0>����̓I�ɂ��L�����������B</font>�i<input type=text name='q31text' size='10'>�j</nobr>");
		pw.println("</td>");
		pw.println("</tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�Q�j�D���ȍ�Ƃ����m�点���������B</b>�i�R���܂Łj</font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("�i<input type=text size='15' name='q32text1'>�j�@�i<input type=text size='15' name='q32text2'>�j�@�i<input type=text size='15' name='q32text3'>�j�@");
		pw.println("</td></tr><TR><TD width='5%'>");
		pw.println("<IMG SRC='/img/bt02.gif' width=24 height=24>");
		pw.println("</td><td width='95%' BGCOLOR='4288FF'>");
		pw.println("<font color='#ffffff'><b>�i�R�j�{��I�Ԋ�����m�点���������B</b><br>�i�����񓚉j</font>");
		pw.println("</td></tr>");
		pw.println("<tr><td width='5%><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='BFD7FF'>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='0'>��Ɓ@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='1'>�o�ŎЁ@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='2'>���]�i�V���j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='3'>���]�i�G���j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='4'>�L���i�V���E�G���E�ԓ��L���E�ŔE�`���V�j�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='5'>�X���̍��m�Ł@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='6'>�F�l�̐��E�@</nobr>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='7'>���ド���L���O�@</nobr>");
		pw.println("<br>");
		pw.println("<nobr><input type='checkbox' name='q33check' value='8'>���̑�<font size=-0>����̓I�ɂ��L�����������B</font>�i<input type=text name='q33text' size='10'>�j</nobr>");
		pw.println("</td></tr></table>");
		pw.println("</td></tr></table>");
		pw.println("<!---------�^Q3�F���ЂɊւ���A���P�[�g�@�i3�`5��j------------->");
		pw.println("<br><br>");
		pw.println("<!---------�Ō��------------->");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width='600'>");
		pw.println("<TR>");
		pw.println("<TD BGCOLOR='FFFFFF'>");
		pw.println("<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5 width='100%'>");
		pw.println("<TR><TD width='5%'><IMG SRC='/img/d.gif'>");
		pw.println("</td><td width='95%' BGCOLOR='FFffff'>");
		pw.println("<font size=+1><b>�Ō��</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='4288FF' colspan=2>");
		pw.println("<font color='#ffffff'><b>������A�A���P�[�g�ɂ����͂��������܂����H</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='BFD7FF' colspan=2>");
		pw.println("�A���P�[�g�̃��[���������肵�Ă���낵���ł����H<br>");
		pw.println("<nobr><input type='radio' name='q41radio' value='0'>�悢�@</nobr>");
		pw.println("<nobr><input type='radio' name='q41radio' value='1'>�����̂�����e�Ȃ�悢�@</nobr>");
		pw.println("<nobr><input type='radio' name='q41radio' value='2'>�v���[���g������Ȃ�悢�@</nobr>");
		pw.println("<nobr><input type='radio' name='q41radio' value='3'>���͂ł��Ȃ��@</nobr>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='4288FF' colspan=2>");
		pw.println("<font color='#ffffff'><b>�A���P�[�g�̃��[���������肷��A�h���X�͏�L�̃A�h���X�ł�낵���ł����H</b></font>");
		pw.println("</td></tr>");
		pw.println("<TR><td width='95%' BGCOLOR='BFD7FF' colspan=2>");
		pw.println("<nobr><input type='radio' name='q42radio' value='0'>��L�̃A�h���X�ł悢�@</nobr><br>");
		pw.println("<nobr><input type='radio' name='q42radio' value='1'>�ʂ̃A�h���X����]�����[���A�h���X </nobr>");
		pw.println("<nobr>�i<input type=text name='q42text' size='20'>�j</nobr>");
		pw.println("</td></tr>");
		pw.println("</td></tr></table>");
		pw.println("</td></tr></table>");
		pw.println("<!---------�^�Ō��------------->");
		pw.println("<IMG SRC='/img/d.gif' WIDTH='5' HEIGHT='10'><BR>");
		pw.println("���L�����I���܂����瑗�M�{�^�����N���b�N���ĉ������B<p>");
		pw.println("<A href='JavaScript:SubmitEnquete()'><IMG SRC='/img/sousin.gif' border=0></A>�@<A href='JavaScript:document.form1.reset()'><IMG SRC='/img/clear.gif' border=0></A>");
		pw.println("</FORM>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("<IMG SRC='/img/d.gif' WIDTH='5' HEIGHT='20'><BR>");
		pw.println("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='600'>");
		pw.println("<TR>");
		pw.println("<TD align=center>");
		pw.println("<HR WIDTH='600'>");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("<TR>");
		pw.println("<TD align=center>");
		pw.println("BOOK TIMES�A���P�[�g������");
		pw.println("</TD>");
		pw.println("</TR>");
		pw.println("</TABLE>");
		pw.println("</CENTER>");
		pw.println("<BR>");
		pw.println("<BR>");
		pw.println("</BODY>");
		pw.println("</HTML>");
	}

	// JavaScript�o��
	private void Script(PrintWriter pw)
			throws ServletException, IOException {

		final String[] script = {
			"function SubmitEnquete() {",
			" with (document.form1) {",
			"  if (present.value == '') {",
			"   alert('����]�̏��Ж�����͂��Ă��������B');",
			"   present.focus();",
			"   return;",
			"  }",
			"  if (shimeisei.value == '') {",
			"   alert('�����O�i���j����͂��Ă��������B');",
			"   shimeisei.focus();",
			"   return;",
			"  }",
			"  if (shimeimei.value == '') {",
			"   alert('�����O�i���j����͂��Ă��������B');",
			"   shimeimei.focus();",
			"   return;",
			"  }",
			"  if (mailaddr.value == '') {",
			"   alert('���[���A�h���X����͂��Ă��������B');",
			"   mailaddr.focus();",
			"   return;",
			"  }",
			"  if (mailaddr.value.indexOf('@') < 1) {",
			"    alert('���������[���A�h���X����͂��Ă��������B');",
			"    mailaddr.focus();",
			"    mailaddr.select();",
			"    return;",
			"  }",
			"  if (mailkind[0].checked == false && mailkind[1].checked == false) {",
			"   alert('����p�܂��͎d���p��I�����Ă��������B');",
			"   mailkind(0).focus();",
			"   return;",
			"  }",
			"  if (zip1.value == '') {",
			"   alert('�X�֔ԍ�����͂��Ă��������B');",
			"   zip1.focus();",
			"   return;",
			"  }",
			"  if (isNaN(Number(zip1.value))) {",
			"   alert('�������X�֔ԍ�����͂��Ă��������B');",
			"   zip1.focus();",
			"   zip1.select();",
			"   return;",
			"  }",
			"  if (zip2.value == '') {",
			"   alert('�X�֔ԍ�����͂��Ă��������B');",
			"   zip2.focus();",
			"   return;",
			"  }",
			"  if (isNaN(Number(zip2.value))) {",
			"   alert('�������X�֔ԍ�����͂��Ă��������B');",
			"   zip2.focus();",
			"   zip2.select();",
			"   return;",
			"  }",
			"  if (todoufuken.value == '') {",
			"   alert('�s���{����I�����Ă��������B');",
			"   todoufuken.focus();",
			"   return;",
			"  }",
			"  if (address.value == '') {",
			"   alert('���Z������͂��Ă��������B');",
			"   address.focus();",
			"   return;",
			"  }",
			"  if (tel1.value == '') {",
			"   alert('�d�b�ԍ�����͂��Ă��������B');",
			"   tel1.focus();",
			"   return;",
			"  }",
			"  if (tel2.value == '') {",
			"   alert('�d�b�ԍ�����͂��Ă��������B');",
			"   tel2.focus();",
			"   return;",
			"  }",
			"  if (tel3.value == '') {",
			"   alert('�d�b�ԍ�����͂��Ă��������B');",
			"   tel3.focus();",
			"   return;",
			"  }",
			"  if (seinen.value != '') {",
			"   var nyear = Number(seinen.value);",
			"   if (isNaN(nyear) || seinen.value.indexOf('.',0) >= 0) {",
			"    alert('�N������������܂���B');",
			"    seinen.focus();",
			"    seinen.select();",
			"    return;",
			"   }",
			"   if (seitsuki.value == '') {",
			"    alert('������͂��Ă��������B');",
			"    seitsuki.focus();",
			"    return;",
			"   }",
			"   var nmonth = Number(seitsuki.value);",
			"   if (isNaN(nmonth) || nmonth < 1 || nmonth > 12 || seitsuki.value.indexOf('.',0) >= 0) {",
			"    alert('��������������܂���B');",
			"    seitsuki.focus();",
			"    seitsuki.select();",
			"    return;",
			"   }",
			"   if (sexxx.value == '') {",
			"    alert('������͂��Ă��������B');",
			"    sexxx.focus();",
			"    return;",
			"   }",
			"   var ndate = Number(sexxx.value);",
			"   if (isNaN(ndate) || ndate < 1 || ndate > 31 || sexxx.value.indexOf('.',0) >= 0) {",
			"    alert('��������������܂���B');",
			"    sexxx.focus();",
			"    sexxx.select();",
			"    return;",
			"   }",
			"  }",
			"  submit();",
			" }",
			"}"
		};

		JavaScript(pw, script);
	}


	// "POST"���\�b�h����
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// ���̓p�����[�^�擾�N���X����
			Param param = new Param(request);

			// �A���P�[�g���e���c�a�ɕۑ�
			StoreDB(param, response);

			// ContentType�ݒ肨���PrintWriter�擾
			PrintWriter pw = GetPrintWriter(response);

			// 39.html �փW�����v
			GotoPage(pw, "/39.html");
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �A���P�[�g���e�c�a�ɕۑ�����
	private void StoreDB(Param param, HttpServletResponse response)
			throws ServletException, IOException, SQLException {

		// ���N�������擾
		String SeinenGappi;
		if (!param.GetParam("seinen").equals("")) {
			int year = Integer.parseInt(param.GetParam("seinen"));
			int month = Integer.parseInt(param.GetParam("seitsuki"));
			int date = Integer.parseInt(param.GetParam("sexxx"));
			SeinenGappi = "'" + new GregorianCalendar(year + 1900, month - 1, date).getTime() + "'";
		} else
			SeinenGappi = "null";
			
		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		String sql = "insert into D_ENQUETE("
			+ "DEN_ENQUETE_DATE,"
			+ "DEN_PRESENT_TEXT,"
			+ "DEN_NAME_SEI,"
			+ "DEN_NAME_MEI,"
			+ "DEN_FURIGANA_SEI,"
			+ "DEN_FURIGANA_MEI,"
			+ "DEN_EMAIL_TEXT,"
			+ "DEN_EMAIL_CODE,"
			+ "DEN_ZIP1_NO,"
			+ "DEN_ZIP2_NO,"
			+ "DEN_TODOUFUKEN_CODE,"
			+ "DEN_ADDRESS_TEXT,"
			+ "DEN_TEL1_NO,"
			+ "DEN_TEL2_NO,"
			+ "DEN_TEL3_NO,"
			+ "DEN_SEIBETSU_CODE,"
			+ "DEN_SEINENGAPPI_DATE,"
			+ "DEN_SHOKUGYOU_CODE,"
			+ "DEN_Q1FLAG,"
			+ "DEN_Q1_TEXT,"
			+ "DEN_Q21_CODE,"
			+ "DEN_Q22_CODE,"
			+ "DEN_Q22_TEXT,"
			+ "DEN_Q23_TEXT,"
			+ "DEN_Q24_CODE,"
			+ "DEN_Q24_TEXT,"
			+ "DEN_Q25_CODE,"
			+ "DEN_Q26_TEXT,"
			+ "DEN_Q31_FLAG,"
			+ "DEN_Q31_TEXT,"
			+ "DEN_Q321_TEXT,"
			+ "DEN_Q322_TEXT,"
			+ "DEN_Q323_TEXT,"
			+ "DEN_Q33_FLAG,"
			+ "DEN_Q33_TEXT,"
			+ "DEN_KYOURYOKU_CODE,"
			+ "DEN_ENQMAIL_CODE,"
			+ "DEN_ENQMAIL_TEXT"
			+ ") values ("
			+ "'" + new Date() + "',"
			+ "'" + param.GetParam("present") + "',"
			+ "'" + param.GetParam("shimeisei") + "',"
			+ "'" + param.GetParam("shimeimei") + "',"
			+ "'" + param.GetParam("furiganasei") + "',"
			+ "'" + param.GetParam("furiganamei") + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("mailaddr")) + "',"
			+ "'" + param.GetParam("mailkind") + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("zip1")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("zip2"))+ "',"
			+ "'" + param.GetParam("todoufuken") + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("address")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("tel1")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("tel2")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("tel3")) + "',"
			+ "'" + NullToBlank(param.GetParam("seibetsu")) + "',"
			+ SeinenGappi + ","
			+ "'" + param.GetParam("shokugyou") + "',"
			+ "'" + GetCheckbox(param, "q1check", 35) + "',"
			+ "'" + param.GetParam("q1text") + "',"
			+ "'" + NullToBlank(param.GetParam("q21radio")) + "',"
			+ "'" + NullToBlank(param.GetParam("q22radio")) + "',"
			+ "'" + param.GetParam("q22text") + "',"
			+ "'" + param.GetParam("q23text") + "',"
			+ "'" + NullToBlank(param.GetParam("q24radio")) + "',"
			+ "'" + param.GetParam("q24text") + "',"
			+ "'" + NullToBlank(param.GetParam("q25radio")) + "',"
			+ "'" + param.GetParam("q26text") + "',"
			+ "'" + GetCheckbox(param, "q31check", 13) + "',"
			+ "'" + param.GetParam("q31text") + "',"
			+ "'" + param.GetParam("q32text1") + "',"
			+ "'" + param.GetParam("q32text2") + "',"
			+ "'" + param.GetParam("q32text3") + "',"
			+ "'" + GetCheckbox(param, "q33check", 9) + "',"
			+ "'" + param.GetParam("q33text") + "',"
			+ "'" + NullToBlank(param.GetParam("q41radio")) + "',"
			+ "'" + NullToBlank(param.GetParam("q42radio")) + "',"
			+ "'" + StrUty.HankakuCnv(param.GetParam("q42text")) + "')";

		st.executeUpdate(sql);
		st.close();

	}
}