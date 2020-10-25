//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���ʃ��[�e�B���e�B
//		FileName:	MyUtil.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.PrintWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.text.ParseException;
import java.sql.*;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.Cookie;

// ���ʃ��[�e�B���e�B�N���X
public class MyUtil
{
	// ���i���ޑI�����X�g�쐬����
	public void BunruiSelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSB_BUNRUI_CODE,MSB_BUNRUI_NAME from M_SBUNRUI order by MSB_ORDER_NO";

		pw.println("<select size=1 name='BunruiCode'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// �J�e�S���[�I�����X�g�쐬����
	public void CategorySelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MCT_CATEGORY_CODE,MCT_CATEGORY_NAME from M_CATEGORY order by MCT_ORDER_NO";

		pw.println("<select size=1 name='CategoryCode'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// �E�ƑI�����X�g�쐬����
	public void ShokugyouSelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSG_SHOKUGYOU_CODE,MSG_SHOKUGYOU_NAME from M_SHOKUGYOU order by MSG_ORDER_NO";

		pw.println("<select size=1 name='ShokugyouCode'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// �x�����@�I�����X�g�쐬����
	public void ShiharaiSelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSI_SHIHARAI_CODE,MSI_SHIHARAI_NAME from M_SHIHARAI order by MSI_ORDER_NO";

		pw.println("<select size=1 name='ShiharaiCode'>");
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

	// ���ʑI�����X�g�쐬����
	public void SeibetsuSelect(PrintWriter pw, String def, String top, String bottom) {

		final String[][] table = {
			{"�j", "�j"},
			{"��", "��"}
		};

		pw.println("<select size=1 name='Seibetsu'>");
		OutputOptionTable(pw, table, def, top, bottom);
		pw.println("</select>");
	}

	// �I�����X�g�쐬���ʏ����i�e�[�u������j
	private void OutputOptionTable(PrintWriter pw, String[][] table, String def, String top, String bottom) {

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

		// �S���R�[�h��ǂݍ��݁A<option>�^�O��t���ďo��
		for (int i = 0; i < table.length; i++) {
			code = table[i][0];
			name = table[i][1];

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
	}

	// ���l�I�����X�g�쐬����
	public void NumberSelect(PrintWriter pw, String name, int start, int end, int def) {

		String sel;

		pw.println("<select size=1 name='" + name + "'>");

		for (int i = start; i <= end; i++) {
			if (def == i)
				sel = " selected";
			else
				sel = "";

			pw.println("<option value='" + i + "'" + sel + ">" + i + "</option>");
		}

		pw.println("</select>");
	}

	// boolean�^�I��p���W�I�{�^���쐬����
	public void BooleanRadio(PrintWriter pw, String name, boolean flag, String f, String t) {

		pw.println("<input type='radio' name='" + name + "' value='false'" + (!flag ? " checked" : "") + ">" + f);
		pw.println("<input type='radio' name='" + name + "' value='true'" + (flag ? " checked" : "") + ">" + t);
	}

	// �E�Ɩ��擾����
	public String GetShokugyouName(String code)
			throws ServletException, IOException, SQLException {

		String sql = "select MSG_SHOKUGYOU_NAME from M_SHOKUGYOU where MSG_SHOKUGYOU_CODE='" + code + "'";

		return LookupMaster(sql);
	}

	// �x�����@���擾����
	public String GetShiharaiName(String code)
			throws ServletException, IOException, SQLException {

		String sql = "select MSI_SHIHARAI_NAME from M_SHIHARAI where MSI_SHIHARAI_CODE='" + code + "'";

		return LookupMaster(sql);
	}

	// �R�[�h���疼�̎擾���ʏ���
	private String LookupMaster(String sql)
			throws ServletException, IOException, SQLException {

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		ResultSet rs = st.executeQuery(sql);
		String name = null;

		// ������Ζ��̂��擾
		if (rs.next())
			name = rs.getString(1);

		// �f�[�^�x�[�X�N���[�Y
		rs.close();
		st.close();

		return name;
	}

	// ����������u������
	public String ReplaceString(String str, String src, String dst) {

		int pos1 = 0;
		int pos2;
		int len = src.length();
		String buf = "";

		while (true) {
			pos2 = str.indexOf(src, pos1);
			if (pos2 == -1)
				break;

			buf += str.substring(pos1, pos2) + dst;
			pos1 = pos2 + len;
		}

		buf += str.substring(pos1);

		return buf;
	}

	// ���t�𕶎���ɕϊ��inull�̓u�����N�j
	public String DateToString(Date date) {

		if (date == null)
			return "";
		else
			return new SimpleDateFormat("yyyy/MM/dd").format(date);
	}

	// ���������t�ɕϊ��i�ϊ��ł��Ȃ��ꍇ��null�j
	public Date StringToDate(String date) {

		try {
			return new SimpleDateFormat("yyyy/MM/dd").parse(date);
		} catch (ParseException e) {
			return null;
		}
	}

	// null �̏ꍇ�A"null"�Ƃ��������ɕϊ�
	public String NullAble(Object value) {

		if (value == null)
			return "null";
		else
			return "'" + value.toString() + "'";
	}

	// Cookie��������
	public void PutCookie(HttpServletResponse response, String name, String value) {

		Cookie cookie = new Cookie(name, value);
		response.addCookie(cookie);
	}

	// Cookie�擾
	public String GetCookieValue(HttpServletRequest request, String name) {

		Cookie[] cookies;
		Cookie cookie;

		if ((cookies = request.getCookies()) != null) {
			for (int i = 0; i < cookies.length; i++) {
				if (cookies[i].getName().equals(name))
      				return cookies[i].getValue();
			}
		}

		return null;
	}
}
