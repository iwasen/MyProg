#pragma once


class CIniFile
{
public:
	// ini�t�@�C���̏����擾����
	static bool Read(void);

private:
	// �N�����ꂽ�A�v���P�[�V�����̃t���p�X�擾
	static void SetExeDir(void);
};
