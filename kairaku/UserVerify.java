//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�o�^���m�F����
//		FileName:	UserVerify.java
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

// ���[�U�o�^���m�F�y�[�W�����N���X
public class UserVerify extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "�o�^���m�F", "", "bgcolor='#CCFFFF'");
		Body(pw, order);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		Order.User user = order.user;

		pw.println("<p align='center' class='s5'><b>���[�U�[�o�^�m�F</b></p>");
		pw.println("<p align='center' class='s1'><b>�p�X���[�h�́A���[���ɂĘA�����܂��B<br>");
		pw.println("</b></p>");
		pw.println("<table width='699' border='2' cellspacing='1' cellpadding='6' height='317' bgcolor='#FFCCCC' bordercolordark='#666666' bordercolorlight='#FFFFFF' class='s1' align='center'>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>�h�c</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.UserID + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>�ӂ肪��</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Furigana + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>����</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Shimei + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>����</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Seibetsu + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>���N����</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.SeiNen + " �N" + user.SeiTsuki + "��" + user.Sexxx + "��</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>�N��</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Nenrei + "��</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>�E��</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + myutil.GetShokugyouName(user.Shokugyou) + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>�X�֔ԍ�</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Zip + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>�Z��</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.Address + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap height='38'>");
		pw.println("<div align='right'>�����E�}���V������</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + ConvertBlank(user.Tatemono) + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>�A����d�b�ԍ�</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.TelNo + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>FAX�ԍ�</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + ConvertBlank(user.FaxNo) + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>E���[���A�h���X</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + user.EMail + "</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='170' nowrap>");
		pw.println("<div align='right'>���Ȃ��̃z�[���y�[�W�A�h���X</div>");
		pw.println("</td>");
		pw.println("<td width='492'>" + ConvertBlank(user.Url) + "</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<form name='form1' method='post' class='s1'>");
		pw.println("<div align='center'>");
		pw.println("<input type='button' value='����o�^�ւ��ǂ�' onClick='history.back()'>");
		pw.println("<input type='submit' name='soushin' value=' ���M '>");
		pw.println("</div>");
		pw.println("</form>");
		pw.println("<p>�@</p>");
		pw.println("<p align='center' class='s1'>�@</p>");
	}

	// "POST"���\�b�h����
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		if (param.GetParam("soushin") != null) {
			if (StoreDB(order))
				SendRegistMail(order);

			response.sendRedirect("OrderList");
		}
	}

	// ���[�U�o�^���ۑ�����
	private boolean StoreDB(Order order)
			throws ServletException, IOException, SQLException {

		Contrxxxaster cm = new Contrxxxaster();
		MyUtil myutil = new MyUtil();
		boolean result = false;

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		db.Transaction();
		Statement st = db.createStatement();

		try {
			// ���[�U�o�^���ۑ�
			Order.User user = order.user;
			user.UserCode = cm.GetNewUserCode();
			String sql = "insert into M_USER values ("
					+ "'" + user.UserCode + "',"
					+ "'" + user.UserID + "',"
					+ "'" + user.Password + "',"
					+ "'" + user.Touroku + "',"
					+ "'" + user.Furigana + "',"
					+ "'" + user.Shimei + "',"
					+ "'" + user.Seibetsu + "',"
					+ user.SeiNen + ","
					+ user.SeiTsuki + ","
					+ user.Sexxx + ","
					+ user.Nenrei + ","
					+ user.Over18Flag + ","
					+ myutil.NullAble(user.Over18Date) + ","
					+ "'" + user.Shokugyou + "',"
					+ "'" + user.Zip + "',"
					+ "'" + user.Address + "',"
					+ "'" + user.Tatemono + "',"
					+ "'" + user.TelNo + "',"
					+ "'" + user.FaxNo + "',"
					+ "'" + user.EMail + "',"
					+ user.MailingList + ","
					+ "'" + user.Url + "')";
			st.executeUpdate(sql);

			// �A���P�[�g���ʕۑ�
			Order.Enquete enquete = order.enquete;
			enquete.EnqueteCode = cm.GetNewEnqueteCode();
			enquete.UserCode = user.UserCode;
			sql = "insert into F_ENQUETE values ("
					+ "'" + enquete.EnqueteCode + "',"
					+ "'" + enquete.UserCode + "',"
					+ "'" + enquete.Setsumon1 + "',"
					+ "'" + enquete.Setsumon2 + "',"
					+ "'" + enquete.Setsumon3 + "',"
					+ "'" + enquete.Setsumon3Koumoku + "',"
					+ "'" + enquete.Setsumon4 + "',"
					+ "'" + enquete.Setsumon4Koumoku + "',"
					+ "'" + enquete.Setsumon5Title + "',"
					+ "'" + enquete.Setsumon4Url + "')";
			st.executeUpdate(sql);

			// ����Ȃ�R�~�b�g
			db.Commit();

			result = true;
		} catch (SQLException e) {
			// �G���[�Ȃ烍�[���o�b�N
			db.Rollback();
		}

		st.close();

		return result;
	}

	// ���[�U�o�^���[�����M����
	private void SendRegistMail(Order order)
			throws ServletException, IOException, SQLException {

		Order.User user = order.user;
		Contrxxxaster.MailInfo mailinfo = new Contrxxxaster().GetMailInfo();
		SendMail mail = new SendMail();

		// �S���҂��ă��[�����M
		mail.send(mailinfo.MailServer, mailinfo.ToAddress, mailinfo.CcAddress, mailinfo.FromAddress, "[Kairaku-DB]�V�K���[�U�[�o�^�m�F", MailBodyTantosha(user));

		// �o�^���[�U���ă��[�����M
		mail.send(mailinfo.MailServer, user.EMail, null, mailinfo.FromAddress, "[OnLineShop<F-net>]����o�^�����̂��m�点", MailBodyUser(user));
	}

	// �o�^���[�U���ă��[���{���쐬
	private String MailBodyTantosha(Order.User user)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		SimpleDateFormat datefmt = new SimpleDateFormat("yyyy�NMM��dd��");

		String text = "���������@���y�s��f�[�^�x�[�X�@��������\n"
				+ "\n"
				+ "�ȉ��̃��[�U�[�������o�^������܂����̂�\n"
				+ "�o�^���e��]���v���܂��B\n"
				+ "\n"
 				+ "���[�U�[�h�c�F" + user.UserID + "\n"
				+ "�p�X���[�h�F" + user.Password + "\n"
				+ "�o�^���F" + datefmt.format(user.Touroku) + "\n"
				+ "�ӂ肪�ȁF" + user.Furigana + "\n"
				+ "�����F" + user.Shimei + "\n"
				+ "���ʁF" + user.Seibetsu + "\n"
				+ "���N�����F" + user.SeiNen + "�N" + user.SeiTsuki + "��" + user.Sexxx + "��" + "\n"
				+ "�N��F" + user.Nenrei + "\n"
				+ "18�Έȏ�m�F�F" + user.Over18Flag + "\n"
				+ "18�Έȏ�m�F���F" + myutil.DateToString(user.Over18Date) + "\n"
				+ "�E�ƁF" + myutil.GetShokugyouName(user.Shokugyou) + "\n"
				+ "�X�֔ԍ��F" + user.Zip + "\n"
				+ "�Z���F" + user.Address + "\n"
				+ "�������F" + user.Tatemono + "\n"
				+ "�d�b�ԍ��F" + user.TelNo + "\n"
				+ "�e�`�w�ԍ��F" + user.FaxNo + "\n"
				+ "���[���A�h���X�F" + user.EMail + "\n"
				+ "���[�����O���X�g�o�^�F" + user.MailingList + "\n"
				+ "�t�q�k�F" + user.Url + "\n"
				+ "\n"
				+ "mailed by JapanLinkServer\n"
				+ "inquire to mc@japanlink.ne.jp\n";
 		return text;
	}

	// �S���҂��ă��[���{���쐬
	private String MailBodyUser(Order.User user) {

		String text = user.Shimei + "�@�l\n\n"
				+ "������ ���y�s��@���[�U�[�o�^�̂��m�点 ������\n"
				+ "\n"
				+ "���[�U�[�o�^�������������܂����̂ŁA�h�c�ƃp�X���[�h�����m�点�v���܂��B\n"
				+ "\n"
				+ "�@�h�c�F" + user.UserID + "\n"
				+ "�@�p�X���[�h�F" + user.Password + "\n"
				+ "\n"
				+ "���o�^���肪�Ƃ��������܂����B\n"
				+ "\n"
				+ "���l�������i�̒ʔ̂��A���c�@�̉����Łu���X�܌^�v�Ƃ��������ɂȂ�܂����̂�\n"
				+ "���y�s��ł͏��߂ď��i�����w�������������ɁA���q�l�̔N��E�g����\�ߊm�F����\n"
				+ "�Ă��������Ă���܂��B\n"
				+ "���L�̕��@�ɂĔN��̊m�F���s���Ă���܂��B\n"
				+ "\n"
				+ "��FAX�A���͕����ɂĔN��m�F���s���܂��i����̂݁j�B\n"
				+ "�Ƌ��؁E�p�X�|�[�g�ȂǁA���q�l�̔N����m�F�ł�����̂̃R�s�[���AFAX�܂���\n"
				+ "�����ɂĉ��L�܂ł����t�������B\n"
				+ "�m�F���o������A�m�F�n�j�̃��[���𑗂点�Ă��������܂��B\n"
				+ "��������Ɋւ��Ă͂����炩��̔N��m�F�n�j�̃��[�����͂�����A�����̑�����\n"
				+ "���肢�v���܂��B�i���������I�����ꂽ���͗��������̕K�v�͂���܂���j\n"
				+ "���������m�F��A���i�̔����ƂȂ�܂��B\n"
				+ "\n"
				+ "��������P�J���o���Ă��N��m�F���ł��Ȃ��ꍇ�͍��񔭍s����ID�E�p�X���[�h��\n"
				+ "�g�p�ł��Ȃ��Ȃ�܂��B\n"
				+ "\n"
				+ "�N��m�F���ޗX����E���₢���킹��\n"
				+ "��162-0813�@�����s�V�h��V���쒬3-10�@SE�r��4F�@���y�s�ꎖ����\n"
				+ "�@�@�@�@�@�@TEL03-5229-7512�@FAX03-5229-7513\n"
				+ "FAX��\n"
				+ "03-5229-7513\n"
				+ "\n"
				+ "�N��m�F�̂��߂ɑ��t�����������R�s�[�ɂ��ẮA���̖ړI�Ɏg�p����邱�Ƃ�\n"
				+ "�����Ă���܂���̂ŁA�����S���������B\n"
				+ "\n"
				+ "���萔�ł����A�X�������肢�v���܂��B\n"
				+ "\n"
				+ "�܂��̂��z�������҂����Ă���܂��B\n"
				+ "\n"
				+ "���y�s�ꑍ���f�p�[�g\n"
				+ "http://www.kairakuichiba.co.jp\n"
				+ "f-net@kairakuichiba.co.jp\n";

		return text;
	}
}
