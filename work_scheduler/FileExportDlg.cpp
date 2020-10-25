// FileExportDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "FileExportDlg.h"
#include "FileExportDlg001.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CFileExportDlg ダイアログ


CFileExportDlg::CFileExportDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CFileExportDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CFileExportDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CFileExportDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CFileExportDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
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
	DDX_Control(pDX, IDC_CHECK_KISHU_1,    m_aCheckKishu[0]);
	DDX_Control(pDX, IDC_CHECK_KISHU_2,    m_aCheckKishu[1]);
	DDX_Control(pDX, IDC_CHECK_KISHU_3,    m_aCheckKishu[2]);
	DDX_Control(pDX, IDC_CHECK_KISHU_4,    m_aCheckKishu[3]);
	DDX_Control(pDX, IDC_CHECK_KISHU_5,    m_aCheckKishu[4]);
	DDX_Control(pDX, IDC_CHECK_ZENKISHU,   m_bCheckZenkishu);
	DDX_Control(pDX, IDC_CHECK_KAJUU_HEIKIN,   m_bCheckKajuHeikin);
	DDX_Control(pDX, IDC_CHECK_WARITUKE,   m_bCheckWaritsuke);

}


BEGIN_MESSAGE_MAP(CFileExportDlg, CDialog)
	//{{AFX_MSG_MAP(CFileExportDlg)
	ON_WM_SHOWWINDOW()
	ON_BN_CLICKED(IDC_BUTTON_ZENKAIJO, OnButtonZenkaijo)
	ON_BN_CLICKED(IDC_BUTTON_ZENSENTAKU, OnButtonZensentaku)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFileExportDlg メッセージ ハンドラ

BOOL CFileExportDlg::OnInitDialog() 
{
	CDialogEx::OnInitDialog();

	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_FileExportDlg );

	return TRUE;

}

void CFileExportDlg::OnShowWindow(BOOL bShow, UINT nStatus) 
{
	if ( bShow ) {

		// 機種選択
		int nKishuId;
		int nKishuNum = g_pDataManager->GetKishuNum();
		for (nKishuId = 0; nKishuId < nKishuNum; nKishuId++ ) {
			SKishu *pKishu = g_pDataManager->GetKishuData( nKishuId );
			m_aCheckKishu[nKishuId] = 0;
			m_aCheckKishu[nKishuId].SetWindowText( pKishu->sKishuName );
			m_aCheckKishu[nKishuId].ShowWindow( SW_SHOW );
		}
		for ( ; nKishuId < MAX_KISHU; nKishuId++ )
			m_aCheckKishu[nKishuId].ShowWindow( SW_HIDE );
		// １機種の場合
		if ( nKishuNum == 1 ) {
			m_aCheckKishu[0] = 1;
			// 全機種、加重平均をグレーアウト
			m_bCheckZenkishu.EnableWindow( FALSE );
			m_bCheckKajuHeikin.EnableWindow( FALSE );
		}


		// サブライン選択
		int nSublineId;
		int nSublineNum = g_pDataManager->GetSublineNum();
		for ( nSublineId = 0; nSublineId < nSublineNum; nSublineId++ ) {
			SSubline *pSubline = g_pDataManager->GetSublineData( nSublineId );
			m_aCheckSubline[nSublineId].SetWindowText( pSubline->sSublineName );
			if ( pSubline->bSagyoshaWaritsuke ) {
				m_aCheckSubline[nSublineId] = pSubline->bManualEditFlag;
				m_aCheckSubline[nSublineId].EnableWindow( TRUE );
				m_aCheckSubline[nSublineId].ShowWindow( SW_SHOW );
			} else
				m_aCheckSubline[nSublineId].EnableWindow( FALSE );
		}
		for ( ; nSublineId < MAX_SUBLINE; nSublineId++ )
			m_aCheckSubline[nSublineId].ShowWindow( SW_HIDE );

	}

	CDialogEx::OnShowWindow(bShow, nStatus);
}

