#if !defined(AFX_CINPLACELIST_H__A125C131_9824_11D5_8E80_00E0292A193B__INCLUDED_)
#define AFX_CINPLACELIST_H__A125C131_9824_11D5_8E80_00E0292A193B__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// CInPlaceList.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CInPlaceList �E�B���h�E

class CInPlaceList : public CComboBox
{
// �R���X�g���N�V����
public:
//	CInPlaceList();
	CInPlaceList(int iItem, int iSubItem, CStringList *plstItems, int nSel);

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CInPlaceList)
	public:
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CInPlaceList();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CInPlaceList)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnChar(UINT nChar, UINT nRepCnt, UINT nFlags);
	afx_msg void OnCloseup();
	afx_msg void OnNcDestroy();
	afx_msg void OnKillFocus(CWnd* pNewWnd);
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()
private:
	int 	m_iItem;
	int 	m_iSubItem;
	CStringList m_lstItems;
	int 	m_nSel;
	BOOL	m_bESC;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_CINPLACELIST_H__A125C131_9824_11D5_8E80_00E0292A193B__INCLUDED_)
