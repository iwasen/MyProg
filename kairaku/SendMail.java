//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		メール送信処理
//		FileName:	SendMail.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import javax.mail.*;
import javax.mail.internet.*;
import javax.servlet.ServletException;

// メール送信処理クラス
public class SendMail {

	// メール送信
	public void send(String host, String to, String cc, String from, String subject, String body)
				throws ServletException {

		boolean debug = false;

		// create some properties and get the default Session
		Properties props = new Properties();
		props.put("mail.smtp.host", host);

		Session session = Session.getInstance(props, null);
		session.setDebug(debug);
		
		try {
			// create a message
			MimeMessage msg = new MimeMessage(session);
			msg.setFrom(new InternetAddress(from));

			InternetAddress[] address = {new InternetAddress(to)};
			msg.setRecipients(Message.RecipientType.TO, address);

			if (cc != null && !cc.equals("")) {
				InternetAddress[] ccaddress = {new InternetAddress(cc)};
				msg.setRecipients(Message.RecipientType.CC, ccaddress);
			}

			msg.setSubject(subject, "iso-2022-jp");
			msg.setSentDate(new Date());
			// If the desired charset is known, you can use
			// setText(text, charset)
			msg.setText(body, "iso-2022-jp");
			
			Transport.send(msg);
		} catch (MessagingException mex) {
			throw new ServletException(mex.toString());
		}
	}
}
