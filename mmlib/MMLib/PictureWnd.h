#pragma once

// PictureWnd.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CPictureWnd �E�B���h�E

class CPictureWnd : public CWnd
{
// �R���X�g���N�V����
public:
	CPictureWnd();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B


// �C���v�������e�[�V����
public:
	virtual ~CPictureWnd();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	afx_msg void OnPaint();
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	DECLARE_MESSAGE_MAP()

public:
	void DrawDIB(HDIB hDIB);

private:
	HDIB m_hDIB;
	short m_nWidth, m_nHeight;
	short m_nLeft, m_nTop;
	CBrush m_BrushBkgnd;
};
