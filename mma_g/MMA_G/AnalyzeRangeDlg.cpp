// AnalyzeRangeDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "AnalyzeRangeDlg.h"
#include "General.h"


// CAnalyzeRangeDlg ダイアログ

IMPLEMENT_DYNAMIC(CAnalyzeRangeDlg, CDialog)

CAnalyzeRangeDlg::CAnalyzeRangeDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CAnalyzeRangeDlg::IDD, pParent)
{

}

CAnalyzeRangeDlg::~CAnalyzeRangeDlg()
{
}

void CAnalyzeRangeDlg::DoDataExchange(CDataExchange* pDX)
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


BEGIN_MESSAGE_MAP(CAnalyzeRangeDlg, CDialog)
END_MESSAGE_MAP()


// CAnalyzeRangeDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CAnalyzeRangeDlg::OnInitDialog
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
BOOL CAnalyzeRangeDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// データ時間範囲を表示
	m_cEditDataBegin.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_dDataBegin.m_YY
		, m_dDataBegin.m_MM
		, m_dDataBegin.m_DD
		, m_dDataBegin.m_hh
		, m_dDataBegin.m_mm
		, m_dDataBegin.m_ss);
	m_cEditDataEnd.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_dDataEnd.m_YY
		, m_dDataEnd.m_MM
		, m_dDataEnd.m_DD
		, m_dDataEnd.m_hh
		, m_dDataEnd.m_mm
		, m_dDataEnd.m_ss);

	m_Edit_s_yy = m_dDataBegin.m_YY;
	m_Edit_s_mm = m_dDataBegin.m_MM;
	m_Edit_s_dd = m_dDataBegin.m_DD;
	m_Edit_s_h = m_dDataBegin.m_hh;
	m_Edit_s_m = m_dDataBegin.m_mm;
	m_Edit_s_s = m_dDataBegin.m_ss;
	m_Edit_e_yy = m_dDataEnd.m_YY;
	m_Edit_e_mm = m_dDataEnd.m_MM;
	m_Edit_e_dd = m_dDataEnd.m_DD;
	m_Edit_e_h = m_dDataEnd.m_hh;
	m_Edit_e_m = m_dDataEnd.m_mm;
	m_Edit_e_s = m_dDataEnd.m_ss;

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

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAnalyzeRangeDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		OKボタン押下処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAnalyzeRangeDlg::OnOK()
{
	// 入力データチェック
	if (!CheckInputData())
		return;

	CDialog::OnOK();
}

