//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�ꗗ�\�����i�w�菈��
//		FileName:	KensakuList.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// �������ʕ\���y�[�W�����N���X
public class KensakuList extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "�ꗗ�\�����i�w��", "", "bgcolor='#CCFFFF' class='s1'");
		Body(pw, param);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Param param)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select M_SHOUHIN.*,MSB_BUNRUI_NAME,MCT_CATEGORY_NAME"
				+ " from M_SHOUHIN,M_SBUNRUI,M_CATEGORY"
				+ " where MSH_BUNRUI_CODE=MSB_BUNRUI_CODE and MSH_CATEGORY_CODE=MCT_CATEGORY_CODE %1"
				+ " order by MSH_SHOUHIN_CODE";

		String str;
		String where = "";

		// ���i���ރR�[�h�̎w�肪����Ώ����ɉ�����
		str = param.GetParam("BunruiCode");
		if (str != null && !str.equals(""))
			where += " and MSH_BUNRUI_CODE='" + str + "'";

		// �J�e�S���[�R�[�h�̎w�肪����Ώ����ɉ�����
		str = param.GetParam("CategoryCode");
		if (str != null && !str.equals(""))
			where += " and MSH_CATEGORY_CODE='" + str + "'";

		// ���Ԃ̎w�肪����Ώ����ɉ�����
		str = param.GetParam("kikan");
		if (str != null && !str.equals("")) {
			GregorianCalendar date = new GregorianCalendar();
			date.add(Calendar.MONTH, -Integer.parseInt(str));
			where += " and MSH_TOUROKU_DATE>'" + date.getTime() + "'";
		}

		// �剉�̎w�肪����Ώ����ɉ�����
		str = param.GetParam("shuen");
		if (str != null && !str.equals(""))
			where += " and MSH_ACTOR_NAME like '%" + str + "%'";

		// SQL ���s
		sql = myutil.ReplaceString(sql, "%1", where);
		ResultSet rs = st.executeQuery(sql);
		int count = 0;

		// ���ʂ�html�ɏo��
		pw.println("<p class='s6' align='center'>��������</p>");
		pw.println("<form name='fm' method='post'>");
		while (rs.next()) {
			pw.println("<input type='hidden' name='code' value='" + rs.getString("MSH_SHOUHIN_CODE") + "'>");
			pw.println("<table width='825' border='2' cellspacing='0' cellpadding='4' height='117' bgcolor='#FFFFCC' class='s3'>");
			pw.println("<tr>");
			pw.println("<td width='63' height='25'>");
			pw.println("<div align='center'>" + rs.getString("MSH_SHOUHIN_NO") + "</div>");
			pw.println("</td>");
			pw.println("<td width='143' rowspan='3'>");
			pw.println("<div align='center'><a href='" + pictLogicalPath + rs.getString("MSH_GAZOU2_FILE") + "' target='new'><img src='" + pictLogicalPath + rs.getString("MSH_GAZOU1_FILE") + "' width='121' height='90'></a></div>");
			pw.println("</td>");
			pw.println("<td width='128' height='25'>");
			pw.println("<div align='center'>" + ConvertBlank(rs.getString("MSH_SERIES_NAME")) + "</div>");
			pw.println("</td>");
			pw.println("<td width='179' height='25'>");
			pw.println("<div align='center'>" + ConvertBlank(rs.getString("MSH_ACTOR_NAME")) + "</div>");
			pw.println("</td>");
			pw.println("<td width='127' rowspan='3'>");
			pw.println("<p align='center'>�艿" + fmt.format(rs.getInt("MSH_TEIKA_YEN")) + "�~</p>");
			pw.println("<p align='center' class='s1'><font color='#FF0000'>����" + fmt.format(rs.getInt("MSH_HANBAI_YEN")) + "�~</font>");
			pw.println("</p>");
			pw.println("</td>");
			pw.println("<td width='78' rowspan='3'>");
			pw.println("<div align='center'>�����ɓ����<br>");
			pw.println("<input type='checkbox' name='basket' value='" + count + "'>");
			pw.println("</div>");
			pw.println("</td>");
			pw.println("<td width='61' rowspan='3' nowrap>");
			pw.println("<div align='center'>");
			pw.println("<input type='text' name='kosuu' size='4' value='1'> " + rs.getString("MSH_TANI_TEXT") + "</div>");
			pw.println("</td>");
			pw.println("</tr>");
			pw.println("<tr>");
			pw.println("<td width='63' rowspan='2'>");
			pw.println("<div align='center'>" + rs.getString("MSB_BUNRUI_NAME") + "</div>");
			pw.println("</td>");
			pw.println("<td width='128' height='23'>");
			pw.println("<div align='center' class='s3'>" + rs.getString("MCT_CATEGORY_NAME") + "</div>");
			pw.println("</td>");
			pw.println("<td rowspan='2' width='179'> ");
			pw.println("<div align='center'>" + ConvertBlank(ConvertCRLF(rs.getString("MSH_KAISETSU_TEXT"))) + "</div>");
			pw.println("</td>");
			pw.println("</tr>");
			pw.println("<tr>");
			pw.println("<td width='128' height='27'>");
			pw.println("<div align='center' class='s1'><font color='#0000CC'>" + rs.getString("MSH_SHOUHIN_NAME") + "</font></div>");
			pw.println("</td>");
			pw.println("</tr>");
			pw.println("</table>");

			count++;
		}

		// �O���������ꍇ�̃��b�Z�[�W
		if (count == 0) {
			pw.println("<h3>�w�肳�ꂽ�����ɍ������i�͂���܂���B</h3>");
		}

		// �{�^�������̏o��
		pw.println("<p><br>");
		pw.println("<span class='s1'>");
		pw.println("<input type='submit' name='register' value='���W�֍s��'>");
		pw.println("<input type='submit' name='continue' value='�������𑱂���'>");
		pw.println("</span></p>");
		pw.println("</form>");

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();
	}

	// "POST"���\�b�h����
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// �I�����ʂ̎擾
		String[] code = param.GetParams("code");
		String[] basket = param.GetParams("basket");
		String[] kosuu = param.GetParams("kosuu");
		// �u�����ɓ����v���`�F�b�N����Ă���f�[�^�𖾍׃f�[�^�Ƃ��ĕۑ�
		if (basket != null) {
			for (int i = 0; i < basket.length; i++) {
				int index = Integer.parseInt(basket[i]);
				int ikosuu = Integer.parseInt(kosuu[index]);
				if (ikosuu > 0) {
					order.AddMeisai(code[index], ikosuu);
				}
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
		if (param.GetParam("register") != null) {
			// �u���W�֍s���v�̏ꍇ�͑I�����i�m�F��ʂ�
			response.sendRedirect("SelectList");
		} else if (param.GetParam("continue") != null) {
			// �u�������𑱂���v�̏ꍇ�͌��������w���ʂ�
			response.sendRedirect("Kensaku");
		}
	}
}
