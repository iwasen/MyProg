// LixxxtrlEx.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "resource.h"
#include "LixxxtrlEx.h"
#include "CInPlaceList.h"
#include "InPlaceEdit.h"

//#include <comdef.h>
#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CLixxxtrlEx

CLixxxtrlEx::CLixxxtrlEx()
{
	// �I���^�C�v
	m_nSelectType			= 1;	// �����s�I��

	// �h���b�O���h���b�v
	m_bPermissionDragDrop	= FALSE;
	m_bDragging				= FALSE;
	m_nMoveCount			= 0;

	//�h���b�O���̃C���[�W���X�g���쐬����
	m_pimageListDrag.Create(IDB_BITMAP_POST_DRAG, 40, 1, RGB(100,100,100));

	// �L�[����
	m_nEditRow	= -99;
	m_nEditCol	= -99;
// Modify ... ( ADD )
	m_bEditShow	 = 0;
// By Y.Itabashi (xxxxx) 2007.03.05

}

CLixxxtrlEx::~CLixxxtrlEx()
{
}


BEGIN_MESSAGE_MAP(CLixxxtrlEx, CLixxxtrl)
	//{{AFX_MSG_MAP(CLixxxtrlEx)
	ON_NOTIFY_REFLECT(LVN_BEGINDRAG, OnBeginDrag)
	ON_WM_MOUSEMOVE()
	ON_WM_LBUTTONUP()
	ON_WM_VSCROLL()
	ON_NOTIFY_REFLECT(LVN_ENDLABELEDIT, OnEndlabeledit)
	ON_WM_LBUTTONDOWN()
	ON_WM_HSCROLL()
	ON_NOTIFY_REFLECT(LVN_BEGINLABELEDIT, OnBeginlabeledit)
	ON_NOTIFY_REFLECT(NM_CLICK, OnClick)
	ON_NOTIFY_REFLECT(LVN_COLUMNCLICK, OnColumnclick)
	ON_NOTIFY_REFLECT(NM_DBLCLK, OnDblclk)
	ON_NOTIFY_REFLECT(NM_CUSTOMDRAW, OnCustomDraw)
	ON_NOTIFY_REFLECT(LVN_DELETEITEM, OnDeleteitem)
	ON_WM_CAPTURECHANGED()
	ON_WM_SETFOCUS()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetColumnAction
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CLixxxtrlEx�̗񖈂̏����^�s�t���O��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nCol		[I] ��ԍ�
//		int			nAct		[I] �ݒ肷��t���O�^�C�v
//										1 : �\�[�g�^�s��
//										2 : �����^�s��
//		BOOL		bFlg		[I] �ݒ�t���O
//										TRUE : ��
//										FALSE : �s��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) �V�K�쐬
//*****************************************************************************************************
void CLixxxtrlEx::SetColumnAction(int nCol, int nAct, BOOL bFlg)
{
	//-------------------------------------------------------
	// ���͈����`�F�b�N
	//-------------------------------------------------------
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if ( nCol < 0 || nCol > nColumnCount) return;

	//-------------------------------------------------------
	// �l�ݒ�
	//-------------------------------------------------------
	switch (nAct) {

	case eCOLUMNACTTYPE_SORT:
		// �\�[�g�^�s�ݒ�
		M_aColumnActFlag[nCol].bSort = bFlg;
		break;

	case eCOLUMNACTTYPE_SEARCH:
		// �����^�s�ݒ�
		M_aColumnActFlag[nCol].bSearch = bFlg;
		break;
	}
}


