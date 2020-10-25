/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: �̔������͏���			*
 *		�t�@�C����	: sales.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <string.h>

/*--------------------------------------*/
/*		�����֐�		*/
/*--------------------------------------*/
static	VOID	SetSalesData(HWND);
static	VOID	WriteSalesData(HWND, BOOL);
static	BOOL	CreateSalesDBF(VOID);
static	BOOL	UseSalesIndex(BOOL);

/*--------------------------------------*/
/*		�����ϐ�		*/
/*--------------------------------------*/
static	SALESDBF	salesBuf;	/* �̔����t�@�C���o�b�t�@ */
static	SALESDBF	salesBuf2;	/* �̔����t�@�C���o�b�t�@�Q */
static	ENTRY_TBL	entryTbl[6] = {	/* ���͏��e�[�u�� */
	{ET_STRINGFIELD, IDD_SALE_SExxxN, 40, salesBuf.sexxxn},
	{ET_STRINGFIELD, IDD_SALE_SERIAL, 16, salesBuf.serial},
	{ET_STRINGFIELD, IDD_SALE_DATE,    8, salesBuf.date},
	{ET_STRINGFIELD, IDD_SALE_PRICE,  12, salesBuf.price},
	{ET_STRINGFIELD, IDD_SALE_SHOP,   40, salesBuf.shop},
	{ET_STRINGFIELD, IDD_SALE_BIKOU,  40, salesBuf.bikou}
};
static	CHAR	userID[8];		/* ���[�U�h�c */
static	SHORT	page;			/* �J�����g�y�[�W */
static	SHORT	nPage;			/* �y�[�W�� */

/*=======================================================================
 |
 |		�̔������̓_�C�A���O�v���V�[�W��
 |
 |	MRESULT CALLBACK SalesDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK SalesDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	switch (msg) {
	case WM_INITDLG:
		memcpy(userID, (CHAR *)mp2, 8);
		nPage = (SHORT)DBCount(salesDBp, userID, 8, indexSales);
		if (nPage == 0)
			page = 0;
		else
			page = nPage - 1;
		SetSalesData(hwnd);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			WriteSalesData(hwnd, FALSE);
			break;
		case DID_CANCEL:
			break;
		case IDD_CMD_DELETE:
			if (WinMessageBox(HWND_DESKTOP, hwnd,
					"�폜���܂��B��낵���ł����H",
					"�m�@�F", ID_MB,
					MB_YESNO | MB_ICONQUESTION |
					MB_APPLMODAL) == MBID_YES) {
				DBDelete2(salesDBp);
				nPage--;
				if (page == nPage && page != 0)
					page--;
				SetSalesData(hwnd);
			}
			return(0);
		case IDD_CMD_PPAGE:
			WriteSalesData(hwnd, TRUE);
			if (page > 0) {
				page--;
				SetSalesData(hwnd);
			}
			return(0);
		case IDD_CMD_NPAGE:
			WriteSalesData(hwnd, TRUE);
			if (page < nPage) {
				page++;
				SetSalesData(hwnd);
			}
			return(0);
		}
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		�̔������_�C�A���O�ɕ\������
 |
 |	VOID	SetSalesData(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	SetSalesData(HWND hwnd)
{
	CHAR	strBuf[40];

	if (page == nPage) {
		memset(&salesBuf, ' ', sizeof(salesBuf));
		memcpy(salesBuf.userID, userID, 8);
	} else {
		DBSearch(salesDBp, userID, 8, indexSales);
		DBSkip(salesDBp, (long)page);
		DBRead(salesDBp, &salesBuf);
	}
	salesBuf2 = salesBuf;

	SetEntryData(hwnd, entryTbl, sizeof(entryTbl) / sizeof(ENTRY_TBL));

	if (page == nPage)
		sprintf(strBuf, "�̔����i�V�K�o�^�j");
	else
		sprintf(strBuf, "�̔���� (%d/%d)", page+1, nPage);
	WinSetWindowText(hwnd, strBuf);

	WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_DELETE), page != nPage);
	WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_PPAGE), page != 0);
	WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_NPAGE), page != nPage);
}

/*=======================================================================
 |
 |		�_�C�A���O���̔��������o���A�t�@�C���ɏ�������
 |
 |	VOID	WriteSalesData(hwnd, fCheck)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		BOOL	fCheck;		�X�V�m�F�L���t���O
 |
 =======================================================================*/
