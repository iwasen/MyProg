#if !defined(AFX_MANUALEDITSUBLINEINFO_H__9CBD6336_4083_48E2_8A75_FB3AB27C5D33__INCLUDED_)
#define AFX_MANUALEDITSUBLINEINFO_H__9CBD6336_4083_48E2_8A75_FB3AB27C5D33__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditSublineInfo.h : �w�b�_�[ �t�@�C��
//

#define MAX_LINES	3

/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineInfo �E�B���h�E

class CManualEditSublineInfo : public CWnd
{
// �R���X�g���N�V����
public:
	CManualEditSublineInfo();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:
	void Create(CWnd *pParentWnd);
	void SetText(LPCTSTR pText1, LPCTSTR pText2, LPCTSTR pText3);

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	//{{AFX_VIRTUAL(CManualEditSublineInfo)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CManualEditSublineInfo();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CManualEditSublineInfo)
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPaint();
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CString m_sText[MAX_LINES];
	CFont m_fontText;
	CRect m_rectMove;
	CPoint m_pointMove;
	BOOL m_bMoving;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALEDITSUBLINEINFO_H__9CBD6336_4083_48E2_8A75_FB3AB27C5D33__INCLUDED_)
