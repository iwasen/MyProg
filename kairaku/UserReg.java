//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���[�U�o�^����
//		FileName:	UserReg.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// ���[�U�o�^�y�[�W�����N���X
public class UserReg extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "���[�U�o�^", "", "bgcolor='#CCFFFF'");
		Body(pw);
		Script(pw);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		// ���[�U�o�^���
		pw.println("<p align='center'><b class='s5'><font color='#FF0000'>���y�s�ꑍ���f�p�[�g</font>�@��������o�^</b></p>");
		pw.println("<p align='center' class='s1'><b> <span class='s5'>��</span><br>");
		pw.println("</b>18�Ζ����̕��́A���w���ł��܂���B<br>");
		pw.println("���y�s��ŏ��i���w������ۂɂ́A18�Έȏ�ł��邩�m�F���邽�߂ɁA<br>");
		pw.println("���Ȃ��̔N����m�F�ł��镨�̃R�s�[���e�`�w�A���͗X����<br>");
		pw.println("�����Ă��������A�N��̊m�F���o���Ă���A���i�̔����ƂȂ�܂��B<br>");
		pw.println("�Ȃ��A�N��̊m�F�ؖ�������o�^��P�J�������Ă��ł��Ȃ��ꍇ�ɂ�<br>");
		pw.println("�h�c�ƃp�X���[�h�͎g�p�ł��Ȃ��Ȃ�܂��B<br>");
		pw.println("�Ȃ��A�N�18�Έȏ�Ɗm�F�����΁A�Q�x�ڈȍ~�͂h�c�ƃp�X���[�h�݂̂�<br>");
		pw.println("���������o����悤�ɂȂ�܂��B </p>");
		pw.println("<form name='form1' method='post' onSubmit='return OnSubmit();' class='s1' >");
		pw.println("<div align='center'>");
		pw.println("<p>����̍��ڂ́A�K�����m�ɂ��L�����������B <br>");
		pw.println("���p�J�i�͎g�p���Ȃ��ŉ������B<br>");
		pw.println("�L���ɁA�ԈႢ������܂��ƁA���w���ł��Ȃ��ꍇ������܂��B<br>");
		pw.println("</p>");
		pw.println("<table width='700' border='2' cellspacing='1' cellpadding='6' height='400' bgcolor='#FFCCCC' bordercolordark='#666666' bordercolorlight='#FFFFFF' class='s1'>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>���h�c</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='uid'>");
		pw.println("�W���ȓ��̔��p�p�����ł��肢���܂��B�@��@MYID1234</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>���ӂ肪��</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='furigana'>");
		pw.println("�S�p�Ђ炪�Ȃł��肢���܂��B�@��@��܂��@���낤</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>������</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='name'>");
		pw.println("�S�p�����ł��肢���܂��@�@�@�@��@�R�c�@���Y</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>������</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<select name='seibetsu'>");
		pw.println("<option value='�j' selected>�j</option>");
		pw.println("<option value='��'>��</option>");
		pw.println("</select>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>�����N����</div>");
		pw.println("</td>");
		pw.println("<td width='572'>�@����");
		pw.println("<input type='text' name='seinen' size='10'>");
		pw.println("�N");
		pw.println("<select name='seituki'>");
		pw.println("<option>1</option>");
		pw.println("<option>2</option>");
		pw.println("<option>3</option>");
		pw.println("<option>4</option>");
		pw.println("<option>5</option>");
		pw.println("<option>6</option>");
		pw.println("<option>7</option>");
		pw.println("<option>8</option>");
		pw.println("<option>9</option>");
		pw.println("<option>10</option>");
		pw.println("<option>11</option>");
		pw.println("<option>12</option>");
		pw.println("</select>");
		pw.println("��");
		pw.println("<select name='sexxx'>");
		pw.println("<option>1</option>");
		pw.println("<option>2</option>");
		pw.println("<option>3</option>");
		pw.println("<option>4</option>");
		pw.println("<option>5</option>");
		pw.println("<option>6</option>");
		pw.println("<option>7</option>");
		pw.println("<option>8</option>");
		pw.println("<option>9</option>");
		pw.println("<option>10</option>");
		pw.println("<option>11</option>");
		pw.println("<option>12</option>");
		pw.println("<option>13</option>");
		pw.println("<option>14</option>");
		pw.println("<option>15</option>");
		pw.println("<option>16</option>");
		pw.println("<option>17</option>");
		pw.println("<option>18</option>");
		pw.println("<option>19</option>");
		pw.println("<option>20</option>");
		pw.println("<option>21</option>");
		pw.println("<option>22</option>");
		pw.println("<option>23</option>");
		pw.println("<option>24</option>");
		pw.println("<option>25</option>");
		pw.println("<option>26</option>");
		pw.println("<option>27</option>");
		pw.println("<option>28</option>");
		pw.println("<option>29</option>");
		pw.println("<option>30</option>");
		pw.println("<option>31</option>");
		pw.println("</select>");
		pw.println("�� </td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>�E��</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		myutil.ShokugyouSelect(pw, "", null, null);
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>���X�֔ԍ�</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='postnum' size='15'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>���Z���i�s���{������j</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='juusyo' size='80' value=''>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap height='38'>");
		pw.println("<div align='right'>�����E�}���V������</div>");
		pw.println("</td>");
		pw.println("<td width='572' height='38'>");
		pw.println("<input type='text' name='tatemono' size='40'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>���A����d�b�ԍ�</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='tel'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>FAX�ԍ�</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='fax'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>��E���[���A�h���X</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='youremail' size='40'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>���Ȃ��̃z�[���y�[�W�A�h���X</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='yoururl' size='50'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<br>");

		// �A���P�[�g
		pw.println("<table width='820' border='2' cellspacing='0' cellpadding='10' height='200' bgcolor='#FFFFCC'>");
		pw.println("<tr>");
		pw.println("<td>");
		pw.println("<p align='center'><b class='s5'>���y�s��A���P�[�g�ɂ����͂��������B</b></p>");
		pw.println("<hr>");
		pw.println("<p><b class='s2'>");
		pw.println("1.���̃z�[���y�[�W���A�ǂ��v���܂������H</b></p>");
		pw.println("<p align='left'><span class='s1'>");
		pw.println("<select name='anhpkanso' class='s1'>");
		pw.println("<option value='5' selected>�ׂɕ���</option>");
		pw.println("<option value='4'>�ʔ���</option>");
		pw.println("<option value='3'>�܂��܂�</option>");
		pw.println("<option value='2'>���܂���</option>");
		pw.println("<option value='1'>�܂�Ȃ�</option>");
		pw.println("</select>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("2.����A���̃z�[���y�[�W�� ��舵���Ă��炢������������΁A���L�����肢���܂��B</b></p>");
		pw.println("<p align='left'><span class='s1'>");
		pw.println("<textarea name='anatukai' cols='100' rows='4'></textarea>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("3.���Ȃ������̃y�[�W�̒��ŁA��Ԗʔ����Ǝv�������ڂ͉��ł����H</b></p>");
		pw.println("<p align='left'><span class='s1'>");
		pw.println("<select name='aniikai' class='s1'>");
		pw.println("<option value='1FF'>1�K</option>");
		pw.println("<option value='B1F'>�n��1�K</option>");
		pw.println("<option value='B2F'>�n��2�K</option>");
		pw.println("<option value='B3F'>�n��3�K</option>");
		pw.println("<option value='B4F'>�n��4�K</option>");
		pw.println("<option value='B5F'>�n��5�K</option>");
		pw.println("<option value='B6F'>�n��6�K</option>");
		pw.println("<option value='B7F'>�n��7�K</option>");
		pw.println("<option value='B8F'>�n��8�K</option>");
		pw.println("<option value='not' selected>�Ȃ�</option>");
		pw.println("</select>");
		pw.println("</span></p>");
		pw.println("<p align='left'><span class='s1'>���ځg����΋L�����Ă��������h<br>");
		pw.println("<input type='text' name='aniikou' size='80' value=''>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("4.���Ȃ������̃y�[�W�̒��ŁA���܂�ʔ����Ȃ��Ǝv�������ڂ͉��ł����H</b></p>");
		pw.println("<p align='left'>");
		pw.println("<select name='anyanakai' class='s1'>");
		pw.println("<option value='1FF'>1�K</option>");
		pw.println("<option value='B1F'>�n��1�K</option>");
		pw.println("<option value='B2F'>�n��2�K</option>");
		pw.println("<option value='B3F'>�n��3�K</option>");
		pw.println("<option value='B4F'>�n��4�K</option>");
		pw.println("<option value='B5F'>�n��5�K</option>");
		pw.println("<option value='B6F'>�n��6�K</option>");
		pw.println("<option value='B7F'>�n��7�K</option>");
		pw.println("<option value='B8F'>�n��8�K</option>");
		pw.println("<option value='not' selected>�Ȃ�</option>");
		pw.println("</select>");
		pw.println("</p>");
		pw.println("<p align='left'><span class='s1'>���ځg����΋L�����Ă��������h<br>");
		pw.println("<input type='text' name='anyanakou' size='80' value=''>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("5.���Ȃ����悭���p����z�[���y�[�W���A�����Ă��������B</b></p>");
		pw.println("<table>");
		pw.println("<tr><td align='right'>�^�C�g��</td><td><input type='text' name='anurltetle' size='50'></td></tr>");
		pw.println("<tr><td align='right'>�t�q�k</td><td><input type='text' name='anurl' size='40'></td></tr>");
		pw.println("</table>");
		pw.println("<hr>");
		pw.println("<p align='center'><b class='s2'>�����͂��肪�Ƃ��������܂����B</b></p>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("");
		pw.println("</div>");
		pw.println("<p><center>");
		pw.println("<input type='submit' name='Submit' value=' ���M '>");
		pw.println("<input type='reset' name='reset' value='���Z�b�g'>");
		pw.println("</center></p>");
		pw.println("</form>");
	}

	// JavaScript�o�͏���
	private void Script(PrintWriter pw)
			throws ServletException, IOException {
		final String[] script = {
			"function OnSubmit() {",
			" with (document.form1) {",
			"  if (uid.value == '') {",
			"   alert('�h�c����͂��Ă��������B');",
			"   uid.focus();",
			"   return false;",
			"  }",
			"  if (furigana.value == '') {",
			"   alert('�ӂ肪�Ȃ���͂��Ă��������B');",
			"   furigana.focus();",
			"   return false;",
			"  }",
			"  if (name.value == '') {",
			"   alert('��������͂��Ă��������B');",
			"   name.focus();",
			"   return false;",
			"  }",
			"  if (seibetsu.value == '') {",
			"   alert('���ʂ���͂��Ă��������B');",
			"   seibetsu.focus();",
			"   return false;",
			"  }",
			"  if (seinen.value == '') {",
			"   alert('���N��������͂��Ă��������B');",
			"   seinen.focus();",
			"   return false;",
			"  } else {",
			"   if (isNaN(Number(seinen.value))) {",
			"    alert('�N������������܂���B');",
			"    seinen.focus();",
			"    seinen.select();",
			"    return false;",
			"   }",
			"  }",
			"  if (postnum.value == '') {",
			"   alert('�X�֔ԍ�����͂��Ă��������B');",
			"   postnum.focus();",
			"   return false;",
			"  }",
			"  if (juusyo.value == '') {",
			"   alert('�Z������͂��Ă��������B');",
			"   juusyo.focus();",
			"   return false;",
			"  }",
			"  if (tel.value == '') {",
			"   alert('�A����d�b�ԍ�����͂��Ă��������B');",
			"   tel.focus();",
			"   return false;",
			"  }",
			"  if (youremail.value == '') {",
			"   alert('E���[���A�h���X����͂��Ă��������B');",
			"   youremail.focus();",
			"   return false;",
			"  }",
			"  if (youremail.value.indexOf('@') < 1) {",
			"   alert('E���[���A�h���X�̌`��������������܂���B');",
			"   youremail.focus();",
			"   return false;",
			"  }",
			" }",
			" return true;",
			"}",
/*
			"function MM_findObj(n, d) { //v3.0",
			"var p,i,x;  if(!d) d=document; if((p=n.indexOf('?'))>0&&parent.frames.length) {",
			"d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}",
			"if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];",
			"for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;",
			"}",
			"",
			"function MM_validateForm() { //v3.0",
			"var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;",
			"for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);",
			"if (val) { nm=val.name; if ((val=val.value)!='') {",
			"if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');",
			"if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\\n';",
			"} else if (test!='R') { num = parseFloat(val);",
			"if (val!=''+num) errors+='- '+nm+' must contain a number.\\n';",
			"if (test.indexOf('inRange') != -1) { p=test.indexOf(':');",
			"min=test.substring(8,p); max=test.substring(p+1);",
			"if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\\n';",
			"} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\\n'; }",
			"} if (errors) alert('The following error(s) occurred:\\n'+errors);",
			"document.MM_returnValue = (errors == '');",
			"}",
*/
		};

		JavaScript(pw, script);
	}

	// "POST"���\�b�h����
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		String uid = param.GetParam("uid");

		// ���͂��ꂽ���[�U�h�c���o�^�ς݂��`�F�b�N
		if (order.UserIDCheck(uid)) {
			// �o�^�ς݂Ȃ烁�b�Z�[�W��\��
			Header(pw, "���[�U�o�^", "", "bgcolor='#CCFFFF'");
			pw.println("<div class='s2'>�w�肵���h�c�͂��łɎg���Ă��܂��B�ʂ̂h�c����͂��Ă��������B</div>");
			pw.println("<br><input type='button' value='�߂�' onClick='history.back()'>");
			Footer(pw);
		} else {
			// ���o�^�Ȃ烆�[�U�o�^���ۑ�
			Order.User user = order.user;
			user.UserCode = null;
			user.UserID = uid;
			user.Password = GeneratePassword();
			user.Touroku = new Date();
			user.Furigana = param.GetParam("furigana");
			user.Shimei = param.GetParam("name");
			user.Seibetsu = param.GetParam("seibetsu");
			user.SeiNen = Integer.parseInt(param.GetParam("seinen"));
			user.SeiTsuki = Integer.parseInt(param.GetParam("seituki"));
			user.Sexxx = Integer.parseInt(param.GetParam("sexxx"));
			user.Nenrei = CalcAge(user.SeiNen, user.SeiTsuki, user.Sexxx);
			user.Over18Flag = false;
			user.Over18Date = null;
			user.Shokugyou = param.GetParam("ShokugyouCode");
			user.Zip = param.GetParam("postnum");
			user.Address = param.GetParam("juusyo");
			user.Tatemono = param.GetParam("tatemono");
			user.TelNo = param.GetParam("tel");
			user.FaxNo = param.GetParam("fax");
			user.EMail = param.GetParam("youremail");
			user.MailingList = false;
			user.Url = param.GetParam("yoururl");

			// �A���P�[�g���ʕۑ�
			Order.Enquete enquete = order.enquete;
			enquete.Setsumon1 = param.GetParam("anhpkanso");
			enquete.Setsumon2 = param.GetParam("anatukai");
			enquete.Setsumon3 = param.GetParam("aniikai");
			enquete.Setsumon3Koumoku = param.GetParam("aniikou");
			enquete.Setsumon4 = param.GetParam("anyanakai");
			enquete.Setsumon4Koumoku = param.GetParam("anyanakou");
			enquete.Setsumon5Title = param.GetParam("anurltetle");
			enquete.Setsumon4Url = param.GetParam("anurl");

			// ���[�U�o�^���m�F�y�[�W��
			response.sendRedirect("UserVerify");
		}
	}

	// �����ɂ��p�X���[�h��������
	private String GeneratePassword() {
		//String chr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		String chr = "abcdefghjkmnpqrstuvwxyz123456789";
		Random rnd = new Random(new Date().getTime());
		String pass = "";
		
		for (int i = 0; i < 8; i++)
			pass += chr.charAt((int)(rnd.nextFloat() * chr.length()));

		return pass;
	}

	// ���N��������N����v�Z
	private int CalcAge(int year, int month, int date) {

		GregorianCalendar calendar = new GregorianCalendar();
		int age = calendar.get(Calendar.YEAR) - year;
		int cmonth = calendar.get(Calendar.MONTH) + 1;
		int cdate = calendar.get(Calendar.DATE);
		if (cmonth < month)
			age--;
		else if (cmonth == month) {
			if (cdate < date)
				age--;
		}

		return age;
	}
}
