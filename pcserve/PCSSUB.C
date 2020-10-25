/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: サーバ共通サブルーチン
 *		ファイル名	: pcssub.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <time.h>
#include "pcserve.h"

#if defined (OS_WNT)
static	CHAR	iniFile[] = ".\\PCSERVE.INI";
#else
static	CHAR	iniFile[] = "PCSERVE.INI";
#endif
static	char	sectionMULTI[] = "MULTI";
static	char	sectionDUPLEX[] = "DUPLEX";
static	char	sectionMAIL[] = "MAIL";

static	BOOL	GetPrivateProfileBool(char *, char *, BOOL, char *);
static	VOID	WritePrivateProfileBool(char *, char *, BOOL, char *);
static	VOID	WritePrivateProfileLong(char *, char *, LONG, char *);
static BOOL _CheckPermission(char *pPathName, char userGrpFlag, char *pUserID, char *pPermission);
static BOOL _CheckPermission2(char *pPathName, char userGrpFlag, char *pUserID, char *pPermission);

CRITICALSECTION	csDirName;
CRITICALSECTION	csPermission;

/*=======================================================================
 |
 |		ASCIIZ文字列からデータベースフィールドにコピー
 |
 |	VOID	CopyToDBF(dbField, strbuf, length)
 |
 |		CHAR	*dbField;	データベースフィールド
 |		CHAR	*strBuf;	ASCIIZ文字列
 |		SHORT	length;		フィールド長
 |
 =======================================================================*/
VOID	CopyToDBF(CHAR *dbField, CHAR *strbuf, SHORT length)
{
	SHORT	slen;

	slen = strlen(strbuf);
	memcpy(dbField, strbuf, slen);
	if (slen < length)
		memset(dbField+slen, ' ', length - slen);
}

/*=======================================================================
 |
 |		データベースフィールドからASCIIZ文字列にコピー
 |
 |	VOID	CopyFromDBF(strbuf, dbField, length)
 |
 |		CHAR	*strBuf;	ASCIIZ文字列
 |		CHAR	*dbField;	データベースフィールド
 |		SHORT	length;		フィールド長
 |
 =======================================================================*/
VOID	CopyFromDBF(CHAR *strbuf, CHAR *dbField, SHORT length)
{
	while (length != 0) {
		if (dbField[length-1] != ' ')
			break;
		length--;
	}
	memcpy(strbuf, dbField, length);
	strbuf[length] = '\0';
}

/*=======================================================================
 |
 |		環境設定ファイル読み込み処理
 |
 |	VOID	ReadIniFile()
 |
 =======================================================================*/
