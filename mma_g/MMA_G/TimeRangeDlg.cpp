//*****************************************************************************************************
//  1. ファイル名
//		TimeRangeDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間範囲設定ダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "TimeRangeDlg.h"
#include "General.h"


// CTimeRangeDlg ダイアログ

IMPLEMENT_DYNAMIC(CTimeRangeDlg, CDialog)

//*****************************************************************************************************
//  1. 関数名
//		CTimeRangeDlg::CTimeRangeDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent		[I] 親ウィンドウへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CTimeRangeDlg::CTimeRangeDlg(CWnd* pParent)
	: CDialog(CTimeRangeDlg::IDD, pParent)
{
	m_bDspFlag = false;
}

void CTimeRangeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DATA_BEGIN, m_cEditDataBegin);
	DDX_Control(pDX, IDC_EDIT_DATA_END, m_cEditDataEnd);
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
}


BEGIN_MESSAGE_MAP(CTimeRangeDlg, CDialog)
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDCANCEL, "何もせずにこのダイアログを閉じます。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CTimeRangeDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE
//*****************************************************************************************************
BOOL CTimeRangeDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_dEndtime = m_dStarttime;
	m_dEndtime += (m_nDataSec * 1000);
	// 選択データ時間範囲を設定
	m_cEditDataBegin.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_dStarttime.m_YY
		, m_dStarttime.m_MM
		, m_dStarttime.m_DD
		, m_dStarttime.m_hh
		, m_dStarttime.m_mm
		, m_dStarttime.m_ss);
	m_cEditDataEnd.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_dEndtime.m_YY
		, m_dEndtime.m_MM
		, m_dEndtime.m_DD
		, m_dEndtime.m_hh
		, m_dEndtime.m_mm
		, m_dEndtime.m_ss);

	if (! m_bDspFlag) {
		m_dDspFrom = m_dStarttime;
		m_dDspTo = m_dEndtime;
	}
	m_Edit_s_yy.Format("%04d", m_dDspFrom.m_YY);
	m_Edit_s_mm.Format("%02d", m_dDspFrom.m_MM);
	m_Edit_s_dd.Format("%02d", m_dDspFrom.m_DD);
	m_Edit_s_h.Format("%02d", m_dDspFrom.m_hh);
	m_Edit_s_m.Format("%02d", m_dDspFrom.m_mm);
	m_Edit_s_s.Format("%02d", m_dDspFrom.m_ss);
	m_Edit_e_yy.Format("%04d", m_dDspTo.m_YY);
	m_Edit_e_mm.Format("%02d", m_dDspTo.m_MM);
	m_Edit_e_dd.Format("%02d", m_dDspTo.m_DD);
	m_Edit_e_h.Format("%02d", m_dDspTo.m_hh);
	m_Edit_e_m.Format("%02d", m_dDspTo.m_mm);
	m_Edit_e_s.Format("%02d", m_dDspTo.m_ss);

	// 表示時間範囲を数字のみ入力可に設定
	m_Edit_s_yy.SetValidChar(VC_NUM);
	m_Edit_s_mm.SetValidChar(VC_NUM);
	m_Edit_s_dd.SetValidChar(VC_NUM);
	m_Edit_s_h.SetValidChar(VC_NUM);
	m_Edit_s_m.SetValidChar(VC_NUM);
	m_Edit_s_s.SetValidChar(VC_NUM);
	m_Edit_e_yy.SetValidChar(VC_NUM);
	m_Edit_e_mm.SetValidChar(VC_NUM);
	m_Edit_e_dd.SetValidChar(VC_NUM);
	m_Edit_e_h.SetValidChar(VC_NUM);
	m_Edit_e_m.SetValidChar(VC_NUM);
	m_Edit_e_s.SetValidChar(VC_NUM);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);
	CString sTooltip;
	sTooltip.Format("指定した時間範囲の%sを表示します。", m_sKind);
	m_tooltip.AddTool(GetDlgItem(IDOK), sTooltip);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CTimeRangeDlg::ConvertSecondToHMS
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		秒数をHHMMSS形式の文字列に変換
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSecond			[I] 秒数
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		変換した文字列
//*****************************************************************************************************
CString CTimeRangeDlg::ConvertSecondToHMS(int nSecond)
{
	int nHour = nSecond / (60 * 60);
	nSecond -= nHour * (60 * 60);
	int nMinute = nSecond / 60;
	nSecond -= nMinute * 60;

	CString sHMS;
	sHMS.Format("%02d%02d%02d", nHour, nMinute, nSecond);

	return sHMS;
}

