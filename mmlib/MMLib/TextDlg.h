#pragma once

// TextDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CTextDlg �_�C�A���O

class CTextDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CTextDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_TEXT };
	CEdit	m_cText;
	CString	m_sText;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnFont();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnCloseText();
	DECLARE_MESSAGE_MAP()

private:
	CFont m_Font;
	CString m_FontName;
	int m_nFontSize;
};
