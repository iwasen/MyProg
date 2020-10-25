// DlgGpsLogger.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgGpsLogger.h"
#define _USE_MATH_DEFINES
#include <math.h>

// CDlgGpsLogger �_�C�A���O

IMPLEMENT_DYNAMIC(CDlgGpsLogger, CDlgCommon)

CDlgGpsLogger::CDlgGpsLogger(CWnd* pParent /*=NULL*/)
	: CDlgCommon(CDlgGpsLogger::IDD, pParent)
{
	m_bUpdate = FALSE;
}

void CDlgGpsLogger::DoDataExchange(CDataExchange* pDX)
{
	CDlgCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_STATIC_GPS_LOGGER, m_cStaticGpsLogger);
	DDX_Control(pDX, IDC_COMBO_GPS_LOGGER, m_cComboGpsLogger);
	DDX_Control(pDX, IDC_EDIT_DATE, m_cEditDate);
}


BEGIN_MESSAGE_MAP(CDlgGpsLogger, CDlgCommon)
	ON_CBN_SELCHANGE(IDC_COMBO_GPS_LOGGER, &CDlgGpsLogger::OnCbnSelchangeComboGpsLogger)
	ON_WM_DROPFILES()
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CDlgGpsLogger::OnBnClickedButtonDelete)
	ON_BN_CLICKED(IDC_BUTTON_PHOTO_LOCATION, &CDlgGpsLogger::OnBnClickedButtonPhotoLocation)
END_MESSAGE_MAP()


// CDlgGpsLogger ���b�Z�[�W �n���h��

BOOL CDlgGpsLogger::OnInitDialog()
{
	CDlgCommon::OnInitDialog();

	m_cEditDate = m_oDate.Format("%Y/%m/%d");
	DispData();

	DragAcceptFiles(TRUE);

	return TRUE;
}

void CDlgGpsLogger::DispData()
{
	CString sSql;
	CDbResult oDbResult;

	sSql.Format("SELECT gl_seq_no,gl_file_name FROM t_gps_logger WHERE gl_date=%s ORDER BY gl_seq_no", m_pDataBase->SqlDate(m_oDate));
	if (m_pDataBase->Select(sSql, oDbResult)) {
		m_cComboGpsLogger.ResetContent();
		int nRec = oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			m_cComboGpsLogger.SetItemData(m_cComboGpsLogger.AddString(oDbResult.GetTextValue(i, "gl_file_name")), oDbResult.GetIntValue(i, "gl_seq_no"));
		}
		if (nRec != 0)
			m_cComboGpsLogger.SetCurSel(0);
		OnCbnSelchangeComboGpsLogger();
	}
}

void CDlgGpsLogger::OnCbnSelchangeComboGpsLogger()
{
	int nIndex = m_cComboGpsLogger.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboGpsLogger.GetItemData(nIndex);

		CString sSql;
		CDbResult oDbResult;

		sSql.Format("SELECT gl_point_data,gl_point_num FROM t_gps_logger WHERE gl_seq_no=%d", nSeqNo);
		if (m_pDataBase->Select(sSql, oDbResult)) {
			if (oDbResult.GetCount() != 0) {
				CDbByteData oCDbByteData;
				oDbResult.GetByteaValue(0, "gl_point_data", oCDbByteData);
				CGpsData oGpsData;
				oGpsData.ReadGpsData(oCDbByteData, oDbResult.GetIntValue(0, "gl_point_num"));
				m_cStaticGpsLogger.DispMap(0, 0, &oGpsData.m_oArrayGpsData);
			}
		}
	} else
		m_cStaticGpsLogger.Clear();
}

