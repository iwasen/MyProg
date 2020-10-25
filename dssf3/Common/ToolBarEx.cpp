// ToolBarEx.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "ToolBarEx.h"


// CToolBarEx

CToolBarEx::CToolBarEx()
{
	m_bInEraseBG = FALSE;
}


BEGIN_MESSAGE_MAP(CToolBarEx, CToolBar)
	ON_WM_ERASEBKGND()
END_MESSAGE_MAP()



// CToolBarEx ���b�Z�[�W �n���h��

BOOL CToolBarEx::OnEraseBkgnd(CDC* pDC)
{
	if (!m_bInEraseBG) {
		m_bInEraseBG = TRUE;

		CWnd *pParent = GetParent();

		CRect rect;
		pDC->GetClipBox(rect);
		MapWindowPoints(pParent, rect);
		pParent->InvalidateRect(rect);
		pParent->UpdateWindow();

		m_bInEraseBG = FALSE;
	}

	return TRUE;
}
