// SelectStartTimeDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "SelectStartTimeDlg.h"
#include "General.h"


// CSelectStartTimeDlg ダイアログ

IMPLEMENT_DYNAMIC(CSelectStartTimeDlg, CDialog)

CSelectStartTimeDlg::CSelectStartTimeDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CSelectStartTimeDlg::IDD, pParent)
{
	m_nSelectIndex = 0;
}

CSelectStartTimeDlg::~CSelectStartTimeDlg()
{
}

void CSelectStartTimeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_SENSOR, m_cComboSensor);
	DDX_Control(pDX, IDOK, m_cButtonOK);
}


BEGIN_MESSAGE_MAP(CSelectStartTimeDlg, CDialog)
	ON_CBN_SELCHANGE(IDC_COMBO_SENSOR, &CSelectStartTimeDlg::OnCbnSelchangeComboSensor)
END_MESSAGE_MAP()


// CSelectStartTimeDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CSelectStartTimeDlg::GetMaiFileName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		計測開始時刻を選択し、そのファイル名を返す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sRsuNo			[I] RSU No.
//		CStringArray	&saFileName		[I] 選択するmaiファイル名の配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		選択したmaiファイル名
//*****************************************************************************************************
CString CSelectStartTimeDlg::GetMaiFileName(const CString &sRsuNo, const CStringArray &saFileName)
{
	LPCTSTR pSeparator = "-_.";
	CString sStartTime;
	CString sEndTime;
	int i, j;

	// ファイル名から開始時刻を取り出す
	for (i = 0; i < saFileName.GetSize(); i++) {
		const CString &sFileName = saFileName[i];

		CStringArray saParts;
		CGeneral::StrSplit(sFileName, pSeparator, saParts);

		if (saParts[0] == sRsuNo) {
			sStartTime.Format("%s/%s/%s %s:%s:%s.%s", saParts[1], saParts[2], saParts[3], saParts[4], saParts[5], saParts[6], saParts[7]);
			sEndTime = saParts[8];

			int nSize = (int)m_aoMaiFile.GetSize();
			for (j = 0; j < nSize; j++) {
				if (sStartTime == m_aoMaiFile[j].sStartTime) {
					if (sEndTime > m_aoMaiFile[j].sEndTime) {
						m_aoMaiFile[j].sFileName = sFileName;
						m_aoMaiFile[j].sEndTime = sEndTime;
					}
					break;
				}
			}

			if (j == nSize) {
				SMaiFile oMaiFile;
				oMaiFile.sFileName = sFileName;
				oMaiFile.sStartTime = sStartTime;
				oMaiFile.sEndTime = sEndTime;
				m_aoMaiFile.Add(oMaiFile);
			}
		}
	}

	// 同一RSUで異なる開始時刻のファイルがあったらダイアログを表示して選択させる
	if (m_aoMaiFile.GetSize() > 1)
		DoModal();

	return m_aoMaiFile[m_nSelectIndex].sFileName;
}

//*****************************************************************************************************
//  1. 関数名
//		CSelectStartTimeDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CSelectStartTimeDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	for (int i = 0; i < m_aoMaiFile.GetSize(); i++) {
		int nIndex = m_cComboSensor.AddString(m_aoMaiFile[i].sStartTime);
		m_cComboSensor.SetItemData(nIndex, i);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CSelectStartTimeDlg::OnCbnSelchangeComboSensor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル名コンボボックス選択処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSelectStartTimeDlg::OnCbnSelchangeComboSensor()
{
	// 開始時刻を選択したらOKボタンを有効にする
	m_cButtonOK.EnableWindow(m_cComboSensor.GetCurSel() != CB_ERR);
}

//*****************************************************************************************************
//  1. 関数名
//		CSelectStartTimeDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		OKボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSelectStartTimeDlg::OnOK()
{
	// 選択した開始時刻のインデックスを保存し、ダイアログを閉じる
	int nIndex = m_cComboSensor.GetCurSel();
	if (nIndex != CB_ERR) {
		m_nSelectIndex = (int)m_cComboSensor.GetItemData(nIndex);

		CDialog::OnOK();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CSelectStartTimeDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キャンセルボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSelectStartTimeDlg::OnCancel()
{
	// 何もしない(ESCまたは閉じるボタンで終了するのを防ぐ）
}
