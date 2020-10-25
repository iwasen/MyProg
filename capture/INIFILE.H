class CIniFile {
public:
	void Read();
	void Write();

	CString m_OutputFile;
	CString m_OutputFile2;
	int m_Quality;
	int m_OutputRate;
	int m_StartupMode;

private:
	void WritePrivateProfileInt(char *section, char *entry, int iValue, char *iniFile);
};

extern CIniFile iniData;
