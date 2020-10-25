// KatashikiKiseiMatrixDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "KatashikiKiseiMatrixDlg001.h"
#include "KatashikiKiseiMatrixDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CKatashikiKiseiMatrixDlg ダイアログ


CKatashikiKiseiMatrixDlg::CKatashikiKiseiMatrixDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CKatashikiKiseiMatrixDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CKatashikiKiseiMatrixDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CKatashikiKiseiMatrixDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CKatashikiKiseiMatrixDlg)
	DDX_Control(pDX, IDOK, m_cButtonOK);
	//}}AFX_DATA_MAP
	DDX_Control(pDX, IDC_CHECK_KATASHIKI_1, m_aCheckKatashiki[0]);
	DDX_Control(pDX, IDC_CHECK_KATASHIKI_2, m_aCheckKatashiki[1]);
	DDX_Control(pDX, IDC_CHECK_KATASHIKI_3, m_aCheckKatashiki[2]);
	DDX_Control(pDX, IDC_CHECK_KATASHIKI_4, m_aCheckKatashiki[3]);
	DDX_Control(pDX, IDC_CHECK_KATASHIKI_5, m_aCheckKatashiki[4]);
}


BEGIN_MESSAGE_MAP(CKatashikiKiseiMatrixDlg, CDialogEx)
	//{{AFX_MSG_MAP(CKatashikiKiseiMatrixDlg)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CKatashikiKiseiMatrixDlg メッセージ ハンドラ

BOOL CKatashikiKiseiMatrixDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_KatashikiKiseiMatrixDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	int nKatashikiId;
	int nKatashikiNum = g_pDataManager->GetKatashikiNum();

	for (nKatashikiId = 0; nKatashikiId < nKatashikiNum; nKatashikiId++) {
		SKatashiki *pKatashiki = g_pDataManager->GetKatashikiData(nKatashikiId);
		m_aCheckKatashiki[nKatashikiId].SetWindowText(pKatashiki->sKatashiki);
		m_aCheckKatashiki[nKatashikiId] = 1;
	}

	for (; nKatashikiId < MAX_KISHU; nKatashikiId++)
		m_aCheckKatashiki[nKatashikiId].ShowWindow(SW_HIDE);

	return TRUE;
}

void CKatashikiKiseiMatrixDlg::OnOK() 
{
	int nKatashikiNum = g_pDataManager->GetKatashikiNum();

	BOOL bEdit = FALSE;
	for (int nKatashikiId = 0; nKatashikiId < nKatashikiNum; nKatashikiId++) {
		SKatashiki *pKatashiki = g_pDataManager->GetKatashikiData(nKatashikiId);
		pKatashiki->bEditFlag = m_aCheckKatashiki[nKatashikiId];

		if (pKatashiki->bEditFlag)
			bEdit = TRUE;
	}

	if (!bEdit) {
		g_pSystem->DispMessage("E006103");
		return;
	}

	EndDialog(IDOK);
}
