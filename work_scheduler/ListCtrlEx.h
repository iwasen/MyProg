#if !defined(AFX_LIxxxTRLEX_H__57A78A5C_6FAE_4035_9D8A_71E7C4D2A1F2__INCLUDED_)
#define AFX_LIxxxTRLEX_H__57A78A5C_6FAE_4035_9D8A_71E7C4D2A1F2__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LixxxtrlEx.h : �w�b�_�[ �t�@�C��
//
#include "afxtempl.h"

#define LVEXS_SELECTATCULOMN	0x0001	//�J�����I���X�^�C��
#define LVEXS_SELECTATROW		0x0002	//�s�I���X�^�C��
#define LVEXS_SELECTIONROW		0x0004	//�s�I��\��

#define START_INPLACE_IS_DBLCLICK	1	// �G�f�B�b�g���͂̃^�C�~���O

// ���[�U�[��`�C�x���g
#define WM_DRAG_DROP	(WM_USER + 200)
#define WM_BEGIN_DRAG	(WM_USER + 201)

#define WM_USEREVENT_LISTEX_CLICK			(WM_USER + 0x0500)
#define WM_USEREVENT_LISTEX_DBLCLICK		(WM_USER + 0x0501)
#define WM_USEREVENT_LISTEX_COMBOCREATE		(WM_USER + 0x0502)
#define WM_USEREVENT_LISTEX_COMBOCHANGE		(WM_USER + 0x0503)		// CInPlaceList.cpp�ɂ��L�q
#define WM_USEREVENT_LISTEX_ENDLABELEDIT	(WM_USER + 0x0504)
#define WM_USEREVENT_LISTEX_ERRLABELEDIT	(WM_USER + 0x0505)		// InPlaceEdit.cpp�ɂ��L�q

#include "LixxxtrlEx.h"
#include "HeaderCtrlEx.h"

/////////////////////////////////////////////////////////////////////////////
// CLixxxtrlEx �E�B���h�E

// �v�m�F��
#define		low_max		300		// �h���b�O���h���b�v�̑I���A�C�e��MAX��
//

//=======================================================
// �\���̒�`
//=======================================================
// ���X�g�����`
struct LIxxxTRLDATA {
	int		nCol;					// ��ԍ�
	int		nHeaderNo;				// �w�b�_�[�ɋL�q���镶���擾��MENUCTRLDATA��No
	int		nWidth;					// �\��������
	int		tFormat;				// �����\���t�H�[�}�b�g
	int		tSort;					// �\�[�g�^�C�v
	int		tSearch;				// �����^�C�v
	int		tDataType;				// �\�������^�C�v
	int		tEditType;				// ���̓��[�h
	char	sDispFormat[255];		// ����
	double	fMinValue;				// ���͍ŏ��l
	double	fMaxValue;				// ���͍ő�l�i�����̏ꍇ�͕������j
	int		nLimitEdit;				// �G�f�B�b�g�{�b�N�X�̃e�L�X�g�����l�i�o�C�g���j
};

//=======================================================
// �񋓌^��`
//=======================================================
// �f�[�^�^�C�v
enum EItemType {
	eITEMTYPE_STRING		= 0,	// ������
	eITEMTYPE_INUM			= 1,	// ����
	eITEMTYPE_RNUM			= 2,	// ����
	eITEMTYPE_ONOFFCHECK	= 3		// �n�m�^�n�e�e�`�F�b�N
};

// ���[�U�[���̓^�C�v
enum EInputType {
	eINPUTTYPE_NOTEDIT		= 0,	// ���[�U�[���͕s��
	eINPUTTYPE_EDITBOX		= 1,	// �G�f�B�b�g�{�b�N�X����
	eINPUTTYPE_ONOFFCHECK	= 2,	// �n�m�^�n�e�e�`�F�b�N
	eINPUTTYPE_COMBOBOX		= 3,	// �R���{�{�b�N�X�I��
};

// �J������������t���O�̃^�C�v
enum EColumnActionType {
	eCOLUMNACTTYPE_SORT		= 1,	// �\�[�g
	eCOLUMNACTTYPE_SEARCH	= 2		// ����
};

