// ActiveFrameWnd.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "workscheduler.h"
#include "ActiveFrameWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CActiveFrameWnd

BEGIN_MESSAGE_MAP(CActiveFrameWnd, CWnd)
	//{{AFX_MSG_MAP(CActiveFrameWnd)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CActiveFrameWnd ���b�Z�[�W �n���h��

BOOL CActiveFrameWnd::OnEraseBkgnd(CDC* pDC)
{
	return TRUE;
}

void CActiveFrameWnd::OnPaint()
{
	CPaintDC dc(this); // �`��p�̃f�o�C�X �R���e�L�X�g

	CRect rect;
	GetClientRect(rect);
	dc.FillSolidRect(rect, RGB(255, 0, 0));
}
