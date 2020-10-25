#pragma once

// LibOpenDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CLibOpenDlg �_�C�A���O

class CLibOpenDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CLibOpenDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_LIBOPEN };
	CListBox	m_cLibraryList;
	CButton	m_cExternal;
	CStatic	m_cSeparator;
	CComboBox	m_cDirectory;
	CComboBox	m_cServerName;
	CButton	m_cCache;
	CString	m_sDirectory;
	int		m_iChType;
	CString	m_sServerName;
	BOOL	m_bCache;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnTcpip();
	afx_msg void OnNetbios();
	afx_msg void OnLocal();
	virtual void OnOK();
	afx_msg void OnReference();
	afx_msg void OnExternal();
	afx_msg void OnSelchangeLibraryList();
	afx_msg void OnDestroy();
	afx_msg void OnDblclkLibraryList();
	DECLARE_MESSAGE_MAP()

private:
	CRect m_rectDlg;

	void SetLibraryList();
	void ReferenceLocal();
	void ReferenceNetwork();
};