// �s�I���^�C�v
enum ESelectType {
	eSELECTTYPE_SINGLE		= 0,	// �P��s�I��
	eSELECTTYPE_MULTI		= 1		// �����s�I��
};
//=======================================================
// �N���X��`
//=======================================================
class CLixxxtrlEx : public CLixxxtrl
{
	//�J������������t���O
	typedef struct _tagCLMACTSTRUCT {

		int		nItemType;			// �f�[�^�^�C�v
		int		nInputType;			// ���[�U�[���̓^�C�v

		BOOL	bSort;				// �\�[�g�^�s�t���O
									//		TRUE	: �\�[�g��
									//		FALSE	: �\�[�g�s��

		BOOL	bSearch;			// �����^�s�t���O
									//		TRUE	: ������
									//		FALSE	: �����s��

		int		nSortDir;			// �\�[�g����
									//		0 : ����
									//		1 : �~��

		char	sDispFormat[255];	// ����

		double	fMinValue;			// ���͍ŏ��l
		double	fMaxValue;			// ���͍ő�l�i�����̏ꍇ�͕������j

		int		nLimitEdit;			// �G�f�B�b�g�{�b�N�X�̃e�L�X�g�����l�i�o�C�g���j


	} ST_CLMACT;

	typedef struct _tagITEMSTATE {

		COLORREF	textColor;		// �e�L�X�g�`��F
		COLORREF	bkgdColor;		// �w�i�F
		DWORD		dwData;			// ���̑��̏��

		_tagITEMSTATE() { textColor = RGB(0,0,0); bkgdColor = RGB(255,255,255); dwData = 0; };	// ������

	} ITEMSTATE;

	typedef ITEMSTATE* PITEMSTATE;	// ITEMSTATE�ւ̃|�C���^�^

// �R���X�g���N�V����
public:
	CLixxxtrlEx();

// �A�g���r���[�g
public:

protected:
	//-------------------------------------------------------
	// �h���b�O���h���b�v
	//--------------------------------------------------------
	BOOL			m_bPermissionDragDrop;	// Drag&Drop�^�s�t���O
	BOOL            m_bDragging;			// Drag���
	int             m_iItemDrag[low_max];	// Drag�A�C�e��
	int             m_iItemDrop[low_max];	// Drop�A�C�e��
	CPoint          m_ptHotSpot;
	CPoint          m_ptOrigin;
	CSize           m_sizeDelta[low_max];

	CImageList      m_pimageListDrag;
	CPoint			m_ptDropPoint;
	int				m_nDropIndex;
    CWnd*			m_pDropWnd;
	int				m_nMoveCount;

	int				m_nSelectType;			// �s�̒P��^�����s�I���^�C�v
											//	0 : �P��s�I��
											//	1 : �����s�I��

protected:

	//-------------------------------------------------------
	// �L�[����
	//--------------------------------------------------------
	int				m_nEditRow;
	int				m_nEditCol;
	double			m_EditDouble;
	int				m_EditInt;
	CString			m_EditText;
// Modify ... ( ADD )
	BOOL			m_bEditShow;
// By Y.Itabashi (xxxxx) 2007.03.05

	//-------------------------------------------------------
	// �\�[�g
	//--------------------------------------------------------
	CArray< ST_CLMACT, ST_CLMACT >		M_aColumnActFlag;		// �񖈂̏����t���O
	int									M_nColumnSort;			// �\�[�g����J�����ԍ�

	//--------------------------------------------------------
	// �����s�w�b�_
	//--------------------------------------------------------
	CFont			m_NewHeaderFont;
	CHeaderCtrlEx	m_HeaderCtrl;

// �I�y���[�V����
public:
	//--------------------------------------------------------
	// ����
	//--------------------------------------------------------
	int		InsertColumn( int nCol, const LV_COLUMN* pColumn );
	int		InsertColumn( int nCol, LPCTSTR lpszColumnHeading, int nFormat = LVCFMT_LEFT, int nWidth = -1, int nSubItem = -1 );

