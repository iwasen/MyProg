//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���i�}�X�^�ꗗ����
//		FileName:	ShouhinList.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.text.*;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// ���i�ꗗ��ʏ����N���X
public class ShouhinList extends BaseServlet1
{
	// "GET"���\�b�h����
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {

		try {
			// ���O�C���`�F�b�N
			if (!LoginCheck(request)) {
				// ���O�C�����Ă��Ȃ��ꍇ�͋����I�Ƀg�b�v�y�[�W�ɖ߂�
				response.sendRedirect(TopPage);
				return;
			}

			MyUtil myutil = new MyUtil();
			Param param = new Param(request);
			PrintWriter pw = GetPrintWriter(response);
			String no;

			// �\�����鎁�����擾
			no = param.GetParam("no");
			if (no == null)
				no = myutil.GetCookieValue(request, "ShouhinNo");
			else
				myutil.PutCookie(response, "ShouhinNo", no);

			Header(pw, "���i�ꗗ");
			Body(pw, no);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, String no)
		throws ServletException, IOException, SQLException {

		String code;
		SimpleDateFormat formatter = new SimpleDateFormat ("yyyy/MM/dd");
		int count = 0;

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select MSH_SHOUHIN_CODE,MSH_SHOUHIN_NO,MSH_SHOUHIN_NAME,MSB_BUNRUI_NAME,MCT_CATEGORY_NAME,MSH_TOUROKU_DATE"
				+ " from M_SHOUHIN,M_SBUNRUI,M_CATEGORY"
				+ " where MSH_BUNRUI_CODE=MSB_BUNRUI_CODE and MSH_CATEGORY_CODE=MCT_CATEGORY_CODE";
		if (no != null && !no.equals(""))
			sql += " and MSH_SHOUHIN_NO like '" + no + "%'";
		sql += " order by MSH_SHOUHIN_CODE";
		ResultSet rs = st.executeQuery(sql);

		// �J�����w�b�_�o��
		pw.println("<table border=1 width='96%' bgcolor='#E0FFE0'>");
		pw.println("<tr bgcolor='#FFE0E0'>");
		pw.println("<th width='9%'>���i�R�[�h</th>");
		pw.println("<th width='15%'>���i�ԍ�</th>");
		pw.println("<th width='38%'>���i��</th>");
		pw.println("<th width='10%'>���i����</th>");
		pw.println("<th width='18%'>�J�e�S���[</th>");
		pw.println("<th width='10%'>�o�^��</th>");
		pw.println("</tr>");

		// ���R�[�h���o��
		while (rs.next()) {
			pw.println("<tr>");
			code = rs.getString(1);
			pw.println("<td><a href='ShouhinItem?code=" + code + "'>" + code + "</td>");
			pw.println("<td>" + rs.getString(2) + "</td>");
			pw.println("<td>" + rs.getString(3) + "</td>");
			pw.println("<td>" + rs.getString(4) + "</td>");
			pw.println("<td>" + rs.getString(5) + "</td>");
			pw.println("<td>" + formatter.format(rs.getDate(6)) + "</td>");
			pw.println("</tr>");
			count++;
		}
		pw.println("</table>");

		// �����\��
		pw.println("<div align='right'>�����F" + count + "</div>");

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();
	}
}
