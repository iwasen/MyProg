#pragma once

// TransRegDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CTransRegDlg �_�C�A���O

class CTransRegDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CTransRegDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_TRANS_REG };
	CLixxxtrl	m_cAppList;
	CString	m_sAppFile;
	CString	m_sAppName;
	int		m_iMenuIcon;
	BOOL	m_bPictBMP;
	BOOL	m_bPictGIF;
	BOOL	m_bPictJPG;
	BOOL	m_bPictPNG;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnDelete();
	afx_msg void OnClose();
	afx_msg void OnReference();
	afx_msg void OnItemchangedAppList(NMHDR* pNMHDR, LRESULT* pResult);
	DECLARE_MESSAGE_MAP()

private:
	void SetAppList();
};
