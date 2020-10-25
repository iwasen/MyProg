//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���[�U�w����͏���
//		FileName:	UserID.java
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

// ���[�U�h�c���̓y�[�W�����N���X
public class UserID extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "���[�U�w�����", "", "bgcolor='#000000' text='#FFFFFF' link='#FFFFFF' vlink='#FFFFFF' alink='#FFFFFF'"
				 + (order.AuthError ? "onLoad='alert(\"�h�c�܂��̓p�X���[�h���Ⴂ�܂��B\")'" : ""));
		order.AuthError = false;
		Body(pw, order);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		pw.println("<center>");
		pw.println("<p class='s2'>���Ȃ��̂h�c�ƃp�X���[�h����͂��ĉ������B</p>");
		pw.println("<form name='form1' method='post' class='s1' >");
		pw.println("<p>�h�c<br>");
		pw.println("<input type='text' name='uid' size='10'>");
		pw.println("</p>");
		pw.println("<p> �p�X���[�h<br>");
		pw.println("<input type='password' name='password' size='10'>");
		pw.println("</p>");
		pw.println("<p>");
		pw.println("<input type='submit' name='ok' value='���M'>");
		pw.println("<input type='reset' name='reset' value='���Z�b�g'>");
		pw.println("</p>");
		pw.println("<p>���߂Ă̕��́A�V�K�o�^�ցI<br>");
		pw.println("�����łh�c�ƃp�X���[�h�𔭍s���܂��B</p>");
		pw.println("<p>");
		pw.println("<input type='submit' name='new' value='�V�K�o�^'>");
		pw.println("</p>");
		pw.println("</form>");
		pw.println("</center>");
	}

	// "POST"���\�b�h����
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// �N���b�N���ꂽ�{�^���ɂ��U�蕪��
		if (param.GetParam("ok") != null) {
			// �u���M�v�{�^���Ȃ烆�[�U�h�c�ƃp�X���[�h���`�F�b�N
			if (order.AuthenticationCheck(param.GetParam("uid"), param.GetParam("password"))) {
				// �n�j�Ȃ珤�i�I�[�_���X�g�؁[����
				response.sendRedirect("OrderList");
			} else {
				// �m�f�Ȃ�ē���
				order.AuthError = true;
				response.sendRedirect("UserID");
			}
		} else if (param.GetParam("new") != null) {
			// �u�V�K�o�^�v�{�^���Ȃ烆�[�U�o�^�y�[�W��
			response.sendRedirect("UserReg");
		}
	}
}
