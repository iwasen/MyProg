#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		AboutDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�o�E�g�_�C�A���O�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

class CAboutDlg : public CDialog
{
	DECLARE_DYNAMIC(CAboutDlg)

public:
	CAboutDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_ABOUTBOX };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	CString m_sStaticProgramName;

	DECLARE_MESSAGE_MAP()
};
