#pragma once

// FolderDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CFolderDlg �_�C�A���O

class CFolderDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CFolderDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_FOLDER };
	CButton	m_cCopyData;
	CString	m_sCreator;
	CString	m_sFolderName;
	CString	m_sRemark;
	CString	m_sKeyword;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnTextData();
	afx_msg void OnCopyData();
	DECLARE_MESSAGE_MAP()

public:
	CString m_Caption;
	CString m_sTextData;
	FOLDERREC m_FolderRec;
};

/////////////////////////////////////////////////////////////////////////////
// CFolderNewDlg �_�C�A���O

class CFolderNewDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CFolderNewDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_FOLDERNEW };
	CButton	m_cCopyData;
	CString	m_sCreator;
	CString	m_sFolderName;
	int		m_iFolderType;
	CString	m_sRemark;
	CString	m_sKeyword;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual void OnOK();
	afx_msg void OnTextData();
	afx_msg void OnCopyData();
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()

public:
	CString m_sTextData;
	FOLDERREC m_FolderRec;
};