	//--------------------------------------------------------
	// ����
	//--------------------------------------------------------
	// ��{�I�Ɍ��J������̂͑����ݒ�̂݁i�擾�͓����Œ��ڑ���j
	void	SetPermissionDragDrop( BOOL bFlg ) { m_bPermissionDragDrop = bFlg; };	// �h���b�O���h���b�v�^�s��
	int		GetDraggingMode() { return m_bDragging; };								// �h���b�O��Ԃ��擾
	void	SetSelectType( int nSelectType );										// �P��^�����s�I���̐ݒ�
	void	SetColumnAction( int nCol, int nAct, BOOL bFlg );						// �񖈂̏����^�s�t���O�ݒ�
	void	SetColumnType( int nCol, int nDataType, int nInputType );				// �񖈂̃f�[�^�^�C�v�A���[�U�[���̓^�C�v�ݒ�
	void	SetColumnFormat( int nCol, LPCTSTR format);
	int		SetValue( int nRow, int nCol, LPCTSTR pValue );							// �l�Z�b�g�i������j
	int		SetValue( int nRow, int nCol, double fValue );							// �l�Z�b�g�i�����j
	int		SetValue( int nRow, int nCol, int nValue );								// �l�Z�b�g�i�����j
	int		SetValue( int nRow, int nCol, bool bValue );							// �l�Z�b�g�i�n�m�^�n�e�e�j
	int		SetComboList(int nRow, int nCol, CStringList &lstItems, int nIndex);		// �R���{�{�b�N�X���X�g�Z�b�g
	int		GetValue( int nRow, int nCol, CString &sValue );
	int		GetValue( int nRow, int nCol, double &fValue );
	int		GetValue( int nRow, int nCol, int &nValue );
	int		GetValue( int nRow, int nCol, bool &bValue );
	int		SetValue2( int nRow, int nCol, LPCTSTR pValue );						// �����Ȃ�
	int		ModifyValue( int nRow, int nCol, LPCTSTR pValue );
	void	GetItemColor( int nRow, int nCol, COLORREF &textColor, COLORREF &bkgdColor );
	void	SetItemColor( int nRow, int nCol, COLORREF textColor, COLORREF bkgdColor );
	void	GetItemColColor( int nCol, COLORREF &textColor, COLORREF &bkgdColor );
	void	SetItemColColor( int nCol, COLORREF textColor, COLORREF bkgdColor );
	void	GetItemRowColor( int nRow, COLORREF &textColor, COLORREF &bkgdColor );
	void	SetItemRowColor( int nRow, COLORREF textColor, COLORREF bkgdColor );
	DWORD	GetItemRowData( int nRow );
	void	SetItemRowData( int nRow, DWORD dwData );
	void	SetColumnMinMax( int nCol, double minvalue, double maxvalue );
	void	SetLimitEdit( int nCol, int nLimit );
	void	SetMultilineHeader( int nLine );
	BOOL	IsEmpty( int nRow, int nCol );
	void	SetColumnText(int nColumn, LPCTSTR pText);

protected:
	//--------------------------------------------------------
	// �\�[�g��r�֐�
	//--------------------------------------------------------
	static int CALLBACK CompareString(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	static int CALLBACK CompareNumeric(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);

	int		HitTestEx(CPoint &point, int *col) const;				// �J�[�\���ʒu�̗�ԍ��擾
	CEdit*	EditSubLabel( int nItem, int nCol, UINT nID ) ;			// ���x���ҏW����

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CLixxxtrlEx)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CLixxxtrlEx();
	void    OnButtonUp(CPoint point);
	void CLixxxtrlEx::DropItemOnList(CLixxxtrlEx* pDropList);

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CLixxxtrlEx)
	afx_msg void OnBeginDrag(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnEndlabeledit(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnBeginlabeledit(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnClick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDblclk(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnCustomDraw(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDeleteitem(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnSetFocus(CWnd* pNewWnd);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
public:
	CComboBox*	ShowInPlaceList( int nItem, int nCol, CStringList &lstItems, int nSel );
	void	SelectChange(int nRow) ;								// �I���s�̕ύX
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_LIxxxTRLEX_H__57A78A5C_6FAE_4035_9D8A_71E7C4D2A1F2__INCLUDED_)
