/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: ���ʃT�u���[�`��			*
 *		�t�@�C����	: csub.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>
#include <io.h>
#include <sys\types.h>
#include <sys\stat.h>

/*=======================================================================
 |
 |		ASCIIZ�����񂩂�f�[�^�x�[�X�t�B�[���h�ɃR�s�[
 |
 |	VOID	CopyToDBF(dbField, strbuf, length)
 |
 |		CHAR	*dbField;	�f�[�^�x�[�X�t�B�[���h
 |		CHAR	*strBuf;	ASCIIZ������
 |		SHORT	length;		�t�B�[���h��
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
 |		�f�[�^�x�[�X�t�B�[���h����ASCIIZ������ɃR�s�[
 |
 |	VOID	CopyFromDBF(strbuf, dbField, length)
 |
 |		CHAR	*strBuf;	ASCIIZ������
 |		CHAR	*dbField;	�f�[�^�x�[�X�t�B�[���h
 |		SHORT	length;		�t�B�[���h��
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
 |		�_�C�A���O�{�b�N�X�̃G���g���t�B�[���h�Ƀf�[�^���Z�b�g
 |
 |	VOID	SetEntryData(hwnd, entryTbl, itemNum)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		ENTRY_TBL *entryTBl;	�G���g���f�[�^�e�[�u��
 |		SHORT	itemNum;	���ڐ�
 |
 =======================================================================*/
VOID	SetEntryData(HWND hwnd, ENTRY_TBL *entryTbl, SHORT itemNum)
{
	CHAR	fieldBuf[100];
	USHORT	i;
	CHAR	*p;

	while (itemNum--) {
		switch (entryTbl->type) {
		case ET_STRINGFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			CopyFromDBF(fieldBuf, entryTbl->ptr, entryTbl->field);
			WinSetDlgItemText(hwnd, entryTbl->id, fieldBuf);
			break;
		case ET_NUMSTRFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			for (i = 0, p = fieldBuf; i < entryTbl->field; i++)
				if (((CHAR *)entryTbl->ptr)[i] != ' ')
					*p++ = ((CHAR *)entryTbl->ptr)[i];
			*p = '\0';
			WinSetDlgItemText(hwnd, entryTbl->id, fieldBuf);
			break;
		case ET_STZEROFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			WinSetDlgItemText(hwnd, entryTbl->id, entryTbl->ptr);
			break;
		case ET_NUMBERFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			WinSetDlgItemShort(hwnd, entryTbl->id,
				*(USHORT *)entryTbl->ptr, FALSE);
			break;
		case ET_RADIOBUTTON:
		case ET_CHECKBUTTON:
			WinSendDlgItemMsg(hwnd, entryTbl->id, BM_SETCHECK,
				MPFROMSHORT(*(CHAR *)entryTbl->ptr ==
				(CHAR)entryTbl->field ? 1 : 0), 0L);
			break;
		}
		entryTbl++;
	}
}

/*=======================================================================
 |
 |		�_�C�A���O�{�b�N�X�̃G���g���t�B�[���h����f�[�^�����o��
 |
 |	VOID	GetEntryData(hwnd, entryTbl, itemNum)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		ENTRY_TBL *entryTBl;	�G���g���f�[�^�e�[�u��
 |		SHORT	itemNum;	���ڐ�
 |
 =======================================================================*/
VOID	GetEntryData(HWND hwnd, ENTRY_TBL *entryTbl, SHORT itemNum)
{
	CHAR	fieldBuf[100];
	SHORT	len;

	while (itemNum--) {
		switch (entryTbl->type) {
		case ET_STRINGFIELD:
			WinQueryDlgItemText(hwnd, entryTbl->id,
					sizeof(fieldBuf), fieldBuf);
			CopyToDBF(entryTbl->ptr, fieldBuf, entryTbl->field);
			break;
		case ET_NUMSTRFIELD:
			WinQueryDlgItemText(hwnd, entryTbl->id,
					sizeof(fieldBuf), fieldBuf);
			len = strlen(fieldBuf);
			memset(entryTbl->ptr, ' ', entryTbl->field - len);
			memcpy((CHAR *)entryTbl->ptr + entryTbl->field - len,
							fieldBuf, len);
			break;
		case ET_STZEROFIELD:
			WinQueryDlgItemText(hwnd, entryTbl->id,
					entryTbl->field + 1, entryTbl->ptr);
			break;
		case ET_NUMBERFIELD:
			WinQueryDlgItemShort(hwnd, entryTbl->id,
					entryTbl->ptr, FALSE);
			break;
		case ET_RADIOBUTTON:
			if (WinSendDlgItemMsg(hwnd, entryTbl->id,
					BM_QUERYCHECK, 0, 0))
				*(CHAR *)entryTbl->ptr = (CHAR)entryTbl->field;
			break;
		case ET_CHECKBUTTON:
			*(CHAR *)entryTbl->ptr = (CHAR)(WinSendDlgItemMsg(hwnd,
				entryTbl->id, BM_QUERYCHECK, 0, 0) ?
				entryTbl->field : ' ');
			break;
		}
		entryTbl++;
	}
}

/*=======================================================================
 |
 |		�G�f�B�^�N������
 |
 |	VOID	ExecEditor(fname)
 |
 |		CHAR	*fname;		�G�f�B�^���s�t�@�C����
 |
 =======================================================================*/
VOID	ExecEditor(CHAR *fname)
{
	static	CHAR	param[40];
	static	STARTDATA	stdata = {
		50, TRUE, FALSE, 0, systemName, sv.env.editor, param,
		0, 0, 1, 0, 0, 0, 32768, 0, 0, 0, 0
	};
	USHORT	idSession, pid;

	sprintf(param, "%s %s", sv.env.edOption, fname);
	DosStartSession(&stdata, &idSession, &pid);
}

/*=======================================================================
 |
 |		�E�B���h�E�̈ʒu�Ƒ傫�����Z�b�g
 |
 |	VOID	SetWindowPos(hwnd, nx, ny)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		SHORT	nx;		�E�B���h�E���̌���
 |		SHORT	ny;		�E�B���h�E���̍s��
 |
 =======================================================================*/
VOID	SetWindowPos(HWND hwnd, SHORT nx, SHORT ny)
{
	SHORT	cx, cy;

	cx = (SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CXSIZEBORDER) * 2 +
		(SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CXVSCROLL) +
		nx * gcxAveChar;

	cy = (SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CYSIZEBORDER) * 2 +
		(SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CYTITLEBAR) +
		(SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CYMENU) +
		ny * gcyChar;

	WinSetWindowPos(hwnd, HWND_TOP,
			(cxScreen - cx) / 2,
			(cyScreen - cy) / 2,
			cx, cy, SWP_MOVE | SWP_SIZE | SWP_ACTIVATE);
}

/*=======================================================================
 |
 |		�_�C�A���O�E�B���h�E�\���ʒu����ʒ����ɃZ�b�g
 |
 |	VOID	CenterDlgBox(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
VOID	CenterDlgBox(HWND hwnd)
{
	SWP	swp;
 
	WinQueryWindowPos(hwnd, &swp);
	WinSetWindowPos(hwnd, HWND_TOP,
			(cxScreen - swp.cx) / 2,
			(cyScreen - swp.cy) / 2,
			0, 0, SWP_MOVE);
}

/*=======================================================================
 |
 |		���ݒ�t�@�C���ǂݍ��ݏ���
 |
 |	VOID	ReadEnvData()
 |
 =======================================================================*/
VOID	ReadEnvData(VOID)
{
	FILE	*fp;

	if ((fp = fopen(fnEnvData, "rb")) != NULL) {
		fread(&sv, sizeof(sv), 1, fp);
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		���ݒ�t�@�C���������ݏ���
 |
 |	VOID	WriteEnvData()
 |
 =======================================================================*/
VOID	WriteEnvData(VOID)
{
	FILE	 *fp;

	if ((fp = fopen(fnEnvData, "wb")) != NULL) {
		fwrite(&sv, sizeof(sv), 1, fp);
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		�V�K�t�@�C���쐬����
 |
 |	FILE	*MakeNewFile(dirName, fileName, pathName, openMode)
 |
 |		CHAR	*dirName;	�f�B���N�g����
 |		CHAR	*fileName;	�쐬�����t�@�C�������i�[����G���A
 |		CHAR	*pathName;	�쐬�����p�X�����i�[����G���A
 |		CHAR	*openMode;	�t�@�C���I�[�v�����[�h
 |
 |		FILE	*�Ԓl		�쐬�����t�@�C���|�C���^
 |
 =======================================================================*/
FILE	*MakeNewFile(CHAR *dirName,
				CHAR *fileName, CHAR *pathName, CHAR *openMode)
{
	static	DOSFSRSEM	sem = {14};
	CHAR	pName[MAXPATHL], fName[13];
	FILE	*fp;
	DATETIME	dateTime;

	DosFSRamSemRequest(&sem, SEM_INDEFINITE_WAIT);

	DosGetDateTime(&dateTime);
	sprintf(fName, "%02u%02u%02u%02u.%02uA",
			dateTime.year % 100,
			dateTime.month,
			dateTime.day,
			dateTime.hours,
			dateTime.minutes);

	for (;;) {
		sprintf(pName, "%s\\%s", dirName, fName);
		if (access(pName, 0) != 0)
			break;
		fName[11]++;
	}

	fp = fopen(pName, openMode);

	DosFSRamSemClear(&sem);

	if (fileName != NULL)
		strcpy(fileName, fName);

	if (pathName != NULL)
		strcpy(pathName, pName);

	return(fp);
}

/*=======================================================================
 |
 |		�t�@�C�������̓_�C�A���O�v���V�[�W��
 |
 |	MRESULT CALLBACK InputFileNameDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK InputFileNameDlg(HWND hwnd, USHORT msg,
						MPARAM mp1, MPARAM mp2)
{
	INPUTFN	*ifnp;

	switch (msg) {
	case WM_INITDLG:
		ifnp = (INPUTFN *)mp2;
		WinSetWindowText(hwnd, ifnp->title);
		WinSetWindowPtr(hwnd, 0, ifnp);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			ifnp = WinQueryWindowPtr(hwnd, 0);
			WinQueryDlgItemText(hwnd, IDD_FILE_NAME,
						ifnp->bufSize, ifnp->bufp);
			break;
		case DID_CANCEL:
			break;
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		�t�@�C���R�s�[����
 |
 |	VOID	FileCopy(hwnd, dstFile, srcFile)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		CHAR	*dstFile;	�R�s�[��t�@�C����
 |		CHAR	*srcFile;	�R�s�[���t�@�C����
 |
 =======================================================================*/
BOOL	FileCopy(HWND hwnd, CHAR *dstFile, CHAR *srcFile)
{
	HWND	hwndDlg, hwndDlgFrame;
	USHORT	action;
	USHORT	byteRead, byteWritten;
	HFILE	rhFile, whFile;
	struct	stat	st;
	CHAR	buf[1024];
	SWP	swp;
	POINTL	pointl;
	LONG	copySize;
	HPS	hps;

	if (DosOpen(srcFile, &rhFile, &action, 0L, FILE_READONLY,
			FILE_OPEN,
			OPEN_ACCESS_READONLY | OPEN_SHARE_DENYWRITE,
			0L) != 0) {
		WinMessageBox(HWND_DESKTOP, hwnd,
				"�R�s�[���t�@�C��������܂���B",
				systemName, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (access(dstFile, 0) == 0) {
		stat(dstFile, &st);
		if (st.st_size != 0) {
			if (WinMessageBox(HWND_DESKTOP, hwnd,
				"�t�@�C���͊��ɑ��݂��܂��B�㏑�����܂����H",
					systemName, ID_MB,
					MB_YESNO | MB_ICONQUESTION |
					MB_APPLMODAL
					) == MBID_NO) {
				DosClose(rhFile);
				return(FALSE);
			}
		}
	}

	if (DosOpen(dstFile, &whFile, &action, 0L, FILE_NORMAL,
			FILE_TRUNCATE | FILE_CREATE,
			OPEN_ACCESS_WRITEONLY | OPEN_SHARE_DENYREADWRITE,
			0L) != 0) {
		WinMessageBox(HWND_DESKTOP, hwnd,
				"�R�s�[��t�@�C�����I�[�v���ł��܂���B",
				systemName, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		DosClose(rhFile);
		return(FALSE);
	}

	stat(srcFile, &st);

	hwndDlg = WinLoadDlg(HWND_DESKTOP, hwnd,
				 CopyMsg, 0, IDD_FILECOPY, NULL);
	hwndDlgFrame = WinWindowFromID(hwndDlg, IDD_FILECOPY_F);
	WinQueryWindowPos(hwndDlgFrame, &swp);

	hps = WinGetPS(hwndDlg);
	pointl.x = swp.x;
	pointl.y = swp.y + 1;
	GpiSetCurrentPosition(hps, &pointl);
	pointl.y = swp.y + swp.cy - 1;
	copySize = 0;

	SetPointer(SPTR_WAIT);
	for (;;) {
		DosRead(rhFile, buf, sizeof(buf), &byteRead);
		if (byteRead == 0)
			break;
		DosWrite(whFile, buf, byteRead, &byteWritten);
		if (byteWritten != byteRead) {
			WinMessageBox(HWND_DESKTOP, hwnd,
					"�t�@�C���̏������݂Ɏ��s���܂����B",
					"�t�@�C���R�s�[",
					ID_MB,
					MB_OK | MB_ICONEXCLAMATION
					| MB_APPLMODAL);
			break;
		}
		copySize += byteRead;
		pointl.x = swp.x + swp.cx * copySize / st.st_size;
		GpiBox(hps, DRO_FILL, &pointl, 0, 0);
	}
	SetPointer(SPTR_ARROW);

	WinReleasePS(hps);
	WinDestroyWindow(hwndDlg);

	DosClose(rhFile);
	DosClose(whFile);

	return(TRUE);
}

/*=======================================================================
 |
 |		�R�s�[�����b�Z�[�W�\���_�C�A���O�v���V�[�W��
 |
 |	MRESULT CALLBACK CopyMsg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK CopyMsg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	switch (msg) {
	case WM_INITDLG:
		CenterDlgBox(hwnd);
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		�}�E�X�J�[�\���`��̃Z�b�g
 |
 |	VOID	SetPointer(iptr)
 |
 |		SHORT	iptr;		�V�X�e���|�C���^
 |
 =======================================================================*/
VOID	SetPointer(SHORT iptr)
{
	HPOINTER	hptr;

	hptr = WinQuerySysPointer(HWND_DESKTOP, iptr, FALSE);
	WinSetPointer(HWND_DESKTOP, hptr);
}

/*=======================================================================
 |
 |		�t�@�C�����`�F�b�N
 |
 |	BOOL	CheckFileName(fileName)
 |
 |		CHAR	*fileName;	�`�F�b�N����t�@�C����
 |
 |		BOOL	�Ԓl		TRUE:����@�@FALSE:�ُ�
 |
 =======================================================================*/
BOOL	CheckFileName(CHAR *fileName)
{
	int	cLen;
	PSZ	lpsz;

	/* �t�@�C�����`�F�b�N */
	if (*fileName == '\0')
		return(FALSE);
	cLen = 0;
	lpsz = fileName;
	while (*lpsz != '\0' && *lpsz != '.') {
		if (++cLen > 8 || *lpsz < 0x20)
			return(FALSE);
		switch (*lpsz++) {
		case '?':
		case '*':
		case '\"':
		case '\\':
		case '/':
		case '[':
		case ']':
		case ':':
		case '|':
		case '<':
		case '>':
		case '+':
		case '=':
		case ';':
		case ',':
			return(FALSE);
		}
	}

	/* �g���q�`�F�b�N */
	if (*lpsz++ == '\0')
		return(TRUE);
	cLen = 0;
	while (*lpsz != '\0') {
		if (++cLen > 3 || *lpsz < 0x20)
			return(FALSE);
		switch (*lpsz++) {
		case '*':
		case '?':
		case '.':
		case '\"':
		case '\\':
		case '/':
		case '[':
		case ']':
		case ':':
		case '|':
		case '<':
		case '>':
		case '+':
		case '=':
		case ';':
		case ',':
			return(FALSE);
		}
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		�����񒆂̉p����啶���ɕϊ�
 |
 |	VOID	ToUpperStr(str, len)
 |
 |		CHAR	*str;		������
 |		SHORT	len;		������̒���
 |
 =======================================================================*/
VOID	ToUpperStr(CHAR *str, SHORT len)
{
	while (len--) {
		*str = (CHAR)toupper((int)*str);
		str++;
	}
}

/*=======================================================================
 |
 |		�f�o�b�O���\������
 |
 |	VOID	DEBUG(fmt, p)
 |
 |		CHAR	*fmt;		�\���t�H�[�}�b�g�iprintf�`���j
 |		VOID	*p;		�\���f�[�^
 |
 =======================================================================*/
VOID	DEBUG(CHAR *fmt, VOID *p)
{
	static	char	szMsg[100];

	vsprintf(szMsg, fmt, (char *)&p);
	WinPostMsg(hwndMain, WM_USER_MSGBOX, szMsg, "Debug Information");
}