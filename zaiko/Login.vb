Public Class frmLogin
    Inherits System.Windows.Forms.Form

#Region " Windows �t�H�[�� �f�U�C�i�Ő������ꂽ�R�[�h "

    Public Sub New()
        MyBase.New()

        ' ���̌Ăяo���� Windows �t�H�[�� �f�U�C�i�ŕK�v�ł��B
        InitializeComponent()

        ' InitializeComponent() �Ăяo���̌�ɏ�������ǉ����܂��B

    End Sub

    ' Form �� dispose ���I�[�o�[���C�h���ăR���|�[�l���g�ꗗ���������܂��B
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub

    ' Windows �t�H�[�� �f�U�C�i�ŕK�v�ł��B
    Private components As System.ComponentModel.IContainer

    ' ���� : �ȉ��̃v���V�[�W���́AWindows �t�H�[�� �f�U�C�i�ŕK�v�ł��B
    ' Windows �t�H�[�� �f�U�C�i���g���ĕύX���Ă��������B  
    ' �R�[�h �G�f�B�^�͎g�p���Ȃ��ł��������B
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents btnLogin As System.Windows.Forms.Button
    Friend WithEvents btnCancel As System.Windows.Forms.Button
    Friend WithEvents stbCopyRight As System.Windows.Forms.StatusBar
    Friend WithEvents sbpCopyRight As System.Windows.Forms.StatusBarPanel
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents txtLoginID As System.Windows.Forms.TextBox
    Friend WithEvents txtPassword As System.Windows.Forms.TextBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.txtLoginID = New System.Windows.Forms.TextBox()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.txtPassword = New System.Windows.Forms.TextBox()
        Me.btnLogin = New System.Windows.Forms.Button()
        Me.btnCancel = New System.Windows.Forms.Button()
        Me.stbCopyRight = New System.Windows.Forms.StatusBar()
        Me.sbpCopyRight = New System.Windows.Forms.StatusBarPanel()
        CType(Me.sbpCopyRight, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 12.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(76, 56)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(61, 16)
        Me.Label1.TabIndex = 2
        Me.Label1.Text = "���[�UID"
        '
        'txtLoginID
        '
        Me.txtLoginID.Font = New System.Drawing.Font("MS UI Gothic", 12.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtLoginID.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.txtLoginID.Location = New System.Drawing.Point(156, 52)
        Me.txtLoginID.Name = "txtLoginID"
        Me.txtLoginID.Size = New System.Drawing.Size(168, 23)
        Me.txtLoginID.TabIndex = 3
        Me.txtLoginID.Text = ""
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Black
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(416, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "�݌ɊǗ��V�X�e�� ���O�C��"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 12.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(76, 92)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(70, 16)
        Me.Label3.TabIndex = 4
        Me.Label3.Text = "�p�X���[�h"
        '
        'txtPassword
        '
        Me.txtPassword.Font = New System.Drawing.Font("MS UI Gothic", 12.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtPassword.Location = New System.Drawing.Point(156, 88)
        Me.txtPassword.Name = "txtPassword"
        Me.txtPassword.PasswordChar = Microsoft.VisualBasic.ChrW(42)
        Me.txtPassword.Size = New System.Drawing.Size(168, 23)
        Me.txtPassword.TabIndex = 5
        Me.txtPassword.Text = ""
        '
        'btnLogin
        '
        Me.btnLogin.Location = New System.Drawing.Point(106, 140)
        Me.btnLogin.Name = "btnLogin"
        Me.btnLogin.Size = New System.Drawing.Size(92, 24)
        Me.btnLogin.TabIndex = 6
        Me.btnLogin.Text = "���O�C��(&L)"
        '
        'btnCancel
        '
        Me.btnCancel.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnCancel.Location = New System.Drawing.Point(218, 140)
        Me.btnCancel.Name = "btnCancel"
        Me.btnCancel.Size = New System.Drawing.Size(92, 24)
        Me.btnCancel.TabIndex = 7
        Me.btnCancel.Text = "�L�����Z��"
        '
        'stbCopyRight
        '
        Me.stbCopyRight.Location = New System.Drawing.Point(0, 185)
        Me.stbCopyRight.Name = "stbCopyRight"
        Me.stbCopyRight.Panels.AddRange(New System.Windows.Forms.StatusBarPanel() {Me.sbpCopyRight})
        Me.stbCopyRight.ShowPanels = True
        Me.stbCopyRight.Size = New System.Drawing.Size(416, 22)
        Me.stbCopyRight.SizingGrip = False
        Me.stbCopyRight.TabIndex = 8
        Me.stbCopyRight.Text = "StatusBar1"
        '
        'sbpCopyRight
        '
        Me.sbpCopyRight.Alignment = System.Windows.Forms.HorizontalAlignment.Center
        Me.sbpCopyRight.AutoSize = System.Windows.Forms.StatusBarPanelAutoSize.Spring
        Me.sbpCopyRight.Width = 416
        '
        'frmLogin
        '
        Me.AcceptButton = Me.btnLogin
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.CancelButton = Me.btnCancel
        Me.ClientSize = New System.Drawing.Size(416, 207)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.stbCopyRight, Me.btnCancel, Me.btnLogin, Me.txtPassword, Me.Label3, Me.lblTitle, Me.txtLoginID, Me.Label1})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmLogin"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Login"
        CType(Me.sbpCopyRight, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** �t�H�[�����[�h�C�x���g
    Private Sub frmLogin_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        Me.sbpCopyRight.Text = Common.COPYRIGHT

        '�f�o�b�O�p�Ƀ��[�UID�ƃp�X���[�h��\��
        If Common.debugMode Then
            Me.txtLoginID.Text = "id1"
            Me.txtPassword.Text = "pass"
        End If
    End Sub

    '*** [���O�C��]�{�^���N���b�N�C�x���g
    Private Sub btnLogin_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnLogin.Click
        Dim dbCmd As New OleDb.OleDbCommand()

        '���[�UID���̓`�F�b�N
        If Me.txtLoginID.Text = "" Then
            MsgBox("���[�UID����͂��Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.txtLoginID.Focus()
            Return
        End If

        '�p�X���[�h���̓`�F�b�N
        If Me.txtPassword.Text = "" Then
            MsgBox("�p�X���[�h����͂��Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.txtPassword.Focus()
            Return
        End If

        '���[�UID/�p�X���[�h�`�F�b�NSQL
        dbCmd.Connection = Common.dbConnection
        dbCmd.CommandType = CommandType.Text
        dbCmd.CommandText = "SELECT ���[�U���x�� FROM ���[�U�}�X�^ WHERE ���[�UID=? AND �p�X���[�h=?"
        dbCmd.Parameters.Add("���[�UID", Me.txtLoginID.Text)
        dbCmd.Parameters.Add("�p�X���[�h", Me.txtPassword.Text)

        '���[�U���x���擾
        Common.userLevel = CType(dbCmd.ExecuteScalar(), Integer)

        If Common.userLevel > 0 Then
            Me.DialogResult = DialogResult.OK
        Else
            MsgBox("���O�C���F�؂Ɏ��s���܂����B" & vbCrLf & "������x���͂��ĉ������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.txtLoginID.Text = ""
            Me.txtPassword.Text = ""
            Me.txtLoginID.Focus()
        End If
    End Sub
End Class