VOID	ReadIniFile(VOID)
{
	CHAR	buf[128], buf2[128];
	int	i;
	int	modemNo;

	ini.bMultiServer = GetPrivateProfileBool(sectionMULTI, "MultiServer",
			FALSE, iniFile);

	GetPrivateProfileString(sectionDUPLEX, "Mode", "0",
					buf, sizeof(buf), iniFile);
	ini.duplexMode = atoi(buf);

	GetPrivateProfileString(sectionDUPLEX, "Server", "",
			ini.duplexServer, sizeof(ini.duplexServer), iniFile);

	GetPrivateProfileString(sectionDUPLEX, "AutoRepairTime", "",
			ini.duplexAutoRepairTime,
			sizeof(ini.duplexAutoRepairTime), iniFile);

	GetPrivateProfileString(sectionMAIL, "SMTPServer", "",
			ini.mailSMTPServer, sizeof(ini.mailSMTPServer),
			iniFile);
	GetPrivateProfileString(sectionMAIL, "ToAddress", "",
			ini.mailToAddress, sizeof(ini.mailToAddress), iniFile);
	GetPrivateProfileString(sectionMAIL, "FromAddress", "",
			ini.mailFromAddress, sizeof(ini.mailFromAddress),
			iniFile);
	ini.mailReportStartStop = GetPrivateProfileBool(sectionMAIL,
			"ReportStartStop", FALSE, iniFile);
	ini.mailReportDuplexServer = GetPrivateProfileBool(sectionMAIL,
			"ReportDuplexServer", FALSE, iniFile);
	ini.mailReportMultiServer = GetPrivateProfileBool(sectionMAIL,
			"ReportMultiServer", FALSE, iniFile);
	ini.mailReportSystemError = GetPrivateProfileBool(sectionMAIL,
			"ReportSystemError", FALSE, iniFile);

	ini.bNetBIOS = GetPrivateProfileBool("CHANNEL", "NetBIOS", TRUE,
								iniFile);
	if (ini.bNetBIOS) {
		GetPrivateProfileString("NETBIOS", "PipeName",
				"\\PIPE\\PCSERVE",
				ini.pipeName, sizeof(ini.pipeName), iniFile);
	}

	ini.bTCPIP = GetPrivateProfileBool("CHANNEL", "TCPIP", TRUE, iniFile);
	if (ini.bTCPIP) {
		GetPrivateProfileString("TCPIP", "PortNo", "5000",
				buf, sizeof(buf), iniFile);
		ini.portNo = atoi(buf);
	}

	for (i = 0; i < N_RS232C; i++) {
		sprintf(buf2, "RS232C_%d", i + 1);
		if (GetPrivateProfileBool("CHANNEL", buf2, FALSE, iniFile)) {
			GetPrivateProfileString(buf2, "BaudRate", "9600",
					buf, sizeof(buf), iniFile);
			ini.rsParam[i].baudRate = atoi(buf);
			GetPrivateProfileString(buf2, "Modem", "0",
					buf, sizeof(buf), iniFile);
			modemNo = atoi(buf);
			if (modemNo == 0)
				ini.rsParam[i].conxxxt = RS232C_DIRECT;
			else {
				ini.rsParam[i].conxxxt = RS232C_MODEM;

				sprintf(buf2, "MODEM_%d", modemNo);

				GetPrivateProfileString(buf2, "InitCommand",
					"ATZ", buf, sizeof(buf), iniFile);
				strcpy(ini.rsParam[i].modem.initCmd, buf);

				GetPrivateProfileString(buf2, "DetectCall",
					"RI", buf, sizeof(buf),iniFile);
				ini.rsParam[i].modem.detectCall =
							(CHAR)toupper(buf[0]);

				GetPrivateProfileString(buf2, "RingMessage",
					"RING",buf, sizeof(buf), iniFile);
				strcpy(ini.rsParam[i].modem.ringMsg, buf);

				GetPrivateProfileString(buf2, "ConxxxtCommand",
					"ATA",buf, sizeof(buf), iniFile);
				strcpy(ini.rsParam[i].modem.conxxxtCmd, buf);

				GetPrivateProfileString(buf2, "Disconxxxt",
					"DTR", buf, sizeof(buf),iniFile);
				ini.rsParam[i].modem.disconxxxt =
							(CHAR)toupper(buf[0]);

				GetPrivateProfileString(buf2, "EscCode",
					"+++",buf, sizeof(buf), iniFile);
				strcpy(ini.rsParam[i].modem.escCode, buf);

				GetPrivateProfileString(buf2, "GuardTime",
					"2",buf, sizeof(buf), iniFile);
				ini.rsParam[i].modem.guardTime = atoi(buf);

				GetPrivateProfileString(buf2,
					"DisconxxxtCommand",
					"ATH", buf, sizeof(buf), iniFile);
				strcpy(ini.rsParam[i].modem.discCmd, buf);

				GetPrivateProfileString(buf2, "DtrOffTime",
					"3",buf, sizeof(buf), iniFile);
				ini.rsParam[i].modem.dtrOffTime = atoi(buf);
			}
		}
	}

	GetPrivateProfileString("DEBUG", "ServerLog", "0",
					buf, sizeof(buf), iniFile);
	ini.serverLog = atoi(buf);

	GetPrivateProfileString("DEBUG", "MaxLogFileSize", "1024",
					buf, sizeof(buf), iniFile);
	ini.maxLogFileSize = atol(buf) * 1024L;

	ini.bNoHideLog = GetPrivateProfileBool("DEBUG", "NoHideLog", FALSE,
								iniFile);

	if (ini.duplexMode == DUPLEX_SLAVE)
		ini.bMultiServer = FALSE;
}

/*=======================================================================
 |
 |		環境設定ファイル書き込み処理
 |
 |	VOID	WriteIniFile()
 |
 =======================================================================*/
