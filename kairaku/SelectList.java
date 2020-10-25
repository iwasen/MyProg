//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�I�����i�m�F����
//		FileName:	SelectList.java
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

// ���i�I�����ʕ\���y�[�W�����N���X
public class SelectList extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "�I�����i�m�F", "", "bgcolor='#CCFFFF'");
		Body(pw, order);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Order order) {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");

		pw.println("<p class='s5' align='left'>���w���i�̊m�F</p>");
		pw.println("<form name='fm' method='post' class='s1'>");
		pw.println("<table width='563' border='2' cellspacing='0' cellpadding='2' height='142' bgcolor='#CCFFCC' class='s1'>");

		pw.println("<tr>");
		pw.println("<td width='95' height='28'>");
		pw.println("<div align='center'>���i�ԍ�</div>");
		pw.println("</td>");
		pw.println("<td width='223' height='28'>");
		pw.println("<div align='center'>���i��</div>");
		pw.println("</td>");
		pw.println("<td width='113' height='28'>");
		pw.println("<div align='center'>�P���i�ō��j</div>");
		pw.println("</td>");
		pw.println("<td width='81' height='28'>");
		pw.println("<div align='center'>��</div>");
		pw.println("</td>");
		pw.println("<td width='81' height='28'>");
		pw.println("<div align='center'>���v</div>");
		pw.println("</td>");
		pw.println("</tr>");

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);

			pw.println("<tr>");
			pw.println("<input type='hidden' name='code' value='" + meisai.ShouhinCode + "'>");
			pw.println("<td width='95' height='45'>");
			pw.println("<div align='center'>" + meisai.ShouhinNo + "</div>");
			pw.println("</td>");
			pw.println("<td width='223' height='45'>");
			pw.println("<div align='center'>" + meisai.ShouhinName + "</div>");
			pw.println("</td>");
			pw.println("<td width='113' height='45'>");
			pw.println("<div align='center'>" + fmt.format(meisai.HanbaiKakaku) + "�~</div>");
			pw.println("</td>");
			pw.println("<td width='81' height='45'>");
			pw.println("<div align='center'>");
			pw.println("<input type='text' name='kosuu' size='4' value='" + meisai.Kosuu + "'> " + meisai.Tani + "</div>");
			pw.println("</td>");
			pw.println("<td width='81' height='45'>");
			pw.println("<div align='center'>" + fmt.format(meisai.Shoukei) + "�~</div>");
			pw.println("</td>");
			pw.println("</tr>");
		}

		pw.println("<tr>");
		pw.println("<td colspan='5'>");
		pw.println("<p align='right'>���v�@" + fmt.format(order.juchu.Total) + "�~�i�ō��݁j</p>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");

		pw.println("<p><span class='s1'><font color='#FF0000'>");
		pw.println("��L�̍��v���z�̑��ɑ����i700�~�j��������܂��B<br>");
		pw.println("��z�������������]�̕��͑����̑��Ɏ萔���i400�~�j�𒸂��܂��B<br>");
		pw.println("�������A15,000�~�ȏエ�����グ�̏ꍇ�A�����A�萔���͖����Ƃ����Ă��������܂��B<br>");
		pw.println("�܂��AHAB�ʐ^�W�Ɋւ��ẮA�����E�萔���́A�����ƂȂ�܂��B���q�l���AHAB�ʐ^�W<br>");
		pw.println("�Ƒ��̏��i�𓯎��ɁA�w�������ꍇ�������E�萔���́A�����܂���B�����ƂȂ�܂��B");
		pw.println("</font></span></p>");

		pw.println("<p>���̕ύX�����̉�ʂŉ\�ł��B���ύX��A�m�Čv�Z�n���N���b�N����ƁA<br>");
		pw.println("���v���z���Čv�Z���܂��B<br>");
		pw.println("���̏��i���w���������ꍇ�́m���̏��i���w������n<br>");
		pw.println("���w���𒆎~���ꂽ�����ɂ́m��߂�n<br>");
		pw.println("�ȉ��̏��i���w�����������́m�w������n���N���b�N���Ă��������B<br>");
		pw.println("<span class='s1'></span></p>");
		pw.println("<p><span class='s1'>");
		pw.println("<input type='submit' name='recalc' value='�Čv�Z'>");
		pw.println("<input type='submit' name='cancel' value='��߂�'>");
		pw.println("<input type='submit' name='other' value='���̏��i���w������'>");
		pw.println("<input type='submit' name='ok' value='�w������'" + (order.juchu.Total == 0 ? " disabled" : "") + ">");
		pw.println("</span>");
		pw.println("</p>");
		pw.println("</form>");
	}

	// "POST"���\�b�h����
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// ���׏����N���A����
		order.DeleteMeisai();

		// ���͂��ꂽ���i�̃R�[�h�ƌ��𖾍׃f�[�^�Ƃ��ĕۑ�
		String code[] = param.GetParams("code");
		String kosuu[] = param.GetParams("kosuu");
		if (code != null) {
			for (int i = 0; i < code.length; i++) {
				int ikosuu = Integer.parseInt(kosuu[i]);
				if (ikosuu > 0)
					order.AddMeisai(code[i], ikosuu);
			}
		}

		// ���v���z�̌v�Z
		int count = order.GetMeisaiNum();
		int total = 0;
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);
			total += meisai.HanbaiKakaku * meisai.Kosuu;
		}
		order.juchu.Total = total;

		// �N���b�N���ꂽ�{�^���ɂ��U�蕪��
		if (param.GetParam("recalc") != null) {
			// �u�Čv�Z�v�Ȃ炱�̃y�[�W���ĕ\��
			doGetHandler(response, pw, session, param, order);
		} else if (param.GetParam("cancel") != null) {
			// �u��߂�v�Ȃ�Z�b�V�������������A�g�b�v�y�[�W�ɖ߂�
			session.invalidate();
			response.sendRedirect(TopPage);
		} else if (param.GetParam("other") != null) {
			// �u���̏��i���w������v�Ȃ猟�������w���ʂɖ߂�
			response.sendRedirect("Kensaku");
		} else if (param.GetParam("ok") != null) {
			// �u�w������v�Ȃ烆�[�U�h�c���̓y�[�W�ɍs��
			response.sendRedirect("UserID");
		}
	}
}
