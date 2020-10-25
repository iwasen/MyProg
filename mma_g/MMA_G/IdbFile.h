#pragma once

class CIdbFile
{
public:
	CIdbFile(void);
	~CIdbFile(void);

public:
	// �f�[�^�͈�
	CString m_period;
	// �_�E�������N��
	CString m_downlink;
	// �l�b�g���[�NID�iUNIT ID��ʂS���j
	int	m_networkid;
	// ���j�b�gID�iUNIT ID���ʂS���j
	int m_rsuid;
	// �Z���T�[ID
	int m_sensorid;
	// �^�C���[��
	CString m_timezero;
	// �^�C���[��(XML)
	CString m_timezeroXML;
	// �Q�C��
	int m_gain;
	// �T���v�����[�g
	double m_samplerate;
	// �J�b�g�I�t���g��
	int m_cutoff;
	// �ۑ��p�X��
	CString m_path;
	// Bias Co Eff
	int m_biascoeff;
	// Sensor Co Sys
	CString m_sensorcosys;
	// Data Co Sys
	CString m_datacosys;
	// Data Quality
	CString m_dataquality;
	// ISS Config
	CString m_issconfig;
	// �X�P�[���t�@�N�^�[
	int m_scalefactor;
	// �R�����g
	CString m_comment;
	// �P���Ԗ��̃t�@�C�����ۑ��G���A
	CStringArray m_File;
	// �J�e�S��
	CString m_sCategory;
	// �v�����
	CString m_sMeasurementKind;
	// idb�t�@�C���p�X
	CString m_sIdbPath;
	// �f�[�^���
	int m_nDataType;

private:
	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	CString	m_starttime;
	// �Z���T�[IDX
	int	m_SensorIdx;
	// IDB�t�@�C���ǂݍ��݃G���A
	CStringArray	m_Data;
// 2009/07/07 Version 3.02 appended by m.hama ->
	// XML�t�@�C���ǂݍ��݃G���A
	CStringArray	m_XmlData;
// <- 2009/07/07 Version 3.02 appended by m.hama

public:
	// �f�[�^�͈�
	void SetPeriod(LPCSTR str)	{ m_period = str; }
	// �_�E�������N��
	void SetDownlink(LPCSTR str)	{ m_downlink = str; }
	// �l�b�g���[�NID�iUNIT ID��ʂS���j
	void SetNetworkid(int id)	{m_networkid = id; }
	// ���j�b�gID�iUNIT ID���ʂS���j
	void SetRsuId(int id)	{ m_rsuid = id; }
	// �Z���T�[ID
	void SetSensorId(int id)	{ m_sensorid = id; }
	// �^�C���[��
	void SetTimeZero(LPCSTR str)	{ m_timezero = str; }
	// �^�C���[��
	void SetTimeZeroXML(LPCSTR str)	{ m_timezeroXML = str; }
	// �Q�C��
	void SetGain(int gain)	{ m_gain = gain; }
	// �T���v�����[�g
	void SetSampleRate(double f)	{ m_samplerate = f; }
	// �J�b�g�I�t���g��
	void SetCutoff(int c)	{ m_cutoff = c; }
	// �ۑ��p�X��
	void SetPath(LPCSTR str)	{ m_path = str; }
	// Bias Co Eff
	void SetBiascoeff(int c)	{ m_biascoeff = c; }
	// Sensor Co Sys
	void SetSensorcosys(LPCSTR str)	{ m_sensorcosys = str; }
	// Data Co Sys
	void SetDatacosys(LPCSTR str)	{ m_datacosys = str; }
	// Data Quality
	void SetDataquality(LPCSTR str)	{ m_dataquality = str; }
	// ISS Config
	void SetIssconfig(LPCSTR str)	{ m_issconfig = str; }
	// �X�P�[���t�@�N�^�[
	void SetScalefactor(int sf)	{ m_scalefactor = sf; }
	// �R�����g
	void SetComment(LPCSTR str)	{ m_comment = str; }
	// �J�e�S��
	void SetCategory(LPCSTR str) { m_sCategory = str; }
	// �v�����
	void SetMeasurementKind(LPCSTR str) { m_sMeasurementKind = str; }

	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	void SetStartTime(long YY, long MM, long DD, long mS)	{ m_starttime.Format("%04d%02d%02d%08d", YY, MM, DD, mS); }
	// �Z���T�[IDX
	void SetSensorIdx(int idx) { m_SensorIdx = idx; }
	// 1���Ԗ��̃t�@�C�����ۑ��G���A
	void AddFile(LPCSTR str)	{ m_File.Add(str); }
	// IDB�t�@�C������������
	bool Write(LPCSTR name);
	// IDB�t�@�C����ǂݍ���
	bool Read(LPCSTR path);
	bool Read2(LPCSTR fname);
	// IDB�t�@�C�������擾����
	bool GetFname(LPCSTR path, CString& fname);
private:
	// IDB�t�@�C����ǂݍ���
	bool GetIdbFileData(LPCSTR path);
	// �f�[�^���Z�b�g����
	void SetIdbFileData(void);
	// �t�@�C���f�[�^����w�荀�ڂ��擾����
	bool GetData(LPCSTR itemStr, CString &oDat);
	// �t�@�C���f�[�^���畡���̎w�荀�ڂ��擾����
	void GetData(LPCSTR itemStr, CStringArray &oDat);
	// �t�@�C���f�[�^����w�荀�ڂ��擾����
	bool GetDataFromIdb(LPCSTR dat, LPCSTR itemStr, CString &oDat);
	// �o�C�i���f�[�^�t�@�C���ۑ���`�F�b�N
	void CheckBinDataPath(LPCTSTR pIdbFilePath);
	// MMA��XML�t�@�C������������
	void WriteXmlMma(CStdioFile &fp, LPCSTR name);
	// MME��XML�t�@�C������������
	void WriteXmlMme(CStdioFile &fp, LPCSTR name);
	// ASU��XML�t�@�C������������
	void WriteXmlAsu(CStdioFile &fp, LPCSTR name);

public:
	bool WriteXml(LPCSTR name, int nDataType);
// 2009/07/07 Version 3.02 appended by m.hama ->
	bool GetXmlFileData(LPCSTR path);
	bool WriteXml2(LPCSTR name);
// <- 2009/07/07 Version 3.02 appended by m.hama

	// ���N���A
	void DelAll(void);
};