VOID	WriteIniFile(VOID)
{
	WritePrivateProfileBool(sectionMULTI, "MultiServer",
					ini.bMultiServer, iniFile);

	WritePrivateProfileLong(sectionDUPLEX, "Mode",
					ini.duplexMode, iniFile);

	WritePrivateProfileString(sectionDUPLEX, "Server",
					ini.duplexServer, iniFile);

	WritePrivateProfileString(sectionDUPLEX, "AutoRepairTime",
					ini.duplexAutoRepairTime, iniFile);

	WritePrivateProfileString(sectionMAIL, "SMTPServer",
					ini.mailSMTPServer, iniFile);
	WritePrivateProfileString(sectionMAIL, "ToAddress",
					ini.mailToAddress, iniFile);
	WritePrivateProfileString(sectionMAIL, "FromAddress",
					ini.mailFromAddress, iniFile);
	WritePrivateProfileBool(sectionMAIL, "ReportStartStop",
					ini.mailReportStartStop, iniFile);
	WritePrivateProfileBool(sectionMAIL, "ReportDuplexServer",
					ini.mailReportDuplexServer, iniFile);
	WritePrivateProfileBool(sectionMAIL, "ReportMultiServer",
					ini.mailReportMultiServer, iniFile);
	WritePrivateProfileBool(sectionMAIL, "ReportSystemError",
					ini.mailReportSystemError, iniFile);

	WritePrivateProfileBool("CHANNEL", "NetBIOS", ini.bNetBIOS, iniFile);
	WritePrivateProfileString("NETBIOS", "PipeName",
					ini.pipeName, iniFile);

	WritePrivateProfileBool("CHANNEL", "TCPIP", ini.bTCPIP, iniFile);
	WritePrivateProfileLong("TCPIP", "PortNo", ini.portNo, iniFile);

	WritePrivateProfileLong("DEBUG", "ServerLog", ini.serverLog, iniFile);

	WritePrivateProfileLong("DEBUG", "MaxLogFileSize",
					ini.maxLogFileSize / 1024L, iniFile);

	WritePrivateProfileBool("DEBUG", "NoHideLog", ini.bNoHideLog, iniFile);
}

static	BOOL	GetPrivateProfileBool(char *section, char *entry,
						BOOL bDefault, char *iniFile)
{
	char	buf[10];

	GetPrivateProfileString(section, entry, bDefault ? "TRUE" : "FALSE",
					buf, sizeof(buf), iniFile);
	StringToUpper(buf);
	if (strcmp(buf, "TRUE") == 0)
		return TRUE;
	else
		return FALSE;
}

static	VOID	WritePrivateProfileBool(char *section, char *entry,
						BOOL bValue, char *iniFile)
{
	WritePrivateProfileString(section, entry, bValue ? "TRUE" : "FALSE",
								iniFile);
}

static	VOID	WritePrivateProfileLong(char *section, char *entry,
						LONG lValue, char *iniFile)
{
	char	buf[20];

	sprintf(buf, "%ld", lValue);
	WritePrivateProfileString(section, entry, buf, iniFile);
}

SHORT	ConvertPathName(CHAR *path1, CHAR *path2)
{
	CHAR *p;
	SHORT find;
	SHORT fieldType, dataSize;
	char buf[LEN_DIRNAME_DIRNAME + 1];
	int	len;

	if (*path1 != '%') {
		strcpy(path2, path1);
	} else {
		path1++;
		if ((p = strchr(path1, '\\')) == NULL)
			 p = &path1[strlen(path1)];

		len = p - path1;
		if (len > LEN_DIRNAME_DIRNAME)
			return ERROR_DIRNAME;

		memset(buf, ' ', LEN_DIRNAME_DIRNAME);
		memcpy(buf, path1, len);
		buf[LEN_DIRNAME_DIRNAME] = '\0';

		ENTER_CRITICAL_SECTION(&csDirName);

		DBSearch2(pDirNameDB, buf, LEN_DIRNAME_DIRNAME, &find);
		if (find == 1) {
			DBGetRecord(pDirNameDB);
			DBGetField(pDirNameDB, "DIRECTORY", path2, &fieldType, &dataSize);
		}

		LEAVE_CRITICAL_SECTION(&csDirName);

		if (find != 1)
			return ERROR_DIRNAME;

		strcat(path2, p);
	}

	return 0;
}