void CFileExportDlg::OnButtonZenkaijo() 
{
	SetAllSublixxxheck(FALSE);
}

void CFileExportDlg::OnButtonZensentaku() 
{
	SetAllSublixxxheck(TRUE);
}

void CFileExportDlg::SetAllSublixxxheck(BOOL bCheck)
{
	int nSublineNum = g_pDataManager->GetSublineNum();
	for ( int nSublineId = 0; nSublineId < nSublineNum; nSublineId++ ) {
		if ( m_aCheckSubline[nSublineId].IsWindowEnabled() )
			 m_aCheckSubline[nSublineId] = bCheck;
	}
}
#include <iostream.h>
#include <iomanip.h>

void CFileExportDlg::OnOK() 
{
	UpdateData( TRUE );

	// エクスポート処理開始
	int bExportSubline = 0;
	int bExportKishu = 0;
	for ( int iSubline=0; iSubline<MAX_SUBLINE; iSubline++){
		if ( m_aCheckSubline[iSubline].GetCheck() ){
			m_aCheckedSubline[iSubline] = true;
			bExportSubline = 1;
		}else
			m_aCheckedSubline[iSubline] = false;
	}
	if( bExportSubline == 0 ){
		g_pSystem->DispMessage( "E012004" );
		return;
	}

	for ( int iKishu=0; iKishu<MAX_KISHU; iKishu++){
		if ( m_aCheckKishu[iKishu].GetCheck() ){
			m_aCheckedKishu[iKishu] = true;
			bExportKishu = 1;
		}else
			m_aCheckedKishu[iKishu] = false;
	}

	m_bCheckedZenkishu = ( m_bCheckZenkishu.GetCheck() == TRUE ) ? true : false;
	m_bCheckedKajuHeikin = ( m_bCheckKajuHeikin.GetCheck() == TRUE ) ? true : false;
	m_bCheckedWaritsuke = ( m_bCheckWaritsuke.GetCheck() == TRUE ) ? true : false;

	if( bExportKishu == 0 && m_bCheckedZenkishu == 0 && m_bCheckedKajuHeikin == 0 ){
		g_pSystem->DispMessage( "E012003" );
		return;
	}

	// プロジェクトフォルダ取得
	CString sDefaultFolderName	= g_pSystem->m_cIniUser.m_sDataPath;

	// ファイル名取得
	CString sDefaultFileName	= g_pDataManager->GetNewExportGraphTemplateFileName();
	LPCTSTR lpszDefExt   = "xls";											// ﾃﾞﾌｫﾙﾄﾌｧｲﾙ拡張子
	LPCTSTR lpszFilter = "Microsoft Excel (*.xls)|*.xls||" ;						// ﾘｽﾄﾎﾞｯｸｽ用ﾌｧｲﾙ名

	// ファイルダイアログ表示
	CFileDialog dlg( FALSE, lpszDefExt, sDefaultFileName, NULL, lpszFilter, this );
	dlg.m_ofn.lpstrInitialDir = sDefaultFolderName;
// Modify ... ( CHANGE )
	dlg.m_ofn.Flags |= OFN_NOCHANGEDIR | OFN_OVERWRITEPROMPT;
//	dlg.m_ofn.Flags |= OFN_NOCHANGEDIR;
// By Y.Itabashi (xxxxx) 2007.02.20
	if( dlg.DoModal() == IDCANCEL )
		return;

	UpdateData( FALSE );
	CString sPathName = dlg.GetPathName();

	BeginWaitCursor();

	g_pDataManager->SetExportCondition( m_aCheckedSubline, m_aCheckedKishu, m_bCheckedWaritsuke, m_bCheckedZenkishu, m_bCheckedKajuHeikin );
	g_pDataManager->ExportGraphTemplate( sPathName );

	EndWaitCursor();

	CDialogEx::OnOK();
}
