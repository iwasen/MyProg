#pragma once

// MailAttDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CMailAttDlg �_�C�A���O

class CMailAttDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CMailAttDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MAIL_ATT };
	CMyEdit	m_cAttVPixel;
	CMyButton	m_cAttVLimit;
	CMyEdit	m_cAttScale;
	CMyEdit	m_cAttHPixel;
	CMyButton	m_cAttHLimit;
	int		m_iAttActual;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnAttActual();
	afx_msg void OnAttReduction();
	afx_msg void OnAttHlimit();
	afx_msg void OnAttVlimit();
	DECLARE_MESSAGE_MAP()

private:
	void EnableControls();
};
