//**********************************************************************
//		MCDB:�V��:����BOOK TIMES �A���P�[�g���W�V�X�e��
//
//		Title:		�t�H�[����{����
//		FileName:	BaseServlet.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/19
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import java.sql.*;

// �}�X�^�[�����e�i���X���ʂ̃N���X
public class BaseServlet extends HttpServlet {

	// �o�͕����R�[�h
	//protected final String Encoding = "iso-2022-jp";
	protected final String Encoding = "Shift_JIS";

	// PrintWriter �擾����
	protected PrintWriter GetPrintWriter(HttpServletResponse response)
			throws IOException {

		response.setContentType("text/html; charset=" + Encoding);
		return response.getWriter();
	}

	// JavaScript�o�͏���
	protected void JavaScript(PrintWriter pw, String[] script) {
		int i;

		pw.println("<script language='JavaScript'><!--");

		for (i = 0; i < script.length; i++) {
			pw.println(script[i]);
		}

		pw.println("// --></script>");
	}

	// �s���{���I�����X�g�쐬����
	protected void TodouhukenSelect(PrintWriter pw, String name, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MTD_TODOUFUKEN_CODE,MTD_TODOUFUKEN_NAME from M_TODOUFUKEN order by MTD_ORDER_NUM";

		pw.println("<select size=1 name='" + name + "'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// �E�ƑI�����X�g�쐬����
	protected void ShokugyouSelect(PrintWriter pw, String name, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSG_SHOKUGYOU_CODE,MSG_SHOKUGYOU_NAME from M_SHOKUGYOU order by MSG_ORDER_NUM";

		pw.println("<select size=1 name='" + name + "'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// �I�����X�g�쐬���ʏ����i�c�a����j
	private void OutputOptionDB(PrintWriter pw, String sql, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String code;
		String name;
		String sel;

		// �擪�ɒǉ����鍀��
		if (top != null) {
			if (def.equals(""))
				sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + top + "</option>");
		}

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		ResultSet rs = st.executeQuery(sql);

		// �S���R�[�h��ǂݍ��݁A<option>�^�O��t���ďo��
		while (rs.next()) {
			code = rs.getString(1).trim();
			name = rs.getString(2).trim();

			if (def.equals(code))
				sel = " selected";
			else
				sel = "";

			pw.println("<option value='" + code + "'" + sel + ">" + name + "</option>");
		}

		// �Ō�ɒǉ����鍀��
		if (bottom != null) {
			if (def.equals(""))
			sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + bottom + "</option>");
		}

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();
	}

	// �`�F�b�N�{�b�N�X�̓��e�𕶎���ɕϊ�
	protected String GetCheckbox(Param param, String name, int num)
			throws ServletException, IOException {

		StringBuffer buf = new StringBuffer(num);
		int i;

		// ���ʂ� "0" �N���A
		for (i = 0; i < num; i++)
			buf.append('0');

		// �`�F�b�N���ꂽ���ڂ� "1" ���Z�b�g
		String[] val = param.GetParams(name);
		if (val != null) {
			for (i = 0; i < val.length; i++) {
				int no = Integer.parseInt(val[i]);
				buf.setCharAt(no, '1');
			}
		}

		return buf.toString();
	}

	// null �̏ꍇ�A�u�����N�ɕϊ�
	protected String NullToBlank(Object value) {

		if (value == null)
			return "";
		else
			return value.toString();
	}

	// �w�肵���y�[�W�ɃW�����v�isendRedirect()��Netscape�ŕ�����������̂ł��̑���j
	protected void GotoPage(PrintWriter pw, String url) {

		pw.println("<html>");
		pw.println("<head>");
		pw.println("<meta http-equiv='Refresh' content='0; URL=" + url + "'>");
		pw.println("</head>");
		pw.println("</html>");
	}
}
