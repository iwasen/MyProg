#pragma once

// FindFrame.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CFindFrame �t���[��

class CFindFrame : public CFrameWnd
{
	DECLARE_DYNCREATE(CFindFrame)
protected:
	CFindFrame();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// �C���v�������e�[�V����
protected:
	virtual ~CFindFrame();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	afx_msg void OnClose();
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	DECLARE_MESSAGE_MAP()

public:
	CReBar m_wndReBar;
	CEdit m_wndKeyword;
	CButton m_wndFind;
};
