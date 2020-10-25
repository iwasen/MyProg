#pragma once


class CIniFile
{
public:
	// iniファイルの情報を取得する
	static bool Read(void);

private:
	// 起動されたアプリケーションのフルパス取得
	static void SetExeDir(void);
};
