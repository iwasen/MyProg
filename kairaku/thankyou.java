//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���M�m�F��ʏ���
//		FileName:	thankyou.java
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

// ���M�m�F�y�[�W�����N���X
public class thankyou extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "���M�m�F", "", "bgcolor='#FFCCCC' class='s1'");
		Body(pw);
		Footer(pw);

		session.invalidate();
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw)
			throws ServletException, IOException, SQLException {

		pw.println("<p align='center'><b><span class='s5'>���w�����肪�Ƃ��������܂����B</span><br>");
		pw.println("<font color='#FF0000' class='s1'>��<br>");
		pw.println("���v���z�̑��ɑ����i700�~�j������܂��B<br>");
		pw.println("��z�������������]�̕��͑����̑��Ɏ萔���i400�~�j�𒸂��܂��B<br>");
		pw.println("�������A15,000�~�ȏエ�����グ�̏ꍇ�A�����A�萔���͖����Ƃ����Ă��������܂��B</font>");
		pw.println("</b></p>");
		pw.println("<hr>");
		pw.println("<p align='center'><span class='s2'><font color='#FF0000'><b>���߂Ă̕��ցi18�Έȏ�N��m�F���܂��̕��j</b></font>");
		pw.println("</span></p>");
		pw.println("<p align='center'> 18�Έȏ�̔N����m�F�ł��鏑�ނ̃R�s�[���A���y�s��܂�FAX�A�܂��͂����t���Ă��������B</p>");
		pw.println("<p align='center' class='s1'> ���t��E�A����<br>");
		pw.println("162-0814�@�����s�V�h��V���쒬3-10SE�r��4F ���y�s�ꎖ����<br>");
		pw.println("TEL03-5229-7512�@FAX03-5229-7513</p>");
		pw.println("<p align='center' class='s1'> 18�Έȏ�̊m�F���o������A���[���ɂĘA���v���܂��B<br>");
		pw.println("����܂ŗ����̂��U�荞�݂͂��Ȃ��ŉ������B<br>");
		pw.println("�N��m�F���ł���܂ŁA���i�̔����͂ł��܂���B</p>");
		pw.println("<hr>");
		pw.println("<p align='center' class='s1'><span class='s2'><font color='#FF0000'><b>�Q�x�ڈȍ~�̕���</b></font></span></p>");
		pw.println("<p align='center' class='s1'> �����̂��x�����ɂ��ẮA ���z�[���y�[�W�n��8�K�@���ϕ��@�ɂ���<br>");
		pw.println("<a href='http://www.kairakuichiba.co.jp/b8F/kessai.html'>http://www.kairakuichiba.co.jp/b8F/kessai.html</a><br>");
		pw.println("���������������B</p>");
		pw.println("<form name='form1' action='"+ TopPage +"'>");
		pw.println("<div align='center'>");
		pw.println("<input type='submit' value='���ǂ�'>");
		pw.println("</div>");
		pw.println("</form>");
	}
}
