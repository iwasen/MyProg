#if !defined(AFX_MANUALEDITCHILDWND1_H__850F24F1_6480_41CE_8BC8_DFDB0DBB3807__INCLUDED_)
#define AFX_MANUALEDITCHILDWND1_H__850F24F1_6480_41CE_8BC8_DFDB0DBB3807__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditChildWnd1.h : �w�b�_�[ �t�@�C��
//

#include "ManualEditChildWnd.h"
#include "SplitterEx.h"

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd1 �t���[��

class CManualEditChildWnd1 : public CManualEditChildWnd
{
	DECLARE_DYNCREATE(CManualEditChildWnd1)
protected:
	CManualEditChildWnd1();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂�

	//{{AFX_VIRTUAL(CManualEditChildWnd1)
	protected:
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	virtual ~CManualEditChildWnd1();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditChildWnd1)
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CSplitterEx m_wndSplitter;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALEDITCHILDWND1_H__850F24F1_6480_41CE_8BC8_DFDB0DBB3807__INCLUDED_)
