#include "stdafx.h"
#include "DssData.h"
#include "Directory.h"

#define FILENAME "DSS.dat"

CDssData g_DssData;

CDssData::CDssData()
{
	CFile file;
	BOOL bRead = FALSE;
	CString sDataDirectory;
	CString sPathName;

	GetDataDirectory(sDataDirectory);
	sPathName.Format("%s\\%s", sDataDirectory, FILENAME);

	if (file.Open(sPathName, CFile::modeRead | CFile::shareDenyNone)) {
		file.Read(this, sizeof(CDssData));
		bRead = TRUE;
	}

	if (!bRead) {
		memset(this, 0, sizeof(CDssData));

		CString sDatabaseDirectory;
		GetDefaultDatabaseDirectory(sDatabaseDirectory);
		strcpy_s(m_sDatabaseFolder, sDatabaseDirectory);

		CString sBackupDirectory;
		GetDefaultBackupDirectory(sBackupDirectory);
		strcpy_s(m_sBackupFolder, sBackupDirectory);

		SaveData();
	}
}

void CDssData::SaveData()
{
	CFile file;
	CString sDataDirectory;
	CString sPathName;

	GetDataDirectory(sDataDirectory);
	sPathName.Format("%s\\%s", sDataDirectory, FILENAME);

	if (file.Open(sPathName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone))
		file.Write((char *)this, sizeof(CDssData));
}
