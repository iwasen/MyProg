#pragma once

// AutoNumDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CAutoNumDlg �_�C�A���O

class CAutoNumDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CAutoNumDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_AUTO_NUMBERING };
	CEdit	m_cStep;
	CEdit	m_cCode;
	CString	m_sCode;
	long	m_lStep;
	BOOL	m_bAutoNumbering;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	afx_msg void OnAutoNumbering();
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()

private:
	void EnableControl();
};