//*****************************************************************************************************
//  1. 関数名
//		CAnalyzeRangeDlg::CheckInputData
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
BOOL CAnalyzeRangeDlg::CheckInputData()
{
	int	yy, mm, dd, h, m, s;
	CString str;
	long Start_msec, End_msec;

	if (m_Edit_s_yy.IsEmpty()) {
		CGeneral::Alert("開始日時：年に値を入力してください。");
		m_Edit_s_yy.SetFocus();
		return FALSE;
	}

	if (m_Edit_s_mm.IsEmpty()) {
		CGeneral::Alert("開始日時：月に値を入力してください。");
		m_Edit_s_mm.SetFocus();
		return FALSE;
	}

	if (m_Edit_s_dd.IsEmpty()) {
		CGeneral::Alert("開始日時：日に値を入力してください。");
		m_Edit_s_dd.SetFocus();
		return FALSE;
	}

	if (m_Edit_s_h.IsEmpty()) {
		CGeneral::Alert("開始日時：時に値を入力してください。");
		m_Edit_s_h.SetFocus();
		return FALSE;
	}

	if (m_Edit_s_m.IsEmpty()) {
		CGeneral::Alert("開始日時：分に値を入力してください。");
		m_Edit_s_m.SetFocus();
		return FALSE;
	}

	if (m_Edit_s_s.IsEmpty()) {
		CGeneral::Alert("開始日時：秒に値を入力してください。");
		m_Edit_s_s.SetFocus();
		return FALSE;
	}

	if (m_Edit_e_yy.IsEmpty()) {
		CGeneral::Alert("終了日時：年に値を入力してください。");
		m_Edit_e_yy.SetFocus();
		return FALSE;
	}

	if (m_Edit_e_mm.IsEmpty()) {
		CGeneral::Alert("終了日時：月に値を入力してください。");
		m_Edit_e_mm.SetFocus();
		return FALSE;
	}

	if (m_Edit_e_dd.IsEmpty()) {
		CGeneral::Alert("終了日時：日に値を入力してください。");
		m_Edit_e_dd.SetFocus();
		return FALSE;
	}

	if (m_Edit_e_h.IsEmpty()) {
		CGeneral::Alert("終了日時：時に値を入力してください。");
		m_Edit_e_h.SetFocus();
		return FALSE;
	}

	if (m_Edit_e_m.IsEmpty()) {
		CGeneral::Alert("終了日時：分に値を入力してください。");
		m_Edit_e_m.SetFocus();
		return FALSE;
	}

	if (m_Edit_e_s.IsEmpty()) {
		CGeneral::Alert("終了日時：秒に値を入力してください。");
		m_Edit_e_s.SetFocus();
		return FALSE;
	}

	// 時間範囲取得
	yy = m_Edit_s_yy;
	mm = m_Edit_s_mm;
	dd = m_Edit_s_dd;
	h = m_Edit_s_h;
	m = m_Edit_s_m;
	s = m_Edit_s_s;
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("開始日時：月に正しい値を入力してください。");
		m_Edit_s_mm.SetFocus();
		return FALSE;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("開始日時：日に正しい値を入力してください。");
		m_Edit_s_dd.SetFocus();
		return FALSE;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("開始日時：時に正しい値を入力してください。");
		m_Edit_s_h.SetFocus();
		return FALSE;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("開始日時：分に正しい値を入力してください。");
		m_Edit_s_m.SetFocus();
		return FALSE;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("開始日時：秒に正しい値を入力してください。");
		m_Edit_s_s.SetFocus();
		return FALSE;
	}
	m_dSelectBegin.SetTime(yy, mm, dd, h, m, s);

	yy = m_Edit_e_yy;
	mm = m_Edit_e_mm;
	dd = m_Edit_e_dd;
	h = m_Edit_e_h;
	m = m_Edit_e_m;
	s = m_Edit_e_s;
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("終了日時：月に正しい値を入力してください。");
		m_Edit_e_mm.SetFocus();
		return FALSE;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("終了日時：日に正しい値を入力してください。");
		m_Edit_e_dd.SetFocus();
		return FALSE;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("終了日時：時に正しい値を入力してください。");
		m_Edit_e_h.SetFocus();
		return FALSE;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("終了日時：分に正しい値を入力してください。");
		m_Edit_e_m.SetFocus();
		return FALSE;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("終了日時：秒に正しい値を入力してください。");
		m_Edit_e_s.SetFocus();
		return FALSE;
	}
	m_dSelectEnd.SetTime(yy, mm, dd, h, m, s);

	Start_msec = m_dSelectBegin - m_dDataBegin;
	End_msec = m_dSelectEnd - m_dDataBegin;
	// 開始日時より前に設定
	if (Start_msec < 0) {
		CGeneral::Alert("開始日時：データ時間範囲の開始日時より前の設定はできません。");
		return FALSE;
	}
	if (End_msec <= 0) {
		CGeneral::Alert("終了日時：データ時間範囲の開始日時以前の設定はできません。");
		return FALSE;
	}

	Start_msec = m_dSelectBegin - m_dDataEnd;
	End_msec = m_dSelectEnd - m_dDataEnd;
	// 終了日時より後に設定
	if (Start_msec >= 0) {
		CGeneral::Alert("開始日時：データ時間範囲の終了日時より後の設定はできません。");
		return FALSE;
	}
	if (End_msec > 0) {
		CGeneral::Alert("終了日時：データ時間範囲の終了日時より後の設定はできません。");
		return FALSE;
	}
	long sa = m_dSelectEnd - m_dSelectBegin;
	if (sa < 0) {
		CGeneral::Alert("終了日時：開始日時より前の設定はできません。");
		return FALSE;
	}

	return TRUE;
}
