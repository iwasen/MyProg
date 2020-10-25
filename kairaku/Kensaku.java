//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�����������͏���
//		FileName:	Kensaku.java
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

// ���������w��y�[�W�����N���X
public class Kensaku extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "������������", "", "bgcolor='#FFCCCC'");
		Body(pw, order);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		pw.println("<p align='center' class='s5'>���y�s��@�w�����i���� </p>");
		pw.println("<div align='center'>");
		pw.println("<table width='379' border='3' cellspacing='0' cellpadding='2' height='148' align='center' bgcolor='#CCCCCC' bordercolordark='#000000' bordercolorlight='#FFFFFF' class='s1'>");
		pw.println("<tr>");
		pw.println("<td>");
		pw.println("<form method='get' action='KensakuList' class='s1'>");
		pw.println("<br>");
		pw.println("<table width='100%' cellpadding=5 class='s1'>");
		pw.println("<tr>");
		pw.println("<td align='right' width='40%'>���i����</td>");
		pw.println("<td>"); myutil.BunruiSelect(pw, order.bunrui, null, "���ׂ�"); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>���i�J�e�S���[</td>");
		pw.println("<td>"); myutil.CategorySelect(pw, "", null, "���ׂ�"); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>�o�^��</td>");
		pw.println("<td><select name='kikan'>");
		pw.println("<option value='1'>�ŐV�P�J��</option>");
		pw.println("<option value='3'>�ŐV�R�J��</option>");
		pw.println("<option value='6'>�ŐV�U�J��</option>");
		pw.println("<option value='12'>�ŐV�P�N</option>");
		pw.println("<option value='' selected>���ׂ�</option>");
		pw.println("</select></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>�o���E�剉��</td>");
		pw.println("<td><input type='text' name='shuen' size='20'></td>");
		pw.println("</tr></table>");
		pw.println("<br>");
		pw.println("<center>");
		pw.println("<input type='submit' value='�@�����@'>");
		pw.println("<input type='reset' value='���Z�b�g'>");
		pw.println("</center>");
		pw.println("</form>");        
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("</div>");
	}
}
