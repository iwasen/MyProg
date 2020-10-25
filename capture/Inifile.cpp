#include "stdafx.h"
#include "inifile.h"

CIniFile iniData;

static char iniFile[] = "CAPTURE.INI";
static char section[] = "CAPTURE1";

// INI �t�@�C������ǂݍ���
void CIniFile::Read()
{
	char tmp[256];

	GetPrivateProfileString(section, "OutputFile", "capture.jpg", tmp, sizeof(tmp), iniFile);
	m_OutputFile = tmp;
	GetPrivateProfileString(section, "OutputFile2", "", tmp, sizeof(tmp), iniFile);
	m_OutputFile2 = tmp;
	m_Quality = GetPrivateProfileInt(section, "Quality", 1, iniFile);
	m_OutputRate = GetPrivateProfileInt(section, "OutputRate", 10, iniFile);
	m_StartupMode = GetPrivateProfileInt(section, "StartupMode", 1, iniFile);
}

// INI �t�@�C���ɏ�������
void CIniFile::Write()
{
	WritePrivateProfileString(section, "OutputFile", m_OutputFile, iniFile);
	WritePrivateProfileString(section, "OutputFile2", m_OutputFile2, iniFile);
	WritePrivateProfileInt(section, "Quality", m_Quality, iniFile);
	WritePrivateProfileInt(section, "OutputRate", m_OutputRate, iniFile);
	WritePrivateProfileInt(section, "StartupMode", m_StartupMode, iniFile);
}

// int �^�f�[�^�̏�������
void CIniFile::WritePrivateProfileInt(char *section, char *entry, int iValue, char *iniFile)
{
	char	buf[20];

	sprintf(buf, "%d", iValue);
	WritePrivateProfileString(section, entry, buf, iniFile);
}
