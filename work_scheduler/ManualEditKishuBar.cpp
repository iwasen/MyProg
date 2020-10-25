// ManualEditKishuBar.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "ManualEditKishuBar.h"
#include "ManualEditDocument.h"
#include "ManualEditFrameWnd.h"
#include "ManualEditChildWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CManualEditKishuBar ダイアログ


CManualEditKishuBar::CManualEditKishuBar()
{
	//{{AFX_DATA_INIT(CManualEditKishuBar)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CManualEditKishuBar::DoDataExchange(CDataExchange* pDX)
{
	CDialogBar::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualEditKishuBar)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CManualEditKishuBar, CDialogBar)
	//{{AFX_MSG_MAP(CManualEditKishuBar)
	ON_UPDATE_COMMAND_UI(IDC_BACK_REF_KOTEI, OnUpdateBack)
	ON_UPDATE_COMMAND_UI(IDC_NEXT_REF_KOTEI, OnUpdateNext)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualEditKishuBar メッセージ ハンドラ

void CManualEditKishuBar::OnUpdateBack(CCmdUI* pCmdUI)
{
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParent();
	CManualEditDocument *pDoc = (CManualEditDocument *)(pWnd->GetActiveDocument());

	if( pDoc->m_aRefYosoSagyogun.GetSize() == 0 )
		pCmdUI->Enable( FALSE );
	else{
		if( pWnd->m_nRefIndex == 0 )
			pCmdUI->Enable( FALSE );
		else
			pCmdUI->Enable( TRUE );
	}
}

void CManualEditKishuBar::OnUpdateNext(CCmdUI* pCmdUI)
{
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParent();
	CManualEditDocument *pDoc = (CManualEditDocument *)(pWnd->GetActiveDocument());

	if( pDoc->m_aRefYosoSagyogun.GetSize() == 0 )
		pCmdUI->Enable( FALSE );
	else{
		if( pWnd->m_nGraphKishu == -1 ){
			if( pWnd->m_nRefIndex == pDoc->m_aRefYosoSagyogun.GetSize()-1 )
				pCmdUI->Enable( FALSE );
		}else{
			for( int i = pDoc->m_aRefYosoSagyogun.GetSize()-1; i > pWnd->m_nRefIndex; i-- ){
				if( pDoc->m_aRefYosoSagyogun[i].nKishuId == pWnd->m_nGraphKishu ){
					pCmdUI->Enable( TRUE );
					return;
				}
			}

			pCmdUI->Enable( FALSE );
		}
	}
}
