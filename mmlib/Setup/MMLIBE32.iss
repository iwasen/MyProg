[Setup]
AppName=Multimedia Library Manager
AppVerName=Multimedia Library Manager Version 1.10
AppVersion=1.10
AppCopyright=Copyright (C) 2000-2011 Yoshimasa Electronic Inc.
DefaultDirName={pf}\YMEC\MMLibE
DefaultGroupName=Multimedia Library Manager
OutputBaseFilename=MMLibE32
LicenseFile=J:\ymec\MyDev\RTF\LicenseAgreementE.rtf
UninstallDisplayIcon={app}\MMLib.exe
Compression=lzma
SolidCompression=yes
OutputDir=Installer
AppPublisher=Yoshimasa Electronic Inc.

[Languages]
Name: "en"; MessagesFile: "compiler:Default.isl"

[Files]
Source: "..\win32\EngRel\MMLIB.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\MMDB.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\PcsWin.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\RegistE.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\YmUpdate.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\YmUpdate.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\CapDll.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\TWainDll.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\Readme\MMLibE\Readme.txt"; DestDir: "{app}"; Flags: isreadme ignoreversion
Source: "J:\YMEC\MyDev\INI\YmUpdate.ini"; DestDir: "{app}"; Flags: ignoreversion
Source: "J:\YMEC\MyDev\DLL32\HardKey.dll"; DestDir: "{app}"; Flags: dontcopy

[Icons]
Name: "{group}\MMLIB Manager"; Filename: "{app}\MMLIB.exe"; WorkingDir: "{app}"

[Code]
procedure GetHardKey(nAddSeed: Integer; sHardKey: AnsiString);
external 'GetHardKey@files:HardKey.dll stdcall setuponly';

function GetHardKey2(param: String): AnsiString;
var
  sHardKey: AnsiString;
begin
  sHardKey := '00000000';

  RegQueryStringValue(HKLM, 'SoftWare\\Yoshimasa Electronic Inc.\\Multimedia Library', 'HardKey', sHardKey);

  if sHardKey = '00000000' then begin
    GetHardKey(0, sHardKey);
  end;

  Result := sHardKey;
end;

[Registry]
Root: HKLM; Subkey: SoftWare\\Yoshimasa Electronic Inc.\\Multimedia Library; ValueType: string; ValueName: HardKey; ValueData: {code:GetHardKey2};
Root: HKLM; Subkey: SoftWare\\Yoshimasa Electronic Inc.\\Multimedia Library; ValueType: dword; ValueName: Version; ValueData: 1;

