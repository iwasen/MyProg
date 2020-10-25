#pragma once

// FindSimpleDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CFindSimpleDlg �_�C�A���O

class CFindSimpleDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CFindSimpleDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_FIND_SIMPLE };
	CButton	m_cFindExpert;
	CMyEdit	m_cFindNum;
	CEdit	m_cSearchText;
	CComboBox	m_cLibrary;
	CString	m_sFindFolder;
	BOOL	m_bSave;
	CString	m_sSearchText;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnFindExpert();
	afx_msg void OnGetfindnum();
	DECLARE_MESSAGE_MAP()

public:
	CItemFolder *m_pItemFolder;
	BOOL m_bUpdateMode;
};
