#include <stdio.h>
#include <dos.h>

main()
{
	char	file_name[64], in_file[64], out_file[64];
	FILE	*in_fp, *out_fp;
	int	i;
	char	far	*vp;

	printf("�������@�G��ʃt�@�C���t�H�[�}�b�g�ϊ����[�e�B���e�B�@������\n\n");
	printf("�t�@�C��������͂��ĉ����� [.ST4] ==>");
	scanf("%s", file_name);

	sprintf(in_file, "%s.ST4", file_name);
	sprintf(out_file, "%s.GRF", file_name);

	if ((in_fp = fopen(in_file, "rb")) == NULL) {
		printf("%s ���I�[�v���ł��܂���\n", in_file);
		exit(1);
	}

	if ((out_fp = fopen(out_file, "wb")) == NULL) {
		printf("%s ���I�[�v���ł��܂���\n", out_file);
		exit(1);
	}

	printf("�ϊ���\n");

	FP_OFF(vp) = 0;
	for (i = 0; i < 4; i++) {
		FP_SEG(vp) = 0xa800;
		fread(vp, 80, 100, in_fp);

		FP_SEG(vp) = 0xb000;
		fread(vp, 80, 100, in_fp);

		FP_SEG(vp) = 0xb800;
		fread(vp, 80, 100, in_fp);

		FP_OFF(vp) += 8000;
	}

	FP_OFF(vp) = 0;
	for (i = 0; i < 16 * 13; i++) {
		FP_SEG(vp) = 0xa800;
		fwrite(vp, 34, 1, out_fp);

		FP_SEG(vp) = 0xb000;
		fwrite(vp, 34, 1, out_fp);

		FP_SEG(vp) = 0xb800;
		fwrite(vp, 34, 1, out_fp);

		FP_OFF(vp) += 80;
	}

	fclose(in_fp);
	fclose(out_fp);

	printf("�ϊ��I��\n");
}