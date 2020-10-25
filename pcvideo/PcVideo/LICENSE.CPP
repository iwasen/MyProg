/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: ライセンス処理
 *		ファイル名	: license.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// license.cpp : インプリメンテーション ファイル
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
// CLicenseWnd ダイアログ

CLicenseWnd::CLicenseWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CLicenseWnd::IDD, pParent)
{
	//{{AFX_DATA_INIT(CLicenseWnd)
		// 注意: ClassWizard はこの位置にメンバの初期化処理を追加します。
	//}}AFX_DATA_INIT
}

void CLicenseWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CLicenseWnd)
		// 注意: ClassWizard はこの位置に DDX と DDV の呼び出しコードを追加します。
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CLicenseWnd, CDialog)
	//{{AFX_MSG_MAP(CLicenseWnd)
		// 注意 － ClassWizard はこの位置にメッセージ マップ用のマクロを追加します。
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLicenseWnd メッセージハンドラ


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
