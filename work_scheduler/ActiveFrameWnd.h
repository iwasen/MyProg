#if !defined(AFX_ACTIVEFRAMEWND_H__68AEA6AC_893D_43B5_98F2_057EE52B6713__INCLUDED_)
#define AFX_ACTIVEFRAMEWND_H__68AEA6AC_893D_43B5_98F2_057EE52B6713__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ActiveFrameWnd.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CActiveFrameWnd �E�B���h�E

class CActiveFrameWnd : public CWnd
{
// �R���X�g���N�V����
public:

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	//{{AFX_VIRTUAL(CActiveFrameWnd)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CActiveFrameWnd)
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPaint();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CPen m_penAvtiveFrame;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_ACTIVEFRAMEWND_H__68AEA6AC_893D_43B5_98F2_057EE52B6713__INCLUDED_)
