// DlgComment.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgComment.h"


// CDlgComment ダイアログ

IMPLEMENT_DYNAMIC(CDlgComment, CDlgCommon)

CDlgComment::CDlgComment(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgComment::IDD, pParent)
{
	m_nSeqNo = 0;
}

void CDlgComment::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DATE, m_cEditDate);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
}


BEGIN_MESSAGE_MAP(CDlgComment, CDlgCommon)
END_MESSAGE_MAP()


// CDlgComment メッセージ ハンドラ

BOOL CDlgComment::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	m_cEditDate = m_oDate.Format("%Y/%m/%d");

	CString sSql;
	CDbResult oDbResult;
	sSql.Format("SELECT cm_seq_no,cm_comment FROM t_comment WHERE cm_date=%s ORDER BY cm_seq_no", m_pDataBase->SqlDate(m_oDate));
	if (m_pDataBase->Select(sSql, oDbResult)) {
		if (oDbResult.GetCount() > 0) {
			m_cEditComment = oDbResult.GetTextValue(0, "cm_comment");
			m_nSeqNo = oDbResult.GetIntValue(0, "cm_seq_no");

			if (!m_cEditComment.IsEmpty()) {
				GetDlgItem(IDCANCEL)->SetFocus();
				return FALSE;
			}
		}
	}

	return TRUE;
}

void CDlgComment::OnOK()
{
	CString sData = m_cEditComment;

	CString sSql;
	if (m_nSeqNo) {
		if (!sData.IsEmpty()) {
			sSql.Format("UPDATE t_comment SET cm_comment=%s WHERE cm_seq_no=%d",
					m_pDataBase->SqlText(sData),
					m_nSeqNo);
		} else {
			sSql.Format("DELETE FROM t_comment WHERE cm_seq_no=%d",
					m_nSeqNo);
		}
	} else {
		if (!sData.IsEmpty()) {
			sSql.Format("INSERT INTO t_comment (cm_date,cm_comment) VALUES (%s,%s)",
					m_pDataBase->SqlDate(m_oDate),
					m_pDataBase->SqlText(sData));
		}
	}

	if (!sSql.IsEmpty())
		m_pDataBase->Exec(sSql);

	CDlgCommon::OnOK();
}
