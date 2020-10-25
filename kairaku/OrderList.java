//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�󒍏����͏���
//		FileName:	OrderList.java
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

// ���i�I�[�_���X�g�\���y�[�W�����N���X
public class OrderList extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "�󒍏�����", "", "bgcolor='#CCFFFF'");
		Body(pw, order);
		Script(pw);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");
		Order.User user = order.user;
		Order.Juchu juchu = order.juchu;

		// �͂�����̏����l�����[�U��񂩂�ݒ�
		juchu.Zip = user.Zip;
		juchu.Address = user.Address;
		juchu.Tatemono = user.Tatemono;
		juchu.TelNo = user.TelNo;

		// �󒍏���\��
		pw.println("<form name='form1' method='post' class='s1' onSubmit='return OnSubmit();'>");
		pw.println("<p class='s5' align='center'><font color='#330099'>��" + user.Shimei + "�l�@�I�[�_�[��</font></p>");
		pw.println("<p align='center' class='s5'>�w���i</p>");
		pw.println("<div align='center'>");
		pw.println("<table width='560' border='2' cellspacing='1' cellpadding='1' class='s1' bgcolor='#FFFFCC'>");

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);

			pw.println("<tr>");
			pw.println("<td width='60' nowrap>" + meisai.ShouhinNo + "</td>");
			pw.println("<td width='241' nowrap>" + meisai.ShouhinName + "</td>");
			pw.println("<td width='74' nowrap>" + meisai.Kosuu + meisai.Tani + "</td>");
			pw.println("<td width='163' nowrap>");
			pw.println("<div align='right'>" + fmt.format(meisai.Shoukei) + "�~</div>");
			pw.println("</td>");
			pw.println("</tr>");
		}

		pw.println("<tr>");
		pw.println("<td colspan='4' nowrap>");
		pw.println("<div align='right'>���v���z�i�ō��j�@" + fmt.format(order.juchu.Total) + "�~</div>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<br>");

		pw.println("<span class='s1'><font color='#FF0000'>");
		pw.println("��L�̍��v���z�̑��ɑ����i700�~�j��������܂��B<br>");
		pw.println("��z�������������]�̕��͑����̑��Ɏ萔���i400�~�j�𒸂��܂��B<br>");
		pw.println("�������A15,000�~�ȏエ�����グ�̏ꍇ�A�����A�萔���͖����Ƃ����Ă��������܂��B<br>");
		pw.println("�܂��AHAB�ʐ^�W�Ɋւ��ẮA�����E�萔���́A�����ƂȂ�܂��B���q�l���AHAB�ʐ^�W<br>");
		pw.println("�Ƒ��̏��i�𓯎��ɁA�w�������ꍇ�������E�萔���́A�����܂���B�����ƂȂ�܂��B");
		pw.println("</font></span>");

		pw.println("</div>");
		pw.println("<blockquote><font color='#330099'>");
		pw.println("�@�@�o�^��Z���ȊO�̏ꏊ�ɑ��t������]�̏ꍇ��<br>");
		pw.println("�@�@���L�u���͂���X�֔ԍ��v�u���͂���Z���v�u���͂��挚���v�u���͂���d�b�ԍ��v����<br>");
		pw.println("�@�@����]�̗X�֔ԍ��A�Z���A�����A�d�b�ԍ�����͂��āu���ցv���N���b�N���ĉ������B<br>");
		pw.println("�@�@���͂���͂������̓x�Ɏw�肷�邱�Ƃ��o���܂��B<br>");
		pw.println("</font></blockquote>");
		pw.println("<table cellpadding=5 class='s1'>");
		pw.println("<tr>");
		pw.println("<td align='right'>���x�������@</td>");
		pw.println("<td>");
		myutil.ShiharaiSelect(pw, "", null, null);
		pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>���͂���X�֔ԍ�</td>");
		pw.println("<td><input type='text' name='postnum' size='10' value='" + juchu.Zip + "'>");
		pw.println("�@<font color='#0000FF'>���p�����@��F123-4567 </font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>���͂���Z��</td>");
		pw.println("<td><input type='text' name='tjuusyo' size='80' value='" + juchu.Address + "'>");
		pw.println("�@<font color='#0000FF'>�S�p���ȁE����</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>���͂��挚��</td>");
		pw.println("<td><input type='text' name='tatemono' size='40' value='" + juchu.Tatemono + "'>");
		pw.println("�@<font color='#0000FF'>�S�p���ȁE����</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>���͂���d�b�ԍ�</td>");
		pw.println("<td><input type='text' name='tel' value='" + juchu.TelNo + "'>");
		pw.println("�@<font color='#0000FF'>��@00-0000-0000</font></td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<p><center>");
		pw.println("<input type='submit' name='next' value=' ���� '>");
		pw.println("</center></p>");
		pw.println("</form>");
	}

	// JavaScript�o�͏���
	private void Script(PrintWriter pw)
			throws ServletException, IOException {
		final String[] script = {
			"function OnSubmit() {",
			" var form1 = document.form1;",
			" if (form1.postnum.value == '') {",
			"  alert('���͂���X�֔ԍ�����͂��Ă��������B');",
			"  form1.postnum.focus();",
			"  return false;",
			" }",
			" if (form1.tjuusyo.value == '') {",
			"  alert('���͂���Z������͂��Ă��������B');",
			"  form1.tjuusyo.focus();",
			"  return false;",
			" }",
			" if (form1.tel.value == '') {",
			"  alert('���͂���d�b�ԍ�����͂��Ă��������B');",
			"  form1.tel.focus();",
			"  return false;",
			" }",
			" return true;",
			"}"
		};

		JavaScript(pw, script);
	}

	// "POST"���\�b�h����
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Order.Juchu juchu = order.juchu;

		if (param.GetParam("next") != null) {
			// ���͂��ꂽ�͂�������󒍏��ɕۑ�
			juchu.Zip = param.GetParam("postnum");
			juchu.Address = param.GetParam("tjuusyo");
			juchu.Tatemono = param.GetParam("tatemono");
			juchu.TelNo = param.GetParam("tel");
			juchu.Shiharai = param.GetParam("ShiharaiCode");

			// ���i�I�[�_�m�F��ʂփ��_�C���N�g
			response.sendRedirect("OrderVerify");
		}
	}
}
