
/**
 * �^�C�g��:     DB Mail Version 3<p>
 * ����:         <p>
 * ���쌠:       Copyright (c) aizawa<p>
 * ��Ж�:       Space Technology Co.,Ltd.<p>
 * @author aizawa
 * @version 1.0
 */

import java.util.*;
import javax.mail.*;
import javax.mail.internet.*;

import javax.activation.DataHandler;
import javax.activation.FileDataSource;

// ���[���T�[�o�N���X
public class MailServer {
  private Properties props;
  private Session session;
  public MimeMessage msg;
  private Transport transport;

  public MailServer(String mail_server, String env_from, String subject, String from, String personal, String replyto, Date send_date, String mail_type)
      throws Exception
  {
    // create some properties and get the default Session
    props = System.getProperties();
    props.put("mail.smtp.host", mail_server);
    if (env_from != null && !env_from.equals(""))
      props.put("mail.smtp.from", env_from);

    session = Session.getDefaultInstance(props, null);
    session.setDebug(false);

    // create a message
    msg = new MimeMessage(session);

    // From �Z�b�g
    setFrom(from, personal);

    // Subject �Z�b�g
    setSubject(subject);

    // Date �Z�b�g
    if (send_date != null)
      msg.setSentDate(send_date);
    else
      msg.setSentDate(new java.util.Date());

    // Reply-To �Z�b�g
    if (replyto != null && !replyto.equals("")) {
      InternetAddress[] address = {new InternetAddress(replyto)};
      msg.setReplyTo(address);
    }

    // Errors-To �Z�b�g
    if (env_from != null && !env_from.equals(""))
      msg.setHeader("Errors-To", env_from);

    transport = session.getTransport("smtp");
    transport.conxxxt();
  }

  // ���[�����M�i���M��A�h���X�L��j
  public void sendMessage(String to_address, String to_header) throws Exception
  {
    msg.setRecipients(Message.RecipientType.TO, to_address);

    Address[] addr = msg.getAllRecipients();

    if (to_header != null)
      msg.setRecipients(Message.RecipientType.TO, to_header);

    transport.sendMessage(msg, addr);
  }

  // ���[���w�b�_�Z�b�g
  public void setHeader(String mail_header) throws Exception
  {
    String header;
    String field_name, field_body;
    ArrayList cc = new ArrayList();
    ArrayList bcc = new ArrayList();
    int n;

    if (mail_header != null && !mail_header.equals("")) {
      StringTokenizer st = new StringTokenizer(mail_header, "\n");
      while (st.hasMoreTokens()) {
        header = st.nextToken();
        if ((n = header.indexOf(':')) > 0) {
          field_name = header.substring(0, n).trim();
          field_body = header.substring(n + 1).trim();

          if (field_name.compareToIgnoreCase("Cc") == 0) {
            StringTokenizer st2 = new StringTokenizer(field_body, ",");
            while (st2.hasMoreTokens()) {
              String mail_addr = st2.nextToken().trim();
              if (mail_addr.length() > 0)
                cc.add(new InternetAddress(mail_addr));
            }
          } else if (field_name.compareToIgnoreCase("Bcc") == 0) {
            StringTokenizer st2 = new StringTokenizer(field_body, ",");
            while (st2.hasMoreTokens()) {
              String mail_addr = st2.nextToken().trim();
              if (mail_addr.length() > 0)
                bcc.add(new InternetAddress(mail_addr));
            }
          } else
            msg.setHeader(field_name, field_body);
        }
      }

      if (!cc.isEmpty())
        msg.setRecipients(Message.RecipientType.CC, (InternetAddress[])cc.toArray(new InternetAddress[1]));

      if (!bcc.isEmpty())
        msg.setRecipients(Message.RecipientType.BCC, (InternetAddress[])bcc.toArray(new InternetAddress[1]));
    }
  }

  // ���[���w�b�_�폜
  public void removeHeader(String mail_header) throws Exception
  {
    String header;
    int n;

    if (mail_header != null && !mail_header.equals("")) {
      StringTokenizer st = new StringTokenizer(mail_header, "\n");
      while (st.hasMoreTokens()) {
        header = st.nextToken();
        if ((n = header.indexOf(':')) > 0) {
          msg.removeHeader(header.substring(0, n).trim());
        }
      }
    }
  }

  // From�Z�b�g
  public void setFrom(String from, String personal) throws Exception
  {
    InternetAddress addr = new InternetAddress(from);

    if (personal != null)
      addr.setPersonal(MimeUtility.encodeText(personal, "iso-2022-jp", "B"));

    msg.setFrom(addr);
  }

  // From�Z�b�g
  public void setFrom2(String from, String personal) throws Exception
  {
    InternetAddress addr = new InternetAddress(from);

    if (addr.getPersonal() != null)
      personal = addr.getPersonal();

    if (personal != null)
      addr.setPersonal(MimeUtility.encodeText(personal, "iso-2022-jp", "B"));

    msg.setFrom(addr);
  }

  // To�Z�b�g
  public void setTo(String to) throws Exception
  {
    msg.setRecipients(Message.RecipientType.TO, to);
  }

  // �T�u�W�F�N�g�Z�b�g
  public void setSubject(String subject) throws Exception
  {
    msg.setSubject(MimeUtility.encodeText(subject, "iso-2022-jp", "B"));
  }

  // �{���Z�b�g
  public void setBody(MailContents mail_contents, String body, String mail_type) throws Exception
  {
    mail_contents.setText(msg, body, mail_type);
    msg.saveChanges();
  }

  // �t�@�C�i���C�U
  protected void finalize() throws Exception
  {
    transport.close();
  }
}
