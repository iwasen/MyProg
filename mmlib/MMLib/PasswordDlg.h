#pragma once

// PasswordDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CPasswordDlg �_�C�A���O

class CPasswordDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CPasswordDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_PASSWORD };
	CComboBox	m_cLibrary;
	CString	m_sPassword;
	CString	m_sPassword2;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSelchangeLibrary();
	DECLARE_MESSAGE_MAP()

public:
	CItemFolder *m_pItemFolder;

private:
	void SetPassword();
};
