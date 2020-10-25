// DlgBoard.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgBoard.h"


// CDlgBoard ダイアログ

IMPLEMENT_DYNAMIC(CDlgBoard, CDlgCommon)

CDlgBoard::CDlgBoard(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgBoard::IDD, pParent)
{
	m_bUpdate = FALSE;
}

void CDlgBoard::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_BOARD, m_cListBoard);
	DDX_Control(pDX, IDC_EDIT_BOARD, m_cEditBoard);
}


BEGIN_MESSAGE_MAP(CDlgBoard, CDlgCommon)
	ON_BN_CLICKED(IDC_BUTTON_ADD, &CDlgBoard::OnBnClickedButtonAdd)
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CDlgBoard::OnBnClickedButtonDelete)
	ON_BN_CLICKED(IDC_BUTTON_UPDATE, &CDlgBoard::OnBnClickedButtonUpdate)
	ON_LBN_SELCHANGE(IDC_LIST_BOARD, &CDlgBoard::OnLbnSelchangeListBoard)
END_MESSAGE_MAP()


// CDlgBoard メッセージ ハンドラ

BOOL CDlgBoard::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	DispData();

	return TRUE;
}

void CDlgBoard::DispData()
{
	CString sSql;
	CDbResult oDbResult;

	sSql = "SELECT bo_seq_no,bo_message FROM t_board WHERE bo_del_flag=FALSE ORDER BY bo_seq_no DESC";
	if (m_pDataBase->Select(sSql, oDbResult)) {
		m_cListBoard.ResetContent();
		int nRec = oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			m_cListBoard.SetItemData(m_cListBoard.AddString(oDbResult.GetTextValue(i, "bo_message")), oDbResult.GetIntValue(i, "bo_seq_no"));
		}
	}
}

void CDlgBoard::OnBnClickedButtonAdd()
{
	if (!m_cEditBoard.IsEmpty()) {
		CString sSql;
		CDbResult oDbResult;

		sSql.Format("INSERT INTO t_board (bo_message) VALUES (%s)", m_pDataBase->SqlText((CString)m_cEditBoard));
		m_pDataBase->Exec(sSql);

		m_cEditBoard.Blank();

		DispData();
		m_bUpdate = TRUE;
	}
}

void CDlgBoard::OnBnClickedButtonDelete()
{
	int nIndex = m_cListBoard.GetCurSel();
	if (nIndex != LB_ERR) {
		CString sSql;
		CDbResult oDbResult;

		sSql.Format("UPDATE t_board SET bo_del_flag=TRUE WHERE bo_seq_no=%d", m_cListBoard.GetItemData(nIndex));
		m_pDataBase->Exec(sSql);

		DispData();
		m_bUpdate = TRUE;
	}
}

void CDlgBoard::OnBnClickedButtonUpdate()
{
	if (!m_cEditBoard.IsEmpty()) {
		int nIndex = m_cListBoard.GetCurSel();
		if (nIndex != LB_ERR) {
			CString sSql;
			CDbResult oDbResult;

			sSql.Format("UPDATE t_board SET bo_message=%s WHERE bo_seq_no=%d", m_pDataBase->SqlText((CString)m_cEditBoard), m_cListBoard.GetItemData(nIndex));
			m_pDataBase->Exec(sSql);

			DispData();
			m_bUpdate = TRUE;
		}
	}
}

void CDlgBoard::OnCancel()
{
	EndDialog(m_bUpdate ? IDOK : IDCANCEL);
}

void CDlgBoard::OnLbnSelchangeListBoard()
{
	int nIndex = m_cListBoard.GetCurSel();
	if (nIndex != LB_ERR) {
		CString sText;
		m_cListBoard.GetText(nIndex, sText);
		m_cEditBoard = sText;
	}
}
