#pragma once

// OptTwain.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// COptTwain �_�C�A���O

class COptTwain : public CPropertyPage
{
	DECLARE_DYNCREATE(COptTwain)

// �R���X�g���N�V����
public:
	COptTwain();
	~COptTwain();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_OPT_TWAIN };
	CString	m_sTwainDriver;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	afx_msg void OnSelectDriver();
	DECLARE_MESSAGE_MAP()

};
