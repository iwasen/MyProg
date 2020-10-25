//*****************************************************************************************************
//  1. �t�@�C����
//		CGMenuDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@�\�}�b�f�\�����j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "CGMenuDlg.h"
#include "MyMessageBox.h"


// CCGMenuDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CCGMenuDlg, CDialogEx)

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::CCGMenuDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CWnd*	pParent		[I] �e�E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CCGMenuDlg::CCGMenuDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CCGMenuDlg::IDD, pParent)
{
}

void CCGMenuDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_KEITOZU, m_cListKeitozu);
	DDX_Control(pDX, IDC_LIST_SETSUMEIZU, m_cListSetsumeizu);
	DDX_Control(pDX, IDC_BUTTON_DISP_KEITOZU, m_cButtonDispKeitozu);
	DDX_Control(pDX, IDC_BUTTON_DISP_SETSUMEIZU, m_cButtonDispSetsumeizu);
}


BEGIN_MESSAGE_MAP(CCGMenuDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_EXIT, &CCGMenuDlg::OnBnClickedButtonExit)
	ON_BN_CLICKED(IDC_BUTTON_DISP_KEITOZU, &CCGMenuDlg::OnBnClickedButtonDispKeitozu)
	ON_BN_CLICKED(IDC_BUTTON_DISP_SETSUMEIZU, &CCGMenuDlg::OnBnClickedButtonDispSetsumeizu)
	ON_BN_CLICKED(IDC_BUTTON_RENZOKU, &CCGMenuDlg::OnBnClickedButtonRenzoku)
	ON_LBN_SELCHANGE(IDC_LIST_KEITOZU, &CCGMenuDlg::OnLbnSelchangeListKeitozu)
	ON_LBN_DBLCLK(IDC_LIST_KEITOZU, &CCGMenuDlg::OnLbnDblclkListKeitozu)
	ON_LBN_SELCHANGE(IDC_LIST_SETSUMEIZU, &CCGMenuDlg::OnLbnSelchangeListSetsumeizu)
	ON_LBN_DBLCLK(IDC_LIST_SETSUMEIZU, &CCGMenuDlg::OnLbnDblclkListSetsumeizu)
END_MESSAGE_MAP()


// CCGMenuDlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CCGMenuDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	AfxGetApp()->m_pMainWnd = this;

	// �@�\�n���}���j���[���ڐݒ�
	AddMenuItem(m_cListKeitozu, "FS01");
	AddMenuItem(m_cListKeitozu, "FS02");
	AddMenuItem(m_cListKeitozu, "FS03");
	AddMenuItem(m_cListKeitozu, "FS04");
	AddMenuItem(m_cListKeitozu, "FS05");
	AddMenuItem(m_cListKeitozu, "FS06");
	AddMenuItem(m_cListKeitozu, "FS07");
	AddMenuItem(m_cListKeitozu, "FS08");
	AddMenuItem(m_cListKeitozu, "FS09");
	AddMenuItem(m_cListKeitozu, "FS10");
	AddMenuItem(m_cListKeitozu, "FS11");
	AddMenuItem(m_cListKeitozu, "FS12");
	AddMenuItem(m_cListKeitozu, "FS13");
	AddMenuItem(m_cListKeitozu, "FS14");
	AddMenuItem(m_cListKeitozu, "FS15");
	AddMenuItem(m_cListKeitozu, "FS16");
	AddMenuItem(m_cListKeitozu, "FS17");
	AddMenuItem(m_cListKeitozu, "FS18");
	AddMenuItem(m_cListKeitozu, "FS19");
	AddMenuItem(m_cListKeitozu, "FS20");

	// �@�\�����}���j���[���ڐݒ�
	AddMenuItem(m_cListSetsumeizu, "FE07");
	AddMenuItem(m_cListSetsumeizu, "FE06");
	AddMenuItem(m_cListSetsumeizu, "FE05");
	AddMenuItem(m_cListSetsumeizu, "FE01");
	AddMenuItem(m_cListSetsumeizu, "FE02");
	AddMenuItem(m_cListSetsumeizu, "FE03");
	AddMenuItem(m_cListSetsumeizu, "FE04");

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::AddMenuItem
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���X�g�{�b�N�X�Ƀ��j���[���ڂ�ݒ肷��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CListBox	&cListBox			[I] ���X�g�{�b�N�X
//		LPCTSTR		pSchematicCode		[I] �}�\�R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::AddMenuItem(CListBox &cListBox, LPCTSTR pSchematicCode)
{
	SSchematicWindow *pSchematicWindow = CSchematic::GetSchematicWindowData(pSchematicCode);
	cListBox.SetItemDataPtr(cListBox.AddString(pSchematicWindow->sWindowTitle), (void *)pSchematicCode);
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnBnClickedButtonExit
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�I���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnBnClickedButtonExit()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnBnClickedButtonDispKeitozu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�n���}�́u�\���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnBnClickedButtonDispKeitozu()
{
	int nIndex = m_cListKeitozu.GetCurSel();
	if (nIndex >= 0)
		CSchematic::OpenSchematicWindow((LPCTSTR)m_cListKeitozu.GetItemDataPtr(nIndex));
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnBnClickedButtonDispSetsumeizu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����}�́u�\���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnBnClickedButtonDispSetsumeizu()
{
	int nIndex = m_cListSetsumeizu.GetCurSel();
	if (nIndex >= 0)
		CSchematic::OpenSchematicWindow((LPCTSTR)m_cListSetsumeizu.GetItemData(nIndex));
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnBnClickedButtonRenzoku
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����}�́u�A���\���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnBnClickedButtonRenzoku()
{
	CMyMessageBox cMyMessageBox;
	if (cMyMessageBox.MessageBox("�@�\�����}�̘A���\�����J�n���܂��B\n��낵���ł����H", "�A���\���m�F", MB_OKCANCEL | MB_ICONQUESTION) == IDOK) {
		CSchematic::RenzokuFE();
//		EnableWindow(FALSE);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnLbnSelchangeListKeitozu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�n���}�̍��ڂ̑I������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnLbnSelchangeListKeitozu()
{
	m_cButtonDispKeitozu.EnableWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnLbnDblclkListKeitozu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�n���}�̍��ڂ̃_�u���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnLbnDblclkListKeitozu()
{
	OnBnClickedButtonDispKeitozu();
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnLbnSelchangeListSetsumeizu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����}�̍��ڂ̑I������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnLbnSelchangeListSetsumeizu()
{
	m_cButtonDispSetsumeizu.EnableWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CCGMenuDlg::OnLbnDblclkListSetsumeizu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����}�̍��ڂ̃_�u���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CCGMenuDlg::OnLbnDblclkListSetsumeizu()
{
	OnBnClickedButtonDispSetsumeizu();
}
