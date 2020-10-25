//*****************************************************************************************************
//  1. �t�@�C����
//		ChangeInfoMmeDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�f�[�^�̏��ύX�_�C�A���O�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2013.10.22 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "ChangeInfoMmeDlg.h"
#include "General.h"
#include <io.h>


// CChangeInfoMmeDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CChangeInfoMmeDlg, CDialog)

CChangeInfoMmeDlg::CChangeInfoMmeDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CChangeInfoMmeDlg::IDD, pParent)
{

}

CChangeInfoMmeDlg::~CChangeInfoMmeDlg()
{
}

void CChangeInfoMmeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DOWNLINK, m_cEditDownlink);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
}


BEGIN_MESSAGE_MAP(CChangeInfoMmeDlg, CDialog)
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDOK, "�ݒ肳�ꂽ���e�Ƀf�[�^�t�@�C�������������܂��B"},
	{IDCANCEL, "���̃_�C�A���O����܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoMmeDlg::ChangeInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ύX�_�C�A���O�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		&sIdbPathName			[I] Idb�t�@�C���p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		INT_PTR		�_�C�A���O�̕Ԓl
//*****************************************************************************************************
INT_PTR CChangeInfoMmeDlg::ChangeInfo(const CString &sIdbPathName)
{
	// Idb�t�@�C���p�X��ۑ�
	m_sIdbPathName = sIdbPathName;

	// �_�C�A���O���J��
	return DoModal();
}

// CChangeInfoMmeDlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoMmeDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CChangeInfoMmeDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// idb�t�@�C���ǂݍ���
	m_oIdbFile.Read(m_sIdbPathName);

	// �_�C�A���O�ɕ\��
	m_cEditDownlink = m_oIdbFile.m_downlink;
	m_cEditComment = m_oIdbFile.m_comment;

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoMmeDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		OK�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CChangeInfoMmeDlg::OnOK()
{
	// �_�C�A���O����idb�f�[�^�ɐݒ�
	m_oIdbFile.m_downlink = m_cEditDownlink;
	m_oIdbFile.m_comment = m_cEditComment;

	// idb�t�@�C���X�V
	CString sFileName;
	if (FindFileName("idb", sFileName))
		m_oIdbFile.Write(sFileName.Left(sFileName.GetLength() - 4));

	AfxMessageBox("����ύX���܂����B", MB_OK | MB_ICONINFORMATION);

	CDialog::OnOK();
}

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoMmeDlg::FindFileName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ�g���q�̃t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pExt			[I] �g���q
//		CString		&sFileName		[O] �������ꂽ�t�@�C����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CChangeInfoMmeDlg::FindFileName(LPCTSTR pExt, CString &sFileName)
{
	CString sPath;
	struct _finddata_t c_file;
    intptr_t hFile;

	// �g���q��".s"�̃t�@�C����T��
	sPath.Format("%s\\*.%s", m_sIdbPathName, pExt);
	if ((hFile = _findfirst(sPath, &c_file )) != -1L) {
		sFileName = c_file.name;
		_findclose(hFile);
		return TRUE;
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoMmeDlg::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E���b�Z�[�W����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		MSG*	pMsg			[I] MSG�\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	�e�N���X�̕Ԓl�����̂܂ܕԂ�
//*****************************************************************************************************
BOOL CChangeInfoMmeDlg::PreTranslateMessage(MSG* pMsg)
{
	// �c�[���`�b�v��\�����邽�߂̏���
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
