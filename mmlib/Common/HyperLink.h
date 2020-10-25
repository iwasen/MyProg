#pragma once

// HyperLink.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CHyperLink �E�B���h�E

class CHyperLink : public CStatic
{
// �R���X�g���N�V����
public:
	CHyperLink();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

// �C���v�������e�[�V����
public:
	virtual ~CHyperLink();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	afx_msg HBRUSH CtlColor(CDC* pDC, UINT nCtlColor);
	afx_msg void OnClicked();
	afx_msg BOOL OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message);
	DECLARE_MESSAGE_MAP()

public:
	void SetHyperLinkURL(LPCTSTR pLinkURL, LPCTSTR pFontName = NULL, int nFontSize = 0);

protected:
	CString m_sLinkURL;
	CFont m_Font;
	CBrush m_brushBG;
};
