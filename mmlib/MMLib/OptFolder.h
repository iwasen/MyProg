#pragma once

// OptFolder.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// COptFolder �_�C�A���O

class COptFolder : public CPropertyPage
{
	DECLARE_DYNCREATE(COptFolder)

// �R���X�g���N�V����
public:
	COptFolder();
	~COptFolder();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_OPT_FOLDER };
	CString	m_sBackupFolder;
	CString	m_sCacheFolder;
	CString	m_sLibraryFolder;
	CString	m_sInboxFolder;
	CString	m_sKeywordFolder;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	public:
	virtual void OnOK();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnLibraryReference();
	afx_msg void OnCacheReference();
	afx_msg void OnBackupReference();
	afx_msg void OnDefault();
	afx_msg void OnInboxReference();
	afx_msg void OnKeywordReference();
	DECLARE_MESSAGE_MAP()

};