void CDlgGpsLogger::OnDropFiles(HDROP hDropInfo)
{
	CStringArray aFileName;
	CString sSql;
	CDbResult oDbResult;

	// �h���b�v���ꂽ�t�@�C�������擾
	UINT iCount = ::DragQueryFile(hDropInfo, 0xFFFFFFFF, NULL, 0);
	char filepath[MAX_PATH + 1];
	for (UINT i = 0; i < iCount; i++) {
		// �t�@�C���̐�΃p�X���擾
		::DragQueryFile(hDropInfo, i, filepath, MAX_PATH + 1);

		LPCTSTR p = ::PathFindExtension(filepath);
		if (_stricmp(p, ".tk2") == 0 || _stricmp(p, ".gpx") == 0)
			aFileName.Add(filepath);
	}

	// �V�X�e�����m�ۂ��������������
	::DragFinish(hDropInfo);

	CGpsData oGpsData;
	CDbPoint oGpsPoint;
	double fLat, fLon;
	int nDataSize;
	int nPointNum;
	CString sLoggerName;
	CTime oStartTime;
	CTime oEndTime;

	for (int i = 0; i < aFileName.GetSize(); i++) {
		BYTE *pData = CGpsData::ReadGpsFile(aFileName[i], nDataSize, nPointNum, sLoggerName, oStartTime, oEndTime);

		CString sFileName = ::PathFindFileName(aFileName[i]);

		sSql.Format("INSERT INTO t_gps_logger (gl_date,gl_point_data,gl_file_name,gl_point_num,gl_logger_name,gl_start_time,gl_end_time) VALUES (%s,%s,%s,%d,%s,%s,%s)",
				m_pDataBase->SqlDate(oStartTime),
				m_pDataBase->SqlByteA(pData, nDataSize),
				m_pDataBase->SqlText(sFileName),
				nPointNum,
				m_pDataBase->SqlText(sLoggerName),
				m_pDataBase->SqlTimestamp(oStartTime),
				m_pDataBase->SqlTimestamp(oEndTime));
		m_pDataBase->Exec(sSql);
		m_bUpdate = TRUE;

		oGpsData.ReadGpsData(pData, nPointNum);
		sSql.Format("SELECT ph_seq_no,ph_datetime FROM t_photo WHERE ph_date=%s AND ph_location IS NULL", m_pDataBase->SqlDate(oStartTime));
		if (m_pDataBase->Select(sSql, oDbResult)) {
			int nRec = oDbResult.GetCount();
			for (int i = 0; i < nRec; i++) {
				if (oGpsData.FindGpsPoint(oDbResult.GetDateTimeValue(i, "ph_datetime"), fLat, fLon)) {
					oGpsPoint.SetData(fLat, fLon);
					sSql.Format("UPDATE t_photo SET ph_location=%s,ph_location_flag=1 WHERE ph_seq_no=%d", m_pDataBase->SqlPoint(oGpsPoint), oDbResult.GetIntValue(i, "ph_seq_no"));
					m_pDataBase->Exec(sSql);
				}
			}
		}

		sSql = "SELECT pl_seq_no,pl_datetime FROM t_photo_location";
		if (m_pDataBase->Select(sSql, oDbResult)) {
			int nRec = oDbResult.GetCount();
			for (int i = 0; i < nRec; i++) {
				if (oGpsData.FindGpsPoint(oDbResult.GetDateTimeValue(i, "pl_datetime"), fLat, fLon)) {
					oGpsPoint.SetData(fLat, fLon);
					sSql.Format("UPDATE t_photo SET ph_location=%s,ph_location_flag=1 WHERE ph_seq_no=%d", m_pDataBase->SqlPoint(oGpsPoint), oDbResult.GetIntValue(i, "pl_seq_no"));
					m_pDataBase->Exec(sSql);

					sSql.Format("DELETE FROM t_photo_location WHERE pl_seq_no=%d", oDbResult.GetIntValue(i, "pl_seq_no"));
					m_pDataBase->Exec(sSql);
				}
			}
		}

		delete [] pData;
	}

	DispData();
}

void CDlgGpsLogger::OnCancel()
{
	EndDialog(m_bUpdate ? IDOK : IDCANCEL);
}

