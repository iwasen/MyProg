// PictFrame.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "MMLib.h"
#include "PictFrame.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CPictFrame

IMPLEMENT_DYNCREATE(CPictFrame, CFrameWnd)

CPictFrame::CPictFrame()
{
}

CPictFrame::~CPictFrame()
{
}


BEGIN_MESSAGE_MAP(CPictFrame, CFrameWnd)
	ON_WM_CREATE()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPictFrame ���b�Z�[�W �n���h��

int CPictFrame::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	if (!m_wndPictToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndPictToolBar.LoadToolBar(IDR_PICT))
	{
		TRACE0("Failed to create toolbar\n");
		return -1;      // �쐬�Ɏ��s
	}

	// create a combo box for the address bar
	if (!m_wndComboBox.Create(CBS_DROPDOWN | WS_CHILD | WS_VISIBLE, CRect(0, 1, 60, 200), &m_wndPictToolBar, IDW_COMBO))
	{
		TRACE0("Failed to create combobox\n");
		return -1;      // fail to create
	}

	m_wndComboBox.SetFont(&g_DefaultFont);

	m_wndComboBox.AddString("200%");
	m_wndComboBox.AddString("150%");
	m_wndComboBox.AddString("100%");
	m_wndComboBox.AddString("75%");
	m_wndComboBox.AddString("50%");
	m_wndComboBox.AddString("25%");
	m_wndComboBox.LimitText(4);

	// TODO: �c�[�� �o�[���h�b�L���O�\�ɂ��Ȃ��ꍇ�͈ȉ��̂R�s���폜
	//       ���Ă��������B
	m_wndPictToolBar.EnableDocking(CBRS_ALIGN_ANY);
	EnableDocking(CBRS_ALIGN_ANY);

	m_wndPictToolBar.SetButtonInfo(0, IDW_COMBO, TBBS_SEPARATOR, 62);

	return 0;
}

BOOL CPictFrame::PreCreateWindow(CREATESTRUCT& cs) 
{
	cs.style &= ~FWS_ADDTOTITLE;

	return CFrameWnd::PreCreateWindow(cs);
}
