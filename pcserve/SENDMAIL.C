#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "pcssock.h"
#include "pcserve.h"

#define	RECVBUFLEN	256
#define	SENDBUFLEN	256

static	BOOL	SendMailData(int, CHAR *, CHAR *, CHAR *, CHAR *, CHAR *,
								CHAR *);
static	BOOL	NetSend(int, char *, ...);
static	BOOL	NetReceive(int, char *);

BOOL	SendReportMail(char *kind, char *fmt, void *p)
{
	char	buf[1024], *bufp;
	char	from[64];
	long	ltime;
	struct	tm	*tm;
	DWORD	nameSize;
	CHAR	computerName[100];

	if (ini.mailSMTPServer[0] == '\0' || ini.mailToAddress[0] == '\0')
		return FALSE;

	if (ini.mailFromAddress[0] != '\0')
		sprintf(from, "\"PCSERVE\" <%s>", ini.mailFromAddress);
	else
		strcpy(from, "PCSERVE");

	time(&ltime);
	tm = localtime(&ltime);

	nameSize = sizeof(computerName);
	GetComputerName(computerName, &nameSize);

	bufp = buf;
	strcpy(bufp, "*** PCSERVE イベントレポート ***\r\n\r\n");
	bufp += strlen(bufp);
	sprintf(bufp, "  コンピュータ名：%s\r\n", computerName);
	bufp += strlen(bufp);
	sprintf(bufp, "  日付：%2u/%02u/%02u\r\n",
				tm->tm_year, tm->tm_mon+1, tm->tm_mday);
	bufp += strlen(bufp);
	sprintf(bufp, "  時刻：%2u:%02u:%02u\r\n",
				tm->tm_hour, tm->tm_min, tm->tm_sec);
	bufp += strlen(bufp);
	sprintf(bufp, "  種類：%s\r\n\r\n", kind);
	bufp += strlen(bufp);
//	strcpy(bufp, "［内容］\r\n");
//	bufp += strlen(bufp);

	vsprintf(bufp, fmt, (char *)&p);
	return(SendMail(ini.mailSMTPServer, ini.mailToAddress, from,
					"PCSERVE event report", buf));
}

BOOL	SendMail(CHAR *hostName, CHAR *to, CHAR *from,
					CHAR *subject, CHAR *data)
{
	int	s;
	CHAR	localHostName[64];
	struct	hostent	*hp;
	struct	sockaddr_in	socketaddr;
	BOOL	rc;

	if ((hp = gethostbyname(hostName)) == NULL)
		return FALSE;
	if (gethostname(localHostName, sizeof(localHostName)) != 0)
		return FALSE;

	if ((s = socket(PF_INET, SOCK_STREAM, 0)) < 0)
		return FALSE;

	socketaddr.sin_family      = AF_INET;
	socketaddr.sin_port        = htons(25);
	socketaddr.sin_addr.s_addr = *(u_long *)hp->h_addr;
	if (conxxxt(s, (struct sockaddr *)&socketaddr,
					sizeof(socketaddr)) != 0) {
		closesocket(s);
		return FALSE;
	}

	rc = SendMailData(s, localHostName, hostName, to, from, subject, data);

	closesocket(s);

	return rc;
}

static	BOOL	SendMailData(int s, CHAR *localHostName, CHAR *hostName,
			CHAR *to, CHAR *from, CHAR *subject, CHAR *data)
{
	CHAR	buf[RECVBUFLEN];
	long	ltime;
	struct	tm	*tm;
	static	char 	*week[7] = {
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"};
	static	char  *mon[12] = {
		"Jan", "Feb", "Mar", "Apr", "May", "Jun",
		"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"};

	if (!NetReceive(s, buf) || strncmp(buf, "220", 3) != 0)
		return FALSE;

	if (!NetSend(s, "HELO %s\r\n", localHostName))
		return FALSE;

	if (!NetReceive(s, buf) || strncmp(buf, "250", 3) != 0)
		return FALSE;

	if (!NetSend(s, "MAIL FROM:%s\r\n", from))
		return FALSE;

	if (!NetReceive(s, buf) || strncmp(buf, "250", 3) != 0)
		return FALSE;

	if (!NetSend(s, "RCPT TO:<%s>\r\n", to))
		return FALSE;

	if (!NetReceive(s, buf) || strncmp(buf, "250", 3) != 0)
		return FALSE;

	if (!NetSend(s, "DATA\r\n"))
		return FALSE;

	if (!NetReceive(s, buf) || strncmp(buf, "354", 3) != 0)
		return FALSE;

	time(&ltime);
	tm = localtime(&ltime);
	if (!NetSend(s, "Date: %s, %02d %s %4d %02d:%02d:%02d +0900\r\n",
			week[tm->tm_wday], tm->tm_mday, mon[tm->tm_mon],
			tm->tm_year + 1900, tm->tm_hour, tm->tm_min,
			tm->tm_sec))
		return FALSE;

	if (!NetSend(s, "From: %s\r\n", from))
		return FALSE;

	if (!NetSend(s, "To: %s\r\n", to))
		return FALSE;

	if (!NetSend(s, "Subject: %s\r\n\r\n", subject))
		return FALSE;

	if (send(s, data, strlen(data), 0) == -1)
		return FALSE;

	if (!NetSend(s, "\r\n.\r\n"))
		return FALSE;

	if (!NetReceive(s, buf) || strncmp(buf, "250", 3) != 0)
		return FALSE;

	if (!NetSend(s, "QUIT\r\n"))
		return FALSE;

	if (!NetReceive(s, buf))
		return FALSE;

	return TRUE;
}

static	BOOL	NetSend(int s, char *fmt, void *p)
{
	char	buf[SENDBUFLEN];

	vsprintf(buf, fmt, (char *)&p);

	return (send(s, buf, strlen(buf), 0) != -1);
}

static	BOOL	NetReceive(int s, char *buf)
{
	fd_set	fdset;
	struct	timeval	timeout;
	int	rc;

	FD_ZERO(&fdset);
	FD_SET(s, &fdset);
	timeout.tv_sec = 10;
	timeout.tv_usec = 0;
	rc = select(32, &fdset, NULL, NULL, &timeout);
	if (rc == 0 || rc == -1)
		return FALSE;

	rc = (int)recv(s, buf, RECVBUFLEN, 0);
	return rc != -1;
}