void CDlgGpsLogger::OnBnClickedButtonDelete()
{
	int nIndex = m_cComboGpsLogger.GetCurSel();
	if (nIndex == CB_ERR)
		return;

	if (::AfxMessageBox("�폜���܂����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	CString sSql;
	sSql.Format("DELETE FROM t_gps_logger WHERE gl_seq_no=%d", m_cComboGpsLogger.GetItemData(nIndex));
	m_pDataBase->Exec(sSql);
	m_bUpdate = TRUE;

	DispData();
}

double GetDistance(double f1, double g1, double f2, double g2)
{
	double fr1,fr2;				// �Q�n�_�̈ܓx(rad)
	double gr1,gr2;				// �Q�n�_�̌o�x�i���o��j(rad)
	double h1,h2;				// �W��(m)
	double a = 6378136.0;		// �ԓ����a(m)
	double e2 = 0.006694470;	// �n���̗��S���̎���
	double x1,y1,z1,x2,y2,z2;	// �Q�n�_�̒������W�l(m)
	double r;					// �Q�n�_�Ԃ̒�����(m)
	double s;					// �Q�n�_�Ԃ̒n�\�ʋ���(m)
	double w;					// �Q�n�_�Ԃ̔��˒��p(��) (���S�p�̂P�^�Q)
	double wr;					// �Q�n�_�Ԃ̔��˒��p(rad)
	double rad;					// �x�����W�A���ϊ��W��
	double N1,N2;				// �ܓx�␳�����n���̔��a(m)

	rad = M_PI / 180.0;
	h1 = h2 = 0.0;				// �����ł́A�W���𖳎�
	if (g1 < 0) g1 = 360.0 + g1;
	fr1 = f1 * rad; gr1 = g1 * rad;
	if (g2 < 0) g2 = 360.0 + g2;
	fr2 = f2 * rad; gr2 = g2 * rad;

	N1 = a / (sqrt(1.0 - e2 * sin(fr1) * sin(fr1)));
	x1 = (N1 + h1) * cos(fr1) * cos(gr1);
	y1 = (N1 + h1) * cos(fr1) * sin(gr1);
	z1 = (N1 * (1.0 - e2) + h1) * sin(fr1);

	N2 = a / (sqrt(1.0 - e2 * sin(fr2) * sin(fr2)));
	x2 = (N2 + h2) * cos(fr2) * cos(gr2);
	y2 = (N2 + h2) * cos(fr2) * sin(gr2);
	z2 = (N2 * (1.0 - e2) + h2) * sin(fr2);

	r = sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2) + (z1 - z2) * (z1 - z2));	// ������
	wr = asin(r / 2 / a);		// ���˒��p(rad)
	w = wr / rad;				// ���˒��p(��)
	s = a * 2 * wr;				// �n�\�ʋ���

	return s;
}

void CDlgGpsLogger::OnBnClickedButtonPhotoLocation()
{
	if (AfxMessageBox("�ʐ^�ʒu��ݒ肵�����܂����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	int nIndex = m_cComboGpsLogger.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboGpsLogger.GetItemData(nIndex);

		CString sSql;
		CDbResult oDbResult;
		CDbPoint oGpsPoint;
		double fLat, fLon;

		sSql.Format("SELECT gl_date,gl_point_data,gl_point_num FROM t_gps_logger WHERE gl_seq_no=%d", nSeqNo);
		if (m_pDataBase->Select(sSql, oDbResult)) {
			if (oDbResult.GetCount() != 0) {
				CDbByteData oCDbByteData;
				oDbResult.GetByteaValue(0, "gl_point_data", oCDbByteData);
				CGpsData oGpsData;
				oGpsData.ReadGpsData(oCDbByteData, oDbResult.GetIntValue(0, "gl_point_num"));
				sSql.Format("SELECT ph_seq_no,ph_datetime FROM t_photo WHERE ph_date=%s", m_pDataBase->SqlDate(oDbResult.GetDateValue(0, "gl_date")));
				if (m_pDataBase->Select(sSql, oDbResult)) {
					int nRec = oDbResult.GetCount();
					for (int i = 0; i < nRec; i++) {
						if (oGpsData.FindGpsPoint(oDbResult.GetDateTimeValue(i, "ph_datetime"), fLat, fLon)) {
							oGpsPoint.SetData(fLat, fLon);
							sSql.Format("UPDATE t_photo SET ph_location=%s,ph_location_flag=1 WHERE ph_seq_no=%d", m_pDataBase->SqlPoint(oGpsPoint), oDbResult.GetIntValue(i, "ph_seq_no"));
							m_pDataBase->Exec(sSql);
						}
					}
				}
			}
		}
	}
}
