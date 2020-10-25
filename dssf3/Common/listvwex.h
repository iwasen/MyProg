// ListVwEx.h : CListViewEx �N���X�̃C���^�[�t�F�C�X
//
// ���̃N���X�̓��|�[�g ���[�h�̃��X�g �r���[ �R���g���[���ɑ΂���
// �s�S�̂̑I�����[�h��񋟂��܂��B
//
// This is a part of the Microsoft Foundation Classes C++ library.
// Copyright (C) 1992-1998 Microsoft Corporation
// All rights reserved.
//
// ���̃\�[�X �R�[�h�� Microsoft Foundation Class ���t�@�����X����сA
// �֘A����I�����C�� �h�L�������g�̕⑫�݂̂�ړI�Ƃ��Ă��܂��B
// Microsoft Foundation Class ���i�Ɋւ���ڂ������͂����̃h�L�������g
// ���Q�Ƃ��Ă��������B
//

#define ITEM_COLOR_BLACK	0
#define ITEM_COLOR_BLUE		1
#define ITEM_COLOR_GREEN	2
#define ITEM_COLOR_CYAN		3
#define ITEM_COLOR_RED		4
#define ITEM_COLOR_MAGENTA	5
#define ITEM_COLOR_YELLOW	6
#define ITEM_COLOR_WHITE	7

class CListViewEx : public CListView
{
	DECLARE_DYNCREATE(CListViewEx)

public:
	CListViewEx();

protected:
	BOOL m_bFullRowSel;

	BOOL SetFullRowSel(BOOL bFillRowSel);
	BOOL GetFullRowSel();

	BOOL m_bClientWidthSel;

	virtual void DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct);

	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

#ifdef _DEBUG
	virtual void Dump(CDumpContext& dc) const;
#endif

	void SetItemColor(int nItem, int nColor);
	static LPCTSTR MakeShortString(CDC* pDC, LPCTSTR lpszLong, int nColumnLen, int nOffset);
	void RepaintSelectedItems();

	int m_cxClient;
	int m_cxStateImageOffset;
	COLORREF m_clrText;
	COLORREF m_clrTextBk;
	COLORREF m_clrBkgnd;

	DECLARE_MESSAGE_MAP()
	afx_msg LRESULT OnSetImageList(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnSetTextColor(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnSetTextBkColor(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnSetBkColor(WPARAM wParam, LPARAM lParam);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnPaint();
	afx_msg void OnSetFocus(CWnd* pOldWnd);
	afx_msg void OnKillFocus(CWnd* pNewWnd);
};