static	VOID	WriteSalesData(HWND hwnd, BOOL fCheck)
{
	GetEntryData(hwnd, entryTbl, sizeof(entryTbl) / sizeof(ENTRY_TBL));

	if (memcmp(&salesBuf, &salesBuf2, sizeof(SALESDBF)) == 0)
		return;

	if (fCheck) {
		if (WinMessageBox(HWND_DESKTOP, hwnd,
				"�X�V���܂����H",
				"�m�@�F", ID_MB,
				MB_YESNO | MB_ICONQUESTION |
				MB_APPLMODAL) != MBID_YES) {
			return;
		}
	}

	if (page == nPage) {
		DBStore(salesDBp, &salesBuf);
		nPage++;
	} else {
		DBUpdate(salesDBp, &salesBuf);
	}
}

/*=======================================================================
 |
 |		�̔����t�@�C���I�[�v������
 |
 |	BOOL	OpenSalesDBF(fIndexCreate)
 |
 |		BOOL	fIndexCreate;	�C���f�b�N�X�t�@�C���쐬�t���O
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�I�[�v�����s�i���~�j
 |
 =======================================================================*/
BOOL	OpenSalesDBF(BOOL fIndexCreate)
{
	if ((salesDBp = DBOpen(fnSALESDBF)) == NULL) {
		if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"�V�K�ɍ쐬���܂����H",
				"�̔����t�@�C��������܂���I", ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
				) == MBID_YES)
			return(CreateSalesDBF());
		else
			return(FALSE);
	} else {
		if (DBCheckSize(salesDBp))
			fIndexCreate = TRUE;
	}

	if (UseSalesIndex(fIndexCreate) == FALSE) {
		DBClose(salesDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		�̔����t�@�C���V�K�쐬����
 |
 |	BOOL	CreateSalesDBF()
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�쐬���s
 |
 =======================================================================*/
static	BOOL	CreateSalesDBF(VOID)
{
	static	DBF_I	fieldData[] = {
		{"���[�U�h�c", 'C',  8, 0},
		{"���i��",     'C', 40, 0},
		{"�V���A��No", 'C', 16, 0},
		{"�w�����t",   'C',  8, 0},
		{"�w�����i",   'C', 12, 0},
		{"�̔��X",     'C', 40, 0},
		{"���l",       'C', 40, 0}
	};

	if ((salesDBp = DBCreate(fnSALESDBF, fieldData,
				sizeof(fieldData)/sizeof(DBF_I))) == NULL) {
		WinMessageBox(HWND_DESKTOP, hwndMain,
				"�t�@�C�����쐬�ł��܂���ł����B",
				"�̔����t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (UseSalesIndex(TRUE) == FALSE) {
		DBClose(salesDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		�̔����t�@�C���̃C���f�b�N�X�t�@�C���I�[�v���^�쐬
 |
 |	BOOL	UseSalesIndex(fCreate)
 |
 |		BOOL	fCreate;	�쐬�t���O
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�쐬���s
 |
 =======================================================================*/
static	BOOL	UseSalesIndex(BOOL fCreate)
{
	if (fCreate || (indexSales = DBIndex(salesDBp, fnSALESNDX)) == 0) {
		if ((indexSales = DBIdxCreate(salesDBp, fnSALESNDX,
					"���[�U�h�c+�w�����t", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"�̔����t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}
	return(TRUE);
}