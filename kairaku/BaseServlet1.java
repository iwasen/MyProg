//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�}�X�^�[�����e�i���X��{�N���X
//		FileName:	BaseServlet1.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.io.OutputStreamWriter;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// �}�X�^�[�����e�i���X���ʂ̃N���X
public class BaseServlet1 extends HttpServlet {

	// �o�͕����R�[�h
	protected final String Encoding = "iso-2022-jp";

	// ���y�s��g�b�v�y�[�WURL
	protected final String TopPage = "master";

	// PrintWriter �擾����
	protected PrintWriter GetPrintWriter(HttpServletResponse response)
			throws IOException {

		response.setContentType("text/html; charset=" + Encoding);
		return new PrintWriter(new OutputStreamWriter(response.getOutputStream(), response.getCharacterEncoding()), true);
		//return response.getWriter();
	}

	// ���O�C���`�F�b�N
	public boolean LoginCheck(HttpServletRequest request)
			throws IOException {

		MyUtil myutil = new MyUtil();
		String login = myutil.GetCookieValue(request, "MasterLogin");

		if (login != null && login.equals("ok"))
			return true;
		else
			return false;
	}

	// �w�b�_�o�͏����ititle �ƌ��o���������j
	protected void Header(PrintWriter pw, String title) {
		Header(pw, title, title);
	}

	// �w�b�_�o�͏����ititle �ƌ��o�����ʁj
	protected void Header(PrintWriter pw, String title, String pagename) {
		pw.println("<html>");
		pw.println("<head>");
		pw.println("<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=" + Encoding + "'>");
		pw.println("<META HTTP-EQUIV='Pragma' CONTENT='no-cache'>");
		//pw.println("<META HTTP-EQUIV='Cache-Control' CONTENT='no-cache'>");
		pw.println("<title>"+title+"</title>");
		pw.println("<style type='TEXT/CSS'>");
		pw.println("<!--");
		pw.println("div.master {margin: 0% 2%}");
		pw.println("-->");
		pw.println("</style>");
		pw.println("</head>");
		pw.println("<body bgcolor='#efefef' text=Black link=blue vlink=blue alink=red>");
		pw.println("<center>");
		pw.println("<table cellspacing=0 cellpadding=0 width='100%'>");
		pw.println("<tr><td align=left valign=bottom norap>");
		pw.println("<small><B>�ŏI�X�V��:</B> May 12, 2000</small>");
		pw.println("</td><td align=right valign=bottom norap>");
		pw.println("<small><font color=Blue>��</font>");
		pw.println("<a href='"+ TopPage +"'>�g�b�v�֖߂�</a>");
		pw.println("</small>");
		pw.println("</td></tr></table>");
		pw.println("<hr>");
		pw.println("<table border=2 width='90%' bgcolor='darkblue' cellspacing=1 cellpadding=6>");
		pw.println("<tr><th>");
		pw.println("<font color=White size=6>" + pagename + "</font>");
		pw.println("</th></tr></table>");
		pw.println("<hr>");
		pw.println("</center>");
		pw.println("<div class='master'>");
	}

	// �t�b�^�o�͏���
	protected void Footer(PrintWriter pw) {
		pw.println("</div>");
		pw.println("<center>");
		pw.println("<hr>");
		pw.println("<table cellspacing=0 cellpadding=0 width=100% border=0>");
		pw.println("<tr valign=bottom><td align=right valign=bottom nowrap>");
		pw.println("<font color=#0000ff>��</font>");
		pw.println("<a href='"+ TopPage +"'>�g�b�v�֖߂�</a>");
		pw.println("</td></tr>");
		pw.println("</table>");
		pw.println("Copyright(C) 2000 ���y�s�� Co.,Ltd. All rights reserved.");
		pw.println("</center>");
		pw.println("</body>");
		pw.println("</html>");
	}

	// �G���[�������p���b�Z�[�W�o��
	protected void Failure(PrintWriter pw, String reason) {
		pw.println("<br>");
		pw.println("�ȉ��̌������Q�l�ɂ��ĉ������B:");
		pw.println("<p>");
		pw.println(reason);
		pw.println("<p>");
		pw.println("�Ȃ��A���s�̗��R���s���̏ꍇ�� ");
		pw.println("<a href='mailto:servlet-admin@java-conf.gr.jp'>");
		pw.println("�T�[�o�Ǘ��l</a> �܂ł��m�点�������B");
	}

	// JavaScript�o�͏���
	protected void JavaScript(PrintWriter pw, String[] script) {
		int i;

		pw.println("<script language='JavaScript'><!--");

		for (i = 0; i < script.length; i++) {
			pw.println(script[i]);
		}

		pw.println("// --></script>");
	}
}
