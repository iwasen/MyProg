//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�}�X�^�[�����e�i���X�������
//		FileName:	master.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.io.*;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// �}�X�^�[�����e�i���X������ʏ����N���X
public class master extends BaseServlet1
{
	// "GET"���\�b�h����
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			PrintWriter pw = GetPrintWriter(response);

			// ���O�C���`�F�b�N
			if (!LoginCheck(request)) {
				if (!Authorization(request, response)) {
					LoginFailure(pw);
					return;
				}
			}

			Header(pw, "�}�X�^�[�����e�i���X");
			Body(pw);
			Script(pw);
			Footer(pw);

		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �F�؏���
	private boolean Authorization(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		boolean loginok = false;

		// �h�c�^�p�X���[�h�`�F�b�N
		String auth = request.getHeader("Authorization");
		if (auth == null) {
			// ID/Password������
			response.setHeader("WWW-Authenticate", "BASIC realm=\"Kairaku Ichiba\"");
			response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
		} else {
			// ID/Password���͍ς�
			String realm = auth.substring(auth.lastIndexOf(' ')+1);
			byte b[] = realm.getBytes();
			int l = b.length;
			ByteArrayOutputStream bout = Base64.decode(new ByteArrayInputStream(b),l);
			String dec = bout.toString();
			String pid = dec.substring(0,dec.indexOf(':'));
			String password = dec.substring(dec.indexOf(':')+1);

			// �p�X���[�h�`�F�b�N
			Contrxxxaster cm = new Contrxxxaster();
			if (cm.CheckAdmin(pid, password)) {
				loginok = true;
				myutil.PutCookie(response, "MasterLogin", "ok");
			} else {
				// �������Ȃ���΍ē���
				response.setHeader("WWW-Authenticate", "BASIC realm=\"Kairaku Ichiba\"");
				response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
			}
		}

		return loginok;
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw)
			throws ServletException, IOException {

		pw.println("<form name='shouhin'>");
		pw.println("<font color='orange'>��</font><b>���i�}�X�^�[</b><br>");
		pw.println("<table border=0>");
		pw.println("<tr><td>");
		pw.println("<input type='button' value='�V�K�o�^' onClick='location.href=\"ShouhinItem\"'>");
		pw.println("</td></tr><tr><td>");
		pw.println("���i�ԍ�");
		pw.println("</td><td>");
		pw.println("<input type='text' name='no' size=28>");
		pw.println("</td><td>");
		pw.println("<input type='button' value='�@�X�V�@' onClick='OnClick_ShouhinList()'>");
		pw.println("</td></tr>");
		pw.println("<tr><td></td><td><font size=-1>�i�O����v�����j</font></td></tr>");
		pw.println("</table>");
		pw.println("</form>");

		pw.println("<form name='user'>");
		pw.println("<font color='orange'>��</font><b>���[�U�[�}�X�^�[</b><br>");
		pw.println("<table border=0>");
		pw.println("<tr><td>");
		pw.println("����");
		pw.println("</td><td>");
		pw.println("<input type='text' name='name' size=28>");
		pw.println("</td><td>");
		pw.println("<input type='button' value='�@�X�V�@' onClick='OnClick_UserUpdate()'>");
		pw.println("</td></tr>");
		pw.println("<tr><td></td><td><font size=-1>�i�O����v�����j</font></td></tr>");
		pw.println("<tr></tr>");
		pw.println("<tr><td>");
		pw.println("�o�^�N����");
		pw.println("</td><td>");
		pw.println("<input type='text' name='year' size=4 maxlength=4>�N");
		pw.println("<input type='text' name='month' size=2 maxlength=2>��");
		pw.println("<input type='text' name='date' size=2 maxlength=2>��");
		pw.println("</td><td>");
		pw.println("<input type='button' value='�@�\���@' onClick='OnClick_UserInfo()'>");
		pw.println("</td></tr>");
		pw.println("<tr><td></td><td><font size=-1>�i�w��o�^���ȍ~�j</font></td></tr>");
		pw.println("</table>");
		pw.println("</form>");

		pw.println("<form name='etc'>");
		pw.println("<table border=0>");
		pw.println("<tr><td width=200>");
		pw.println("<font color='orange'>��</font><b>���i���ރ}�X�^�[</b><br>");
		pw.println("<input type='button' value='�@�ҏW�@' onClick='location.href=\"BunruiMaster\"'>");
		pw.println("</td><td>");
		pw.println("<font color='orange'>��</font><b>�E�ƃ}�X�^�[</b><br>");
		pw.println("<input type='button' value='�@�ҏW�@' onClick='location.href=\"ShokugyouMaster\"'>");
		pw.println("</td></tr>");
		pw.println("<tr><td colspan=2><br></td></tr>");
		pw.println("<tr><td>");
		pw.println("<font color='orange'>��</font><b>�J�e�S���[�}�X�^�[</b><br>");
		pw.println("<input type='button' value='�@�ҏW�@' onClick='location.href=\"CategoryMaster\"'>");
		pw.println("</td><td>");
		pw.println("<font color='orange'>��</font><b>�x�����@�}�X�^�[</b><br>");
		pw.println("<input type='button' value='�@�ҏW�@' onClick='location.href=\"ShiharaiMaster\"'>");
		pw.println("</td></tr>");
		pw.println("</table>");
		pw.println("</form>");
	}

	// JavaScript�o�͏���
	private void Script(PrintWriter pw)
			throws ServletException, IOException {

		final String[] script = {
			"function OnClick_ShouhinList() {",
			" document.shouhin.action='ShouhinList';",
			" document.shouhin.submit();",
			"}",
			"function OnClick_UserUpdate() {",
			" document.user.action='UserList';",
			" document.user.submit();",
			"}",
			"function OnClick_UserInfo() {",
			" with (document.user) {",
			"  var nyear = Number(year.value);",
			"  if (year.value != '') {",
			"   if (isNaN(nyear)) {",
			"    alert('�N������������܂���B');",
			"    year.focus();",
			"    year.select();",
			"    return;",
			"   }",
			"   if (month.value == '') {",
			"    alert('������͂��Ă��������B');",
			"    month.focus();",
			"    return;",
			"   }",
			"   var nmonth = Number(month.value);",
			"   if (isNaN(nmonth) || nmonth < 1 || nmonth > 12) {",
			"    alert('��������������܂���B');",
			"    month.focus();",
			"    month.select();",
			"    return;",
			"   }",
			"   if (date.value == '') {",
			"    alert('������͂��Ă��������B');",
			"    date.focus();",
			"    return;",
			"   }",
			"   var ndate = Number(date.value);",
			"   if (isNaN(ndate) || ndate < 1 || ndate > 31) {",
			"    alert('��������������܂���B');",
			"    date.focus();",
			"    date.select();",
			"    return;",
			"   }",
			"  }",
			" }",
			" document.user.action='UserInfo';",
			" document.user.method='post';",
			" document.user.submit();",
			"}",
		};

		JavaScript(pw, script);
	}

	// ���O�C�����s�����b�Z�[�W�o��
	private void LoginFailure(PrintWriter pw)
			throws ServletException, IOException {

		Header(pw, "�}�X�^�[�����e�i���X");
		pw.println("<h2>���O�C���ł��܂���B</h2>");
		Footer(pw);
	}
}
