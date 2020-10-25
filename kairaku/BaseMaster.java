//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�}�X�^�[�X�V��{�N���X
//		FileName:	BaseMaster.java
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

// �R�[�h�A�\�����A���̂����̃}�X�^�[��p��{�N���X
public abstract class BaseMaster extends BaseServlet1
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

			PrintWriter pw = GetPrintWriter(response);
			Header(pw, GetTitle());
			Body(pw);
			Script(pw);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw)
		throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		ResultSet rs = st.executeQuery(GetSelectSql());

		// �J�������擾
		String[] header = GetHeader();

		// �w�b�_���o��
		pw.println("<form method='post' name='fm' onSubmit='return OnSubmit();'>");
		pw.println("<center>");
		pw.println("<table border=1 bgcolor='#E0FFE0'>");
		pw.println("<tr bgcolor='#FFE0E0'>");
		pw.println("<th>" + header[0] + "</th>");
		pw.println("<th>" + header[1] + "</th>");
		pw.println("<th>" + header[2] + "</th>");
		pw.println("</tr>");

		// ���R�[�h���e�o��
		while (rs.next()) {
			pw.println("<tr>");
			pw.println("<td><input type='text' name='code' value='" + rs.getString(1).trim() + "' size=20></td>");
			pw.println("<td><input type='text' name='order' value='" + rs.getString(2).trim() + "' size=20></td>");
			pw.println("<td><input type='text' name='name' value='" + rs.getString(3).trim() + "' size=50></td>");
			pw.println("</tr>");
		}

		// �V�K�ǉ��p�̍s���o��
		pw.println("<tr>");
		pw.println("<td><input type='text' name='code' size=20></td>");
		pw.println("<td><input type='text' name='order' size=20></td>");
		pw.println("<td><input type='text' name='name' size=50></td>");
		pw.println("</tr>");

		// �{�^���o��
		pw.println("</table><br>");
		pw.println("<input type='submit' value='�@�X�V�@'>");
		pw.println("<input type='button' value='�@�I���@' onClick='location.href=\"master\"'>");
		pw.println("</center>");
		pw.println("</form>");

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();
	}

	// �N���C�A���g�pJavaScrip�o��
	private void Script(PrintWriter pw)
			throws ServletException, IOException {
		final String[] script = {
			"function OnSubmit() {",
			" return confirm('�}�X�^�[���X�V���܂��B��낵���ł����H')",
			"}",
		};

		JavaScript(pw, script);
	}

	// "POST" ���\�b�h����
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {

		try {
			// �}�X�^�[�e�[�u���X�V
			String msg = UpdateMaster(request);
			if (msg == null)
				doGet(request, response);
			else {
				PrintWriter pw = GetPrintWriter(response);
				Header(pw, GetTitle());
				pw.println("<h2>�X�V�ŃG���[���������܂����B</h2>");
				pw.println(msg + "<p>");
				pw.println("<input type='button' value='�߂�' onClick='history.back()'>");
				Footer(pw);
			}
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �}�X�^�[�e�[�u���X�V����
	private String UpdateMaster(HttpServletRequest request)
		throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		Param param = new Param(request);
		DataBase db = new DataBase();
		Statement st = db.createStatement();

		// �g�����U�N�V�����J�n
		db.Transaction();

		int i = 0;

		try {
			// �S���R�[�h�폜
			st.executeUpdate(GetDeleteSql());

			// ���̓f�[�^�擾
			String[] code = param.GetParams("code");
			String[] order = param.GetParams("order");
			String[] name = param.GetParams("name");

			// ���͂��ꂽ���R�[�h����Insert���s
			for (i = 0; i < code.length; i++) {
				if (!code[i].equals("")) {
					String sql = GetInsertSql() + " ('" + code[i] + "'," + order[i] + ",'" + name[i] + "')";
					st.executeUpdate(sql);
				}
			}

			// �f�[�^�x�[�X�N���[�Y
			st.close();

			// �R�~�b�g
			db.Commit();
		} catch (SQLException e) {
			// �G���[�����������ꍇ�̓��[���o�b�N
			db.Rollback();
			return e.toString();
		} catch (NumberFormatException e) {
			// ���l���ڂɐ����ȊO�����͂��ꂽ�ꍇ
			db.Rollback();
			return (i + 1) + "�s�ڂ̓��̓f�[�^�Ɍ�肪����܂��B";
		}

		return null;
	}

	// ���ꂼ��̃}�X�^�[�ɍ��킹�ăJ�X�^�}�C�Y�p�̊֐��Q
	protected abstract String GetTitle();
	protected abstract String GetSelectSql();
	protected abstract String GetDeleteSql();
	protected abstract String GetInsertSql();
	protected abstract String[] GetHeader();
}
