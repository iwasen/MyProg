[Setup]
AppName=Diagnostic System for Sound Fields
AppVerName=Diagnostic System for Sound Fields Version 5E
AppVersion=5.2.0
AppCopyright=Copyright (C) 2000-2020 Yoshimasa Electronic Inc.
VersionInfoVersion=5.2.0
PrivilegesRequired=admin
DefaultDirName={commonpf}\YMEC\DSSF3E
DefaultGroupName=Acoustic Analyzing System
OutputBaseFilename=DSSF3E32
LicenseFile=..\..\MyDev\RTF\LicenseAgreementE.rtf
UninstallDisplayIcon={app}\RTA.exe
Compression=lzma
SolidCompression=yes
OutputDir=Installer
AppPublisher=Yoshimasa Electronic Inc.

[Languages]
Name: "en"; MessagesFile: "compiler:Default.isl"

[Files]
Source: "..\win32\EngRel\DSS.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\RTA.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\SAS.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\NMS.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\MMDB.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\PcsWin.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\RegistE.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\RTA_E.chm"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\dssf3e_ra.pdf"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\YmUpdate.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\win32\EngRel\YmUpdate.dll"; DestDir: "{app}"; Flags: ignoreversion
Source: "..\Readme\Eng\Readme.txt"; DestDir: "{app}"; Flags: isreadme ignoreversion
Source: "..\SampleEng\ACPARAM.BIN"; DestDir: "{app}\DataBase"
Source: "..\SampleEng\ACPARAM.DBF"; DestDir: "{app}\DataBase"
Source: "..\SampleEng\FOLDER.DBF"; DestDir: "{app}\DataBase"
Source: "..\SampleEng\IMPULSE.BIN"; DestDir: "{app}\DataBase"
Source: "..\SampleEng\IMPULSE.DBF"; DestDir: "{app}\DataBase"
;Source: "..\..\MyDev\FONT\SSerife2.fon"; DestDir: "{fonts}"; FontInstall: onlyifdoesntexist uninsneveruninstall
Source: "..\..\MyDev\DLL32\HardKey.dll"; DestDir: "{app}"; Flags: dontcopy

[Icons]
Name: "{group}\Setting Utility"; Filename: "{app}\DSS.exe"; WorkingDir: "{app}"
Name: "{group}\Realtime Analyzer"; Filename: "{app}\RTA.exe"; WorkingDir: "{app}"
Name: "{group}\Sound Analyzing System"; Filename: "{app}\SAS.exe"; WorkingDir: "{app}"
Name: "{group}\Noise Measurement System"; Filename: "{app}\NMS.exe"; WorkingDir: "{app}"

[Code]
procedure GetHardKey(nAddSeed: Integer; sHardKey: AnsiString);
external 'GetHardKey@files:HardKey.dll stdcall setuponly';

function GetHardKey2(param: String): String;
var
  asHardKey: AnsiString;
  sHardKey: String;
  rc: Boolean;
begin
  rc := RegQueryStringValue(HKLM, 'SoftWare\Yoshimasa Electronic Inc.\Analyzing System for Sound Fields Ver5', 'HardKey', sHardKey);
  if rc = false then begin
    asHardKey := '00000000';
    GetHardKey(0, asHardKey);
    sHardKey := String(asHardKey);
  end;
  Result := sHardKey;
end;

[Registry]
Root: HKLM; Subkey: SoftWare\\Yoshimasa Electronic Inc.\\Analyzing System for Sound Fields Ver5; ValueType: string; ValueName: HardKey; ValueData: {code:GetHardKey2};
Root: HKLM; Subkey: SoftWare\\Yoshimasa Electronic Inc.\\Analyzing System for Sound Fields Ver5; ValueType: dword; ValueName: Version; ValueData: 1;

Root: HKCR; Subkey: ".wavdssf"; ValueType: string; ValueName: ""; ValueData: "Dssf3.AutoRec"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "Dssf3.AutoRec"; ValueType: string; ValueName: ""; ValueData: "Dssf3 Automatic Recorded file"; Flags: uninsdeletevalue deletevalue
Root: HKCR; Subkey: "Dssf3.AutoRec\shell\open\command"; ValueType: string; ValueName: ""; ValueData: """{app}\RTA.exe"" /A ""%1"""; Flags: uninsdeletevalue deletevalue
