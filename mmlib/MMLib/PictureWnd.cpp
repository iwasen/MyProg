// PictureWnd.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "MMLib.h"
#include "PictureWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CPictureWnd

CPictureWnd::CPictureWnd()
{
	m_hDIB = NULL;
	m_BrushBkgnd.CreateStockObject(GRAY_BRUSH);
}

CPictureWnd::~CPictureWnd()
{
	if (m_hDIB != NULL)
		MMPictureFree(m_hDIB);
}


BEGIN_MESSAGE_MAP(CPictureWnd, CWnd)
	ON_WM_PAINT()
	ON_WM_ERASEBKGND()
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CPictureWnd ���b�Z�[�W �n���h��

void CPictureWnd::OnPaint() 
{
	if (m_hDIB != NULL) {
		CPaintDC dc(this); // �`��p�̃f�o�C�X �R���e�L�X�g

		MMPictureDraw(m_hDIB, dc.m_hDC, m_nLeft, m_nTop, m_nWidth, m_nHeight, 0, 0, -1, -1);
	} else
		CWnd::OnPaint();
}

void CPictureWnd::DrawDIB(HDIB hDIB)
{
	CRect rect;
	GetClientRect(rect);

	if (m_hDIB != NULL) {
		MMPictureFree(m_hDIB);
		m_hDIB = NULL;
	}

	if (hDIB != NULL) {
		CRect rect;
		GetClientRect(rect);

		if ((m_hDIB = MakeSmallPicture(hDIB, rect.right, rect.bottom)) != NULL) {
			short color;
			MMPictureGetInfo(m_hDIB, &m_nWidth, &m_nHeight, &color);
			m_nLeft = (short)((rect.right - m_nWidth) / 2);
			m_nTop = (short)((rect.bottom - m_nHeight) / 2);
		}
	}

	Invalidate(TRUE);
}

BOOL CPictureWnd::OnEraseBkgnd(CDC* pDC) 
{
	CRect rect;
	GetClientRect(rect);
	pDC->FillRect(rect, &m_BrushBkgnd);
	return TRUE;
}
