// CsvData.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "CsvData.h"
#include "DataDateTime.h"
#include "General.h"
#include <shlwapi.h>

// CCsvData ダイアログ

IMPLEMENT_DYNAMIC(CCsvData, CDialog)

CCsvData::CCsvData(CWnd* pParent /*=NULL*/)
	: CDialog(CCsvData::IDD, pParent)
{

}

CCsvData::~CCsvData()
{
}

void CCsvData::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_START, m_Edit_Start);
	DDX_Control(pDX, IDC_EDIT_END, m_Edit_Endtime);
	DDX_Control(pDX, IDC_EDIT_MAI, m_Edit_FileName);
	DDX_Control(pDX, IDC_EDIT_S_YY, m_Edit_s_yy);
	DDX_Control(pDX, IDC_EDIT_S_MM, m_Edit_s_mm);
	DDX_Control(pDX, IDC_EDIT_S_DD, m_Edit_s_dd);
	DDX_Control(pDX, IDC_EDIT_S_H, m_Edit_s_h);
	DDX_Control(pDX, IDC_EDIT_S_M, m_Edit_s_m);
	DDX_Control(pDX, IDC_EDIT_S_S, m_Edit_s_s);
	DDX_Control(pDX, IDC_EDIT_E_YY, m_Edit_e_yy);
	DDX_Control(pDX, IDC_EDIT_E_MM, m_Edit_e_mm);
	DDX_Control(pDX, IDC_EDIT_E_DD, m_Edit_e_dd);
	DDX_Control(pDX, IDC_EDIT_E_H, m_Edit_e_h);
	DDX_Control(pDX, IDC_EDIT_E_M, m_Edit_e_m);
	DDX_Control(pDX, IDC_EDIT_E_S, m_Edit_e_s);
	DDX_Control(pDX, IDC_COMBO_SAMPLE_RATE, m_cComboSampleRate);
	DDX_Control(pDX, IDC_COMBO_TARGET_DATA, m_cComboTargetData);
}


BEGIN_MESSAGE_MAP(CCsvData, CDialog)
	ON_BN_CLICKED(IDC_BUTTON_REF, &CCsvData::OnBnClickedButtonRef)
	ON_BN_CLICKED(IDC_BUTTON_CONV, &CCsvData::OnBnClickedButtonConv)
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_REF, "出力するCSVファイルを指定するためのダイアログを開きます。"},
	{IDC_BUTTON_CONV, "CSVファイルの出力を実行します。"},
	{IDCANCEL, "このダイアログを閉じます。"},
	{0, NULL}
};


// CCsvData メッセージ ハンドラ

void CCsvData::DspModal(LPCSTR pFilePath, int nDataType)
{
	// データ種別(MMA or MME)保存
	m_cDataFile.m_nDataType = nDataType;

	// 情報ファイル読み込み
	if (!m_cDataFile.ReadInfoFile(pFilePath))
		return;

	// 情報ファイルに従い開始時間編集
	m_startTime.SetTime(m_cDataFile.m_nStartYear
		, m_cDataFile.m_nStartMonth
		, m_cDataFile.m_nStartDay
		, m_cDataFile.m_nStartMiliSecond);

	// 情報ファイルに従い終了時間編集
	m_endTime = m_startTime;
	m_endTime += (int)(m_cDataFile.m_fTotalSecond * 1000);

	// 設定ダイアログを開く
	if (DoModal() == IDCANCEL)
		return;

	m_cDataFile.m_dStarttime = m_startTime;
	m_cDataFile.m_dInStarttime = m_In_startTime;
	m_cDataFile.m_dInEndtime = m_In_endTime;

	// 設定情報に従い、開始秒数～終了秒数算出
	CTime oTime1(m_cDataFile.m_dStarttime.m_YY, m_cDataFile.m_dStarttime.m_MM, m_cDataFile.m_dStarttime.m_DD, 0, 0, 0);
	CTime oTime2(m_cDataFile.m_dInStarttime.m_YY, m_cDataFile.m_dInStarttime.m_MM, m_cDataFile.m_dInStarttime.m_DD, m_cDataFile.m_dInStarttime.m_hh, m_cDataFile.m_dInStarttime.m_mm, m_cDataFile.m_dInStarttime.m_ss);
	CTimeSpan oTimeSpan = oTime2 - oTime1;
	int	from = (int)oTimeSpan.GetTotalSeconds();
	int	to = from + (m_In_endTime - m_In_startTime) / 1000;

	// データ読み込み
	if (!m_cDataFile.ReadWriteDataFile(m_sCsvPath, from, to, TRUE)) {
		return;
	}
}

