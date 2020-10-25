//*****************************************************************************************************
//  1. �t�@�C����
//		ColorButton.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CColorButton �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2004.12.16 S.Imura(xxxxx) �V�K�쐬
//*****************************************************************************************************
#if !defined(AFX_COLORBUTTON_H__31FBC88C_5C07_4796_9906_0E5A96EBF1C3__INCLUDED_)
#define AFX_COLORBUTTON_H__31FBC88C_5C07_4796_9906_0E5A96EBF1C3__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ColorButton.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CColorButton �E�B���h�E
//
// �J���[�{�^����`�t�@�C��

class CColorButton : public CButton
{
	DECLARE_DYNAMIC(CColorButton)

private:
	COLORREF	m_fg;		// �L���v�V�����J���[(�ʏ펞)
	COLORREF	m_bg;		// �w�i�F
	COLORREF	m_disabled;	// �{�^�����������̃L���v�V�����J���[
	int m_checkState;		// �`�F�b�N�{�^���� �`�F�b�NOn/Off

	CFont m_Font;
	float m_fSizeRatio;		// �t�H���g�T�C�Y�W��
	long  m_lfWeight;		// �t�H���g���� LOGFONT�\���̂��Q�Ƃ̎�

// �R���X�g���N�V����
public:
	CColorButton();

// �A�g���r���[�g
public:
	void SetTextColor(const COLORREF color){m_fg = color;}		// �L���v�V�����J���[�ݒ�
	void SetBackGroundColor(const COLORREF color){m_bg = color;}	// �w�i�F�ݒ�
	void SetDisabledColor(const COLORREF color){m_disabled = color;}	// �{�^���������̃L���v�V�����J���[�ݒ�
	COLORREF GetTextColor() { return m_fg; }				// �L���v�V�����J���[�擾
	COLORREF GetBackGroundColor() { return m_bg; }		// �w�i�F�擾
	COLORREF GetDisabledColor() { return m_disabled; }			// �{�^���������̃L���v�V�����J���[�擾

// �I�y���[�V����
public:
	virtual void SetCheck(int nCheck){m_checkState = nCheck;};
	virtual int GetCheck(){return m_checkState;}

protected:
	virtual void DrawItem(LPDRAWITEMSTRUCT lpDIS);			// �{�^���̃I�[�i�[�`��
	void DrawButtonText(CDC *DC, CRect R, const char *Buf, COLORREF TextColor);	// �e�L�X�g�`��

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CColorButton)
	protected:
	virtual void PreSubclassWindow();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CColorButton();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CColorButton)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	//}}AFX_MSG
	afx_msg LRESULT OnSetCheck(WPARAM wParam, LPARAM lParam);

	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_COLORBUTTON_H__31FBC88C_5C07_4796_9906_0E5A96EBF1C3__INCLUDED_)
