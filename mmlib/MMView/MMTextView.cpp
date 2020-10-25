// MMTextView.cpp : �C���v�������e�[�V���� �t�@�C��
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
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMTextView �`��

void CMMTextView::OnDraw(CDC* pDC)
{
	CDocument* pDoc = GetDocument();
	// TODO: ���̈ʒu�ɕ`��p�̃R�[�h��ǉ����Ă�������
}

/////////////////////////////////////////////////////////////////////////////
// CMMTextView �f�f

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
// CMMTextView ���b�Z�[�W �n���h��

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
