// BinFile.h: CBinFile �N���X�̃C���^�[�t�F�C�X
//
//////////////////////////////////////////////////////////////////////

#pragma once

class CBinFile  
{
public:
	CBinFile();
	virtual ~CBinFile();

public:
	BOOL OpenBin(LPCSTR fname, BOOL bRetry = FALSE);
	int WriteData(CStdioFile &wfp);
	int WriteData(CStdioFile &wfp, int from, int to);
	void CloseBin(void);
	BOOL ReadHeader(void);

public:
	int m_from_YY;	// ����
	int m_from_DD;
	int m_from_MM;
	int m_from_SS;
	int m_to_YY;	// ����
	int m_to_DD;
	int m_to_MM;
	int m_to_SS;
	int	m_SensorNo;		// �Z���T�[�`�����l���i���o�[
	int	m_SampleRate;	// �T���v�����[�g
	int	m_CutOff;		// �t�B���^�[�J�b�g�I�t
	int	m_CalGain;		// Cal/Gain
	int	m_WxxxupTime;	// WxxxupTime
	int	m_Offset;		// Offset
	int	m_AcqRate;		// Acq rate
	LONG	m_Cnt;		// �f�[�^��
	int	m_AutoZero;		// AutoZero
	LONG	m_SampleIndex;	// SampleIndex
	LONG	m_Size;		// �f�[�^�T�C�Y
	CArray<unsigned short, unsigned short>m_DACM;
	CArray<unsigned short, unsigned short>m_x;
	CArray<unsigned short, unsigned short>m_y;
	CArray<unsigned short, unsigned short>m_z;

private:
	CStdioFile m_fp;
	// ����t�@�C����
	CString m_fname;

protected:
	int WriteDataAll(CStdioFile &wfp);
	int WriteHeadData(CStdioFile &wfp);
	int WriteStringHeadData(CStdioFile &wfp);
};
