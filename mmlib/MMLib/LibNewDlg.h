#pragma once

// LibNewDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CLibNewDlg �_�C�A���O

class CLibNewDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CLibNewDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_LIBNEW };
	CStatic	m_cSeparator;
	CButton	m_cExternal;
	CButton	m_cReference;
	CComboBox	m_cServerName;
	CComboBox	m_cDirectory;
	CButton	m_cCache;
	CString	m_sCreator;
	CString	m_sDirectory;
	CString	m_sLibName;
	int		m_iChType;
	CString	m_sServerName;
	CString	m_sRemark;
	CString	m_sKeyword;
	BOOL	m_bCache;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnLocal();
	afx_msg void OnTcpip();
	afx_msg void OnNetbios();
	virtual void OnOK();
	afx_msg void OnReference();
	afx_msg void OnExternal();
	DECLARE_MESSAGE_MAP()

private:
	CRect m_rectDlg;
};
