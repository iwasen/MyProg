//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���[�U�[�}�X�^�X�V����
//		FileName:	UserUpdate.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/10
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// ���[�U�[�X�V�폜�����N���X
public class UserUpdate extends BaseServlet1
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
			Header(pw, "���[�U�[�}�X�^�[�X�V");
			Body(pw, request);
			Script(pw);
			Footer(pw);
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, HttpServletRequest request)
			throws ServletException, IOException, SQLException {

		Param param = new Param(request);
		String code = param.GetParam("code");

		UserMaster um = new UserMaster();
		if (code != null) {
			// �R�[�h�̎w�肪����΍X�V�܂��͍폜�Ȃ̂Ō��݂̃f�[�^���擾
			if (um.Read(code)) {
				UserInfo(pw, um, true);
			} else {
				pw.println("<h2>�w�肵�����[�U�R�[�h�͓o�^����Ă��܂���B</h2>");
				pw.println("<a href='master'>�߂�</a>");
			}
		} else {
			// �R�[�h�̎w�肪�Ȃ���ΐV�K�ǉ�
			um.Init();
			um.UserCode = new Contrxxxaster().GetNewUserCode();
			UserInfo(pw, um, false);
		}
	}

	// ���[�U�[���\��
	private void UserInfo(PrintWriter pw, UserMaster um, boolean update)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		pw.println("<form method='POST' name='fm' onSubmit='return OnSubmit();'>");
		pw.println("<td><input type='hidden' name='UserCode' size=15 value='" + um.UserCode + "'>");
		pw.println("<table border=1 size='90%' align='center' bgcolor='#E0FFE0'>");
		pw.println("<tr>");
		pw.println("<td bgcolor='#FFE0E0'>���[�U�[�h�c</td>");
		pw.println("<td><input type='text' name='UserID' size=15 value='" + um.UserID + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�p�X���[�h</td>");
		pw.println("<td><input type='text' name='Password' size=15 value='" + um.Password + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�o�^��</td>");
		pw.println("<td><input type='text' name='TourokuDate' size=15 value='" + myutil.DateToString(um.TourokuDate) + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�ӂ肪��</td>");
		pw.println("<td><input type='text' name='Furigana' size=50 value='" + um.Furigana + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>����</td>");
		pw.println("<td><input type='text' name='Shimei' size=50 value='" + um.Shimei + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>����</td>");
		pw.println("<td>"); myutil.SeibetsuSelect(pw, um.Seibetsu, "�I�����Ă�������", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���N����</td>");
		pw.println("<td><input type='text' name='SeinenYear' size=5 value='" + um.SeinenYear + "'>�N");
		myutil.NumberSelect(pw, "SeinenMonth", 1, 12, um.SeinenMonth); pw.println("��");
		myutil.NumberSelect(pw, "SeinenDay", 1, 31, um.SeinenDay); pw.println("��");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�N��</td>");
		pw.println("<td><input type='text' name='Nenrei' size=5 value='" + um.Nenrei + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>18�Έȏ�m�F</td>");
		pw.println("<td>");
		pw.println("<input type='radio' name='Over18Flag' value='false'" + (!um.Over18Flag ? " checked" : "") + " onClick='OnClick_Mikakunin()'>���m�F");
		pw.println("<input type='radio' name='Over18Flag' value='true'" + (um.Over18Flag ? " checked" : "") + " onClick='OnClick_KakuninZumi()'>�m�F�ς�");
		pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>18�Έȏ�m�F��</td>");
		pw.println("<td><input type='text' name='Over18Date' size=15 value='" + myutil.DateToString(um.Over18Date) + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�E��</td>");
		pw.println("<td>"); myutil.ShokugyouSelect(pw, um.Shokugyou, "�I�����Ă�������", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�X�֔ԍ�</td>");
		pw.println("<td><input type='text' name='Zip' size=15 value='" + um.Zip + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�Z��</td>");
		pw.println("<td><input type='text' name='Address' size=50 value='" + um.Address + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>������</td>");
		pw.println("<td><input type='text' name='Tatemono' size=50 value='" + um.Tatemono + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�A����d�b�ԍ�</td>");
		pw.println("<td><input type='text' name='TelNo' size=20 value='" + um.TelNo + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>FAX�ԍ�</td>");
		pw.println("<td><input type='text' name='FaxNo' size=20 value='" + um.FaxNo + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���[���A�h���X</td>");
		pw.println("<td><input type='text' name='MailAddr' size=50 value='" + um.MailAddr + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���[�����O���X�g�o�^</td>");
		pw.println("<td>"); myutil.BooleanRadio(pw, "MailList", um.MailList, "���o�^", "�o�^�ς�"); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�t�q�k</td>");
		pw.println("<td><input type='text' name='Url' size=50 value='" + um.Url + "'></td>");
		pw.println("</tr>");
		pw.println("</table><p>");

		pw.println("<center>");
		pw.println("<input type='hidden' name='action'>");
		if (update) {
			pw.println("<input type='submit' name='change' value='�@�ύX�@' onClick='document.fm.action.value=\"�ύX\"'>");
			pw.println("<input type='submit' name='delete' value='�@�폜�@' onClick='document.fm.action.value=\"�폜\"'>");
			pw.println("<input type='submit' name='cancel1' value='�@���~�@' onClick='document.fm.action.value=\"���~\"'>");
		} else {
			pw.println("<input type='submit' name='regist' value='�@�o�^�@' onClick='document.fm.action.value=\"�o�^\"'>");
			pw.println("<input type='submit' name='cancel2' value='�@���~�@' onClick='document.fm.action.value=\"���~\"'>");
		}
		pw.println("</center>");
		pw.println("</form>");
	}

	// JavaScript�o��
	private void Script(PrintWriter pw)
			throws ServletException, IOException {

		final String[] script = {
			"function OnClick_Mikakunin() {",
			" document.fm.Over18Date.value = '';",
			"}",
			"function OnClick_KakuninZumi() {",
			" xx = new Date();",
			" yy = xx.getYear();",
			" mm = xx.getMonth() + 1;",
			" dd = xx.getDate();",
			" if (yy < 2000) yy += 1900;",
			" if (mm < 10) mm = '0' + mm;",
			" if (dd < 10) dd = '0' + dd;",
			" document.fm.Over18Date.value = yy + '/' + mm + '/' + dd;",
  			"}",
			"function OnSubmit() {",
			" with (document.fm) {",
			" if (action.value == '�o�^' || action.value == '�ύX') {",
			" if (UserID.value == '') {",
			"  alert('���[�U�[�h�c����͂��Ă��������B');",
			"  UserID.focus();",
			"  return false;",
			" }",
			" if (Password.value == '') {",
			"  alert('�p�X���[�h����͂��Ă��������B');",
			"  Password.focus();",
			"  return false;",
			" }",
			" if (TourokuDate.value == '') {",
			"  alert('�o�^������͂��Ă��������B');",
			"  TourokuDate.focus();",
			"  return false;",
			" }",
			" if (Furigana.value == '') {",
			"  alert('�ӂ肪�Ȃ�I�����Ă��������B');",
			"  Furigana.focus();",
			"  return false;",
			" }",
			" if (Shimei.value == '') {",
			"  alert('��������͂��Ă��������B');",
			"  Shimei.focus();",
			"  return false;",
			" }",
			" if (Seibetsu.value == '') {",
			"  alert('���ʂ�I�����Ă��������B');",
			"  Seibetsu.focus();",
			"  return false;",
			" }",
			" if (SeinenYear.value == '') {",
			"  alert('���N��������͂��Ă��������B');",
			"  SeinenYear.focus();",
			"  return false;",
			" } else {",
			"  if (isNaN(Number(SeinenYear.value))) {",
			"   alert('�N������������܂���B');",
			"   SeinenYear.focus();",
			"   SeinenYear.select();",
			"   return false;",
			"  }",
			" }",
			" if (ShokugyouCode.value == '') {",
			"  alert('�E�Ƃ�I�����Ă��������B');",
			"  ShokugyouCode.focus();",
			"  return false;",
			" }",
			" if (Zip.value == '') {",
			"  alert('�X�֔ԍ�����͂��Ă��������B');",
			"  Zip.focus();",
			"  return false;",
			" }",
			" if (Address.value == '') {",
			"  alert('�Z������͂��Ă��������B');",
			"  Address.focus();",
			"  return false;",
			" }",
			" }",
			" return confirm('���̃��[�U�[��' + action.value + '���܂��B��낵���ł����H')",
			"}}",
		};

		JavaScript(pw, script);
	}

	// "POST"���\�b�h����
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		Param param = new Param(request);

		try {
			// �{�^���ɂ�鏈���U�蕪��
			if (param.GetParam("regist") != null) {
				// "�o�^"�Ȃ�f�[�^��ۑ����čēx������ʂ�\��
				StoreData(param);
				response.sendRedirect("UserUpdate");
			} else if (param.GetParam("change") != null) {
				// "�ύX"�Ȃ�f�[�^��ۑ����Ĉꗗ��ʂɖ߂�
				StoreData(param);
				response.sendRedirect("UserList");
			} else if (param.GetParam("delete") != null) {
				// "�폜"�Ȃ�f�[�^���폜���Ĉꗗ��ʂɖ߂�
				DeleteData(param);
				response.sendRedirect("UserList");
			} else if (param.GetParam("cancel1") != null) {
				// �ύX��"���~"�Ȃ�ꗗ��ʂɖ߂�
				response.sendRedirect("UserList");
			} else if (param.GetParam("cancel2") != null) {
				// �V�K�ǉ���"���~"�Ȃ猻�݂̃��[�U�[�R�[�h���L�����Z�����ď�����ʂɖ߂�
				new Contrxxxaster().CancelUserCode(param.GetParam("UserCode"));
				response.sendRedirect("master");
			}
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �f�[�^�ۑ�����
	private void StoreData(Param param)
			throws ServletException, IOException, SQLException, ParseException {

		MyUtil myutil = new MyUtil();

		// ���͂��ꂽ�f�[�^�����[�U�[�}�X�^�N���X�ɃZ�b�g
		UserMaster um = new UserMaster();
		um.UserCode = param.GetParam("UserCode");
		um.UserID = param.GetParam("UserID");
		um.Password = param.GetParam("Password");
		um.TourokuDate = myutil.StringToDate(param.GetParam("TourokuDate"));
		um.Furigana = param.GetParam("Furigana");
		um.Shimei = param.GetParam("Shimei");
		um.Seibetsu = param.GetParam("Seibetsu");
		um.SeinenYear = Integer.parseInt(param.GetParam("SeinenYear"));
		um.SeinenMonth = Integer.parseInt(param.GetParam("SeinenMonth"));
		um.SeinenDay = Integer.parseInt(param.GetParam("SeinenDay"));
		um.Nenrei = Integer.parseInt(param.GetParam("Nenrei"));
		um.Over18Flag = param.GetParam("Over18Flag").equals("true");
		um.Over18Date = myutil.StringToDate(param.GetParam("Over18Date"));
		um.Shokugyou = param.GetParam("ShokugyouCode");
		um.Zip = param.GetParam("Zip");
		um.Address = param.GetParam("Address");
		um.Tatemono = param.GetParam("Tatemono");
		um.TelNo = param.GetParam("TelNo");
		um.FaxNo = param.GetParam("FaxNo");
		um.MailAddr = param.GetParam("MailAddr");
		um.MailList = param.GetParam("MailList").equals("true");
		um.Url = param.GetParam("Url");

		// �f�[�^�x�[�X�ɕۑ�
		um.Store(true);
	}

	// �f�[�^�폜����
	private void DeleteData(Param param)
			throws ServletException, IOException, SQLException {

		// �\���������[�U�[�R�[�h�̃f�[�^���f�[�^�x�[�X����폜
		UserMaster um = new UserMaster();
		um.UserCode = param.GetParam("UserCode");
		um.Delete();
	}
}
