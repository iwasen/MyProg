// DialogEx.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "workscheduler.h"
#include "DialogEx.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CDialogEx �_�C�A���O


CDialogEx::CDialogEx(UINT nID, CWnd* pParent /*=NULL*/)
	: CDialog(nID, pParent)
{
	//{{AFX_DATA_INIT(CDialogEx)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_INIT

	// �N���X�ϐ�������
	m_sizeDlg = 0;
	m_sizeMin = 0;
	m_bSizeChange = FALSE;
// Modify ... ( ADD )
	m_bCloseLock = 0;
// By Y.Itabashi (xxxxx) 2007.03.05
}


void CDialogEx::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CDialogEx)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CDialogEx, CDialog)
	//{{AFX_MSG_MAP(CDialogEx)
	ON_WM_SIZE()
	ON_WM_GETMINMAXINFO()
	ON_WM_SETFOCUS()
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_EXITSIZEMOVE, OnExitSizeMove)
	ON_MESSAGE(WM_USER + 0x0505, OnEditError)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CDialogEx ���b�Z�[�W �n���h��

BOOL CDialogEx::OnInitDialog()
{
	CDialog::OnInitDialog();

	// �E�B���h�E�T�C�Y��ۑ�
	CRect rect;
	GetClientRect(rect);
	m_sizeDlg = rect.Size();
	m_sizeMin = m_sizeDlg;

	return TRUE;
}

void CDialogEx::OnSize(UINT nType, int cx, int cy)
{
	CDialog::OnSize(nType, cx, cy);

	// �X�e�[�^�X�o�[���Ĕz�u
	RepositionBars(AFX_IDW_CONTROLBAR_FIRST, AFX_IDW_CONTROLBAR_LAST, 0);

	// �_�C�A���O�R���g���[����K�؂Ȉʒu�ɍĔz�u
	if (m_sizeDlg.cx && cx != 0) {
		// �T�C�Y�̍���
		int nx = cx - m_sizeDlg.cx;
		int ny = cy - m_sizeDlg.cy;

		// �ύX���ꂽ�T�C�Y���_�C�A���O���̃R���g���[���̑傫���ƈʒu��ύX����
		int nDlgCtlPosition = m_aDlgCtlPosition.GetSize();
		for (int i = 0; i < nDlgCtlPosition; i++) {
			// �q�E�B���h�E���擾
			SDlgCtlPosition &cDlgCtlPosition = m_aDlgCtlPosition[i];
			CWnd *pWnd = GetDlgItem(cDlgCtlPosition.nID);

			// �q�E�B���h�E�̈ʒu�A�傫�����擾
			CRect rect(cDlgCtlPosition.rectCtl);

			// �ʒu�Ƒ傫���𒲐�
			rect.left += (int)(nx * cDlgCtlPosition.fLeft);
			rect.top += (int)(ny * cDlgCtlPosition.fTop);
			rect.right += (int)(nx * cDlgCtlPosition.fRight);
			rect.bottom += (int)(ny * cDlgCtlPosition.fBottom);

			// �q�E�B���h�E���ړ�
			pWnd->SetWindowPos(NULL, rect.left, rect.top, rect.Width(), rect.Height(), SWP_NOZORDER);

			// �ĕ`��
			pWnd->Invalidate(FALSE);
		}

		// �T�C�Y�ύX�t���OON
		m_bSizeChange = TRUE;
	}
}

// �E�B���h�E�T�C�Y�ύX�A�ړ��I������
long CDialogEx::OnExitSizeMove(WPARAM wParam, LPARAM lParam)
{
	// �T�C�Y�ύX�t���O��ON�Ȃ�_�C�A���O���ĕ\��
	if (m_bSizeChange) {
		Invalidate(FALSE);
		m_bSizeChange = FALSE;
	}

	return 0;
}

void CDialogEx::OnGetMinMaxInfo(MINMAXINFO FAR* lpMMI)
{
	// �E�B���h�E�̍ŏ��T�C�Y��ݒ�
	if (m_sizeMin.cx != 0) {
		lpMMI->ptMinTrackSize.x = m_sizeMin.cx;
		lpMMI->ptMinTrackSize.y = m_sizeMin.cy;
	}

	CDialog::OnGetMinMaxInfo(lpMMI);
}

// �R���g���[���ʒu�ݒ菈��
void CDialogEx::SetCtlPosition(UINT nID, double fLeft, double fTop, double fRight, double fBottom)
{
	SDlgCtlPosition cDlgCtlPosition;
	cDlgCtlPosition.nID = nID;
	cDlgCtlPosition.fLeft = fLeft;
	cDlgCtlPosition.fTop = fTop;
	cDlgCtlPosition.fRight = fRight;
	cDlgCtlPosition.fBottom = fBottom;

	WINDOWPLACEMENT wp;
	GetDlgItem(nID)->GetWindowPlacement(&wp);
	cDlgCtlPosition.rectCtl = wp.rcNormalPosition;

	m_aDlgCtlPosition.Add(cDlgCtlPosition);
}

// �ŏ��_�C�A���O�T�C�Y�ݒ菈��
void CDialogEx::SetMinSize(int nWidth, int nHeight)
{
	m_sizeMin.cx = nWidth;
	m_sizeMin.cy = nHeight;
}

void CDialogEx::SetSpreadColumn(CSpreadSheet &cSpread)
{
	CString sHeader;

	for (int nCol = 1; nCol <= cSpread.GetMaxCols(); nCol++) {
		cSpread.SetRow(0);
		cSpread.SetCol(nCol);
		sHeader = cSpread.GetText();
		sHeader.Replace(' ', '\n');
		cSpread.SetText(sHeader);
	}
}

// Lixxxtrl�̃w�b�_�[�ݒ�Ɗe�J�����̐���ݒ�
void CDialogEx::SetLixxxolumn(CLixxxtrlEx &cList, MENUCTRLDATA *MenuCtrlData, LIxxxTRLDATA *LixxxtrlData, int SelectMode )
{
	CString	sHeader;

	int id = cList.GetDlgCtrlID();


	int	nWidth = cList.GetStringWidth( "W" );
	int i = 0;

	cList.SetExtendedStyle( LVS_EX_FULLROWSELECT | LVS_EX_GRIDLINES );
	cList.ModifyStyle(0,LVS_EDITLABELS);

	while( LixxxtrlData[i].nCol != -1 ){

		// �w�b�_�[�̃Z�b�g
		if( LixxxtrlData[i].nHeaderNo != -1 && MenuCtrlData[LixxxtrlData[i].nHeaderNo].MenuNo[0] != NULL ){
			CString str = g_pSystem->m_cMenuDisp.GetMenu( MenuCtrlData[LixxxtrlData[i].nHeaderNo].MenuNo,
				MenuCtrlData[LixxxtrlData[i].nHeaderNo].ItemNo );
			sHeader.Format( str );
		}else if( LixxxtrlData[i].nHeaderNo != -1 && MenuCtrlData[LixxxtrlData[i].nHeaderNo].str1[0] != NULL )
			sHeader = MenuCtrlData[LixxxtrlData[i].nHeaderNo].str1;
		else if( LixxxtrlData[i].nHeaderNo == -1 )
			sHeader.Format( "", i+1 );
		else{
			i++;
			continue;
		}

		cList.InsertColumn( LixxxtrlData[i].nCol,
			sHeader,
			LixxxtrlData[i].tFormat,
			LixxxtrlData[i].nWidth*nWidth,
			-1 );

		// �e�J�����̐���̐ݒ�
		cList.SetColumnAction( LixxxtrlData[i].nCol, eCOLUMNACTTYPE_SORT, LixxxtrlData[i].tSort );
		cList.SetColumnAction( LixxxtrlData[i].nCol, eCOLUMNACTTYPE_SEARCH, LixxxtrlData[i].tSearch );

		cList.SetColumnType( LixxxtrlData[i].nCol, LixxxtrlData[i].tDataType, LixxxtrlData[i].tEditType );

		if( LixxxtrlData[i].sDispFormat[0] != NULL )
			cList.SetColumnFormat( LixxxtrlData[i].nCol, LixxxtrlData[i].sDispFormat );
		cList.SetLimitEdit( LixxxtrlData[i].nCol, LixxxtrlData[i].nLimitEdit );
		cList.SetColumnMinMax( LixxxtrlData[i].nCol,
			LixxxtrlData[i].fMinValue, LixxxtrlData[i].fMaxValue );

		i++;
	}

	cList.SetSelectType( SelectMode );
}

BOOL CDialogEx::EditDataCheck(CDataCheck &cDataCheck, CWnd &cEdit, LPCTSTR pItemName)
{
	if (!EditDataCheckSub(cDataCheck, cEdit, pItemName)) {
		cEdit.SetFocus();
		return FALSE;
	}

	return TRUE;
}

BOOL CDialogEx::EditDataCheckSub(CDataCheck &cDataCheck, CWnd &cEdit, LPCTSTR pItemName)
{
	CString sData;

	//�@�\������Ă���ꍇ�̂݃`�F�b�N����
	if (cEdit.IsWindowVisible()) {
		cEdit.GetWindowText(sData);

		// �����̓`�F�b�N
		if (!cDataCheck.BlankCheck(sData)) {
			g_pSystem->DispMessage("E000001", pItemName);
			return FALSE;
		}

		// �L�������`�F�b�N
		if (!cDataCheck.ValidCharCheck(sData)) {
			LPCTSTR pDataTypeName = "";
			switch (cDataCheck.m_nDataType) {
			case eDataTypeNumber:
				pDataTypeName = "���p����";
				break;
			case eDataTypeAlphaNum:
				pDataTypeName = "���p�p����";
				break;
			case eDataTypeAlphaNumKana:
				pDataTypeName = "���p�p���J�i";
				break;
			}
			g_pSystem->DispMessage("E000006", pItemName, pDataTypeName);
			return FALSE;
		}

		if (cDataCheck.m_nDataType == eDataTypeNumber) {
			// �����`�F�b�N
			if (!cDataCheck.LengthCheck(sData)) {
				g_pSystem->DispMessage("E000003", pItemName, cDataCheck.m_nDecimal == 0 ? cDataCheck.m_nLength : cDataCheck.m_nLength - cDataCheck.m_nDecimal - 1, cDataCheck.m_nDecimal);
				return FALSE;
			}

			// �͈̓`�F�b�N
			if (!cDataCheck.RangeCheck(sData)) {
				if (cDataCheck.m_nDecimal == 0)
					g_pSystem->DispMessage("E000004", pItemName, (int)cDataCheck.m_fMin, (int)cDataCheck.m_fMax);
				else
					g_pSystem->DispMessage("E000005", pItemName, cDataCheck.m_fMin, cDataCheck.m_fMax);
				return FALSE;
			}
		}
	}

	return TRUE;
}

BOOL CDialogEx::ComboDataCheck(CComboBox &cComboBox, LPCTSTR pItemName)
{
	//�@�\������Ă���ꍇ�̂݃`�F�b�N����
	if (cComboBox.IsWindowVisible()) {
		if (cComboBox.GetWindowTextLength() == 0) {
			cComboBox.SetFocus();
			g_pSystem->DispMessage("E000002", pItemName);
			return FALSE;
		}
	}

	return TRUE;
}

// Modify ... ( ADD )
LRESULT CDialogEx::OnEditError(WPARAM wp, LPARAM lp)
{
	m_bCloseLock = 1;
	return TRUE;
}

void CDialogEx::OnSetFocus(CWnd* pOldWnd) 
{
	if( m_bCloseLock ){
		pOldWnd->SetFocus();
		return;
	}

	CDialog::OnSetFocus(pOldWnd);
	
}
// By Y.Itabashi (xxxxx) 2007.03.05
