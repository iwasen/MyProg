#pragma once

// PictFrame.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CPictFrame �t���[��

class CPictFrame : public CFrameWnd
{
	DECLARE_DYNCREATE(CPictFrame)
protected:
	CPictFrame();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B

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
	virtual ~CPictFrame();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	DECLARE_MESSAGE_MAP()

public:
	CComboBox &GetComboBox() {return m_wndComboBox;}

private:
	CMyToolBar m_wndPictToolBar;
	CComboBox m_wndComboBox;
};