//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetColumnType
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CLixxxtrlEx�̗񖈂̃f�[�^�^�C�v�^���[�U�[���̓^�C�v��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nCol		[I] ��ԍ�
//		int			nDataType	[I] ��̃f�[�^�^�C�v
//										0 : ������
//										1 : ����
//										2 : ����
//										3 : �n�m�^�n�e�e�`�F�b�N
//		int			nInputType	[I] ���[�U�[���̓^�C�v
//										0 : ���[�U�[���͕s��
//										1 : �G�f�B�b�g�{�b�N�X����
//										2 : �n�m�^�n�e�e�`�F�b�N
//										3 : �R���{�{�b�N�X�I��
//									
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		NG : ( �f�[�^�^�C�v��������������͐��l ) ���A���̓^�C�v���n�m�^�n�e�e�`�F�b�N
//		NG : �f�[�^�^�C�v���n�m�^�n�e�e�ɂ�������炸���̓^�C�v���n�m�^�n�e�e�`�F�b�N�ȊO
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) �V�K�쐬
//*****************************************************************************************************
void CLixxxtrlEx::SetColumnType(int nCol, int nDataType, int nInputType)
{
	//-------------------------------------------------------
	// ���͈����`�F�b�N
	//-------------------------------------------------------
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if ( nCol < 0 || nCol > nColumnCount) return;

	if ( ( nDataType == eITEMTYPE_STRING
		|| nDataType == eITEMTYPE_INUM
		|| nDataType == eITEMTYPE_RNUM )
		&& nInputType == eINPUTTYPE_ONOFFCHECK ) return;

	if ( nDataType == eITEMTYPE_ONOFFCHECK
		&& nInputType != eINPUTTYPE_ONOFFCHECK ) return;

	//-------------------------------------------------------
	// �l�ݒ�
	//-------------------------------------------------------
	M_aColumnActFlag[nCol].nItemType = nDataType;
	M_aColumnActFlag[nCol].nInputType = nInputType;

	//-------------------------------------------------------
	// ����������
	//-------------------------------------------------------
	switch ( nDataType ) {
	case eITEMTYPE_STRING:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%s" );
		break;
	case eITEMTYPE_INUM:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%d" );
		break;
	case eITEMTYPE_RNUM:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%lf" );
		break;
	case eITEMTYPE_ONOFFCHECK:
		strcpy(M_aColumnActFlag[nCol].sDispFormat, "%c" );
		break;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetSelectType
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CLixxxtrlEx�̒P��^�����s�I��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nSelectType	[I] �I���^�C�v
//										0 : �P��s�I��
//										1 : �����s�I��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		�����l�����łȂ�CLixxxtrl::ModifyStyle�ɂĐU�镑�����ύX
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) �V�K�쐬
//*****************************************************************************************************
void CLixxxtrlEx::SetSelectType(int nSelectType)
{
	if (nSelectType != eSELECTTYPE_SINGLE && nSelectType != eSELECTTYPE_MULTI) return;

	m_nSelectType = nSelectType;
	switch (nSelectType) {

	case eSELECTTYPE_SINGLE:
		ModifyStyle( NULL, LVS_SINGLESEL );
		break;
	case eSELECTTYPE_MULTI:
		ModifyStyle( LVS_SINGLESEL, NULL );
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetValue
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�C�e���Z�b�g
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		int			nCol			[I] ��
//		LPCTSTR		pValue			[I]	������
//	{	int			nValue			[I] ����	}
//	{	double		fValue			[I] ����	}
//  {   char		cValue			[I] �n�m�^�n�e�e }
//										"1" : �n�m
//										""  : �n�e�e
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int							[O] �X�e�[�^�X
//										> 0 : ����I���A�}�������s�ԍ�
//										 -1 : �Z�b�g�Ɏ��s
//										 -2 : �Z�b�g����ӏ��̃f�[�^�^�C�v�̌^���قȂ�
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		�T�u�A�C�e���̃Z�b�g(nCol>0)�̏ꍇ�AInsertItem�ł͂Ȃ�SetItem���s��
//		���쌋�ʂ�InsertItem�ASetItem�ɏ���
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) �V�K�쐬
//*****************************************************************************************************
// ������Z�b�g
int CLixxxtrlEx::SetValue(int nRow, int nCol, LPCTSTR pValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

//	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_STRING ) return -2;
	CString sValue;
	sValue.Format( M_aColumnActFlag[nCol].sDispFormat, pValue );

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= (LPTSTR)(LPCTSTR)sValue;
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= (LPTSTR)(LPCTSTR)sValue;
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// �����Z�b�g
int CLixxxtrlEx::SetValue(int nRow, int nCol, int nValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_INUM ) return -2;

	CString	sValue;
	sValue.Format( M_aColumnActFlag[nCol].sDispFormat, nValue );

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// �����Z�b�g
int CLixxxtrlEx::SetValue(int nRow, int nCol, double fValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_RNUM ) return -2;

	CString	sValue;
	sValue.Format( M_aColumnActFlag[nCol].sDispFormat, fValue );

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// �n�m�^�n�e�e�Z�b�g
int CLixxxtrlEx::SetValue(int nRow, int nCol, bool bValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_ONOFFCHECK ) return -2;

	CString	sValue;
	if ( bValue )
		sValue = "*";
	else
		sValue = "";

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= sValue.GetBuffer(strlen(sValue));
		item.cchTextMax	= strlen(sValue);

		return SetItem(&item);
	}
	return -1;
}

// ������Z�b�g
int CLixxxtrlEx::SetValue2(int nRow, int nCol, LPCTSTR pValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	LVITEM	item;
	if ( nCol == 0 ) {

		PITEMSTATE pItemState = new ITEMSTATE[nColumnCount];

		item.mask		= LVIF_TEXT | LVIF_PARAM | LVIF_STATE;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.state		= 0;
		item.stateMask	= LVIS_SELECTED | LVIS_FOCUSED;
		item.pszText	= (LPTSTR)pValue;
		item.cchTextMax	= strlen(pValue);
		item.iImage		= NULL;
//		item.lParam		= nRow;
		item.lParam		= (LPARAM)pItemState;

		return InsertItem(&item);

	} else {

		item.mask		= LVIF_TEXT;
		item.iItem		= nRow;
		item.iSubItem	= nCol;
		item.pszText	= (LPTSTR)pValue;
		item.cchTextMax	= strlen(pValue);

		return SetItem(&item);
	}
	return -1;
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::ModifyValue
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�C�e���ύX
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		int			nCol			[I] ��
//		LPCTSTR		pValue			[I]	������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int							[O] �X�e�[�^�X
//										> 0 : ����I���A�}�������s�ԍ�
//										 -1 : �Z�b�g�Ɏ��s
//										 -2 : �Z�b�g����ӏ��̃f�[�^�^�C�v�̌^���قȂ�
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		��������J�����̃f�[�^�^�C�v�ɍ��킹�ĕϊ����ăZ�b�g
//		�����ύX�̕K�v�ȏꍇ�g�p
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) �V�K�쐬
//*****************************************************************************************************
// ������Z�b�g
int CLixxxtrlEx::ModifyValue(int nRow, int nCol, LPCTSTR pValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	CString sValue;
	switch ( M_aColumnActFlag[nCol].nItemType ) {
	case eITEMTYPE_STRING:
		sValue.Format( M_aColumnActFlag[nCol].sDispFormat, pValue );
		break;

	case eITEMTYPE_INUM:
// Modify ... ( CHANGE )
		if( strlen( pValue ) == 0 ){
			sValue = pValue;
		}else{
			int nValue;
			sscanf( pValue, "%d", &nValue );
			sValue.Format( M_aColumnActFlag[nCol].sDispFormat, nValue );
		}
// By Y.Itabashi (xxxxx) 2007.02.12
		break;

	case eITEMTYPE_RNUM:
// Modify ... ( CHANGE )
		if( strlen( pValue ) == 0 ){
			sValue = pValue;
		}else{
			double fValue;
			sscanf( pValue, "%lf", &fValue );
			sValue.Format( M_aColumnActFlag[nCol].sDispFormat, fValue );
		}
// By Y.Itabashi (xxxxx) 2007.02.12
		break;
	}
	LVITEM	item;
	item.mask		= LVIF_TEXT;
	item.iItem		= nRow;
	item.iSubItem	= nCol;
	item.pszText	= sValue.GetBuffer(strlen(sValue));
	item.cchTextMax	= strlen(sValue);

	return SetItem(&item);
}

// �R���{�{�b�N�X�f�[�^�Z�b�g
int CLixxxtrlEx::SetComboList(int nRow, int nCol, CStringList &lstItems, int nIndex)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nInputType != eINPUTTYPE_COMBOBOX ) return -2;

	// �R���{�{�b�N�X�̍쐬
	(CComboBox*)this->ShowInPlaceList( nRow, nCol, lstItems, nIndex );

	return -1;
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::GetValue
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�C�e���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		int			nCol			[I] ��
//		CSting&		sValue			[O]	������
//	{	int&		nValue			[O] ����	}
//	{	double&		fValue			[O] ����	}
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int							[O] �X�e�[�^�X
//										> 0 : ����I���A�}�������s�ԍ�
//										 -1 : �擾�Ɏ��s
//										 -2 : �擾����ӏ��̃f�[�^�^�C�v�̌^���قȂ�
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) �V�K�쐬
//*****************************************************************************************************
// ������擾
int CLixxxtrlEx::GetValue(int nRow, int nCol, CString &sValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_STRING ) return -2;

	sValue = GetItemText(nRow, nCol);

	return 0;
}

// �����擾
int CLixxxtrlEx::GetValue(int nRow, int nCol, int &nValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_INUM ) return -2;

	CString sValue = GetItemText(nRow, nCol);
// Modify ... ( CHANGE )
	if( sValue.GetLength() > 0 )
		sscanf(sValue, "%d", &nValue);
	else
		nValue = 0;
//	sscanf(sValue, "%d", &nValue);
// By Y.Itabashi (xxxxx) 2007.02.01

	return 0;
}

int CLixxxtrlEx::GetValue(int nRow, int nCol, double &fValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_RNUM ) return -2;


	CString sValue = GetItemText(nRow, nCol);
// Modify ... ( CHANGE )
	if( sValue.GetLength() > 0 )
		sscanf(sValue, "%lf", &fValue);
	else
		fValue = 0;
//	sscanf(sValue, "%lf", &fValue);
// By Y.Itabashi (xxxxx) 2007.02.01

	return 0;
}

// �n�m�^�n�e�e�擾
int CLixxxtrlEx::GetValue(int nRow, int nCol, bool &bValue)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if (nCol < 0 || nCol >= nColumnCount ) return -1;

	if ( M_aColumnActFlag[nCol].nItemType != eITEMTYPE_ONOFFCHECK ) return -2;

	CString	sValue = GetItemText(nRow, nCol);
	if ( sValue == "") 
		bValue = false;
	else if ( sValue == "*" )
		bValue = true;
	else
		return -1;

	return 0;
}

/////////////////////////////////////////////////////////////////////////////
//
// CLixxxtrlEx ���b�Z�[�W �n���h��
//
/////////////////////////////////////////////////////////////////////////////


//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnBeginDrag
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�C�e���̃h���b�O�J�n������
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�v�C���F��A���ȕ����I���A�C�e���Ή�
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnBeginDrag(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if ( pNMListView->iItem < 0 || pNMListView->iSubItem < 0 ) return;

	GetParent()->SendMessage(WM_BEGIN_DRAG, (WPARAM)m_hWnd, (LPARAM)0);

	if ( m_bPermissionDragDrop ) {		// �h���b�O���h���b�v���H

		CPoint          ptItem, ptAction, ptImage;

		int count = GetSelectedCount();
		POSITION pos = GetFirstSelectedItemPosition();

		m_bDragging = TRUE;
		int  i_index;
// Modify ... ( CHANGE )
		for( int i = 0; pos; i++ ){
//		for( int i = 0; i < count; i++ ){
// By Y.Itabshi (xxxxx) 2007.02.07

			// �v�C���F�A�����Ă��Ȃ��Ƃ��I���@���@�h���b�v���ɘA�����đ}��

			i_index = GetNextSelectedItem( pos );
			/*
			if( i > 0 ) {	//�h���b�O�ł���̂͘A�����Ă���I�𕔕������Ƃ���
				if( (i_index-1) != m_iItemDrag[i-1] ) {	//�A�����Ă��Ȃ������͈ړ��ΏۂƂ��Ȃ�
					break;
				}
			}
			*/
			m_iItemDrag[i] = i_index;	//�I������Ă���C���f�b�N�X���擾
			m_nMoveCount++;

			if( i <= 0 ) {
			ptAction = pNMListView->ptAction;
			GetItemPosition(m_iItemDrag[i], &ptItem);  // ptItem is relative to (0,0) and not the view origin
			GetOrigin(&m_ptOrigin);

			//�h���b�O���̃C���[�W���X�g���쐬����
			m_sizeDelta[i] = ptAction - ptImage;   // difference between cursor pos and image pos
			m_ptHotSpot = ptAction - ptItem + m_ptOrigin;  // calculate hotspot for the cursor
			m_pimageListDrag.BeginDrag(i, CPoint(8, 8));
			ptAction -= m_sizeDelta[i];

// Modify ... ( CHANGE )
			m_pDropWnd = this;

			m_pimageListDrag.DragEnter(GetParent(), ptAction);
//			m_pimageListDrag.DragEnter(this, ptAction);
// By Y.Itabashi (xxxxx) 2007.02.08
			m_pimageListDrag.DragShowNolock(TRUE);  // lock updates and show drag image
			m_pimageListDrag.SetDragCursorImage(i, m_ptHotSpot);  // define the hot spot for the new cursor image
			m_pimageListDrag.DragMove(ptAction);  // move image to overlap original icon

			SetCapture();
			}
		}
	}
	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�ړ�������
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�h���b�O���h���b�v����
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnMouseMove(UINT nFlags, CPoint point) 
{
    if( m_bDragging )
    {
        m_ptDropPoint = point;
        ClientToScreen(&m_ptDropPoint);
    
        //MOVE THE DRAG IMAGE
// Modify ... ( CHANGE )
		CPoint pointParent(m_ptDropPoint);
		CRect rect;
		GetParent()->GetWindowRect(rect);
		pointParent -= rect.TopLeft();
        m_pimageListDrag.DragMove(pointParent);
//        m_pimageListDrag.DragMove(point);
// By Y.Itabashi (xxxxx) 2007.02.07
    
        //TEMPORARILY UNLOCK WINDOW UPDATES
        m_pimageListDrag.DragShowNolock(FALSE);                
    
        //CONVERT THE DROP POINT TO CLIENT CO-ORDIANTES
        m_pDropWnd = WindowFromPoint(m_ptDropPoint);
        m_pDropWnd->ScreenToClient(&m_ptDropPoint);

        //SCROLL VIEW IF xxxESSARY
		if (m_pDropWnd->IsKindOf(RUNTIME_CLASS(CLixxxtrl))) {
			int iOverItem = HitTest(point);
			int iTopItem = GetTopIndex();
			int iBottomItem = iTopItem + GetCountPerPage() - 1;
			if (iOverItem == iTopItem && iTopItem != 0)
			{
				EnsureVisible(iOverItem - 1, false);
				::UpdateWindow(this->m_hWnd);
			}
			else if (iOverItem == iBottomItem && iBottomItem != (GetItemCount() - 1))
			{
				EnsureVisible(iOverItem + 1, false);
			   ::UpdateWindow(this->m_hWnd);
			}

// Modify ... ( ADD )
			UINT uFlags;
			CLixxxtrl* pList = (CLixxxtrl*)m_pDropWnd;

			if (m_nDropIndex != -1) {
				pList->SetItemState(m_nDropIndex, 0, LVIS_DROPHILITED);
				pList->RedrawItems(m_nDropIndex, m_nDropIndex);
				pList->UpdateWindow();
			}

			m_nDropIndex = ((CLixxxtrl*)m_pDropWnd)->HitTest(m_ptDropPoint, &uFlags);
			if( ((CLixxxtrl*)m_pDropWnd)->GetItemCount() == 0 )
				m_nDropIndex = -1;

			if (m_nDropIndex != -1) {
				pList->SetItemState(m_nDropIndex, LVIS_DROPHILITED, LVIS_DROPHILITED);
				pList->RedrawItems(m_nDropIndex, m_nDropIndex);
				pList->UpdateWindow();
			}
// By Y.Itabashi (xxxxx) 2007.02.07
		}

        //LOCK WINDOW UPDATES
        m_pimageListDrag.DragShowNolock(TRUE);
    }	
//	CLixxxtrl::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnButtonUp
//		CLixxxtrlEx::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X���{�^���𗣂������̏���
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�h���b�O���h���b�v����
//		�v�C���F�}���ʒu�̏�ɑ}�����ׂ�
//		�v�C���F��A���ȕ����I���A�C�e���̃h���b�v�������@���@�A�����đ}��
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnLButtonUp(UINT nFlags, CPoint point) 
{
	OnButtonUp(point);
	CLixxxtrl::OnLButtonUp(nFlags, point);
}

void CLixxxtrlEx::OnButtonUp(CPoint point) 
{
	if (m_bDragging)  // end of the drag operation
	{
        //RELEASE THE MOUSE CAPTURE AND END THE DRAGGING
        ::ReleaseCapture();
// Modify ... ( CHANGE )
        m_pimageListDrag.DragLeave(GetParent());
//        m_pimageListDrag.DragLeave(GetDesktopWindow());
// By Y.Itabashi (xxxxx) 2007.02.07
        m_pimageListDrag.EndDrag();

		// ���X�g�O�Ńh���b�v���ꂽ�ꍇ�͏���������
// Modify ... ( DELETE )
/*		CRect	listRect;
		GetClientRect(&listRect);
		if ( !(listRect.PtInRect( point )) ) {
			m_nMoveCount = 0;
			return;
		}*/
// By Y.Itabashi (xxxxx) 2007.02.07

        //GET THE WINDOW UNDER THE DROP POINT
        CPoint pt(point);
        ClientToScreen(&pt);
        m_pDropWnd = WindowFromPoint(pt);

// Modify ... ( ADD )
		CWnd *pWnd = m_pDropWnd->GetParent();
		if ( m_pDropWnd->IsKindOf(RUNTIME_CLASS(CLixxxtrl)) )
			DropItemOnList((CLixxxtrlEx*)m_pDropWnd);
		else if ( m_pDropWnd->IsKindOf(RUNTIME_CLASS(CHeaderCtrl)) ){
			m_pDropWnd = pWnd;
			m_nDropIndex = -2;		// HEADER�p�R�[�h
			DropItemOnList((CLixxxtrlEx*)m_pDropWnd);
		}
// By Y.Itabashi (xxxxx) 2007.02.07

		m_nMoveCount = 0;

		GetParent()->SendMessage(WM_DRAG_DROP, (WPARAM)m_hWnd, (LPARAM)m_pDropWnd->m_hWnd);

        m_bDragging = FALSE;
	}	
//	CLixxxtrl::OnLButtonUp(nFlags, point);
}

void CLixxxtrlEx::DropItemOnList(CLixxxtrlEx* pDropList)
{
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();

	CString     cstr;
	int			j;
	int i = 0;
	// The drop target's sub-item text is replaced by the dragged item's
	// main text

// Modify ... ( CHANGE )
//	if( pDropList->GetItemCount() == 0 || m_nDropIndex == -1 )
//		return;

	pDropList->SetItemState(m_nDropIndex, 0, LVIS_DROPHILITED);
/*	m_ptDropPoint.y += 10;
	m_ptDropPoint.x = 0;//allows dropping to right of last column
	m_nDropIndex = HitTest(m_ptDropPoint);*/
// By Y.Itabashi (xxxxx) 2007.02.07

// Modify ... ( CHANGE )
	if( m_nDropIndex != m_iItemDrag[i] || pDropList != this ) {
//	if( m_nDropIndex != m_iItemDrag[i] ) {

		// �I���ʒu�̌��ɓ����
		if( m_nDropIndex != -2 && m_nDropIndex < 0 )		//�ړ��悪�ŏI�s�̏ꍇ�A�ŏI�s�ԍ����Z�b�g���Ȃ���
			m_nDropIndex = pDropList->GetItemCount();
		else if( pDropList->GetItemCount() < m_nDropIndex )	//�ړ��悪�ŏI�s�𒴂���̏ꍇ�A�ŏI�s�ԍ����Z�b�g���Ȃ���
			m_nDropIndex = pDropList->GetItemCount();
		else if( m_nDropIndex < 0 )							//�ړ��悪�擪�̏ꍇ�A�擪�s�ԍ����Z�b�g���Ȃ���
			m_nDropIndex = 0;
		else
			m_nDropIndex = m_nDropIndex + 1;

		for( i = 0; i < m_nMoveCount; i++ ){
			cstr = GetItemText(m_iItemDrag[i], 0);
			PITEMSTATE pItemState = (PITEMSTATE)GetItemData(m_iItemDrag[i]);
			COLORREF textcolor = pItemState[0].textColor;
			COLORREF bkgdcolor = pItemState[0].bkgdColor;
			DWORD dwData = pItemState->dwData;
			pDropList->SetValue( m_nDropIndex, 0, cstr );
			pDropList->SetItemColor( m_nDropIndex, 0, textcolor, bkgdcolor );
			pDropList->SetItemRowData( m_nDropIndex, dwData );
			pDropList->SetItemState( m_nDropIndex, LVIS_SELECTED, LVIS_SELECTED );
			for( j = 1; j < nColumnCount; j++ ){
				cstr = GetItemText(m_iItemDrag[i], j);
				textcolor = pItemState[j].textColor;
				bkgdcolor = pItemState[j].bkgdColor;
				pDropList->SetValue( m_nDropIndex, j, cstr );
				pDropList->SetItemColor( m_nDropIndex, j, textcolor, bkgdcolor );
			}
			m_nDropIndex++;
		}
		if( pDropList != this || ( (m_nDropIndex-1) > m_iItemDrag[i] && (m_nDropIndex-1) > m_iItemDrag[m_nMoveCount-1]  ) )
		{	//�ړ��悪�A�I������Ă���C���f�b�N�X�����̏ꍇ

			for( i = m_nMoveCount-1; i >= 0; i-- ){
				DeleteItem( m_iItemDrag[i] );
			}
		}
		else if( pDropList == this && (m_nDropIndex-1) < m_iItemDrag[i] && (m_nDropIndex-1) < m_iItemDrag[m_nMoveCount-1] )
		{	//�ړ��悪�A�I������Ă���C���f�b�N�X���O�̏ꍇ

			for( i = m_nMoveCount-1; i >= 0; i-- ){
				DeleteItem( (m_iItemDrag[i]+m_nMoveCount) );
			}
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.07
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnVScroll
//		CLixxxtrlEx::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�N���[��������
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�h���b�O���h���b�v����
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	if( GetFocus() != this ) SetFocus();

	CLixxxtrl::OnVScroll(nSBCode, nPos, pScrollBar);
}

void CLixxxtrlEx::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	if( GetFocus() != this ) SetFocus();
	
	CLixxxtrl::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnBeginlabeledit
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���͏����J�n������
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�A�C�e�����͏���
//		�R���{�{�b�N�X�I������
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnBeginlabeledit(NMHDR* pNMHDR, LRESULT* pResult) 
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)pNMHDR;
	// TODO: ���̈ʒu�ɃR���g���[���ʒm�n���h���p�̃R�[�h��ǉ����Ă�������
	
//	*pResult = 0;
	*pResult = -1;
}


//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnEndlabeledit
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���͏����I��������
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�A�C�e�����͏���
//		�R���{�{�b�N�X�I������
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnEndlabeledit(NMHDR* pNMHDR, LRESULT* pResult) 
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)pNMHDR;

	LV_ITEM		 *plvItem = &pDispInfo->item;

	if (plvItem->pszText != NULL)
	{
//		SetItemText(plvItem->iItem, plvItem->iSubItem, plvItem->pszText);
		ModifyValue( plvItem->iItem, plvItem->iSubItem, plvItem->pszText );
	}

	m_nEditRow = -99;
	m_nEditCol = -99;
	*pResult = 0;

	GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_ENDLABELEDIT, reinterpret_cast<WPARAM> (pNMHDR));
// Modify ... ( ADD )
	m_bEditShow	 = 0;
// By Y.Itabashi (xxxxx) 2007.03.05
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnClick
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���{�^���N���b�N������
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�A�C�e�����͏���
//		�n�m�^�n�e�e����
//		�R���{�{�b�N�X�I������
//		�v�C���F�J������������t���O���珈���̉^�s���擾
//		�v�C���F�R���{�{�b�N�X�̏����������i�I���A�C�e���̐ݒ�j
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnClick(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

//	char b_ln[3];
	int index = pNMListView->iItem;
	int colnum = pNMListView->iSubItem;
	UINT flag = LVIS_FOCUSED;
	CString	sItem;
	PITEMSTATE	pItemState = NULL;

	if (index < 0 || colnum < 0) return;

	switch (M_aColumnActFlag[colnum].nInputType) {

	case eINPUTTYPE_ONOFFCHECK:

		// �n�m�^�n�e�e�؂�ւ�
		sItem = GetItemText(index, colnum);
		if (sItem == "*") {
//			ModifyValue( index, colnum, "" );
			SetItemText(index, colnum, "");
		} else {
//			ModifyValue( index, colnum, "*" );
			SetItemText(index, colnum, "*");
		}
		break;

	case eINPUTTYPE_COMBOBOX:

		// �R���{�{�b�N�X�I��
		if( (this->GetItemState( index, flag ) & flag) == flag )
		{
			// �ҏW�\�ȃ��X�g�ł��邩�`�F�b�N����i LVS_EDITLABELS �j
			if( GetWindowLong(m_hWnd, GWL_STYLE) & LVS_EDITLABELS )
			{

				// �R���{�{�b�N�X�쐬�C�x���g����
				GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_COMBOCREATE, reinterpret_cast<WPARAM> (pNMHDR));
			}
		}
		break;

#ifndef START_INPLACE_IS_DBLCLICK
	case eINPUTTYPE_EDITBOX:
		// �G�f�B�b�g�{�b�N�X�ɂ��L�[�C������
		if( GetWindowLong(m_hWnd, GWL_STYLE) & LVS_EDITLABELS )
			EditSubLabel( index, colnum, GetDlgCtrlID() );
		break;
#endif

	}

	// �e�X�g�p�ɃC�x���g����
	GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_CLICK, reinterpret_cast<WPARAM> (pNMHDR));

	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::ShowInPlaceList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���{�{�b�N�X�I���A�C�e���̍��{�^���N���b�N������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nItem		[I] �s�ԍ�
//		int			nCol		[I] ��ԍ�
//		CStringList	&IstItems	[I]	���X�g������
//		int			nSel		[I] �R���{�{�b�N�X�����C���f�b�N�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CComboBox*				[O] �R���{�{�b�N�X�̃|�C���^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		�R���{�{�b�N�X�I������
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
CComboBox* CLixxxtrlEx::ShowInPlaceList(int nItem,
										int nCol,
										CStringList &lstItems,
										int nSel)
{

//::AfxSetResourceHandle(hInst);
	// The returned pointer should not be saved

	// Make sure that the item is visible
	if( !EnsureVisible( nItem, TRUE ) ) return NULL;

	// Make sure that nCol is valid 
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if( nCol >= nColumnCount || GetColumnWidth(nCol) < 10 ) {
//::AfxSetResourceHandle(hInstResourceClient);
		return NULL;
	}

	// Get the column offset
	int offset = 0;
	for( int i = 0; i < nCol; i++ )
		offset += GetColumnWidth( i );

	CRect rect;
	GetItemRect( nItem, &rect, LVIR_BOUNDS );

	// Now scroll if we need to expose the column
	CRect rcClient;
	GetClientRect( &rcClient );
	if( offset + rect.left < 0 || offset + rect.left > rcClient.right )
	{
		CSize size;
		size.cx = offset + rect.left;
		size.cy = 0;
		Scroll( size );
		rect.left -= size.cx;
	}

	rect.left += offset+4;
	rect.right = rect.left + GetColumnWidth( nCol ) - 3 ;
	int height = rect.bottom-rect.top;
	rect.bottom += 5*height;
	if( rect.right > rcClient.right) rect.right = rcClient.right;

	DWORD dwStyle = WS_BORDER|WS_CHILD|WS_VISIBLE|WS_VSCROLL
					|CBS_DROPDOWNLIST|CBS_DISABLENOSCROLL;
	CComboBox *pList = new CInPlaceList(nItem, nCol, &lstItems, nSel);
	pList->Create( dwStyle, rect, this, IDC_COMBOEX );
	pList->SetItemHeight( -1, height);
	pList->SetHorizontalExtent( GetColumnWidth( nCol ));
//::AfxSetResourceHandle(hInstResourceClient);

	return pList;
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnColumnclick
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�b�_�[���{�^���N���b�N������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nItem		[I] �s�ԍ�
//		int			nCol		[I] ��ԍ�
//		CStringList	&IstItems	[I]	���X�g������
//		int			nSel		[I] �R���{�{�b�N�X�����C���f�b�N�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CComboBox*				[O] �R���{�{�b�N�X�̃|�C���^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		�\�[�g����
//		�v�C���F�\�[�g�^�s�t���O�ɂď�������
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
void CLixxxtrlEx::OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult) 
{

	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if ( pNMListView->iSubItem < 0 ) return;

	int	iSubItem = pNMListView->iSubItem;

	if ( M_aColumnActFlag[iSubItem].bSort ) {	// �\�[�g���H

		M_nColumnSort = pNMListView->iSubItem;

		if ( M_aColumnActFlag[M_nColumnSort].nSortDir == 1 ) {
			M_aColumnActFlag[M_nColumnSort].nSortDir = 0;
		}
		else {
			M_aColumnActFlag[M_nColumnSort].nSortDir =1;
		}
		switch ( M_aColumnActFlag[M_nColumnSort].nItemType ) {
		case eITEMTYPE_STRING:		// ������\�[�g
			SortItems( CompareString, reinterpret_cast <DWORD> (this) );
			break;
		case eITEMTYPE_INUM:
		case eITEMTYPE_RNUM:// ���l�\�[�g
			SortItems( CompareNumeric, reinterpret_cast <DWORD> (this) );
			break;
		case eITEMTYPE_ONOFFCHECK:
			break;
		}
	}
	*pResult = 0;
}
//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::CompareString
//		CLixxxtrlEx::CompareNumeric
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\�[�g��r�֐�
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		CLixxxtrl::SortItems�ɂĎg�p
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
int CALLBACK CLixxxtrlEx::CompareString(
			LPARAM lParam1,
			LPARAM lParam2,
			LPARAM lParamSort)
{
	CLixxxtrlEx* pLixxxtrl = reinterpret_cast <CLixxxtrlEx*> (lParamSort);

	int nColumn = pLixxxtrl->M_nColumnSort;

	//	��r���ڂ̃A�C�e���C���f�b�N�X���擾����
	LV_FINDINFO	sFindInfo;
	sFindInfo.flags  = LVFI_PARAM;
	sFindInfo.lParam = lParam1;
	int nItem1 = pLixxxtrl->FindItem( &sFindInfo );
	sFindInfo.lParam = lParam2;
	int nItem2 = pLixxxtrl->FindItem( &sFindInfo );

	//	��r�Ώە�������擾����
	CString cStr1 = pLixxxtrl->GetItemText( nItem1, nColumn );
	CString cStr2 = pLixxxtrl->GetItemText( nItem2, nColumn );

	//	��������r����
	switch ( pLixxxtrl->M_aColumnActFlag[nColumn].nSortDir ) {
	case 0:		//����
		if ( cStr1 < cStr2 ) {
			return( -1 );
		} else if ( cStr1 > cStr2 ) {
			return( 1 );
		}
		break;
	case 1:		//�~��
		if ( cStr1 < cStr2 ) {
			return( 1 );
		} else if ( cStr1 > cStr2 ) {
			return( -1 );
		}
		break;
	}

	return( 0 );

}

int CALLBACK CLixxxtrlEx::CompareNumeric(
			LPARAM lParam1,
			LPARAM lParam2,
			LPARAM lParamSort)
{

	CLixxxtrlEx* pLixxxtrl = reinterpret_cast <CLixxxtrlEx*> (lParamSort);

	int nColumn = pLixxxtrl->M_nColumnSort;

	//	��r���ڂ̃A�C�e���C���f�b�N�X���擾����
	LV_FINDINFO	sFindInfo;
	sFindInfo.flags  = LVFI_PARAM;
	sFindInfo.lParam = lParam1;
	int nItem1 = pLixxxtrl->FindItem( &sFindInfo );
	sFindInfo.lParam = lParam2;
	int nItem2 = pLixxxtrl->FindItem( &sFindInfo );

	//	��r�Ώە�������擾����
	CString cStr1 = pLixxxtrl->GetItemText( nItem1, nColumn );
	CString cStr2 = pLixxxtrl->GetItemText( nItem2, nColumn );

	BOOL nullcheck1 = cStr1.IsEmpty();
	BOOL nullcheck2 = cStr2.IsEmpty();
	if ( nullcheck1 || nullcheck2 ) {	// �ǂ��炩���l�����͂̏ꍇ

		// �\�[�g�����֌W�Ȃ�

		if ( nullcheck1 ) {
			if ( !nullcheck2 )
				return( 1 );
			else
				return( 0 );
		} else {
			if ( nullcheck2 )
				return( -1 );
		}
	}

	double rVal1 = atof( (LPCTSTR) cStr1 );
	double rVal2 = atof( (LPCTSTR) cStr2 );

	//	���l���r����
	switch ( pLixxxtrl->M_aColumnActFlag[nColumn].nSortDir ) {
	case 0:		//����
		if ( rVal1 < rVal2 ) {
			return( -1 );
		} else if ( rVal1 > rVal2 ) {
			return( 1 );
		}
		break;
	case 1:		//�~��
		if ( rVal1 < rVal2 ) {
			return( 1 );
		} else if ( rVal1 > rVal2 ) {
			return( -1 );
		}
		break;
	}

	return( 0 );

}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::InsertColumn
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��̑}��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nItem		[I] �s�ԍ�
//		int			nCol		[I] ��ԍ�
//		CStringList	&IstItems	[I]	���X�g������
//		int			nSel		[I] �R���{�{�b�N�X�����C���f�b�N�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CComboBox*				[O] �R���{�{�b�N�X�̃|�C���^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		��̏�������t���O�������̂��߂̃I�[�o�[���C�h
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx) ALPHA�̃\�[�X���p
//*****************************************************************************************************
int CLixxxtrlEx::InsertColumn(
			int nCol,
			const LV_COLUMN* pColumn)
{
	ST_CLMACT sAct = {	eITEMTYPE_STRING,	// ������
						eINPUTTYPE_NOTEDIT,	// ���[�U�[���͕s��
						FALSE,				// �\�[�g�s��
						FALSE,				// �����s��
						1,					// �\�[�g�����~��
						"%s",				// ����
						0,					// �ŏ��l
						0,					// �ő�l
						-1};				// ���̓e�L�X�g�����l�i�V�X�e���f�t�H���g�j
	M_aColumnActFlag.InsertAt( nCol, sAct );

	return CLixxxtrl::InsertColumn( nCol, pColumn);
}

int CLixxxtrlEx::InsertColumn(
			int nCol,
			LPCTSTR lpszColumnHeading,
			int nFormat,
			int nWidth,
			int nSubItem)
{
	ST_CLMACT sAct = {	eITEMTYPE_STRING,	// ������
						eINPUTTYPE_NOTEDIT,	// ���[�U�[���͕s��
						FALSE,				// �\�[�g�s��
						FALSE,				// �����s��
						1,					// �\�[�g�����~��
						"%s",				// ����
						0,
						0,
						-1};				// ���̓e�L�X�g�����l�i�V�X�e���f�t�H���g�j
	M_aColumnActFlag.InsertAt( nCol, sAct );

	return CLixxxtrl::InsertColumn( nCol, lpszColumnHeading, nFormat, nWidth, nSubItem);
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::HitTestEx
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���W�l�̈ʒu����J�����ԍ��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CPoint		&point		[I] ���W�l
//		int			*col		[O] ��ԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int						[O] �s�ԍ�
//									< 0 : ���X�g��ɂȂ�
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx)
//*****************************************************************************************************
int CLixxxtrlEx::HitTestEx(CPoint &point, int *col) const
{
	int colnum = 0;
	int row = HitTest( point, NULL );

	if( col ) *col = 0;

	// Make sure that the ListView is in LVS_REPORT
	if( (GetWindowLong(m_hWnd, GWL_STYLE) & LVS_TYPEMASK) != LVS_REPORT )
		return row;

	// Get the top and bottom row visible
	row = GetTopIndex();
	int bottom = row + GetCountPerPage();
	if( bottom > GetItemCount() )
		bottom = GetItemCount();

	// Get the number of columns
	CHeaderCtrl* pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();

	// Loop through the visible rows
	for( ;row <=bottom;row++)
	{
		// Get bounding rect of item and check whether point falls in it.
		CRect rect;
		GetItemRect( row, &rect, LVIR_BOUNDS );
		if( rect.PtInRect(point) )
		{
			// Now find the column
			for( colnum = 0; colnum < nColumnCount; colnum++ )
			{
				int colwidth = GetColumnWidth(colnum);
				if( point.x >= rect.left
					&& point.x <= (rect.left + colwidth ) )
				{
					if( col ) *col = colnum;
					return row;
				}
				rect.left += colwidth;
			}
		}
	}
	return -1;
}


//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::EditSubLabel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�G�f�B�b�g�{�b�N�X�ɂ��L�[�C������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nItem		[I] �s�ԍ�
//		int			nCol		[I] ��ԍ�
//		UINT		nID			[I] �G�f�B�b�g�R���g���[��ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CEdit*					[O] �G�f�B�b�g�R���g���[���ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.24 K.Kimura (xxxxx)
//*****************************************************************************************************
CEdit* CLixxxtrlEx::EditSubLabel( int nItem, int nCol, UINT nID )
{
	// The returned pointer should not be saved

	// Make sure that the item is visible
	if( !EnsureVisible( nItem, TRUE ) ) return NULL;

	// Make sure that nCol is valid
	CHeaderCtrl *pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	if( nCol >= nColumnCount || GetColumnWidth(nCol) < 5 )
		return NULL;

	// Get the column offset
	int offset = 0;
	for( int i = 0; i < nCol; i++ )
		offset += GetColumnWidth( i );

	CRect rect;
	GetItemRect( nItem, &rect, LVIR_BOUNDS );

	// Now scroll if we need to expose the column
	CRect rcClient;
	GetClientRect( &rcClient );
	if( offset + rect.left < 0 || offset + rect.left > rcClient.right )
	{
		CSize size;
		size.cx = offset + rect.left;
		size.cy = 0;
		Scroll( size );
		rect.left -= size.cx;
	}

	// Get Column alignment
	LV_COLUMN lvcol;
	lvcol.mask = LVCF_FMT;
	GetColumn( nCol, &lvcol );
	DWORD dwStyle ;
	if((lvcol.fmt&LVCFMT_JUSTIFYMASK) == LVCFMT_LEFT)
		dwStyle = ES_LEFT;
	else if((lvcol.fmt&LVCFMT_JUSTIFYMASK) == LVCFMT_RIGHT)
		dwStyle = ES_RIGHT;
	else dwStyle = ES_CENTER;

	rect.left += offset+4;
	rect.right = rect.left + GetColumnWidth( nCol ) - 3 ;
	if( rect.right > rcClient.right) rect.right = rcClient.right;

	m_nEditRow	= nItem;
	m_nEditCol	= nCol;

	dwStyle |= WS_BORDER|WS_CHILD|WS_VISIBLE|ES_AUTOHSCROLL;
	CEdit *pEdit = new CInPlaceEdit(nItem, nCol, GetItemText( nItem, nCol ));
	pEdit->Create( dwStyle, rect, this, IDC_EDITEX );
//	pEdit->Create( dwStyle, rect, this, nID );

	if ( M_aColumnActFlag[nCol].nLimitEdit > 0 )
		pEdit->SetLimitText( UINT(M_aColumnActFlag[nCol].nLimitEdit) );

// Modify ... ( ADD )
	m_bEditShow	 = 1;
// By Y.Itabashi (xxxxx) 2007.03.05

	return pEdit;
}

#ifdef START_INPLACE_IS_DBLCLICK
// �v�C���F�V���O���N���b�N�ɂ����͂ɕύX���͕s�v�ƂȂ�
//
//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnDblclk
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�u���N���b�N���̏���
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.24 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::OnDblclk(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if ( pNMListView->iItem < 0 || pNMListView->iSubItem < 0 ) return;

	int iRow = pNMListView->iItem;
	int iCol = pNMListView->iSubItem;
	UINT flag = LVIS_FOCUSED;

	if ( M_aColumnActFlag[iCol].nInputType == eINPUTTYPE_EDITBOX ) {

		// Add check for LVS_EDITLABELS
		if( GetWindowLong(m_hWnd, GWL_STYLE) & LVS_EDITLABELS )
			EditSubLabel( iRow, iCol, GetDlgCtrlID() );
	}
//	GetParentOwner()->SendMessage( WM_USEREVENT_LISTEX_DBLCLICK, reinterpret_cast<WPARAM> (pNMHDR));

	*pResult = 0;
}
#endif

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SelectChange
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�I���̕ύX
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�I������s���w��A�P��I���̂Ƃ��̂݋@�\����
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SelectChange(int nRow) 
{
// Modify ... ( CHANGE )
//	if( m_nSelectType != eSELECTTYPE_SINGLE )	return;

	SetFocus();
	POSITION pos = GetFirstSelectedItemPosition();
	int nBeforeRow = GetNextSelectedItem( pos );
	if( m_nSelectType == eSELECTTYPE_SINGLE )
		SetItemState( nBeforeRow, 0, LVIS_SELECTED );
// By Y.Itabashi (xxxxx) 2007.02.07

	SetItemState( nRow, LVIS_SELECTED, LVIS_SELECTED );

	this->EnsureVisible( nRow, true );
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnCustomDraw
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�X�^���h���[����
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::OnCustomDraw(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NMLVCUSTOMDRAW* pLVCD = reinterpret_cast<NMLVCUSTOMDRAW*>( pNMHDR );

	PITEMSTATE	pItemState = NULL;

	*pResult = CDRF_DODEFAULT;
	if ( CDDS_PREPAINT == pLVCD->nmcd.dwDrawStage )
	{
		*pResult = CDRF_NOTIFYITEMDRAW;
	}
	else if ( CDDS_ITEMPREPAINT == pLVCD->nmcd.dwDrawStage )
	{
		*pResult = CDRF_NOTIFYSUBITEMDRAW;
	}
	else if ( (CDDS_ITEMPREPAINT | CDDS_SUBITEM) == pLVCD->nmcd.dwDrawStage )
	{
		COLORREF crText, crBkgnd;

		pItemState = (PITEMSTATE)GetItemData( pLVCD->nmcd.dwItemSpec );
		if ( pItemState != NULL) {
			crText	= pItemState[pLVCD->iSubItem].textColor;
			crBkgnd	= pItemState[pLVCD->iSubItem].bkgdColor;
		}
		pLVCD->clrText = crText;
		pLVCD->clrTextBk = crBkgnd;

		*pResult = CDRF_DODEFAULT;
	}
}


//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::GetItemColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���ʒu�̃e�L�X�g�`��F�A�w�i�F���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		int			nCol			[I] ��
//		COLORREF&	textColor		[O]	�����F
//		COLORREF&	bkgdColor		[O] �w�i�F
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::GetItemColor( int nRow, int nCol, COLORREF &textColor, COLORREF &bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( nRow );
	if ( pItemState != NULL ) {
		textColor = pItemState[nCol].textColor;
		bkgdColor = pItemState[nCol].bkgdColor;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetItemColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���ʒu�̃e�L�X�g�`��F�A�w�i�F���Z�b�g
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		int			nCol			[I] ��
//		COLORREF	textColor		[I]	�����F
//		COLORREF	bkgdColor		[I] �w�i�F
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemColor( int nRow, int nCol, COLORREF textColor, COLORREF bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
	if ( pItemState != NULL ) {
		pItemState[nCol].textColor = textColor;
		pItemState[nCol].bkgdColor = bkgdColor;
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::GetItemRowColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���s�̃e�L�X�g�`��F�A�w�i�F���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		COLORREF&	textColor		[O]	�����F
//		COLORREF&	bkgdColor		[O] �w�i�F
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		1��ڂ̃J���[���擾
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::GetItemRowColor( int nRow, COLORREF &textColor, COLORREF &bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( nRow );
	if ( pItemState != NULL ) {
		textColor = pItemState[0].textColor;
		bkgdColor = pItemState[0].bkgdColor;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetItemRowColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���s�̃e�L�X�g�`��F�A�w�i�F���Z�b�g
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		COLORREF	textColor		[I]	�����F
//		COLORREF	bkgdColor		[I] �w�i�F
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemRowColor( int nRow, COLORREF textColor, COLORREF bkgdColor) 
{
	PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
	if ( pItemState != NULL ) {
		int nMax = this->GetHeaderCtrl()->GetItemCount();
		for( int nCol = 0; nCol < nMax; nCol++ ){
			pItemState[nCol].textColor = textColor;
			pItemState[nCol].bkgdColor = bkgdColor;
		}
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::GetItemColColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w����̃e�L�X�g�`��F�A�w�i�F���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] ��
//		COLORREF&	textColor		[O]	�����F
//		COLORREF&	bkgdColor		[O] �w�i�F
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		1�s�ڂ̃J���[���擾
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::GetItemColColor( int nCol, COLORREF &textColor, COLORREF &bkgdColor) 
{
	if( nCol < 0 || nCol > GetHeaderCtrl()->GetItemCount() ){
		textColor = RGB( 0xff, 0xff, 0xff );
		bkgdColor = RGB( 0x00, 0x00, 0x00 );
		return;
	}

	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( 0 );
	if ( pItemState != NULL ) {
		textColor = pItemState[nCol].textColor;
		bkgdColor = pItemState[nCol].bkgdColor;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetItemColColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w����̃e�L�X�g�`��F�A�w�i�F���Z�b�g
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nCol			[I] ��
//		COLORREF	textColor		[I]	�����F
//		COLORREF	bkgdColor		[I] �w�i�F
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemColColor( int nCol, COLORREF textColor, COLORREF bkgdColor) 
{
	if( nCol < 0 || nCol > GetHeaderCtrl()->GetItemCount() )	return;

	int nMax = this->GetItemCount();
	for( int nRow = 0; nRow < nMax; nRow++ ){
		PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
		if ( pItemState != NULL ) {
			pItemState[nCol].textColor = textColor;
			pItemState[nCol].bkgdColor = bkgdColor;
		}
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::GetItemRowData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���ʒu�̃e�L�X�g�`��F�A�w�i�F�ȊO�̃f�[�^���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		DWORD		dwData			[O]	�A�C�e���Ɋ֘A�t������32 �r�b�g�̒l
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
DWORD CLixxxtrlEx::GetItemRowData( int nRow ) 
{
	if( nRow < 0 || nRow >= GetItemCount() )	return 0;

	PITEMSTATE pItemState = (PITEMSTATE)GetItemData( nRow );
	if ( pItemState != NULL ) {
		return( pItemState[0].dwData );
	}

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetItemRowData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���ʒu�̃e�L�X�g�`��F�A�w�i�F�ȊO�̃f�[�^���Z�b�g
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nRow			[I] �s
//		DWORD		dwData			[I]	�A�C�e���Ɋ֘A�t������32 �r�b�g�̒l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.01.28 Y.Itabashi (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetItemRowData( int nRow, DWORD dwData) 
{
	if( nRow < 0 || nRow >= GetItemCount() )	return;

	PITEMSTATE pItemState = (PITEMSTATE) GetItemData( nRow );
	if ( pItemState != NULL ) {
		pItemState[0].dwData = dwData;
		SetItemData( nRow, (DWORD)pItemState );
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::OnDeleteitem
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�C�e���j��������
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//		�A�C�e������̍폜
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		DeleteItem�A�_�C�A���O�j�����ɌĂяo�����
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::OnDeleteitem( NMHDR* pNMHDR, LRESULT* pResult ) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int	nRow = pNMListView->iItem;
	PITEMSTATE pItemState = (PITEMSTATE)GetItemData(nRow);
	if (pItemState != NULL) {
		delete [] pItemState;
		pItemState = NULL;
	}
	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CLixxxtrlEx::SetColumnFormat
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�������̏����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �����T�v
//----------------------------------------------------------------------------------------------------
//  4. ���l
//		�^���镶�����printf���Ŏg�p����"%d"�A"������=%s"�A"�����l=%2.3lf"�����̂܂�
//		�A���A���l�J������"�����l=%d"���ƕ�������܂߂������ɂ����ꍇ�A�\�[�g�Ƃ̕��p�͂ł��Ȃ�
//		�i�ł��邪����ۏ؂Ȃ��j
//		�����l��"%s"�A"%d"�A"%lf"�A"%c"
//----------------------------------------------------------------------------------------------------
//  5. ����
//		2007.01.28 K.Kimura (xxxxx)
//*****************************************************************************************************
void CLixxxtrlEx::SetColumnFormat( int nCol, LPCTSTR format )
{
	strcpy(M_aColumnActFlag[nCol].sDispFormat, format);
}

void CLixxxtrlEx::DoDataExchange(CDataExchange* pDX) 
{
	CLixxxtrl::DoDataExchange(pDX);

	if ( m_nEditRow >= 0 && m_nEditCol >= 0 ) {

		int	maxchar	= 0;
		int	minnum	= 0;
		int	maxnum	= 0;
		double	minval	= 0.0;
		double	maxval	= 0.0;
		switch ( M_aColumnActFlag[m_nEditCol].nItemType ) {

		case eITEMTYPE_STRING:

			maxchar = int( M_aColumnActFlag[m_nEditCol].fMaxValue );
			if ( maxchar > 0 ) {
				DDX_Text( pDX, IDC_EDITEX, m_EditText );
				DDV_MaxChars( pDX, m_EditText, int( M_aColumnActFlag[m_nEditCol].fMaxValue ) );
			}
			break;

		case eITEMTYPE_INUM:

			minnum = int( M_aColumnActFlag[m_nEditCol].fMinValue );
			maxnum = int( M_aColumnActFlag[m_nEditCol].fMaxValue );
			if ( minnum == 0 && maxnum == 0 || minnum == maxnum || minnum > maxnum ) {
				break;
			}
			DDX_Text( pDX, IDC_EDITEX, m_EditInt );
			DDV_MinMaxInt( pDX, m_EditInt, minnum, maxnum );
			break;

		case eITEMTYPE_RNUM:

			minval = M_aColumnActFlag[m_nEditCol].fMinValue;
			maxval = M_aColumnActFlag[m_nEditCol].fMaxValue; 
			if ( minval == 0.0 && maxval == 0.0 || minval == maxval || minval > maxval ) {
				break;
			}
			DDX_Text( pDX, IDC_EDITEX, m_EditDouble );
			DDV_MinMaxDouble( pDX, m_EditDouble, minval, maxval );
			break;

		}
	}
}

void CLixxxtrlEx::SetColumnMinMax( int nCol, double minvalue, double maxvalue )
{
	CHeaderCtrl *pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	
	if ( nCol > nColumnCount ) return;

	if ( M_aColumnActFlag[nCol].nInputType == eINPUTTYPE_EDITBOX ) {

		switch ( M_aColumnActFlag[nCol].nItemType ) {

		case eITEMTYPE_STRING:

			M_aColumnActFlag[nCol].fMaxValue = (int)maxvalue;
			break;

		case eITEMTYPE_INUM:

			M_aColumnActFlag[nCol].fMinValue = (int)minvalue;
			M_aColumnActFlag[nCol].fMaxValue = (int)maxvalue;
			break;

		case eITEMTYPE_RNUM:

			M_aColumnActFlag[nCol].fMinValue = minvalue;
			M_aColumnActFlag[nCol].fMaxValue = maxvalue;
			break;

		}
	}
}

void CLixxxtrlEx::SetLimitEdit( int nCol, int nLimit )
{
	CHeaderCtrl *pHeader = (CHeaderCtrl*)GetDlgItem(0);
	int nColumnCount = pHeader->GetItemCount();
	
	if ( nCol > nColumnCount ) return;

	M_aColumnActFlag[nCol].nLimitEdit = nLimit;
}

void CLixxxtrlEx::SetMultilineHeader(int nLine)
{
	HDITEM	hdItem;
	CHeaderCtrl* pHeader = NULL;
	pHeader = GetHeaderCtrl();

//	m_NewHeaderFont.CreatePointFont(190,"MS Serif");
	m_NewHeaderFont.CreatePointFont(100*nLine,"SYSTEM_FONT");

	if(pHeader==NULL)
		return;

	VERIFY(m_HeaderCtrl.SubclassWindow(pHeader->m_hWnd));	

	m_HeaderCtrl.SetFont(&(m_NewHeaderFont));

	hdItem.mask = HDI_FORMAT;

	for(int i=0; i <m_HeaderCtrl.GetItemCount(); i++)
	{
		m_HeaderCtrl.GetItem(i,&hdItem);

		hdItem.fmt|= HDF_OWNERDRAW;
			
		m_HeaderCtrl.SetItem(i,&hdItem);
	}
}

void CLixxxtrlEx::SetColumnText(int nColumn, LPCTSTR pText)
{
	LVCOLUMN lvc;

	memset(&lvc, 0, sizeof(lvc));
	lvc.mask = LVCF_TEXT;
	lvc.pszText = (LPTSTR)pText;
	SetColumn(nColumn, &lvc);
}

void CLixxxtrlEx::OnSetFocus(CWnd* pOldWnd) 
{
	if( m_bEditShow ){
		pOldWnd->SetFocus();
		return;
	}

	CLixxxtrl::OnSetFocus(pOldWnd);
	
}
