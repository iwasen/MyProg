//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		���i�}�X�^�X�V����
//		FileName:	ShouhinItem.java
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
import javax.servlet.ServletException;

// ���i�X�V�폜�����N���X
public class ShouhinItem extends BaseServlet1
{
	// �摜�t�@�C���_���p�X
	private final String pictLogicalPath = "/pict/";
	// �摜�t�@�C�������p�X
	private String pictPhysicalPath = "/usr/users/kairaku/webs/pict/";

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
			Header(pw, "���i�}�X�^�[�o�^");
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

		ShouhinMaster sm = new ShouhinMaster();
		if (code != null) {
			// �R�[�h�̎w�肪����΍X�V�܂��͍폜�Ȃ̂Ō��݂̃f�[�^���擾
			if (sm.Read(code)) {
				ShouhinInfo(pw, sm, true);
			} else {
				pw.println("<h2>�w�肵�����i�R�[�h�͓o�^����Ă��܂���B</h2>");
				pw.println("<a href='master'>�߂�</a>");
			}
		} else {
			// �R�[�h�̎w�肪�Ȃ���ΐV�K�ǉ�
			sm.Init();
			sm.ShouhinCode = new Contrxxxaster().GetNewShouhinCode();
			ShouhinInfo(pw, sm, false);
		}
	}

	// ���i���\��
	private void ShouhinInfo(PrintWriter pw, ShouhinMaster sm, boolean update)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		SimpleDateFormat formatter = new SimpleDateFormat ("yyyy/MM/dd");
 
		pw.println("<form enctype='multipart/form-data' method='POST' name='fm' onSubmit='return OnSubmit();'>");
		pw.println("<table border=1 size='90%' align='center' bgcolor='#E0FFE0'>");
		pw.println("<tr>");
		pw.println("<td bgcolor='#FFE0E0'>���i�R�[�h</td>");
		pw.println("<td><input type='hidden' name='ShouhinCode' size=15 value='" + sm.ShouhinCode + "'>");
		pw.println("<input type='text' size=15 value='" + sm.ShouhinCode + "' disabled></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���i��</td>");
		pw.println("<td><input type='text' name='ShouhinName' size=50 value='" + sm.ShouhinName + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���i�ԍ�</td>");
		pw.println("<td><input type='text' name='ShouhinNo' size=50 value='" + sm.ShouhinNo + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���i���ރR�[�h</td>");
		pw.println("<td>"); myutil.BunruiSelect(pw, sm.BunruiCode, "�I�����Ă�������", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�J�e�S���[</td>");
		pw.println("<td>"); myutil.CategorySelect(pw, sm.CategoryCode, "�I�����Ă�������", null); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�o�^��</td>");
		pw.println("<td><input type='text' name='TourokuDate' size=15 value='" + formatter.format(sm.TourokuDate) + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�V���[�Y��</td>");
		pw.println("<td><input type='text' name='SeriesName' size=50 value='" + sm.SeriesName + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���D��</td>");
		pw.println("<td><input type='text' name='ActorName' size=50 value='" + sm.ActorName + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���i���</td>");
		pw.println("<td><textarea name='KaisetsuText' rows=4 cols=40 wrap='soft'>" + sm.KaisetsuText + "</textarea></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�艿</td>");
		pw.println("<td><input type='text' name='TeikaYen' size=15 value='" + (update ? String.valueOf(sm.TeikaYen) : "") + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�̔����i</td>");
		pw.println("<td><input type='text' name='HanbaiYen' size=15 value='" + (update ? String.valueOf(sm.HanbaiYen) : "") + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�d�����i</td>");
		pw.println("<td><input type='text' name='ShiireYen' size=15 value='" + (update ? String.valueOf(sm.ShiireYen) : "") + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>�P��</td>");
		pw.println("<td><input type='text' name='TaniText' size=15 value='" + sm.TaniText + "'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���i�摜�P�i���摜�j</td>");
		pw.println("<td><input type='file' name='Gazou1File' size=50 accept='image'>");
		pw.println("<input type='hidden' name='Gazou1FileName' size=50 value='" + sm.Gazou1File + "'>");
		pw.println("<input type='button' value='�\��' onClick='RefreshPict1()'><br>");
		pw.println("<img src='" + pictLogicalPath + sm.Gazou1File + "' name='pict1'></td>");
		pw.println("</tr><tr>");
		pw.println("<td bgcolor='#FFE0E0'>���i�摜�Q�i��摜�j</td>");
		pw.println("<td><input type='file' name='Gazou2File' size=50 accept='image'>");
		pw.println("<input type='hidden' name='Gazou2FileName' size=50 value='" + sm.Gazou2File + "'>");
		pw.println("<input type='button' value='�\��' onClick='RefreshPict2()'><br>");
		pw.println("<img src='" + pictLogicalPath + sm.Gazou2File + "' name='pict2'></td>");
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
			"function RefreshPict1() {",
			" if (document.fm.Gazou1File.value != '')",
			"  document.pict1.src = document.fm.Gazou1File.value;",
			"}",
			"function RefreshPict2() {",
			" if (document.fm.Gazou2File.value != '')",
			"  document.pict2.src = document.fm.Gazou2File.value;",
			"}",
			"function OnSubmit() {",
			" with (document.fm) {",
			" if (action.value == '�o�^' || action.value == '�ύX') {",
			" if (ShouhinName.value == '') {",
			"  alert('���i������͂��Ă��������B');",
			"  ShouhinName.focus();",
			"  return false;",
			" }",
			" if (ShouhinNo.value == '') {",
			"  alert('���i�ԍ�����͂��Ă��������B');",
			"  ShouhinNo.focus();",
			"  return false;",
			" }",
			" if (BunruiCode.value == '') {",
			"  alert('���i���ރR�[�h��I�����Ă��������B');",
			"  BunruiCode.focus();",
			"  return false;",
			" }",
			" if (CategoryCode.value == '') {",
			"  alert('�J�e�S���[��I�����Ă��������B');",
			"  CategoryCode.focus();",
			"  return false;",
			" }",
			" if (TourokuDate.value == '') {",
			"  alert('�o�^������͂��Ă��������B');",
			"  TourokuDate.focus();",
			"  return false;",
			" }",
			" if (TeikaYen.value == '') {",
			"  alert('�艿����͂��Ă��������B');",
			"  TeikaYen.focus();",
			"  return false;",
			" }",
			" if (HanbaiYen.value == '') {",
			"  alert('�̔����i����͂��Ă��������B');",
			"  HanbaiYen.focus();",
			"  return false;",
			" }",
			" if (ShiireYen.value == '') {",
			"  alert('�d�����i����͂��Ă��������B');",
			"  ShiireYen.focus();",
			"  return false;",
			" }",
			" if (TaniText.value == '') {",
			"  alert('�P�ʂ���͂��Ă��������B');",
			"  TaniText.focus();",
			"  return false;",
			" }",
			" if (action.value == '�o�^') {",
			" if (Gazou1File.value == '') {",
			"  alert('���i�摜�P�i���摜�j����͂��Ă��������B');",
			"  Gazou1File.focus();",
			"  return false;",
			" }",
			" if (Gazou2File.value == '') {",
			"  alert('���i�摜�Q�i��摜�j����͂��Ă��������B');",
			"  Gazou2File.focus();",
			"  return false;",
			" }}}",
			" return confirm('���̏��i��' + action.value + '���܂��B��낵���ł����H')",
			"}}",
		};

		JavaScript(pw, script);
	}

	// "POST"���\�b�h����
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			// �}���`�p�[�g����
			MultiPart multiPart = new MultiPart(request);

			// �{�^���ɂ�鏈���U�蕪��
			if (multiPart.GetParameter("regist") != null) {
				// "�o�^"�Ȃ�f�[�^��ۑ����čēx������ʂ�\��
				StoreData(multiPart);
				response.sendRedirect("ShouhinItem");
			} else if (multiPart.GetParameter("change") != null) {
				// "�ύX"�Ȃ�f�[�^��ۑ����Ĉꗗ��ʂɖ߂�
				StoreData(multiPart);
				response.sendRedirect("ShouhinList");
			} else if (multiPart.GetParameter("delete") != null) {
				// "�폜"�Ȃ�f�[�^���폜���Ĉꗗ��ʂɖ߂�
				DeleteData(multiPart);
				response.sendRedirect("ShouhinList");
			} else if (multiPart.GetParameter("cancel1") != null) {
				// �ύX��"���~"�Ȃ�ꗗ��ʂɖ߂�
				response.sendRedirect("ShouhinList");
			} else if (multiPart.GetParameter("cancel2") != null) {
				// �V�K�ǉ���"���~"�Ȃ猻�݂̏��i�R�[�h���L�����Z�����ď�����ʂɖ߂�
				new Contrxxxaster().CancelShouhinCode(multiPart.GetParameter("ShouhinCode"));
				response.sendRedirect("master");
			}
		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// �f�[�^�ۑ�����
	private void StoreData(MultiPart multiPart)
			throws ServletException, IOException, SQLException, ParseException {

		// ���͂��ꂽ�f�[�^�����i�}�X�^�N���X�ɃZ�b�g
		ShouhinMaster sm = new ShouhinMaster();
		sm.ShouhinCode = multiPart.GetParameter("ShouhinCode");
		sm.ShouhinName = multiPart.GetParameter("ShouhinName");
		sm.ShouhinNo = multiPart.GetParameter("ShouhinNo");
		sm.BunruiCode = multiPart.GetParameter("BunruiCode");
		sm.CategoryCode = multiPart.GetParameter("CategoryCode");
		sm.TourokuDate = new SimpleDateFormat("yyyy/MM/dd").parse(multiPart.GetParameter("TourokuDate"));
		sm.SeriesName = multiPart.GetParameter("SeriesName");
		sm.ActorName = multiPart.GetParameter("ActorName");
		sm.KaisetsuText = multiPart.GetParameter("KaisetsuText");
		sm.TeikaYen = Integer.parseInt(multiPart.GetParameter("TeikaYen"));
		sm.HanbaiYen = Integer.parseInt(multiPart.GetParameter("HanbaiYen"));
		sm.ShiireYen = Integer.parseInt(multiPart.GetParameter("ShiireYen"));
		sm.TaniText = multiPart.GetParameter("TaniText");
		sm.Gazou1File = multiPart.GetParameter("Gazou1File");
		if (sm.Gazou1File == null || sm.Gazou1File.equals(""))
			sm.Gazou1File = multiPart.GetParameter("Gazou1FileName");
		sm.Gazou2File = multiPart.GetParameter("Gazou2File");
		if (sm.Gazou2File == null || sm.Gazou2File.equals(""))
			sm.Gazou2File = multiPart.GetParameter("Gazou2FileName");

		// �f�[�^�x�[�X�ɕۑ�
		sm.Store(true);

		// �摜�t�@�C�����쐬
		multiPart.CreateFile(pictPhysicalPath);
	}

	// �f�[�^�폜����
	private void DeleteData(MultiPart multiPart)
			throws ServletException, IOException, SQLException {

		// �\���������i�R�[�h�̃f�[�^���f�[�^�x�[�X����폜
		ShouhinMaster sm = new ShouhinMaster();
		sm.ShouhinCode = multiPart.GetParameter("ShouhinCode");
		sm.Delete();
	}
}
