// DlgWalking.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgWalking.h"


// CDlgWalking ダイアログ

IMPLEMENT_DYNAMIC(CDlgWalking, CDlgCommon)

CDlgWalking::CDlgWalking(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgWalking::IDD, pParent)
{

}

void CDlgWalking::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DATE, m_cEditDate);
	DDX_Control(pDX, IDC_EDIT_WALKING_STEP, m_cEditWalkingStep);
	DDX_Control(pDX, IDC_EDIT_WALKING_CALORIE, m_cEditWalkingCalorie);
	DDX_Control(pDX, IDC_EDIT_WALKING_FAT, m_cEditWalkingFat);
	DDX_Control(pDX, IDC_EDIT_WALKING_DISTANCE, m_cEditWalkingDistance);
	DDX_Control(pDX, IDC_EDIT_WALKING_TIME, m_cEditWalkingTime);
	DDX_Control(pDX, IDC_EDIT_WALKING_SPEED, m_cEditWalkingSpeed);
}


BEGIN_MESSAGE_MAP(CDlgWalking, CDlgCommon)
	ON_BN_CLICKED(IDC_BUTTON_ALL_ZERO, &CDlgWalking::OnBnClickedButtonAllZero)
END_MESSAGE_MAP()


// CDlgWalking メッセージ ハンドラ

BOOL CDlgWalking::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	m_cEditDate = m_oDate.Format("%Y/%m/%d");

	CString sSql;
	CDbResult oDbResult;
	sSql.Format("SELECT wk_step,wk_calorie,wk_fat,wk_distance,wk_time,wk_speed FROM t_walking WHERE wk_date=%s", m_pDataBase->SqlDate(m_oDate));
	if (m_pDataBase->Select(sSql, oDbResult)) {
		if (oDbResult.GetCount() > 0) {
			m_cEditWalkingStep.SetWindowText(oDbResult.GetTextValue(0, "wk_step"));
			m_cEditWalkingCalorie.SetWindowText(oDbResult.GetTextValue(0, "wk_calorie"));
			m_cEditWalkingFat.SetWindowText(oDbResult.GetTextValue(0, "wk_fat"));
			m_cEditWalkingDistance.SetWindowText(oDbResult.GetTextValue(0, "wk_distance"));
			m_cEditWalkingTime.SetWindowText(oDbResult.GetTextValue(0, "wk_time"));
			m_cEditWalkingSpeed.SetWindowText(oDbResult.GetTextValue(0, "wk_speed"));
/* Peb用
			m_cEditWalkingStep.SetWindowText(oDbResult.GetTextValue(0, "wk_step"));
			m_cEditWalkingCalorie.SetWindowText(RemovePoint(oDbResult.GetTextValue(0, "wk_calorie")));
			m_cEditWalkingFat.SetWindowText(RemovePoint(oDbResult.GetTextValue(0, "wk_fat")));
			m_cEditWalkingDistance.SetWindowText(RemovePoint(oDbResult.GetTextValue(0, "wk_distance")));
			m_cEditWalkingTime.SetWindowText(oDbResult.GetTextValue(0, "wk_time"));
			m_cEditWalkingSpeed.SetWindowText(RemovePoint(oDbResult.GetTextValue(0, "wk_speed")));
*/
		}
	}

	m_cEditWalkingStep.SetValidChar(VC_NUM);
	m_cEditWalkingCalorie.SetValidChar(VC_NUM);
	m_cEditWalkingFat.SetValidChar(VC_NUM);
	m_cEditWalkingDistance.SetValidChar(VC_NUM);
	m_cEditWalkingTime.SetValidChar(VC_NUM);
	m_cEditWalkingSpeed.SetValidChar(VC_NUM);

	return TRUE;
}

void CDlgWalking::OnOK()
{
	CString sSql;

	sSql.Format("DELETE FROM t_walking WHERE wk_date=%s", m_pDataBase->SqlDate(m_oDate));
	m_pDataBase->Exec(sSql);

	sSql.Format("INSERT INTO t_walking (wk_date,wk_step,wk_calorie,wk_fat,wk_distance,wk_time,wk_speed) VALUES (%s,%s,%s,%s,%s,%s,%s)",
			m_pDataBase->SqlDate(m_oDate),
			m_pDataBase->SqlNumber((CString)m_cEditWalkingStep),
			m_pDataBase->SqlNumber((CString)m_cEditWalkingCalorie),
			m_pDataBase->SqlNumber((CString)m_cEditWalkingFat),
			m_pDataBase->SqlNumber((CString)m_cEditWalkingDistance),
			m_pDataBase->SqlNumber((CString)m_cEditWalkingTime),
			m_pDataBase->SqlNumber((CString)m_cEditWalkingSpeed));
/* Peb用
			m_pDataBase->SqlNumber((CString)m_cEditWalkingStep),
			m_pDataBase->SqlNumber(SetPoint(m_cEditWalkingCalorie, 1)),
			m_pDataBase->SqlNumber(SetPoint(m_cEditWalkingFat, 1)),
			m_pDataBase->SqlNumber(SetPoint(m_cEditWalkingDistance, 2)),
			m_pDataBase->SqlNumber((CString)m_cEditWalkingTime),
			m_pDataBase->SqlNumber(SetPoint(m_cEditWalkingSpeed, 2)));
*/
	m_pDataBase->Exec(sSql);

	EndDialog(IDOK);
}

void CDlgWalking::OnBnClickedButtonAllZero()
{
	m_cEditWalkingStep = "0";
	m_cEditWalkingCalorie = "0";
	m_cEditWalkingFat = "0";
	m_cEditWalkingDistance = "0";
	m_cEditWalkingTime = "0";
	m_cEditWalkingSpeed = "0";
}

CString CDlgWalking::SetPoint(CString sNumber, int nPoint)
{
	int len = sNumber.GetLength();
	if (len >= nPoint)
		return sNumber.Left(len - nPoint) + "." + sNumber.Right(nPoint);
	else
		return "";
}

CString CDlgWalking::RemovePoint(CString sNumber)
{
	CString sTemp = sNumber;
	sTemp.Remove('.');
	return sTemp;
}
