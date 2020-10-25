[Setup]
AppName=マルチメディアライブラリ
AppVerName=マルチメディアライブラリ Version 1.10
AppVersion=1.10
AppCopyright=Copyright (C) 2000-2011 Yoshimasa Electronic Inc.
DefaultDirName={pf}\YMEC\MMLibJ
DefaultGroupName=マルチメディアライブラリ
OutputBaseFilename=MMLibJ32
LicenseFile=J:\ymec\MyDev\RTF\LicenseAgreementJ.rtf
UninstallDisplayIcon={app}\MMLib.exe
Compression=lzma
SolidCompression=yes
OutputDir=Installer
AppPublisher=Yoshimasa Electronic Inc.

[Languages]
Name: japanese; MessagesFile: compiler:Languages\Japanese.isl

[Files]
Source: "..\win32\JpnRel\MMLIB.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\MMDB.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\PcsWin.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\RegistJ.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\YmUpdate.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\YmUpdate.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\CapDll.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\JpnRel\TWainDll.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\Readme\MMLibJ\Readme.txt"; DestDir: "{app}"; Flags: isreadme ignoreversion
Source: "J:\YMEC\MyDev\INI\YmUpdate.ini"; DestDir: "{app}"; Flags: ignoreversion
Source: "J:\YMEC\MyDev\DLL32\HardKey.dll"; DestDir: "{app}"; Flags: dontcopy

[Icons]
Name: "{group}\MMLIBマネージャ"; Filename: "{app}\MMLIB.exe"; WorkingDir: "{app}"

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

