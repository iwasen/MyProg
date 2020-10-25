//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�󒍏��m�F��������
//		FileName:	OrderVerify.java
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

// ���i�I�[�_�m�F�y�[�W�����N���X
public class OrderVerify extends BaseServlet2
{
	// "GET"���\�b�h����
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "�󒍏�����", "", "bgcolor='#FFFFCC'");
		Body(pw, order);
		Footer(pw);
	}

	// �{�̕�html�o�͏���
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		DecimalFormat fmt = new DecimalFormat("#,###,###");
		Order.Juchu juchu = order.juchu;

		pw.println("<p align='center' class='s5'><font color='#000000'>��" + order.user.Shimei + "�l�@�󒍊m�F��</font></p>");
		pw.println("<p align='center' class='s1'> <font color='#000000'><br>");
		pw.println("</font></p>");
		pw.println("<div align='center'>");
		pw.println("<table width='560' border='2' cellspacing='1' cellpadding='1' class='s1' bgcolor='#CCFFFF' height='62'>");

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);

			pw.println("<tr>");
			pw.println("<td width='60' nowrap><font color='#000000'>" + meisai.ShouhinNo + "</font></td>");
			pw.println("<td width='241' nowrap><font color='#000000'>" + meisai.ShouhinName + "</font></td>");
			pw.println("<td width='74' nowrap><font color='#000000'>" + meisai.Kosuu + meisai.Tani + "</font></td>");
			pw.println("<td width='163' nowrap>");
			pw.println("<div align='right'><font color='#000000'>" + fmt.format(meisai.Shoukei) + "�~</font></div>");
			pw.println("</td>");
			pw.println("</tr>");
		}

		pw.println("<tr>");
		pw.println("<td colspan='4' nowrap>");
		pw.println("<div align='right'> <font color='#000000'>���v���z�i�ō��j�@" + fmt.format(order.juchu.Total) + "�~</font></div>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<br>");

		pw.println("<span class='s1'><font color='#FF0000'>");
		pw.println("��L�̍��v���z�̑��ɑ����i700�~�j�����p�ӂ��������B<br>");
		pw.println("��z�������������]�̕��͑����̑��Ɏ萔���i400�~�j�𒸂��܂��B<br>");
		pw.println("�������A15,000�~�ȏエ�����グ�̏ꍇ�A�����A�萔���͖����Ƃ����Ă��������܂��B<br>");
		pw.println("�܂��AHAB�ʐ^�W�Ɋւ��ẮA�����E�萔���́A�����ƂȂ�܂��B���q�l���AHAB�ʐ^�W<br>");
		pw.println("�Ƒ��̏��i�𓯎��ɁA�w�������ꍇ�������E�萔���́A�����܂���B�����ƂȂ�܂��B");
		pw.println("</font> </span><br>");

		pw.println("</div>");
		pw.println("<br>");
		pw.println("<table cellpadding=8>");
		pw.println("<tr>");
		pw.println("<td align='right'><font color='#000000'>�X�֔ԍ�</td>");
		pw.println("<td>" + juchu.Zip + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>�Z��</td>");
		pw.println("<td>" + juchu.Address + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>���͂��挚��</td>");
		pw.println("<td>" + juchu.Tatemono + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>���͂���d�b�ԍ�</td>");
		pw.println("<td>" + juchu.TelNo + "</font></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'><font color='#000000'>���x�������@</td>");
		pw.println("<td>" + myutil.GetShiharaiName(juchu.Shiharai) + "</font></td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<form name='form1' method='post'>");
		pw.println("<font color='#000000'>");
		pw.println("<div align='center'>");
		pw.println("<input type='submit' name='ok' value=' ���M '>");
		pw.println("<input type='submit' name='cancel' value='��߂�'>");
		pw.println("</div>");
		pw.println("</font>");
		pw.println("</form>");
	}

	// "POST"���\�b�h����
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// �N���b�N���ꂽ�{�^���ɂ��U�蕪��
		if (param.GetParam("ok") != null) {
			// �u���M�v�{�^�����N���b�N���ꂽ��󒍏����f�[�^�x�[�X�ɕۑ��ƃ��[���̑��M
			if (StoreDB(order))
				SendOrderMail(order);
			response.sendRedirect("thankyou");
		} else if (param.GetParam("cancel") != null) {
			// �u��߂�v�{�^�����N���b�N���ꂽ��g�b�v�y�[�W�ɖ߂�
			response.sendRedirect(TopPage);
		}
	}

	// �󒍏��ۑ�����
	private boolean StoreDB(Order order)
			throws ServletException, IOException, SQLException {

		Contrxxxaster cm = new Contrxxxaster();
		DecimalFormat mf = new DecimalFormat("000");
		boolean result = false;

		// �f�[�^�x�[�X�ڑ�
		DataBase db = new DataBase();
		db.Transaction();
		Statement st = db.createStatement();

		try {
			// �󒍏��ۑ�
			Order.Juchu juchu = order.juchu;
			juchu.JuchuCode = cm.GetNewJuchuCode();
			juchu.JuchuDate = new java.util.Date();
			juchu.UserCode = order.user.UserCode;
			juchu.Over18Flag = order.user.Over18Flag;
			String sql = "insert into F_JUCHU values ("
					+ "'" + juchu.JuchuCode + "',"
					+ "'" + juchu.JuchuDate + "',"
					+ "'" + juchu.UserCode + "',"
					+ juchu.Over18Flag + ","
					+ "'" + juchu.Zip + "',"
					+ "'" + juchu.Address + "',"
					+ "'" + juchu.Tatemono + "',"
					+ "'" + juchu.TelNo + "',"
					+ "'" + juchu.Total + "',"
					+ "'" + juchu.Shiharai + "')";
			st.executeUpdate(sql);

			// �󒍖��וۑ�
			int count = order.GetMeisaiNum();
			for (int i = 0; i < count; i++) {
				Order.Meisai meisai = order.GetMeisai(i);
				meisai.MeisaiCode = mf.format(i + 1);
				sql = "insert into F_JUCHUMEISAI values ("
						+ "'" + juchu.JuchuCode + "',"
						+ "'" + meisai.MeisaiCode + "',"
						+ "'" + meisai.ShouhinNo + "',"
						+ "'" + meisai.ShouhinName + "',"
						+ meisai.HanbaiKakaku + ","
						+ meisai.Kosuu + ","
						+ meisai.Shoukei + ")";
				st.executeUpdate(sql);
			}

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

	// ���[�����M����
	private void SendOrderMail(Order order)
			throws ServletException, IOException, SQLException {

		Order.User user = order.user;
		Contrxxxaster.MailInfo mailinfo = new Contrxxxaster().GetMailInfo();
		SendMail mail = new SendMail();

		// �S���҂��ă��[�����M
		if (!user.UserID.equals("aizawa"))
		mail.send(mailinfo.MailServer, mailinfo.ToAddress, mailinfo.CcAddress, mailinfo.FromAddress, "[Kairaku-DB]���i�󒍊m�F", MailBodyTantosha(order));

		// �w�����[�U���ă��[�����M
		mail.send(mailinfo.MailServer, user.EMail, null, mailinfo.FromAddress, "[OnLineShop<F-net>]���������肪�Ƃ��������܂���", MailBodyUser(order));
	}

	// �S���҂��ă��[���{���쐬
	private String MailBodyTantosha(Order order)
			throws ServletException, IOException, SQLException {

		DecimalFormat decfmt = new DecimalFormat("#,###,###");
		SimpleDateFormat datefmt = new SimpleDateFormat("yyyy�NMM��dd��");
		MyUtil myutil = new MyUtil();
		Order.Juchu juchu = order.juchu;
		Order.User user = order.user;

		String text = "���������@���y�s��f�[�^�x�[�X�@��������\n"
				+ "\n" 
				+ "�ȉ��̉�����珤�i�̂�����������܂����̂�\n" 
				+ "�������e��]���v���܂��B\n" 
				+ "\n"
				+ "�m�������n\n"
				+ "--------------------------------------------------------\n";

		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);
			text += meisai.ShouhinNo + "�@"
					+ meisai.ShouhinName + "�@"
					+ meisai.Kosuu + meisai.Tani + "�@"
					+ decfmt.format(meisai.Shoukei) + "�~\n";
		}

		text += "--------------------------------------------------------\n"
				+ "���v " + decfmt.format(juchu.Total) + "�~\n"
				+ "\n"
				+ "�m���͂�����n\n"
				+ "�@�󒍓��F" + datefmt.format(juchu.JuchuDate) + "\n"
				+ "�@�����F" + user.Shimei + "\n"
				+ "�@�X�֔ԍ��F" + juchu.Zip + "\n"
				+ "�@�Z���F" + juchu.Address + "\n"
				+ "�@�������F" + juchu.Tatemono + "\n"
				+ "�@�d�b�ԍ��F" + juchu.TelNo + "\n"
				+ "�@�x�����@�F" + myutil.GetShiharaiName(juchu.Shiharai) + "\n"
				+ "\n"
				+ "�m���[�U�[���n\n"
 				+ "�@���[�U�[�h�c�F" + user.UserID + "\n"
				+ "�@�p�X���[�h�F" + user.Password + "\n"
				+ "�@�o�^���F" + datefmt.format(user.Touroku) + "\n"
				+ "�@�ӂ肪�ȁF" + user.Furigana + "\n"
				+ "�@�����F" + user.Shimei + "\n"
				+ "�@���ʁF" + user.Seibetsu + "\n"
				+ "�@���N�����F" + user.SeiNen + "�N" + user.SeiTsuki + "��" + user.Sexxx + "��" + "\n"
				+ "�@�N��F" + user.Nenrei + "\n"
				+ "�@18�Έȏ�m�F�F" + user.Over18Flag + "\n"
				+ "�@18�Έȏ�m�F���F" + myutil.DateToString(user.Over18Date) + "\n"
				+ "�@�E�ƁF" + myutil.GetShokugyouName(user.Shokugyou) + "\n"
				+ "�@�X�֔ԍ��F" + user.Zip + "\n"
				+ "�@�Z���F" + user.Address + "\n"
				+ "�@�������F" + user.Tatemono + "\n"
				+ "�@�d�b�ԍ��F" + user.TelNo + "\n"
				+ "�@�e�`�w�ԍ��F" + user.FaxNo + "\n"
				+ "�@���[���A�h���X�F" + user.EMail + "\n"
				+ "�@���[�����O���X�g�o�^�F" + user.MailingList + "\n"
				+ "�@�t�q�k�F" + user.Url + "\n"
				+ "\n"
				+ "mailed by JapanLinkServer\n"
				+ "inquire to mc@japanlink.ne.jp\n";
 
		return text;
	}

	// �o�^���[�U���ă��[���{���쐬
	private String MailBodyUser(Order order)
			throws ServletException, IOException, SQLException {

		DecimalFormat decfmt = new DecimalFormat("#,###,###");
		MyUtil myutil = new MyUtil();
		Order.Juchu juchu = order.juchu;
		Order.User user = order.user;

		String text = user.Shimei + "�@�l\n\n";
		text += "������ ���y�s��@���������e ������\n";

		text += "--------------------------------------------------------\n";
		int count = order.GetMeisaiNum();
		for (int i = 0; i < count; i++) {
			Order.Meisai meisai = order.GetMeisai(i);
			text += meisai.ShouhinNo + "�@"
					+ meisai.ShouhinName + "�@"
					+ meisai.Kosuu + meisai.Tani + "�@"
					+ decfmt.format(meisai.Shoukei) + "�~\n";
		}
		text += "--------------------------------------------------------\n";
		text += "���v " + decfmt.format(juchu.Total) + "�~\n\n";

		text += "�󒍔ԍ��F" + juchu.JuchuCode + "\n"
				+ "���͂���X�֔ԍ��F" + juchu.Zip + "\n"
				+ "���͂���Z���F" + juchu.Address + "\n"
				+ "���͂��挚���F" + juchu.Tatemono + "\n"
				+ "���͂���d�b�ԍ��F" + juchu.TelNo + "1\n"
				+ "���x�����@�F" + myutil.GetShiharaiName(juchu.Shiharai) + "\n"
				+ "\n"
				+ ShiharaiGuide(juchu.Shiharai)
				+ "\n"
				+ "********************************************** \n"
				+ "�����߂Ă̕��ցi18�Έȏ�N��m�F���܂��̕��j�� \n"
				+ "\n"
				+ "18�Έȏ�̔N����m�F�ł��镨���A���y�s��܂�FAX�A�܂��͂����t���Ă��������B\n"
				+ "\n"
				+ "[���t��E�A����]\n"
				+ "162-0814�@�����s�V�h��V���쒬3-10SE�r��4F ���y�s�ꎖ����\n"
				+ "TEL03-5229-7512�@FAX03-5229-7513\n"
				+ "\n"
				+ "18�Έȏ�̊m�F���o������A���[���ɂĘA���v���܂��B\n"
				+ "����܂ŗ����̂��U�荞�݂͂��Ȃ��ŉ������B\n"
				+ "�N��m�F���ł���܂ŁA���i�̔����͂ł��܂���B\n"
				+ "**********************************************\n"
				+ "\n"
				+ "���������肪�Ƃ��������܂����B\n";

		return text;
	}

	private String ShiharaiGuide(String ShiharaiCode) {

		String guide = null;

		if (ShiharaiCode.equals("01")) {		// �X�ֈב�
			guide = "���L�̌����ցi���i���v���+����700�~�j����������������B\n"
					+ "�������A�w������̍��v��15,000�~�ȏ�̏ꍇ�A�����A����萔���͖����Ƃ�����\n"
					+ "���������܂��B\n"
					+ "���AHAB�ʐ^�W�Ɋւ��ẮA�����́A�����ƂȂ�܂��B\n"
					+ "���q�l���AHAB�ʐ^�W�Ƒ��̏��i�� �����ɁA�w�������ꍇ�������́A�����܂���B\n"
					+ "���i���v����݂̂��������������B\n"
					+ "�����m�F��A���i�𔭑��������܂��B\n"
					+ "�@�@�@�@�@�@�@�@�@�@�@�V�h�V���쒬�X�֋ǁ@�@00160-3-189753\n"
					+ "�@�@�@�@�@�@�@�@�@�@�@�������@�@�@���y�s��\n";
		} else if (ShiharaiCode.equals("02")) {	// ��s�U��
			guide = "���L�̌����ցi���i���v���+����700�~�j����������������B\n"
					+ "�������A�w������̍��v��15,000�~�ȏ�̏ꍇ�A�����A����萔���͖����Ƃ�����\n"
					+ "���������܂��B\n"
					+ "���AHAB�ʐ^�W�Ɋւ��ẮA�����́A�����ƂȂ�܂��B\n"
					+ "���q�l���AHAB�ʐ^�W�Ƒ��̏��i�� �����ɁA�w�������ꍇ�������E�萔���́A����\n"
					+ "�܂���B���i���v����݂̂��������������B\n"
					+ "�����m�F��A���i�𔭑��������܂��B\n"
					+ " �@�@�@�@�@�@�@�@�@�@�@�Z�F��s�@�ѓc���x�X�@�i���j775751\n"
					+ " �@�@�@�@�@�@�@�@�@�@�@�������@�@�@�t���[�_���G�t���y�s��\n";
		} else if (ShiharaiCode.equals("03")) {	// �������
			guide = "���i�𔭑��������܂��B\n"
					+ "\n"
					+ "���͂����i�ƈ��������ɑ���i���i���v���+����700�~+�萔��400�~�j�����x����\n"
					+ "���������B\n"
					+ "�������A�w������̍��v��15,000�~�ȏ�̏ꍇ�A�����A����萔���͖����Ƃ�����\n"
					+ "���������܂��B\n"
					+ "���AHAB�ʐ^�W�Ɋւ��ẮA�����E�萔���́A�����ƂȂ�܂��B\n"
					+ "���q�l���AHAB�ʐ^�W�Ƒ��̏��i�� �����ɁA�w�������ꍇ�������E�萔���́A����\n"
					+ "�܂���B ���i���v����݂̂��������������B\n";
		} else if (ShiharaiCode.equals("04")) {	// ��������
			guide = "�����m�F��A���i�𔭑��������܂��B\n"
					+ "\n"
					+ "���͂����i�ƈ��������ɑ���i���i���v���+����700�~�j�����x�������������B\n"
					+ "�������A�w������̍��v��15,000�~�ȏ�̏ꍇ�A�����A����萔���͖����Ƃ�����\n"
					+ "���������܂��B\n"
					+ "���AHAB�ʐ^�W�Ɋւ��ẮA�����́A�����ƂȂ�܂��B���q�l���A\n"
					+ "HAB�ʐ^�W�Ƒ��̏��i�� �����ɁA�w�������ꍇ�������E�萔���́A�����܂���B\n"
					+ "���i���v����݂̂��������������B\n";
		}

		return guide;
	}
}
