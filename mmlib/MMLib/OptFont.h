#pragma once

// OptFont.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// COptFont �_�C�A���O

class COptFont : public CPropertyPage
{
	DECLARE_DYNCREATE(COptFont)

// �R���X�g���N�V����
public:
	COptFont();
	~COptFont();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_OPT_FONT };
	int		m_iFontSize;
	int		m_iViewFontSize;
	CString	m_sViewFontName;


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
	afx_msg void OnViewFontChange();
	afx_msg void OnDefault();
	DECLARE_MESSAGE_MAP()

public:
	BOOL m_bChangeViewFont;
};
