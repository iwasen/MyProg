#if !defined(AFX_LBVIEW_H__4E0CC7F3_BB11_11D2_8BC7_00104B939DF5__INCLUDED_)
#define AFX_LBVIEW_H__4E0CC7F3_BB11_11D2_8BC7_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LBView.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CLBView �E�B���h�E

class CLBView : public CListBox
{
// �R���X�g���N�V����
public:
	CLBView();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CLBView)
	public:
	virtual void DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct);
	virtual void MeasureItem(LPMEASUREITEMSTRUCT lpMeasureItemStruct);
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CLBView();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CLBView)
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()

private:
	CBrush m_brushWindow;
	CBrush m_brushHighLight;
	CPen m_penWindowText;
	CPen m_penHighLightText;
	CBitmap m_bitmapVideo;
	CBitmap m_bitmapVideoMask;
	CBitmap m_bitmapChat;
	CBitmap m_bitmapChatMask;
	CBitmap m_bitmapVoice;
	CBitmap m_bitmapVoiceMask;
	CBitmap m_bitmapReflect;
	CBitmap m_bitmapReflectMask;
	CBitmap m_bitmapDll;
	CBitmap m_bitmapDllMask;
	CBitmap m_bitmapNone;
	CBitmap m_bitmapNoneMask;
	CDC m_dc;
	int	m_itemHeight;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_LBVIEW_H__4E0CC7F3_BB11_11D2_8BC7_00104B939DF5__INCLUDED_)
