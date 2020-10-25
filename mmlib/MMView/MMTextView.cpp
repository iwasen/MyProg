// MMTextView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMView.h"
#include "MainFrm.h"
#include "MMViewDoc.h"
#include "MMTextView.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMTextView

IMPLEMENT_DYNCREATE(CMMTextView, CEditView)

CMMTextView::CMMTextView()
{
}

CMMTextView::~CMMTextView()
{
}


BEGIN_MESSAGE_MAP(CMMTextView, CEditView)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMTextView 描画

void CMMTextView::OnDraw(CDC* pDC)
{
	CDocument* pDoc = GetDocument();
	// TODO: この位置に描画用のコードを追加してください
}

/////////////////////////////////////////////////////////////////////////////
// CMMTextView 診断

#ifdef _DEBUG
void CMMTextView::AssertValid() const
{
	CEditView::AssertValid();
}

void CMMTextView::Dump(CDumpContext& dc) const
{
	CEditView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMTextView メッセージ ハンドラ

BOOL CMMTextView::PreCreateWindow(CREATESTRUCT& cs) 
{
	cs.style |= ES_AUTOHSCROLL | ES_AUTOVSCROLL | ES_MULTILINE | WS_VSCROLL | ES_READONLY;	
	return CEditView::PreCreateWindow(cs);
}

void CMMTextView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
{
	switch (lHint) {
	case VIEWHINT_SETFONT:
		SetFont((CFont *)pHint, TRUE);
		break;
	default:
		DrawText();
		break;
	}
}

void CMMTextView::DrawText()
{
	CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CString text;

	if (pMainWnd != NULL) {
		if (pDoc->m_nViewMode == VIEWMODE_PICT) {
			CItem *pItem = pDoc->GetCurrentItem();
			if (pItem != NULL)
				text = pItem->m_Text;
		} else
			text = pDoc->m_Text;

		if (text.GetLength() != 0) {
			SetWindowText(text);
			pMainWnd->ShowTextView(TRUE);
		} else {
			SetWindowText("");
			pMainWnd->ShowTextView(FALSE);
		}
	}
}

void CMMTextView::OnInitialUpdate() 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	SetFont(&pDoc->m_Font, FALSE);

	CEditView::OnInitialUpdate();
}