BOOL CCsvData::OnInitDialog()
{
	CDialog::OnInitDialog();

	// サンプリング周波数のコンボボックスを設定
	CGeneral::SetSampleRateList(m_cComboSampleRate, m_cDataFile.m_fSampleRate);

	// CSV変換対象データをコンボボックスに設定
	m_cComboTargetData.SetItemData(m_cComboTargetData.AddString("加速度データ"), FALSE);
	if (m_cDataFile.m_nDataType == DATA_TYPE_MMA)
		m_cComboTargetData.SetItemData(m_cComboTargetData.AddString("温度データ"), TRUE);
	m_cComboTargetData.SetCurSel(0);	// デフォルトは加速度データ

	CString	str;

	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
			, m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
			, m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss);
	m_Edit_Start.SetWindowText(str);

	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
			, m_endTime.m_YY, m_endTime.m_MM, m_endTime.m_DD
			, m_endTime.m_hh, m_endTime.m_mm, m_endTime.m_ss);
	m_Edit_Endtime.SetWindowText(str);

	str.Format("%04d", m_startTime.m_YY);
	m_Edit_s_yy.SetWindowText(str);

	str.Format("%02d", m_startTime.m_MM);
	m_Edit_s_mm.SetWindowText(str);

	str.Format("%02d", m_startTime.m_DD);
	m_Edit_s_dd.SetWindowText(str);

	str.Format("%02d", m_startTime.m_hh);
	m_Edit_s_h.SetWindowText(str);

	str.Format("%02d", m_startTime.m_mm);
	m_Edit_s_m.SetWindowText(str);

	str.Format("%02d", m_startTime.m_ss);
	m_Edit_s_s.SetWindowText(str);

	str.Format("%04d", m_endTime.m_YY);
	m_Edit_e_yy.SetWindowText(str);

	str.Format("%02d", m_endTime.m_MM);
	m_Edit_e_mm.SetWindowText(str);

	str.Format("%02d", m_endTime.m_DD);
	m_Edit_e_dd.SetWindowText(str);

	str.Format("%02d", m_endTime.m_hh);
	m_Edit_e_h.SetWindowText(str);

	str.Format("%02d", m_endTime.m_mm);
	m_Edit_e_m.SetWindowText(str);

	str.Format("%02d", m_endTime.m_ss);
	m_Edit_e_s.SetWindowText(str);

	LPITEMIDLIST a_pItemList;

	HRESULT hresult;
	LPMALLOC pMalloc;

	hresult = ::SHGetMalloc( &pMalloc );
	if(FAILED(hresult)){
		AfxMessageBox("");
	}

	hresult = ::SHGetSpecialFolderLocation((HWND)this->m_hWnd,CSIDL_DESKTOP, &a_pItemList);
	char strPath[MAX_PATH+1];
	::SHGetPathFromIDList(a_pItemList, strPath);

	CString strFileName;
	strFileName.Format("\\%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d.csv"
			,m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
			,m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss
			,m_endTime.m_YY,   m_endTime.m_MM,   m_endTime.m_DD
			,m_endTime.m_hh,   m_endTime.m_mm,   m_endTime.m_ss);

	CString strFilePath;
	strFilePath.Format("%s", strPath);
	strFilePath += strFileName;
	m_Edit_FileName.SetWindowText(strFilePath);
	pMalloc->Free(a_pItemList);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}


void CCsvData::OnBnClickedButtonRef()
{
	// ファイル選択ダイアログを開く
	CString strFileName;
	m_Edit_FileName.GetWindowText(strFileName);

	CFileDialog cFDlg(TRUE
		, "csv"
		, strFileName
		, OFN_HIDEREADONLY | OFN_OVERWRITEPROMPT 
		, "CSVファイル(*.csv)|*.csv|全てのファイル(*.*)|*.*||", this);

	// キャンセルならば何もしない
	if (cFDlg.DoModal() == IDCANCEL) {
		return;
	}
	CString str;

	str = cFDlg.GetPathName();
	m_Edit_FileName.SetWindowText(str);
}

