#include <stdio.h>

main()
{
	FILE	*fp;
	int	i, j, n_msg, msg_no, n_kahenbu, kahenbu_no, type, c, len;
	long	offset;
	static	char	buf[1024];

	if ((fp = fopen("loc.msg", "rb")) == NULL)
		exit(1);

	fread(&n_msg, sizeof(int), 1, fp);
	printf("���b�Z�[�W�� = %d\n\n", n_msg);

	for (i = 0; i < n_msg; i++) {
		fseek(fp, (long)(sizeof(int) + (sizeof(int) + sizeof(long)) * i), 0);
		fread(&msg_no, sizeof(int), 1, fp);
		fread(&offset, sizeof(long), 1, fp);
		printf("���b�Z�[�W�ԍ� = %d\n", msg_no);

		fseek(fp, offset, 0);

		fread(&n_kahenbu, sizeof(int), 1, fp);
		printf("�ϕ��� = %d\n", n_kahenbu);

		for (j = 0; j < n_kahenbu; j++) {
			fread(&kahenbu_no, sizeof(int), 1, fp);
			fread(&type, sizeof(int), 1, fp);
			printf("�ϕ��ԍ� = %d  �^ = %d\n", kahenbu_no, type);
		}

		fread(&len, sizeof(int), 1, fp);
		fread(buf, 1, len, fp);
		buf[len] = '\0';
		printf("[%s]\n\n", buf);
	}

	fclose(fp);
}