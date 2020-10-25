// ManualEditSublineDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "ManualEditSublineDlg001.h"
#include "ManualEditSublineDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineDlg ダイアログ


CManualEditSublineDlg::CManualEditSublineDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CManualEditSublineDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualEditSublineDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CManualEditSublineDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualEditSublineDlg)
	DDX_Control(pDX, IDOK, m_cButtonOK);
	//}}AFX_DATA_MAP
	DDX_Control(pDX, IDC_CHECK_SUBLINE_1, m_aCheckSubline[0]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_2, m_aCheckSubline[1]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_3, m_aCheckSubline[2]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_4, m_aCheckSubline[3]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_5, m_aCheckSubline[4]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_6, m_aCheckSubline[5]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_7, m_aCheckSubline[6]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_8, m_aCheckSubline[7]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_9, m_aCheckSubline[8]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_10, m_aCheckSubline[9]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_11, m_aCheckSubline[10]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_12, m_aCheckSubline[11]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_13, m_aCheckSubline[12]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_14, m_aCheckSubline[13]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_15, m_aCheckSubline[14]);
}


BEGIN_MESSAGE_MAP(CManualEditSublineDlg, CDialogEx)
	//{{AFX_MSG_MAP(CManualEditSublineDlg)
	ON_BN_CLICKED(IDC_BUTTON_ZENSENTAKU, OnButtonZensentaku)
	ON_BN_CLICKED(IDC_BUTTON_ZENKAIJO, OnButtonZenkaijo)
	//}}AFX_MSG_MAP
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_1, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_2, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_3, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_4, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_5, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_6, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_7, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_8, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_9, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_10, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_11, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_12, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_13, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_14, OnCheckSubline)
	ON_BN_CLICKED(IDC_CHECK_SUBLINE_15, OnCheckSubline)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineDlg メッセージ ハンドラ

BOOL CManualEditSublineDlg::OnInitDialog() 
{
	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualEditSublineDlg );
// By Y.Itabashi (xxxxx) 2007.01.26

	int nSublineId;
	int nSublineNum = g_pDataManager->GetSublineNum();

	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		CMyButton &cCheckSubline = m_aCheckSubline[nSublineId];
		cCheckSubline.SetWindowText(pSubline->sSublineName);
		if (pSubline->bSagyoshaWaritsuke) {
			cCheckSubline = pSubline->bManualEditFlag;
			cCheckSubline.EnableWindow(TRUE);
			cCheckSubline.ShowWindow(SW_SHOW);
		} else
			cCheckSubline.EnableWindow(FALSE);
	}

	for (; nSublineId < MAX_SUBLINE; nSublineId++)
		m_aCheckSubline[nSublineId].ShowWindow(SW_HIDE);

	EnableExecButton();

	return TRUE;
}

void CManualEditSublineDlg::OnOK() 
{
	// チェックされているサブラインを取得
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		pSubline->bManualEditFlag = m_aCheckSubline[nSublineId];
	}

	EndDialog(IDOK);
}

void CManualEditSublineDlg::OnButtonZensentaku() 
{
	SetAllSublixxxheck(TRUE);
	EnableExecButton();
}

void CManualEditSublineDlg::OnButtonZenkaijo() 
{
	SetAllSublixxxheck(FALSE);
	EnableExecButton();
}

void CManualEditSublineDlg::OnCheckSubline() 
{
	EnableExecButton();
}

void CManualEditSublineDlg::EnableExecButton() 
{
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		if ((int)m_aCheckSubline[nSublineId] != 0) {
			m_cButtonOK.EnableWindow(TRUE);
			return;
		}
	}
	m_cButtonOK.EnableWindow(FALSE);
}

void CManualEditSublineDlg::SetAllSublixxxheck(BOOL bCheck)
{
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		if (m_aCheckSubline[nSublineId].IsWindowEnabled())
			m_aCheckSubline[nSublineId] = bCheck;
	}
}
