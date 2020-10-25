// ConxxxtDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "PcVideo.h"
#include "ConxxxtDlg.h"
#include "pcvcomm.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CConxxxtDlg ダイアログ


CConxxxtDlg::CConxxxtDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CConxxxtDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CConxxxtDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CConxxxtDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CConxxxtDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CConxxxtDlg, CDialog)
	//{{AFX_MSG_MAP(CConxxxtDlg)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CConxxxtDlg メッセージ ハンドラ

BOOL CConxxxtDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	int	i;	
	for (i = 0; i < 8; i++) {
		if (iniData.hostList.host[i][0] != '\0') {
			SendDlgItemMessage(IDC_HOSTNAME,
				CB_ADDSTRING, 0,
				(LONG)(LPSTR)iniData.hostList.host[i]);
		}
	}
	SendDlgItemMessage(IDC_HOSTNAME,
				CB_SELECTSTRING, (WPARAM)-1,
				(LONG)(LPSTR)iniData.hostList.target);
	SetDlgItemInt(IDC_CHANNEL_NO,
				iniData.hostList.channelNo, FALSE);

	return TRUE;
}

void CConxxxtDlg::OnOK() 
{
	char	hostName[LEN_HOSTNAME];
	int	channelNo;
	int	err;

	if (GetDlgItemText(IDC_HOSTNAME, hostName, sizeof(hostName)) == 0)
		return;

	channelNo = GetDlgItemInt(IDC_CHANNEL_NO, &err, FALSE);

	ConxxxtHost(hostName, channelNo);

	EndDialog(TRUE);
}

void CConxxxtDlg::OnCancel() 
{
	EndDialog(FALSE);
}
