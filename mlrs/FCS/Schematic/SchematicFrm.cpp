//*****************************************************************************************************
//  1. �t�@�C����
//		SchematicFrm.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�E�@�\�n���}�E�@�\�����}��Flash�\���p�t���[���E�B���h�E����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "SchematicFrm.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CSchematicFrame

IMPLEMENT_DYNAMIC(CSchematicFrame, CFrameWnd)

BEGIN_MESSAGE_MAP(CSchematicFrame, CFrameWnd)
	ON_WM_CREATE()
	ON_WM_DESTROY()
	ON_WM_CLOSE()
END_MESSAGE_MAP()

static UINT indicators[] =
{
	ID_SEPARATOR,           // �X�e�[�^�X ���C�� �C���W�P�[�^
	ID_INDICATOR_CAPS,
	ID_INDICATOR_NUM,
	ID_INDICATOR_SCRL,
};

//*****************************************************************************************************
//  1. �֐���
//		CSchematicFrame::CreateFrameWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���E�B���h�E���쐬
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		int		nActionCode			[I] �A�N�V�����R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicFrame::CreateFrameWindow(int nActionCode)
{
	GetSchematicInfo();

	// �f�X�N�g�b�v�̃T�C�Y���擾
	CRect rect;
	::SystemParametersInfo(SPI_GETWORKAREA, 0, rect, 0);

	// �f�X�N�g�b�v���10�s�N�Z���������\������
	rect.DeflateRect(10, 10);

	// �t���[���E�B���h�E���쐬����
	if (!Create(AfxRegisterWndClass(0, 0, 0, AfxGetApp()->LoadIcon(IDR_MAINFRAME)), m_sWindowTitle, WS_OVERLAPPEDWINDOW | WS_VISIBLE, rect, AfxGetMainWnd(), NULL, 0L))
		return;

	// ���C���E�B���h�E�̃|�C���^��ݒ�
	if (AfxGetApp()->m_pMainWnd == NULL)
		AfxGetApp()->m_pMainWnd = this;

	m_pWndView->ShowSchematic(m_pMenuClass, m_sFlashFileName, nActionCode);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicFrame::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���E�B���h�E�쐬�O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CREATESTRUCT&		cs			[I] �E�B���h�E�������p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CSchematicFrame::PreCreateWindow(CREATESTRUCT& cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	// WS_EX_CLIENTEDGE�X�^�C�����폜
	cs.dwExStyle &= ~WS_EX_CLIENTEDGE;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicFrame::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���E�B���h�E�쐬������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCREATESTRUCT		lpCreateStruct			[I] �E�B���h�E�������p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		0
//*****************************************************************************************************
int CSchematicFrame::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// �r���[�E�B���h�E���쐬
	m_pWndView = (CSchematicView *)m_pViewClass->CreateObject();
	if (!m_pWndView->Create(NULL, NULL, AFX_WS_DEFAULT_VIEW | WS_CLIPCHILDREN, CRect(0, 0, 0, 0), this, AFX_IDW_PANE_FIRST, NULL))
		return -1;

	// �X�e�[�^�X�o�[���쐬
	if (!m_wndStatusBar.Create(this) || !m_wndStatusBar.SetIndicators(indicators, sizeof(indicators)/sizeof(UINT)))
		return -1;      // �쐬�ł��܂���ł����B

	return 0;
}


// CSchematicFrame ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CSchematicFrame::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���E�B���h�E�j������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicFrame::OnDestroy()
{
	CFrameWnd::OnDestroy();

	// ���C���E�B���h�E��O�ʂɕ\��������
	AfxGetMainWnd()->SetForegroundWindow();
	AfxGetMainWnd()->UpdateWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicFrame::GetSchematicInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�\�E�B���h�E�f�[�^�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicFrame::GetSchematicInfo()
{
	SSchematicWindow *pSchematicWindow = m_lSchematicWindow.RemoveHead();

	// �}�\���X�g����f�[�^�������o�ϐ��ɐݒ肷��
	m_sWindowTitle = pSchematicWindow->sWindowTitle;
	m_sFlashFileName = pSchematicWindow->sFlashFileName;
	m_pMenuClass = pSchematicWindow->pMenuClass;
	m_pViewClass = pSchematicWindow->pViewClass;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicFrame::OnClose
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�N���[�Y����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicFrame::OnClose()
{
	if (m_lSchematicWindow.IsEmpty()) {
		// �}�\���X�g����Ȃ�E�B���h�E�����
		CFrameWnd::OnClose();
	} else {
		// �}�\���X�g����łȂ���Ύ��̐}�\��\������
		GetSchematicInfo();
		SetWindowText(m_sWindowTitle);
		m_pWndView->ShowSchematic(m_pMenuClass, m_sFlashFileName, 0);
	}
}
