/*	EBCDIC control code	*/

#define	C_STX		(char)0x02	/* start of text	*/
#define	C_ETX		(char)0x03	/* end of text		*/
#define	C_ETB		(char)0x26	/* end of block		*/
#define	C_EOT		(char)0x37	/* end of transsmition	*/
#define	C_ENQ		(char)0x2d	/* enquiry		*/
#define	C_ACK0		(char)0x70	/* acknowledge 0	*/
#define	C_ACK1		(char)0x61	/* acknowledge 1	*/
#define	C_DLE		(char)0x10	/* data link escape	*/
#define	C_NAK		(char)0x3d	/* negative acknowledge	*/
#define	C_SYN		(char)0x32	/* synchronous idle	*/
#define	C_WACK		(char)0x6b	/* wait acknowledge	*/
#define	C_RVI		(char)0x7c	/* reverse interrupt	*/
#define	C_TTD		(char)0x2d	/* temporary time deley	*/
#define	C_TPAD		(char)0xff	/* 			*/

#define	C_SI		(char)0x0f	/* shift in		*/
#define	C_SO		(char)0x0e	/* shift out		*/

#define	C_XON		(char)0x13	/* xon  code		*/
#define	C_XOFF		(char)0x11	/* xoff code		*/