//*****************************************************************************************************
//  1. 関数名
//		CTimeRangeDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		表示ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CTimeRangeDlg::OnOK()
{
	// 時間の正当性チェック
	if (!CheckInputData()) {
		return;
	}

	CDialog::OnOK();
}

//*****************************************************************************************************
//  1. 関数名
//		CTimeRangeDlg::CheckInputData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間の正当性チェック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CTimeRangeDlg::CheckInputData()
{
	int	yy, mm, dd, h, m, s;
	CString str;
	long Start_msec, End_msec;

	UpdateData(TRUE);

	// 時間範囲取得
	yy = m_Edit_s_yy;
	mm = m_Edit_s_mm;
	dd = m_Edit_s_dd;
	h = m_Edit_s_h;
	m = m_Edit_s_m;
	s = m_Edit_s_s;
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("開始時間：月に正しい値を入力してください。");
		return FALSE;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("開始時間：日に正しい値を入力してください。");
		return FALSE;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("開始時間：時に正しい値を入力してください。");
		return FALSE;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("開始時間：分に正しい値を入力してください。");
		return FALSE;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("開始時間：秒に正しい値を入力してください。");
		return FALSE;
	}
	m_dInStarttime.SetTime(yy, mm, dd, h, m, s);

	yy = m_Edit_e_yy;
	mm = m_Edit_e_mm;
	dd = m_Edit_e_dd;
	h = m_Edit_e_h;
	m = m_Edit_e_m;
	s = m_Edit_e_s;
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("終了時間：月に正しい値を入力してください。");
		return FALSE;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("終了時間：日に正しい値を入力してください。");
		return FALSE;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("終了時間：時に正しい値を入力してください。");
		return FALSE;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("終了時間：分に正しい値を入力してください。");
		return FALSE;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("終了時間：秒に正しい値を入力してください。");
		return FALSE;
	}
	m_dInEndtime.SetTime(yy, mm, dd, h, m, s);

	Start_msec = m_dInStarttime - m_dStarttime;
	End_msec = m_dInEndtime - m_dStarttime;
	// 開始時間より前に設定
	if (Start_msec < 0) {
		CGeneral::Alert("開始時間：開始時間より前の設定はできません。");
		return FALSE;
	}
	if (End_msec <= 0) {
		CGeneral::Alert("終了時間：開始時間以前の設定はできません。");
		return FALSE;
	}

	Start_msec = m_dStarttime - m_dEndtime;
	End_msec = m_dInEndtime - m_dEndtime;
	// 終了時間より後に設定
	if (Start_msec >= 0) {
		CGeneral::Alert("開始時間：終了時間より後の設定はできません。");
		return FALSE;
	}
	if (End_msec > 0) {
		CGeneral::Alert("終了時間：終了時間より後の設定はできません。");
		return FALSE;
	}
	long sa = m_dInEndtime - m_dInStarttime;
	if (sa < 0) {
		CGeneral::Alert("終了時間：開始時間より前の設定はできません。");
		return FALSE;
	}

	// 秒数に変換
	CTime oTime1(m_dStarttime.m_YY, m_dStarttime.m_MM, m_dStarttime.m_DD, 0, 0, 0);
	CTime oTime2(m_dInStarttime.m_YY, m_dInStarttime.m_MM, m_dInStarttime.m_DD, m_dInStarttime.m_hh, m_dInStarttime.m_mm, m_dInStarttime.m_ss);
	CTimeSpan oTimeSpan = oTime2 - oTime1;
	m_nDispBegin = (int)oTimeSpan.GetTotalSeconds();
	m_nDispEnd = m_nDispBegin + (sa / 1000);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CTimeRangeDlg::PreTranslateMessage
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
BOOL CTimeRangeDlg::PreTranslateMessage(MSG* pMsg)
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