SHORT	CheckPermission(PSCB pSCB, char *pPathName, SHORT *pPermission)
{
	static char validPermissionID[] = "_VALIDPERM";
	char fullPath[256];
	char permission1[5];
	char permission2[5];
	int i;
	PDB pUserGrpDB;
	char userKey[10];
	DBBufUsrGrp userGrpBuf;
	char groupID[11];
	SHORT nRead;

	if (pSCB->bMultiPertner) {
		*pPermission = PERMISSION_ALL;
		return 0;
	}

	*pPermission = PERMISSION_NONE;

	GetFullPath(pPathName, fullPath, sizeof(fullPath));

	if (_CheckPermission(fullPath, ' ', validPermissionID, NULL)) {
		if (!pSCB->bLoginUser)
			return ERROR_NOLOGIN;

		memset(permission1, ' ', sizeof(permission1));
		if (_CheckPermission(fullPath, ' ', pSCB->userID, permission2)) {
			for (i = 0; i < sizeof(permission1); i++) {
				if (permission2[i] == '*')
					permission1[i] = '*';
			}
		}

		if (OpenUserGrpDBF(&pUserGrpDB) == 0) {
			CopyToDBF(userKey, pSCB->userID, 10);
			DBSearch(pUserGrpDB, userKey, sizeof(userKey));
			for (;;) {
				if (DBReadNext(pUserGrpDB, 1, (char *)&userGrpBuf, &nRead) != 0 || nRead == 0)
					break;

				if (memcmp(userGrpBuf.userID, userKey, sizeof(userKey)) != 0)
					break;

				CopyFromDBF(groupID, userGrpBuf.groupID, sizeof(userGrpBuf.groupID));
				if (_CheckPermission(fullPath, '*', groupID, permission2)) {
					for (i = 0; i < sizeof(permission1); i++) {
						if (permission2[i] == '*')
							permission1[i] = '*';
					}
				}
			}

			for (i = 0; i < 5; i++) {
				if (permission1[i] == '*')
					*pPermission |= 1 << i;
			}
		}
	} else
		*pPermission = PERMISSION_ALL;

	return 0;
}

static BOOL _CheckPermission(char *pPathName, char userGrpFlag, char *pUserID, char *pPermission)
{
	char *p;
	char c;
	BOOL rc;

	if (_CheckPermission2(pPathName, userGrpFlag, pUserID, pPermission))
		return TRUE;

	if ((p = strrchr(pPathName, '\\')) == NULL)
		return FALSE;

	if (*(p - 1) == ':')
		p++;

	c = *p;
	*p = '\0';
	rc = _CheckPermission2(pPathName, userGrpFlag, pUserID, pPermission);
	*p = c;

	return rc;
}

static BOOL _CheckPermission2(char *pPathName, char userGrpFlag, char *pUserID, char *pPermission)
{
	struct {
		char directory[LEN_PERM_DIRECTORY];
		char userGrpFlg[LEN_PERM_USERGRPFLG];
		char userID[LEN_PERM_USERID];
	} key;
	DBBufPerm buf;
	SHORT find;

	CopyToDBF(key.directory, pPathName, sizeof(key.directory));
	key.userGrpFlg[0] = userGrpFlag;
	CopyToDBF(key.userID, pUserID, sizeof(key.userID));

	if (DBReadKey(pPermissionDB, (char *)&key, sizeof(key), (char *)&buf, 0, &find) == 0 && find == 1) {
		if (pPermission != NULL)
			memcpy(pPermission, buf.permission, LEN_PERM_PERMISSION);

		return TRUE;
	}

	return FALSE;
}

VOID	SystemError(char *message)
{
	ErrorLog(NULL, message);
}

VOID	ErrorLog(PSCB pSCB, char *fmt, void *p)
{
	FILE	*fp;
	long	ltime;
	struct	tm	*tm;

	time(&ltime);
	tm = localtime(&ltime);

	if ((fp = fopen(FN_ERROR_LOG, "a")) != NULL) {
		fprintf(fp, "%2u/%02u/%02u %2u:%02u:%02u ",
				tm->tm_year, tm->tm_mon+1, tm->tm_mday,
				tm->tm_hour, tm->tm_min, tm->tm_sec);
		if (pSCB != NULL) {
			fprintf(fp, "ClinetID=%s ComputerName=%s ",
					pSCB->login.clientID,
					pSCB->login.computerName);
		}
		vfprintf(fp, fmt, (char *)&p);
		fprintf(fp, "\r\n");
		fclose(fp);
	}

	if (ini.mailReportSystemError) {
		char	buf[256], *bufp;

		bufp = buf;
		if (pSCB != NULL) {
			sprintf(p, "ClinetID=%s ComputerName=%s ",
					pSCB->login.clientID,
					pSCB->login.computerName);
			bufp += strlen(bufp);
		}
		vsprintf(bufp, fmt, (char *)&p);
		SendReportMail("システムエラー", buf);
	}
}
