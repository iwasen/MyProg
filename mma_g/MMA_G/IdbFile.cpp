#include "stdafx.h"
#include "io.h"
#include "GlobalData.h"
#include "IdbFile.h"
#include "General.h"
#include "MMA_G.h"

static const char *xml_mma[] = {
	"<?xml version=\"1.0\" encoding=\"US-ASCII\"?>\n",
	"<mma_accel>\n",
	"<SensorID>%04X</SensorID>\n",
	"<TimeZero>%s</TimeZero>\n",
	"<Gain>%d</Gain>\n",
	"<SampleRate>%.2lf</SampleRate>\n",
	"<CutoffFreq>%d</CutoffFreq>\n",
	"<GData format=\"binary 64 bit IEEE double little endian\" file=\"%s\"/>\n",
	"<BiasCoeff x=\"%lf\" y=\"%lf\" z=\"%lf\"/>\n",
//	"<SensorCoodinateSystem name=\"%04X\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\"/>\n",
//	"<DataCoordinateSystem name=\"%04X\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\"/>\n",
// 2008/4/25 modified by yG for rpw addition ->
//	"<SensorCoordinateSystem name=\"%04X\" r=\"%2.1lf\" p=\"%2.1lf\" w=\"%2.1lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
//	"<DataCoordinateSystem name=\"%04X\" r=\"%2.1lf\" p=\"%2.1lf\" w=\"%2.1lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
	"<SensorCoordinateSystem name=\"%04X\" r=\"%lf\" p=\"%lf\" w=\"%lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
	"<DataCoordinateSystem name=\"%04X\" r=\"%lf\" p=\"%lf\" w=\"%lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
// <- 2008/4/25 modified by yG for rpw addition
	"<DataQualityMeasure>%s</DataQualityMeasure>\n",
	"<ISSConfiguration>%s</ISSConfiguration>\n",
	"<ScaleFactor x=\"%lf\" y=\"%lf\" z=\"%lf\"/>\n",
	"</mma_accel>\n"
};

static const char *xml_mme[] = {
	"<?xml version=\"1.0\" encoding=\"US-ASCII\"?>\n",
	"<mme_accel>\n",
	"<SensorID>%d</SensorID>\n",
	"<TimeZero>%s</TimeZero>\n",
	"<SampleRate>%g</SampleRate>\n",
	"<GData format=\"binary 64 bit IEEE double little endian\" file=\"%s\"/>\n",
	"<SensorCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"<DataCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"</mme_accel>\n"
};

static const char *xml_asu[] = {
	"<?xml version=\"1.0\" encoding=\"US-ASCII\"?>\n",
	"<asu_accel>\n",
	"<SensorID>%d</SensorID>\n",
	"<TimeZero>%s</TimeZero>\n",
	"<SampleRate>%g</SampleRate>\n",
	"<GData format=\"binary 64 bit IEEE double little endian\" file=\"%s\"/>\n",
	"<SensorCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"<DataCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"</asu_accel>\n"
};

CIdbFile::CIdbFile(void)
: m_path(_T(""))
, m_scalefactor(0)
, m_issconfig(_T(""))
, m_dataquality(_T(""))
, m_datacosys(_T(""))
, m_sensorcosys(_T(""))
, m_biascoeff(0)
, m_cutoff(0)
, m_gain(0)
, m_timezero(_T(""))
, m_sensorid(0)
, m_rsuid(0)
, m_downlink(_T(""))
, m_period(_T(""))
, m_samplerate(0)
, m_comment(_T(""))
, m_sCategory(_T(""))
, m_sMeasurementKind(_T(""))
{
}

CIdbFile::~CIdbFile(void)
{
}

