#pragma once

// CheckPwdDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CCheckPwdDlg �_�C�A���O

class CCheckPwdDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CCheckPwdDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_CHECK_PWD };
	CString	m_sLibrary;
	CString	m_sPassword;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	DECLARE_MESSAGE_MAP()
};
