//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���[�U�[�}�X�^���\������
//		FileName:	UserInfo.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.util.GregorianCalendar;
import java.text.*;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// ���[�U�[�ꗗ��ʏ����N���X
public class UserInfo extends BaseServlet1
{
	// "GET"���\�b�h����
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {

		try {
			// ���O�C���`�F�b�N
			if (!LoginCheck(request)) {
				// ���O�C�����Ă��Ȃ��ꍇ�͋����I�Ƀg�b�v�y�[�W�ɖ߂�
				response.sendRedirect(TopPage);
				return;
			}

			Param param = new Param(request);
			response.setContentType("application/vnd.ms-excel; charset=iso-2022-jp");
			PrintWriter pw = response.getWriter();

			Body(pw, param);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Param param)
		throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select M_USER.*,MSG_SHOKUGYOU_NAME from M_USER,M_SHOKUGYOU where MUS_SHOKUGYO_CODE=MSG_SHOKUGYOU_CODE";
		String year = param.GetParam("year");
		if (year != null && !year.equals("")) {
			int nyear = Integer.parseInt(param.GetParam("year"));
			int nmonth = Integer.parseInt(param.GetParam("month"));
			int ndate = Integer.parseInt(param.GetParam("date"));
			GregorianCalendar date = new GregorianCalendar(nyear, nmonth - 1, ndate);
			sql += " and MUS_TOUROKU_DATE >= '" + date.getTime() + "'";
		}
		sql += " order by MUS_USER_CODE";
		ResultSet rs = st.executeQuery(sql);

		// �w�b�_���o��
		pw.println("<html>");
		pw.println("<head>");
		pw.println("<title>���[�U�[�ꗗ</title>");
		pw.println("</head>");
		pw.println("<body>");

		// �J�����w�b�_�o��
		pw.println("<table border=1 BORDERCOLOR='gray'>");
		pw.println("<tr>");
		//pw.println("<th width='10%' bgcolor='#FFE0E0'>���[�U�[�R�[�h</th>");
		pw.println("<th width='10%' bgcolor='#FFE0E0'>���[�U�[�h�c</th>");
		pw.println("<th width='10%' bgcolor='#FFE0E0'>�p�X���[�h</th>");
		pw.println("<th width='20%' bgcolor='#FFE0E0'>�o�^��</th>");
		pw.println("<th width='20%' bgcolor='#FFE0E0'>�ӂ肪��</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>����</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>����</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>���N����</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>�N��</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>18�Έȏ�m�F</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>18�Έȏ�m�F��</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>�E��</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>�X�֔ԍ�</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>�Z��</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>������</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>�A����d�b�ԍ�</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>FAX�ԍ�</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>���[���A�h���X</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>���[�����O���X�g�o�^</th>");
		pw.println("<th width='15%' bgcolor='#FFE0E0'>�t�q�k</th>");
		pw.println("</tr>");
		pw.println("</thead>");

		// ���R�[�h���o��
		while (rs.next()) {
			pw.println("<tr>");
			//pw.println("<td>" + rs.getString(1) + "</td>");
			pw.println("<td>" + rs.getString(2) + "</td>");
			pw.println("<td>" + rs.getString(3) + "</td>");
			pw.println("<td>" + myutil.DateToString(rs.getDate(4)) + "</td>");
			pw.println("<td>" + rs.getString(5) + "</td>");
			pw.println("<td>" + rs.getString(6) + "</td>");
			pw.println("<td>" + rs.getString(7) + "</td>");
			pw.println("<td>" + rs.getString(8) + "/" + rs.getString(9) + "/" + rs.getString(10) + "</td>");
			pw.println("<td>" + rs.getInt(11) + "</td>");
			pw.println("<td>" + rs.getBoolean(12) + "</td>");
			pw.println("<td>" + myutil.DateToString(rs.getDate(13)) + "</td>");
			pw.println("<td>" + rs.getString("MSG_SHOKUGYOU_NAME") + "</td>");
			pw.println("<td>" + rs.getString(15) + "</td>");
			pw.println("<td>" + rs.getString(16) + "</td>");
			pw.println("<td>" + rs.getString(17) + "</td>");
			pw.println("<td>" + rs.getString(18) + "</td>");
			pw.println("<td>" + rs.getString(19) + "</td>");
			pw.println("<td>" + rs.getString(20) + "</td>");
			pw.println("<td>" + rs.getBoolean(21) + "</td>");
			pw.println("<td>" + rs.getString(22) + "</td>");
			pw.println("</tr>");
		}
		pw.println("</table>");

		pw.println("</body>");
		pw.println("</html>");

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();
	}
}