// 2009/07/07 Version 3.02 appended by m.hama ->
// XML�t�@�C����ǂݍ���
bool CIdbFile::GetXmlFileData(LPCSTR path)
{
	CStdioFile	fp;
	CFileException fileException;
	CString buf;

	m_XmlData.RemoveAll();

	if (! fp.Open(path, CFile::modeRead, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", path, fileException.m_cause );
		return false;
	}
	for (;;) {
		if (! fp.ReadString(buf))
			break;
		m_XmlData.Add(buf);
	}
	fp.Close();

	return true;
}

// XML�t�@�C����ύX����
bool CIdbFile::WriteXml2(LPCSTR name)
{
	CStdioFile	fp;
	CFileException fileException;
	CString fname, buf, str;
	int	cnt, i;

	fname.Format("%s\\%s.header", m_path, name);

	// ������XML�t�@�C���̓��e�����ׂēǂ݂���ł���
	if (! GetXmlFileData(fname))
		return false;

	// XML�t�@�C���̍s���擾
	cnt = (int)m_XmlData.GetSize();

	// XML�t�@�C���̍s�������[�v
	for (i = 0; i < cnt; i++) {
		// <GData format�s�H
		if (m_XmlData.GetAt(i).Mid(1, 12) == "GData format") {
			// �ۑ��p�X��
			str = m_path;
			str += "\\";
			str += name;
			buf.Format(xml_mma[i], str);
			m_XmlData.SetAt(i, buf);
		}
		// <SensorCoordinateSystem�s�H
		if (m_XmlData.GetAt(i).Mid(1, 22) == "SensorCoordinateSystem") {
			buf.Format(xml_mma[i], m_sensorid
				//, SensorInfo[m_SensorIdx].x
				//, SensorInfo[m_SensorIdx].y
				//, SensorInfo[m_SensorIdx].z
		// 2008/4/25 modified by yG for rpw addition ->
		//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
				, g_pSensorInfo[m_SensorIdx].roll
				, g_pSensorInfo[m_SensorIdx].pitch
				, g_pSensorInfo[m_SensorIdx].yaw
		// <- 2008/4/25 modified by yG for rpw addition
				, g_pSensorInfo[m_SensorIdx].x
				, g_pSensorInfo[m_SensorIdx].y
				, g_pSensorInfo[m_SensorIdx].z
				, m_sensorcosys
				, gLaunchTime);
				m_XmlData.SetAt(i, buf);
		}
		// <DataCoordinateSystem�s�H
		if (m_XmlData.GetAt(i).Mid(1, 20) == "DataCoordinateSystem") {
			buf.Format(xml_mma[i], m_sensorid
				//, SensorInfo[m_SensorIdx].x
				//, SensorInfo[m_SensorIdx].y
				//, SensorInfo[m_SensorIdx].z
		// 2008/4/25 modified by yG for rpw addition ->
		//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
				, g_pSensorInfo[m_SensorIdx].roll
				, g_pSensorInfo[m_SensorIdx].pitch
				, g_pSensorInfo[m_SensorIdx].yaw
		// <- 2008/4/25 modified by yG for rpw addition
				, g_pSensorInfo[m_SensorIdx].x
				, g_pSensorInfo[m_SensorIdx].y
				, g_pSensorInfo[m_SensorIdx].z
				, m_datacosys
				, gLaunchTime);
				m_XmlData.SetAt(i, buf);
		}
		// <DataQualityMeasure�s�H
		if (m_XmlData.GetAt(i).Mid(1, 18) == "DataQualityMeasure") {
			// Data Quality
			buf.Format(xml_mma[i], m_dataquality);
			m_XmlData.SetAt(i, buf);
		}
		// <ISSConfiguration�s�H
		if (m_XmlData.GetAt(i).Mid(1, 16) == "ISSConfiguration") {
			// ISS Config
			buf.Format(xml_mma[i], m_issconfig);
			m_XmlData.SetAt(i, buf);
		}
	}

	if (! fp.Open(fname, CFile::modeCreate | CFile::modeWrite, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", fname, fileException.m_cause );
		return false;
	}

	for (i = 0; i < cnt; i++) {
		fp.WriteString(m_XmlData.GetAt(i));
		if (m_XmlData.GetAt(i).Right(1) != "\n")
			fp.WriteString("\n");
	}
	fp.Close();

	return true;
}
// <- 2009/07/07 Version 3.02 appended by m.hama

// XML�t�@�C������������
bool CIdbFile::WriteXml(LPCSTR name, int nDataType)
{
	CStdioFile	fp;
	CFileException fileException;
	CString fname;

//	fname = m_path + "\\" + name + ".heder.xml";
	fname.Format("%s\\%s.header", m_path, name);
	if (! fp.Open(fname, CFile::modeCreate | CFile::modeWrite, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", fname, fileException.m_cause );
		return false;
	}

	switch (nDataType) {
	case DATA_TYPE_MMA:
		WriteXmlMma(fp, name);
		break;
	case DATA_TYPE_MME:
		WriteXmlMme(fp, name);
		break;
	case DATA_TYPE_ASU:
		WriteXmlAsu(fp, name);
		break;
	}

	fp.Close();

	return true;
}

// MMA��XML�t�@�C������������
void CIdbFile::WriteXmlMma(CStdioFile &fp, LPCSTR name)
{
	CString buf, str;
	int	cnt = 0;

	// �w�b�_
	fp.WriteString(xml_mma[cnt++]);
	fp.WriteString(xml_mma[cnt++]);

	// �Z���T�[ID
	buf.Format(xml_mma[cnt++], m_sensorid);
	fp.WriteString(buf);

	// �^�C���[��
	buf.Format(xml_mma[cnt++], m_timezeroXML);
	fp.WriteString(buf);

	// �Q�C��
	buf.Format(xml_mma[cnt++], m_gain);
	fp.WriteString(buf);

	// �T���v�����[�g
	buf.Format(xml_mma[cnt++], m_samplerate);
	fp.WriteString(buf);

	// �J�b�g�I�t���g��
	buf.Format(xml_mma[cnt++], m_cutoff);
	fp.WriteString(buf);

	// �ۑ��p�X��
	str = m_path;
	str += "\\";
	str += name;
//	str += ".dat";
	buf.Format(xml_mma[cnt++], str);
	fp.WriteString(buf);

	// Bias Co Eff (TBD)
	buf.Format(xml_mma[cnt++], g_BIAS_x, g_BIAS_y, g_BIAS_z);
	fp.WriteString(buf);

	// Sensor Co Sys
//	double a_drpqVaule = 0.0;
	buf.Format(xml_mma[cnt++], m_sensorid
		//, SensorInfo[m_SensorIdx].x
		//, SensorInfo[m_SensorIdx].y
		//, SensorInfo[m_SensorIdx].z
// 2008/4/25 modified by yG for rpw addition ->
//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
		, g_pSensorInfo[m_SensorIdx].roll
		, g_pSensorInfo[m_SensorIdx].pitch
		, g_pSensorInfo[m_SensorIdx].yaw
// <- 2008/4/25 modified by yG for rpw addition
		, g_pSensorInfo[m_SensorIdx].x
		, g_pSensorInfo[m_SensorIdx].y
		, g_pSensorInfo[m_SensorIdx].z
		, m_sensorcosys
		, gLaunchTime);
	fp.WriteString(buf);

	// Data Co Sys
	buf.Format(xml_mma[cnt++], m_sensorid
		//, SensorInfo[m_SensorIdx].x
		//, SensorInfo[m_SensorIdx].y
		//, SensorInfo[m_SensorIdx].z
// 2008/4/25 modified by yG for rpw addition ->
//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
		, g_pSensorInfo[m_SensorIdx].roll
		, g_pSensorInfo[m_SensorIdx].pitch
		, g_pSensorInfo[m_SensorIdx].yaw
// <- 2008/4/25 modified by yG for rpw addition
		, g_pSensorInfo[m_SensorIdx].x
		, g_pSensorInfo[m_SensorIdx].y
		, g_pSensorInfo[m_SensorIdx].z
		, m_datacosys
		, gLaunchTime);
	fp.WriteString(buf);

	// Data Quality
	buf.Format(xml_mma[cnt++], m_dataquality);
	fp.WriteString(buf);

	// ISS Config
	buf.Format(xml_mma[cnt++], m_issconfig);
	fp.WriteString(buf);

	// �X�P�[���t�@�N�^�[
	buf.Format(xml_mma[cnt++], g_SF_x, g_SF_y, g_SF_z);
	fp.WriteString(buf);

	// �g���[��
	fp.WriteString(xml_mma[cnt]);
}

// MME��XML�t�@�C������������
void CIdbFile::WriteXmlMme(CStdioFile &fp, LPCSTR name)
{
	CString buf, str;
	int	cnt = 0;

	// �w�b�_
	fp.WriteString(xml_mme[cnt++]);
	fp.WriteString(xml_mme[cnt++]);

	// �Z���T�[ID
	buf.Format(xml_mme[cnt++], m_sensorid);
	fp.WriteString(buf);

	// �^�C���[��
	buf.Format(xml_mme[cnt++], m_timezeroXML);
	fp.WriteString(buf);

	// �T���v�����[�g
	buf.Format(xml_mme[cnt++], m_samplerate);
	fp.WriteString(buf);

	// �ۑ��p�X��
	str = m_path;
	str += "\\";
	str += name;
	buf.Format(xml_mme[cnt++], str);
	fp.WriteString(buf);

	// Sensor Co Sys
	buf.Format(xml_mme[cnt++], m_sensorid
			, g_pSensorInfoMme[m_SensorIdx].roll
			, g_pSensorInfoMme[m_SensorIdx].pitch
			, g_pSensorInfoMme[m_SensorIdx].yaw
			, g_pSensorInfoMme[m_SensorIdx].x
			, g_pSensorInfoMme[m_SensorIdx].y
			, g_pSensorInfoMme[m_SensorIdx].z
			, m_sensorcosys, gLaunchTime);
	fp.WriteString(buf);

	// Data Co Sys
	buf.Format(xml_mme[cnt++], m_sensorid
			, g_pSensorInfoMme[m_SensorIdx].roll
			, g_pSensorInfoMme[m_SensorIdx].pitch
			, g_pSensorInfoMme[m_SensorIdx].yaw
			, g_pSensorInfoMme[m_SensorIdx].x
			, g_pSensorInfoMme[m_SensorIdx].y
			, g_pSensorInfoMme[m_SensorIdx].z
			, m_datacosys, gLaunchTime);
	fp.WriteString(buf);

	// �g���[��
	fp.WriteString(xml_mme[cnt]);
}

// ASU��XML�t�@�C������������
void CIdbFile::WriteXmlAsu(CStdioFile &fp, LPCSTR name)
{
	CString buf, str;
	int	cnt = 0;

	// �w�b�_
	fp.WriteString(xml_asu[cnt++]);
	fp.WriteString(xml_asu[cnt++]);

	// �Z���T�[ID
	buf.Format(xml_asu[cnt++], m_sensorid);
	fp.WriteString(buf);

	// �^�C���[��
	buf.Format(xml_asu[cnt++], m_timezeroXML);
	fp.WriteString(buf);

	// �T���v�����[�g
	buf.Format(xml_asu[cnt++], m_samplerate);
	fp.WriteString(buf);

	// �ۑ��p�X��
	str = m_path;
	str += "\\";
	str += name;
	buf.Format(xml_asu[cnt++], str);
	fp.WriteString(buf);

	// Sensor Co Sys
	buf.Format(xml_asu[cnt++], m_sensorid
			, g_pSensorInfoAsu[m_SensorIdx].roll
			, g_pSensorInfoAsu[m_SensorIdx].pitch
			, g_pSensorInfoAsu[m_SensorIdx].yaw
			, g_pSensorInfoAsu[m_SensorIdx].x
			, g_pSensorInfoAsu[m_SensorIdx].y
			, g_pSensorInfoAsu[m_SensorIdx].z
			, m_sensorcosys, gLaunchTime);
	fp.WriteString(buf);

	// Data Co Sys
	buf.Format(xml_asu[cnt++], m_sensorid
			, g_pSensorInfoAsu[m_SensorIdx].roll
			, g_pSensorInfoAsu[m_SensorIdx].pitch
			, g_pSensorInfoAsu[m_SensorIdx].yaw
			, g_pSensorInfoAsu[m_SensorIdx].x
			, g_pSensorInfoAsu[m_SensorIdx].y
			, g_pSensorInfoAsu[m_SensorIdx].z
			, m_datacosys, gLaunchTime);
	fp.WriteString(buf);

	// �g���[��
	fp.WriteString(xml_asu[cnt]);
}

// IDB�t�@�C������������
bool CIdbFile::Write(LPCSTR name)
{
	CStdioFile	fp;
	CFileException fileException;
	CString buf;

	m_sIdbPath = m_path + "\\" + name + ".idb";
	if (! fp.Open(m_sIdbPath, CFile::modeCreate | CFile::modeWrite, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", m_sIdbPath, fileException.m_cause );
		return false;
	}

	// �R�����g
	fp.WriteString("COMMENT=" + m_comment + "\n");
	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	fp.WriteString("STARTTIME=" + m_starttime + "\n");
	// �T���v�����[�g
	buf.Format("SAMPLERATE=%.2lf\n", m_samplerate);
	fp.WriteString(buf);
	// ����
	fp.WriteString("PERIOD=" + m_period + "\n");
	// �_�E�������N��
	fp.WriteString("DOWNLINK=" + m_downlink + "\n");
	// ���j�b�gID
	buf.Format("RSUID=%d\n", m_rsuid);
	fp.WriteString(buf);
	// �l�b�g���[�NID
	buf.Format("NETWORKID=%d\n", m_networkid);
	fp.WriteString(buf);
	// �Z���T�[ID
	buf.Format("SENSORID=%d\n", m_sensorid);
	fp.WriteString(buf);
	// �Z���T�[IDX
	buf.Format("SENSORIDX=%d\n", m_SensorIdx);
	fp.WriteString(buf);
	// �^�C���[��
	fp.WriteString("TIMEZERO=" + m_timezero + "\n");
	// �Q�C��
	buf.Format("GAIN=%d\n", m_gain);
	fp.WriteString(buf);
	// �J�b�g�I�t���g��
	buf.Format("CUTOFF=%d\n", m_cutoff);
	fp.WriteString(buf);
	// Bias Co Eff
	buf.Format("BIASCOEFF=%d\n", m_biascoeff);
	fp.WriteString(buf);
	// Sensor Co Sys
	fp.WriteString("SENSORCOSYS=" + m_sensorcosys + "\n");
	// Data Co Sys
	fp.WriteString("DATACOSYS=" + m_datacosys + "\n");
	// Data Quality
	fp.WriteString("DATAQUALITY=" + m_dataquality + "\n");
	// ISS Config
	fp.WriteString("ISSCONFIG=" + m_issconfig + "\n");
	// �X�P�[���t�@�N�^�[
	buf.Format("SCALEFACTOR=%d\n", m_scalefactor);
	fp.WriteString(buf);
	// �ۑ��p�X��
	fp.WriteString("PATH=" + m_path + "\n");
	// �J�e�S��
	fp.WriteString("CATEGORY=" + m_sCategory + "\n");
	// �v�����
	fp.WriteString("MEASUREMENT_KIND=" + m_sMeasurementKind + "\n");

	// �P���Ԗ��̃t�@�C�����ۑ��G���A
	int cnt = (int)m_File.GetSize();
	for (int i = 0; i < cnt; i++) {
		fp.WriteString("FILE=" + m_File.GetAt(i) + "\n");
	}

	fp.Close();

	return true;
}

// IDB�t�@�C����ǂݍ���
bool CIdbFile::Read(LPCSTR path)
{
	CString fname;

	// IDB�t�@�C�������擾����
	if (! GetFname(path, fname))
		return false;

	// IDB�t�@�C����ǂݍ���
	return Read2(fname);
}

// IDB�t�@�C����ǂݍ���
bool CIdbFile::Read2(LPCSTR fname)
{
	// IDB�t�@�C����ǂݍ���
	if (! GetIdbFileData(fname))
		return false;

	// �f�[�^���Z�b�g����
	SetIdbFileData();

	// �o�C�i���f�[�^�ۑ���p�X���`�F�b�N����
	CheckBinDataPath(fname);

	return true;
}

// IDB�t�@�C�������擾����
bool CIdbFile::GetFname(LPCSTR path, CString& fname)
{
	char wpath[MAX_PATH];
	struct _finddata_t c_file;
    intptr_t hFile;

	memset(wpath, 0, sizeof(wpath));
	strcpy_s(wpath, path);
	strcat_s(wpath, "\\*.idb");

    /* �J�����g �f�B���N�g�����̍ŏ��̃t�@�C����T���܂��B*/
	if( (hFile = _findfirst(wpath, &c_file )) != -1L ) {
		fname.Format("%s\\%s", path, c_file.name);
		_findclose( hFile );
		return true;
	}
	return false;
}

// IDB�t�@�C����ǂݍ���
bool CIdbFile::GetIdbFileData(LPCSTR path)
{
	CStdioFile	fp;
	CFileException fileException;
	CString buf;

	if (! fp.Open(path, CFile::modeRead, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", path, fileException.m_cause );
		return false;
	}
	for (;;) {
		if (! fp.ReadString(buf))
			break;
		m_Data.Add(buf);
	}
	fp.Close();

	return true;
}

// �f�[�^���Z�b�g����
void CIdbFile::SetIdbFileData(void)
{
	CString	buf;

	// �R�����g
	if (GetData("COMMENT", buf) != false) {
		m_comment = buf;
	}
	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	if (GetData("STARTTIME", buf) != false) {
		m_starttime = buf;
	}
	// �T���v�����[�g
	if (GetData("SAMPLERATE", buf) != false) {
		m_samplerate = atof((LPCSTR)buf);
	}
	// ����
	if (GetData("PERIOD", buf) != false) {
		m_period = buf;
	}
	// �_�E�������N��
	if (GetData("DOWNLINK", buf) != false) {
		m_downlink = buf;
	}
	// ���j�b�gID
	if (GetData("RSUID", buf) != false) {
		m_rsuid = atoi(buf);
	}
	// �l�b�g���[�NID
	if (GetData("NETWORKID", buf) != false) {
		m_networkid = atoi(buf);
	}
	// �Z���T�[ID
	if (GetData("SENSORID", buf) != false) {
		m_sensorid = atoi(buf);
	}
	// �Z���T�[IDX
	if (GetData("SENSORIDX", buf) != false) {
		m_SensorIdx = atoi(buf);
	}
	// �^�C���[��
	if (GetData("TIMEZERO", buf) != false) {
		m_timezero = buf;
	}
	// �Q�C��
	if (GetData("GAIN", buf) != false) {
		m_gain = atoi(buf);
	}
	// �J�b�g�I�t���g��
	if (GetData("CUTOFF", buf) != false) {
		m_cutoff = atoi(buf);
	}
	// Bias Co Eff
	if (GetData("BIASCOEFF", buf) != false) {
		m_biascoeff = atoi(buf);
	}
	// Sensor Co Sys
	if (GetData("SENSORCOSYS", buf) != false) {
		m_sensorcosys = buf;
	}
	// Data Co Sys
	if (GetData("DATACOSYS", buf) != false) {
		m_datacosys = buf;
	}
	// Data Quality
	if (GetData("DATAQUALITY", buf) != false) {
		m_dataquality = buf;
	}
	// ISS Config
	if (GetData("ISSCONFIG", buf) != false) {
		m_issconfig = buf;
	}
	// �X�P�[���t�@�N�^�[
	if (GetData("SCALEFACTOR", buf) != false) {
		m_scalefactor = atoi(buf);
	}
	// �ۑ��p�X��
	if (GetData("PATH", buf) != false) {
		m_path = buf;
	}
	// �J�e�S��
	if (GetData("CATEGORY", buf) != false) {
		m_sCategory = buf;
	}
	// �v�����
	if (GetData("MEASUREMENT_KIND", buf) != false) {
		m_sMeasurementKind = buf;
	}
	// �o�C�i���t�@�C����
	GetData("FILE", m_File);
}

// �t�@�C���f�[�^����w�荀�ڂ��擾����
bool CIdbFile::GetData(LPCSTR itemStr, CString &oDat)
{
	INT_PTR	i, cnt;

	cnt = m_Data.GetSize();
	for (i = 0; i < cnt; i++) {
		if (GetDataFromIdb(m_Data.GetAt(i), itemStr, oDat) != false)
			return true;
	}
	return false;
}

// �t�@�C���f�[�^���畡���̎w�荀�ڂ��擾����
void CIdbFile::GetData(LPCSTR itemStr, CStringArray &oDat)
{
	INT_PTR	i, cnt;
	CString sDat;

	oDat.RemoveAll();
	cnt = m_Data.GetSize();
	for (i = 0; i < cnt; i++) {
		if (GetDataFromIdb(m_Data.GetAt(i), itemStr, sDat) != false)
			oDat.Add(sDat);
	}
}

// �t�@�C���f�[�^����w�荀�ڂ��擾����
bool CIdbFile::GetDataFromIdb(LPCSTR dat, LPCSTR itemStr, CString &oDat)
{
	CString sItemKey, sItemValue;
	CString sDat(dat);
	int nPos= 0;

	sItemKey = sDat.Tokenize("=", nPos);
	sItemValue = sDat.Tokenize("", nPos);

	if (sItemKey != itemStr)
		return false;

	oDat = sItemValue;
	return true;
}


// ���N���A
void CIdbFile::DelAll(void)
{
	// �f�[�^�͈�
	m_period = "";
	// �_�E�������N��
	m_downlink = "";
	// �l�b�g���[�NID�iUNIT ID��ʂS���j
	m_networkid = 0;
	// ���j�b�gID�iUNIT ID���ʂS���j
	m_rsuid = 0;
	// �Z���T�[ID
	m_sensorid = 0;
	// �^�C���[��
	m_timezero = "";
	// �Q�C��
	m_gain = 0;
	// �T���v�����[�g
	m_samplerate = 0.0;
	// �J�b�g�I�t���g��
	m_cutoff = 0;
	// �ۑ��p�X��
	m_path = "";
	// Bias Co Eff
	m_biascoeff = 0;
	// Sensor Co Sys
	m_sensorcosys = "";
	// Data Co Sys
	m_datacosys = "";
	// Data Quality
	m_dataquality = "";
	// ISS Config
	m_issconfig  = "";
	// �X�P�[���t�@�N�^�[
	m_scalefactor = 0;
	// �R�����g
	m_comment = "";
	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	m_starttime = "";
	// �Z���T�[IDX
	m_SensorIdx = 0;
	// �J�e�S��
	m_sCategory = "";
	// �v�����
	m_sMeasurementKind = "";
	// �P���Ԗ��̃t�@�C�����ۑ��G���A
	m_File.RemoveAll();
	// IDB�t�@�C���ǂݍ��݃G���A
	m_Data.RemoveAll();
}

// �o�C�i���f�[�^�t�@�C���ۑ���`�F�b�N
void CIdbFile::CheckBinDataPath(LPCTSTR pIdbFilePath)
{
	// idb�t�@�C���p�X��\�ŕ������Ĕz��ɃZ�b�g����
	CStringArray saIdbFilePath;
	CGeneral::StrSplit(pIdbFilePath, "\\", saIdbFilePath);
	int sSize = (int)saIdbFilePath.GetSize();

	// idb�t�@�C��������g���q����菜��
	CString sName;
	sName = saIdbFilePath[sSize - 1];
	sName = sName.Left(sName.GetLength() - 4);

	// idb�t�@�C���p�X����idb�t�@�C���������������̂��������ăp�X�����擾����
	CString sPath;
	saIdbFilePath.RemoveAt(sSize - 1);
	CGeneral::StrJoin(saIdbFilePath, "\\", sPath);

	// �ۑ��p�X���قȂ�ꍇ�͐������p�X�ɕύX����
	if (m_path != sPath) {
		m_path = sPath;

		Write(sName);
	}
}
