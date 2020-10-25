#if !defined(AFX_SPLITTEREX_H__D5F33B9F_34F0_4274_987D_DD4BF655BA72__INCLUDED_)
#define AFX_SPLITTEREX_H__D5F33B9F_34F0_4274_987D_DD4BF655BA72__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SplitterEx.h : �w�b�_�[ �t�@�C��
//

#include "ActiveFrameWnd.h"

/////////////////////////////////////////////////////////////////////////////
// CSplitterEx �E�B���h�E

class CSplitterEx : public CSplitterWnd
{
// �R���X�g���N�V����
public:

// �A�g���r���[�g
public:

// �I�y���[�V����
public:
	void ChangeSplit(CPoint point);
	BOOL IsActivePane(CWnd *pView);

// �I�[�o�[���C�h
	virtual void RecalcLayout();
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	//{{AFX_VIRTUAL(CSplitterEx)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CSplitterEx)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void DisplayActiveFrame();

	CActiveFrameWnd m_wndLeft;
	CActiveFrameWnd m_wndTop;
	CActiveFrameWnd m_wndRight;
	CActiveFrameWnd m_wndBottom;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_SPLITTEREX_H__D5F33B9F_34F0_4274_987D_DD4BF655BA72__INCLUDED_)
