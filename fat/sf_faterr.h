/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Error Code                                *
**********************************************************/

#ifndef __SF_FATERR_H__
#define __SF_FATERR_H__

/* FAT File System のエラー・コードの定義 */
#define SF_EC_Ok                  (0)     /* OK */
#define SF_EC_InvalidFunctionCode (1)     /* Invalid Function Code */
#define SF_EC_FileNotFound        (2)     /* File is not Found     */
#define SF_EC_PathNotFound        (3)     /* Path is not Found     */
#define SF_EC_TooManyOpen         (4)     /* Open too many File    */
#define SF_EC_AccessDenied        (5)     /* File access is Denied */
#define SF_EC_InvalidHandle       (6)     /* Invalid File Handle   */
#define SF_EC_NotDirEmpty         (7)     /* Director is Not Empty */
#define SF_EC_FileNameTooLong     (8)     /* File Name is too Long */
#define SF_EC_InvalidAccessCode   (0xC)   /* Invalid Access Code   */
#define SF_EC_InvalidData         (0xD)   /* Invalid Data          */
#define SF_EC_InvalidDrive        (0xF)   /* Invalid Drive(Device) */
#define SF_EC_NotSameDevice       (0x11)  /* Not Same Device       */
#define SF_EC_NotDOSdisk          (0x1A)  /* Not DOS Disk          */
#define SF_EC_WriteFault          (0x1D)  /* Write is Failed       */
#define SF_EC_ReadFault           (0x1E)  /* Read is Failed        */
#define SF_EC_GeneralFailure      (0x1F)  /* General Fault         */

#endif /* __SF_FATERR_H__ */
