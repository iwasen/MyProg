//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���[�U�[�}�X�^�ꗗ����
//		FileName:	UserList.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.text.*;
import java.sql.*;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// ���[�U�[�ꗗ��ʏ����N���X
public class UserList extends BaseServlet1
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
			String name;

			// �\�����鎁�����擾
			name = param.GetParam("name");
			if (name == null)
				name = myutil.GetCookieValue(request, "UserName");
			else
				myutil.PutCookie(response, "UserName", name);

			Header(pw, "���[�U�[�ꗗ");
			Body(pw, name);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, String name)
		throws ServletException, IOException, SQLException {

		SimpleDateFormat formatter = new SimpleDateFormat ("yyyy/MM/dd");
		int count = 0;

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		String sql = "select MUS_USER_CODE,MUS_USER_ID,MUS_PASSWORD_TEXT,MUS_18OVER_FLAG,MUS_SHIMEI_TEXT,MUS_FURIGANA_TEXT,MUS_MAIL_ADDR,MUS_TEL_NO from M_USER";
		if (name != null && !name.equals(""))
			sql += " where MUS_SHIMEI_TEXT like '" + name + "%'";
		sql += " order by MUS_USER_CODE";
		ResultSet rs = st.executeQuery(sql);

		// �J�����w�b�_�o��
		pw.println("<table border=1 width='96%' bgcolor='#E0FFE0'>");
		pw.println("<tr bgcolor='#FFE0E0'>");
		pw.println("<th width='10%'>���[�U�[ID</th>");
		pw.println("<th width='10%'>�p�X���[�h</th>");
		pw.println("<th width='10%'>18�Έȏ�</th>");
		pw.println("<th width='20%'>����</th>");
		pw.println("<th width='20%'>�ӂ肪��</th>");
		pw.println("<th width='15%'>e-mail</th>");
		pw.println("<th width='15%'>�d�b�ԍ�</th>");
		pw.println("</tr>");

		// ���R�[�h���o��
		while (rs.next()) {
			pw.println("<tr>");
			pw.println("<td><a href='UserUpdate?code=" + rs.getString(1) + "'>" + rs.getString(2) + "</td>");
			pw.println("<td>" + rs.getString(3) + "</td>");
			pw.println("<td align='center'>" + (rs.getBoolean(4) ? "��" : "<br>") + "</td>");
			pw.println("<td>" + rs.getString(5) + "</td>");
			pw.println("<td>" + rs.getString(6) + "</td>");
			pw.println("<td>" + rs.getString(7) + "</td>");
			pw.println("<td>" + rs.getString(8) + "</td>");
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
