/************************************************************************
 *
 *			�o�b�|�u�h�c�d�n
 *
 *		����		: ���C�Z���X����
 *		�t�@�C����	: license.cpp
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

// license.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "mainfrm.h"
#include "license.h"

#ifdef _DEBUG
#undef THIS_FILE
static char BASED_CODE THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CLicenseWnd �_�C�A���O

CLicenseWnd::CLicenseWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CLicenseWnd::IDD, pParent)
{
	//{{AFX_DATA_INIT(CLicenseWnd)
		// ����: ClassWizard �͂��̈ʒu�Ƀ����o�̏�����������ǉ����܂��B
	//}}AFX_DATA_INIT
}

void CLicenseWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CLicenseWnd)
		// ����: ClassWizard �͂��̈ʒu�� DDX �� DDV �̌Ăяo���R�[�h��ǉ����܂��B
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CLicenseWnd, CDialog)
	//{{AFX_MSG_MAP(CLicenseWnd)
		// ���� �| ClassWizard �͂��̈ʒu�Ƀ��b�Z�[�W �}�b�v�p�̃}�N����ǉ����܂��B
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLicenseWnd ���b�Z�[�W�n���h��


void	ReceiveLicense(PLICENSE pLicense)
{
	LICENSE	license;

	switch (pLicense->mode) {
	case LICENSE_CHECK:
		if (pLicense->header.id != localIPAddress &&
				strcmp(pLicense->registNo,
					iniData.license.registNo) == 0) {
			license.header.flag = FLAG_LICENSE;
			license.header.id = localIPAddress;
			license.mode = LICENSE_REPLY;
			strcpy(license.registNo, iniData.license.registNo);
			SendUDP(sockUDP, pLicense->header.id,
					htons(PORTNO),
					(char *)&license, sizeof(license));
		}
		break;
	case LICENSE_REPLY:
		if (++licenseReplyCount > 0) {
			licenseReplyCount = -1;
			AfxMessageBox(IDS_LISENCE_LIMIT, MB_OK|MB_ICONEXCLAMATION);
			pMainWnd->PostMessage(WM_CLOSE, 0, 0L);
		}
		break;
	}
}

void	SendLicenseCheck()
{
	LICENSE	license;

	license.header.flag = FLAG_LICENSE;
	license.header.id = localIPAddress;
	license.mode = LICENSE_CHECK;
	strcpy(license.registNo, iniData.license.registNo);
	SendUDP(sockUDP, INADDR_BROADCAST, htons(PORTNO),
				(char *)&license, sizeof(license));
}