void CCsvData::OnBnClickedButtonConv()
{
	// 入力項目を取得する
	if (GetInputData())
		return;

	// 実行確認
	if (AfxMessageBox("実行しますか？", MB_YESNO) != IDYES)
		return;

	// CSVファイル存在及び上書き確認
	if (::PathFileExists(m_sCsvPath)) {
		if (AfxMessageBox("CSVファイルは既に存在します。上書きしますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
			return;
	}

	CDialog::OnOK();
}

// 入力項目を取得する
int CCsvData::GetInputData(void)
{
	int	yy, mm, dd, h, m, s;
	CString str;
	long Start_msec, End_msec;

	UpdateData(TRUE);

	// ダウンリンクデータ指定チェック
	m_Edit_FileName.GetWindowText(str);
	if (str.GetLength() == 0) {
		CGeneral::Alert("CSVファイル名を指定してください。");
		return 1;
	}
	m_sCsvPath = str;

	// 時間範囲取得
	m_Edit_s_yy.GetWindowText(str);
	yy = atoi(str);
	m_Edit_s_mm.GetWindowText(str);
	mm = atoi(str);
	m_Edit_s_dd.GetWindowText(str);
	dd = atoi(str);
	m_Edit_s_h.GetWindowText(str);
	h = atoi(str);
	m_Edit_s_m.GetWindowText(str);
	m = atoi(str);
	m_Edit_s_s.GetWindowText(str);
	s = atoi(str);
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("開始時間：月に正しい値を入力してください。");
		return 1;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("開始時間：日に正しい値を入力してください。");
		return 1;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("開始時間：時に正しい値を入力してください。");
		return 1;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("開始時間：分に正しい値を入力してください。");
		return 1;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("開始時間：秒に正しい値を入力してください。");
		return 1;
	}
	m_In_startTime.SetTime(yy, mm, dd, h, m, s);

	m_Edit_e_yy.GetWindowText(str);
	yy = atoi(str);
	m_Edit_e_mm.GetWindowText(str);
	mm = atoi(str);
	m_Edit_e_dd.GetWindowText(str);
	dd = atoi(str);
	m_Edit_e_h.GetWindowText(str);
	h = atoi(str);
	m_Edit_e_m.GetWindowText(str);
	m = atoi(str);
	m_Edit_e_s.GetWindowText(str);
	s = atoi(str);
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("終了時間：月に正しい値を入力してください。");
		return 1;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("終了時間：日に正しい値を入力してください。");
		return 1;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("終了時間：時に正しい値を入力してください。");
		return 1;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("終了時間：分に正しい値を入力してください。");
		return 1;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("終了時間：秒に正しい値を入力してください。");
		return 1;
	}
	m_In_endTime.SetTime(yy, mm, dd, h, m, s);

	Start_msec = m_In_startTime - m_startTime;
	End_msec = m_In_endTime - m_startTime;
	// 開始時間より前に設定
	if (Start_msec < 0) {
		CGeneral::Alert("開始時間：開始時間より前の設定はできません。");
		return 1;
	}
	if (End_msec < 0) {
		CGeneral::Alert("終了時間：開始時間より前の設定はできません。");
		return 2;
	}

	Start_msec = m_In_startTime - m_endTime;
	End_msec = m_In_endTime - m_endTime;
	// 終了時間より後に設定
	if (Start_msec >= 0) {
		CGeneral::Alert("開始時間：終了時間より後の設定はできません。");
		return 3;
	}
	if (End_msec > 0) {
		CGeneral::Alert("終了時間：終了時間より後の設定はできません。");
		return 4;
	}
	long period = m_In_endTime - m_In_startTime;
	if (period > 60 * 60 * 1000) {
		CGeneral::Alert("時間範囲：１時間分までにしてください。");
		return 5;
	}

	int	nIndex;
	if ((nIndex = m_cComboSampleRate.GetCurSel()) == CB_ERR) {
		CGeneral::Alert("サンプリング周波数：いずれかを選択してください。");
		return 6;
	}
	// 間引きレート取得
	m_cDataFile.m_nDownRate = (int)m_cComboSampleRate.GetItemData(nIndex);

	if ((nIndex = m_cComboTargetData.GetCurSel()) == CB_ERR) {
		CGeneral::Alert("対象データ：いずれかを選択してください。");
		return 7;
	}
	// 対象データを取得
	m_cDataFile.m_bTempOnly = (BOOL)m_cComboTargetData.GetItemData(nIndex);
	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CCsvData::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		MSG*	pMsg			[I] MSG構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値をそのまま返す
//*****************************************************************************************************
BOOL CCsvData::PreTranslateMessage(MSG* pMsg)
{
	// ツールチップを表示するための処理
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
