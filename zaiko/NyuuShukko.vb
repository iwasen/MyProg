Public Class frmNyuuShukko
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
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents txtSuuryou_4 As System.Windows.Forms.TextBox
    Friend WithEvents txtZaikoSuu_4 As System.Windows.Forms.TextBox
    Friend WithEvents txtSuuryou_3 As System.Windows.Forms.TextBox
    Friend WithEvents txtZaikoSuu_3 As System.Windows.Forms.TextBox
    Friend WithEvents txtSuuryou_2 As System.Windows.Forms.TextBox
    Friend WithEvents txtZaikoSuu_2 As System.Windows.Forms.TextBox
    Friend WithEvents txtSuuryou_1 As System.Windows.Forms.TextBox
    Friend WithEvents txtZaikoSuu_1 As System.Windows.Forms.TextBox
    Friend WithEvents txtSuuryou_0 As System.Windows.Forms.TextBox
    Friend WithEvents txtZaikoSuu_0 As System.Windows.Forms.TextBox
    Friend WithEvents Panel1 As System.Windows.Forms.Panel
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents cmbShoriKubun_4 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbShoriKubun_3 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbShoriKubun_2 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbShoriKubun_1 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbShoriKubun_0 As System.Windows.Forms.ComboBox
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents cmbPartsCode_0 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbPartsCode_1 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbPartsCode_2 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbPartsCode_3 As System.Windows.Forms.ComboBox
    Friend WithEvents cmbPartsCode_4 As System.Windows.Forms.ComboBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.txtSuuryou_4 = New System.Windows.Forms.TextBox()
        Me.txtZaikoSuu_4 = New System.Windows.Forms.TextBox()
        Me.cmbShoriKubun_4 = New System.Windows.Forms.ComboBox()
        Me.txtSuuryou_3 = New System.Windows.Forms.TextBox()
        Me.txtZaikoSuu_3 = New System.Windows.Forms.TextBox()
        Me.cmbShoriKubun_3 = New System.Windows.Forms.ComboBox()
        Me.txtSuuryou_2 = New System.Windows.Forms.TextBox()
        Me.txtZaikoSuu_2 = New System.Windows.Forms.TextBox()
        Me.cmbShoriKubun_2 = New System.Windows.Forms.ComboBox()
        Me.txtSuuryou_1 = New System.Windows.Forms.TextBox()
        Me.txtZaikoSuu_1 = New System.Windows.Forms.TextBox()
        Me.cmbShoriKubun_1 = New System.Windows.Forms.ComboBox()
        Me.txtSuuryou_0 = New System.Windows.Forms.TextBox()
        Me.txtZaikoSuu_0 = New System.Windows.Forms.TextBox()
        Me.cmbShoriKubun_0 = New System.Windows.Forms.ComboBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.Panel1 = New System.Windows.Forms.Panel()
        Me.cmbPartsCode_4 = New System.Windows.Forms.ComboBox()
        Me.cmbPartsCode_3 = New System.Windows.Forms.ComboBox()
        Me.cmbPartsCode_2 = New System.Windows.Forms.ComboBox()
        Me.cmbPartsCode_1 = New System.Windows.Forms.ComboBox()
        Me.cmbPartsCode_0 = New System.Windows.Forms.ComboBox()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.Panel1.SuspendLayout()
        Me.SuspendLayout()
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Green
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "���ɁE�o�ɓ���"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(166, 304)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 27
        Me.btnOK.Text = "OK"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.Location = New System.Drawing.Point(273, 304)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 28
        Me.btnClose.Text = "����(&C)"
        '
        'txtSuuryou_4
        '
        Me.txtSuuryou_4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtSuuryou_4.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.txtSuuryou_4.Location = New System.Drawing.Point(420, 180)
        Me.txtSuuryou_4.Name = "txtSuuryou_4"
        Me.txtSuuryou_4.Size = New System.Drawing.Size(56, 22)
        Me.txtSuuryou_4.TabIndex = 26
        Me.txtSuuryou_4.Text = ""
        Me.txtSuuryou_4.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'txtZaikoSuu_4
        '
        Me.txtZaikoSuu_4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtZaikoSuu_4.Location = New System.Drawing.Point(324, 180)
        Me.txtZaikoSuu_4.Name = "txtZaikoSuu_4"
        Me.txtZaikoSuu_4.ReadOnly = True
        Me.txtZaikoSuu_4.Size = New System.Drawing.Size(68, 22)
        Me.txtZaikoSuu_4.TabIndex = 25
        Me.txtZaikoSuu_4.TabStop = False
        Me.txtZaikoSuu_4.Text = ""
        Me.txtZaikoSuu_4.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'cmbShoriKubun_4
        '
        Me.cmbShoriKubun_4.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbShoriKubun_4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbShoriKubun_4.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbShoriKubun_4.ItemHeight = 15
        Me.cmbShoriKubun_4.Location = New System.Drawing.Point(172, 180)
        Me.cmbShoriKubun_4.Name = "cmbShoriKubun_4"
        Me.cmbShoriKubun_4.Size = New System.Drawing.Size(120, 23)
        Me.cmbShoriKubun_4.TabIndex = 24
        '
        'txtSuuryou_3
        '
        Me.txtSuuryou_3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtSuuryou_3.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.txtSuuryou_3.Location = New System.Drawing.Point(420, 144)
        Me.txtSuuryou_3.Name = "txtSuuryou_3"
        Me.txtSuuryou_3.Size = New System.Drawing.Size(56, 22)
        Me.txtSuuryou_3.TabIndex = 22
        Me.txtSuuryou_3.Text = ""
        Me.txtSuuryou_3.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'txtZaikoSuu_3
        '
        Me.txtZaikoSuu_3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtZaikoSuu_3.Location = New System.Drawing.Point(324, 144)
        Me.txtZaikoSuu_3.Name = "txtZaikoSuu_3"
        Me.txtZaikoSuu_3.ReadOnly = True
        Me.txtZaikoSuu_3.Size = New System.Drawing.Size(68, 22)
        Me.txtZaikoSuu_3.TabIndex = 21
        Me.txtZaikoSuu_3.TabStop = False
        Me.txtZaikoSuu_3.Text = ""
        Me.txtZaikoSuu_3.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'cmbShoriKubun_3
        '
        Me.cmbShoriKubun_3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbShoriKubun_3.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbShoriKubun_3.ItemHeight = 15
        Me.cmbShoriKubun_3.Location = New System.Drawing.Point(172, 144)
        Me.cmbShoriKubun_3.Name = "cmbShoriKubun_3"
        Me.cmbShoriKubun_3.Size = New System.Drawing.Size(120, 23)
        Me.cmbShoriKubun_3.TabIndex = 20
        '
        'txtSuuryou_2
        '
        Me.txtSuuryou_2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtSuuryou_2.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.txtSuuryou_2.Location = New System.Drawing.Point(420, 108)
        Me.txtSuuryou_2.Name = "txtSuuryou_2"
        Me.txtSuuryou_2.Size = New System.Drawing.Size(56, 22)
        Me.txtSuuryou_2.TabIndex = 18
        Me.txtSuuryou_2.Text = ""
        Me.txtSuuryou_2.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'txtZaikoSuu_2
        '
        Me.txtZaikoSuu_2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtZaikoSuu_2.Location = New System.Drawing.Point(324, 108)
        Me.txtZaikoSuu_2.Name = "txtZaikoSuu_2"
        Me.txtZaikoSuu_2.ReadOnly = True
        Me.txtZaikoSuu_2.Size = New System.Drawing.Size(68, 22)
        Me.txtZaikoSuu_2.TabIndex = 17
        Me.txtZaikoSuu_2.TabStop = False
        Me.txtZaikoSuu_2.Text = ""
        Me.txtZaikoSuu_2.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'cmbShoriKubun_2
        '
        Me.cmbShoriKubun_2.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbShoriKubun_2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbShoriKubun_2.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbShoriKubun_2.ItemHeight = 15
        Me.cmbShoriKubun_2.Location = New System.Drawing.Point(172, 108)
        Me.cmbShoriKubun_2.Name = "cmbShoriKubun_2"
        Me.cmbShoriKubun_2.Size = New System.Drawing.Size(120, 23)
        Me.cmbShoriKubun_2.TabIndex = 16
        '
        'txtSuuryou_1
        '
        Me.txtSuuryou_1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtSuuryou_1.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.txtSuuryou_1.Location = New System.Drawing.Point(420, 72)
        Me.txtSuuryou_1.Name = "txtSuuryou_1"
        Me.txtSuuryou_1.Size = New System.Drawing.Size(56, 22)
        Me.txtSuuryou_1.TabIndex = 14
        Me.txtSuuryou_1.Text = ""
        Me.txtSuuryou_1.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'txtZaikoSuu_1
        '
        Me.txtZaikoSuu_1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtZaikoSuu_1.Location = New System.Drawing.Point(324, 72)
        Me.txtZaikoSuu_1.Name = "txtZaikoSuu_1"
        Me.txtZaikoSuu_1.ReadOnly = True
        Me.txtZaikoSuu_1.Size = New System.Drawing.Size(68, 22)
        Me.txtZaikoSuu_1.TabIndex = 13
        Me.txtZaikoSuu_1.TabStop = False
        Me.txtZaikoSuu_1.Text = ""
        Me.txtZaikoSuu_1.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'cmbShoriKubun_1
        '
        Me.cmbShoriKubun_1.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbShoriKubun_1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbShoriKubun_1.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbShoriKubun_1.ItemHeight = 15
        Me.cmbShoriKubun_1.Location = New System.Drawing.Point(172, 72)
        Me.cmbShoriKubun_1.Name = "cmbShoriKubun_1"
        Me.cmbShoriKubun_1.Size = New System.Drawing.Size(120, 23)
        Me.cmbShoriKubun_1.TabIndex = 12
        '
        'txtSuuryou_0
        '
        Me.txtSuuryou_0.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtSuuryou_0.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.txtSuuryou_0.Location = New System.Drawing.Point(420, 36)
        Me.txtSuuryou_0.MaxLength = 5
        Me.txtSuuryou_0.Name = "txtSuuryou_0"
        Me.txtSuuryou_0.Size = New System.Drawing.Size(56, 22)
        Me.txtSuuryou_0.TabIndex = 10
        Me.txtSuuryou_0.Text = ""
        Me.txtSuuryou_0.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'txtZaikoSuu_0
        '
        Me.txtZaikoSuu_0.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.txtZaikoSuu_0.Location = New System.Drawing.Point(324, 36)
        Me.txtZaikoSuu_0.Name = "txtZaikoSuu_0"
        Me.txtZaikoSuu_0.ReadOnly = True
        Me.txtZaikoSuu_0.Size = New System.Drawing.Size(68, 22)
        Me.txtZaikoSuu_0.TabIndex = 9
        Me.txtZaikoSuu_0.TabStop = False
        Me.txtZaikoSuu_0.Text = ""
        Me.txtZaikoSuu_0.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'cmbShoriKubun_0
        '
        Me.cmbShoriKubun_0.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbShoriKubun_0.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbShoriKubun_0.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbShoriKubun_0.ItemHeight = 15
        Me.cmbShoriKubun_0.Location = New System.Drawing.Point(172, 36)
        Me.cmbShoriKubun_0.Name = "cmbShoriKubun_0"
        Me.cmbShoriKubun_0.Size = New System.Drawing.Size(120, 23)
        Me.cmbShoriKubun_0.TabIndex = 8
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(44, 12)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(73, 15)
        Me.Label1.TabIndex = 3
        Me.Label1.Text = "�p�[�c����"
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(188, 12)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(83, 15)
        Me.Label2.TabIndex = 4
        Me.Label2.Text = "���o�ɋ敪"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(408, 12)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(83, 15)
        Me.Label3.TabIndex = 6
        Me.Label3.Text = "���o�ɐ���"
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(324, 12)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(67, 15)
        Me.Label4.TabIndex = 5
        Me.Label4.Text = "���݌ɐ�"
        '
        'Panel1
        '
        Me.Panel1.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.Panel1.Controls.AddRange(New System.Windows.Forms.Control() {Me.cmbPartsCode_4, Me.cmbPartsCode_3, Me.cmbPartsCode_2, Me.cmbPartsCode_1, Me.cmbPartsCode_0, Me.txtSuuryou_1, Me.txtSuuryou_2, Me.txtSuuryou_4, Me.Label1, Me.cmbShoriKubun_1, Me.txtZaikoSuu_4, Me.cmbShoriKubun_3, Me.txtZaikoSuu_1, Me.cmbShoriKubun_4, Me.Label3, Me.txtSuuryou_0, Me.Label2, Me.Label4, Me.txtZaikoSuu_0, Me.txtSuuryou_3, Me.cmbShoriKubun_0, Me.txtZaikoSuu_3, Me.cmbShoriKubun_2, Me.txtZaikoSuu_2})
        Me.Panel1.Location = New System.Drawing.Point(12, 48)
        Me.Panel1.Name = "Panel1"
        Me.Panel1.Size = New System.Drawing.Size(500, 224)
        Me.Panel1.TabIndex = 2
        '
        'cmbPartsCode_4
        '
        Me.cmbPartsCode_4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsCode_4.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbPartsCode_4.Location = New System.Drawing.Point(20, 180)
        Me.cmbPartsCode_4.Name = "cmbPartsCode_4"
        Me.cmbPartsCode_4.Size = New System.Drawing.Size(128, 23)
        Me.cmbPartsCode_4.TabIndex = 23
        '
        'cmbPartsCode_3
        '
        Me.cmbPartsCode_3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsCode_3.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbPartsCode_3.Location = New System.Drawing.Point(20, 144)
        Me.cmbPartsCode_3.Name = "cmbPartsCode_3"
        Me.cmbPartsCode_3.Size = New System.Drawing.Size(128, 23)
        Me.cmbPartsCode_3.TabIndex = 19
        '
        'cmbPartsCode_2
        '
        Me.cmbPartsCode_2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsCode_2.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbPartsCode_2.Location = New System.Drawing.Point(20, 108)
        Me.cmbPartsCode_2.Name = "cmbPartsCode_2"
        Me.cmbPartsCode_2.Size = New System.Drawing.Size(128, 23)
        Me.cmbPartsCode_2.TabIndex = 15
        '
        'cmbPartsCode_1
        '
        Me.cmbPartsCode_1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsCode_1.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbPartsCode_1.Location = New System.Drawing.Point(20, 72)
        Me.cmbPartsCode_1.Name = "cmbPartsCode_1"
        Me.cmbPartsCode_1.Size = New System.Drawing.Size(128, 23)
        Me.cmbPartsCode_1.TabIndex = 11
        '
        'cmbPartsCode_0
        '
        Me.cmbPartsCode_0.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbPartsCode_0.ImeMode = System.Windows.Forms.ImeMode.Disable
        Me.cmbPartsCode_0.Location = New System.Drawing.Point(20, 36)
        Me.cmbPartsCode_0.MaxLength = 12
        Me.cmbPartsCode_0.Name = "cmbPartsCode_0"
        Me.cmbPartsCode_0.Size = New System.Drawing.Size(128, 23)
        Me.cmbPartsCode_0.TabIndex = 7
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 288)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 29
        Me.GroupBox1.TabStop = False
        '
        'frmNyuuShukko
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 339)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox1, Me.Panel1, Me.btnOK, Me.lblTitle, Me.btnClose})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmNyuuShukko"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "NyuuShukko"
        Me.Panel1.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub

#End Region

    '�R���g���[���z��
    Private cmbPartsCode() As ComboBox
    Private cmbShoriKubun() As ComboBox
    Private txtZaikoSuu() As TextBox
    Private txtSuuryou() As TextBox

    '*** �t�H�[�����[�h�C�x���g
    Private Sub frmNyuuShukko_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Dim i As Integer

        '�^�C�g���ݒ�
        Me.Text = Common.TITLE

        '�R���g���[���z��ݒ�
        cmbPartsCode = New ComboBox() {cmbPartsCode_0, cmbPartsCode_1, cmbPartsCode_2, cmbPartsCode_3, cmbPartsCode_4}
        cmbShoriKubun = New ComboBox() {cmbShoriKubun_0, cmbShoriKubun_1, cmbShoriKubun_2, cmbShoriKubun_3, cmbShoriKubun_4}
        txtZaikoSuu = New TextBox() {txtZaikoSuu_0, txtZaikoSuu_1, txtZaikoSuu_2, txtZaikoSuu_3, txtZaikoSuu_4}
        txtSuuryou = New TextBox() {txtSuuryou_0, txtSuuryou_1, txtSuuryou_2, txtSuuryou_3, txtSuuryou_4}

        '�����敪�h���b�v�_�E��
        For i = 0 To 4
            Dim listSource As New ArrayList()
            listSource.Add(New ListSource("", ""))
            listSource.Add(New ListSource("����", "01"))
            listSource.Add(New ListSource("�o��", "03"))
            listSource.Add(New ListSource("�p��", "04"))
            cmbShoriKubun(i).DataSource = listSource
            cmbShoriKubun(i).DisplayMember = "DisplayMember"
            cmbShoriKubun(i).ValueMember = "ValueMember"
        Next

        '�C�x���g�n���h���ݒ�i�h���b�v�_�E���Ńp�[�c���̂��Z�b�g�j
        AddHandler cmbPartsCode_0.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_1.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_2.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_3.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_4.DropDown, AddressOf cmbPartsCode_DropDown

        '�C�x���g�n���h���ݒ�i�p�[�c���̂̓��͕��������j
        AddHandler cmbPartsCode_0.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_1.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_2.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_3.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_4.TextChanged, AddressOf cmbPartsCode_TextChanged

        '�C�x���g�n���h���ݒ�i�p�[�c���̃`�F�b�N�ƍ݌ɐ��̕\���j
        AddHandler cmbPartsCode_0.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_1.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_2.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_3.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_4.Validating, AddressOf cmbPartsCode_Validating

        '�C�x���g�n���h���ݒ�i���o�ɐ��̃`�F�b�N�j
        AddHandler txtSuuryou_0.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_1.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_2.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_3.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_4.Validating, AddressOf txtSuuryou_Validating

        '�L�[���͐����p�C�x���g�n���h���ݒ�
        AddHandler txtSuuryou_0.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_1.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_2.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_3.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_4.KeyPress, AddressOf KeyPress_NumOnly
    End Sub

    '*** [OK]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        Dim i As Integer
        Dim existFlag As Boolean = False

        '���̓`�F�b�N
        For i = 0 To 4
            If cmbPartsCode(i).Text <> "" Then
                existFlag = True

                If cmbShoriKubun(i).SelectedIndex = 0 Then
                    MsgBox("���o�ɋ敪��I�����Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                    cmbShoriKubun(i).Focus()
                    Return
                End If

                If txtSuuryou(i).Text = "" Then
                    MsgBox("���o�ɐ��ʂ���͂��Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                    txtSuuryou(i).Focus()
                    Return
                ElseIf txtSuuryou(i).Text = 0 Then
                    MsgBox("���o�ɐ��ʂ�1�ȏ����͂��Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                    txtSuuryou(i).Focus()
                    Return
                End If
            End If
        Next
        If Not existFlag Then
            MsgBox("�f�[�^�����͂���Ă��܂���B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Return
        End If

        '���s�m�F
        If MsgBox("���o�ɓ��͂�o�^���܂��B��낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        Dim trans As OleDb.OleDbTransaction
        trans = dbConnection.BeginTransaction()

        '�p�[�c�݌Ƀ}�X�^�I��SQL
        Dim dbcZaikoSelect As New OleDb.OleDbCommand("SELECT ���݌ɐ�, �����_����, �����_�x����, ���i�ɂȂ����� FROM �p�[�c�݌Ƀ}�X�^ WHERE �p�[�c����=?", dbConnection, trans)
        dbcZaikoSelect.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)

        '�p�[�c�݌Ƀ}�X�^�X�VSQL
        Dim dbcZaikoUpdate As New OleDb.OleDbCommand("UPDATE �p�[�c�݌Ƀ}�X�^ SET ���݌ɐ�=?, �����_�x���� = ?, ���i�ɂȂ�����=? WHERE �p�[�c����=?", dbConnection, trans)
        dbcZaikoUpdate.Parameters.Add("���݌ɐ�", Data.OleDb.OleDbType.Integer)
        dbcZaikoUpdate.Parameters.Add("�����_�x����", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("���i�ɂȂ�����", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)

        '�݌ɕύX����ǉ�SQL
        Dim dbcRirekiInsert As New OleDb.OleDbCommand("INSERT INTO �݌ɕύX���� (�p�[�c����, ��������, ���o�ɋ敪, ����) VALUES(?, ?, ?, ?)", dbConnection, trans)
        dbcRirekiInsert.Parameters.Add("�p�[�c����", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("��������", Data.OleDb.OleDbType.DBDate)
        dbcRirekiInsert.Parameters.Add("���o�ɋ敪", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("����", Data.OleDb.OleDbType.Integer)

        'DB�X�V
        Try
            Dim dtNow As DateTime = Now
            Dim row As dsWork.���o�ɓ���Row
            Dim num As Integer
            Dim zaikoOld, zaikoNew, hacchuuten As Integer
            Dim dtrZaiko As OleDb.OleDbDataReader
            For i = 0 To 4
                If cmbPartsCode(i).Text <> "" Then
                    '���o�ɐ��ʎ擾
                    If cmbShoriKubun(i).SelectedValue = "01" Then
                        num = txtSuuryou(i).Text
                    Else
                        num = -txtSuuryou(i).Text
                    End If

                    '�݌ɐ��擾
                    dbcZaikoSelect.Parameters("�p�[�c����").Value = cmbPartsCode(i).Text
                    dtrZaiko = dbcZaikoSelect.ExecuteReader()
                    dtrZaiko.Read()
                    zaikoOld = dtrZaiko("���݌ɐ�")
                    zaikoNew = zaikoOld + num

                    '�����_�x���`�F�b�N
                    If Not dtrZaiko("�����_����") Is DBNull.Value Then
                        hacchuuten = dtrZaiko("�����_����")
                        If zaikoNew >= hacchuuten Then
                            dbcZaikoUpdate.Parameters("�����_�x����").Value = DBNull.Value
                        ElseIf dtrZaiko("�����_�x����") Is DBNull.Value Or (zaikoOld >= hacchuuten And zaikoNew < hacchuuten) Then
                            dbcZaikoUpdate.Parameters("�����_�x����").Value = dtNow
                        Else
                            dbcZaikoUpdate.Parameters("�����_�x����").Value = dtrZaiko("�����_�x����")
                        End If
                    End If

                    '���i�`�F�b�N
                    If zaikoNew >= 0 Then
                        dbcZaikoUpdate.Parameters("���i�ɂȂ�����").Value = DBNull.Value
                    ElseIf dtrZaiko("���i�ɂȂ�����") Is DBNull.Value Or (zaikoOld >= 0 And zaikoNew < 0) Then
                        dbcZaikoUpdate.Parameters("���i�ɂȂ�����").Value = dtNow
                    Else
                        dbcZaikoUpdate.Parameters("���i�ɂȂ�����").Value = dtrZaiko("���i�ɂȂ�����")
                    End If

                    dtrZaiko.Close()

                    '�p�[�c�݌Ƀ}�X�^�X�V
                    dbcZaikoUpdate.Parameters("�p�[�c����").Value = cmbPartsCode(i).Text
                    dbcZaikoUpdate.Parameters("���݌ɐ�").Value = zaikoNew
                    dbcZaikoUpdate.ExecuteNonQuery()

                    dbcRirekiInsert.Parameters("�p�[�c����").Value = cmbPartsCode(i).Text
                    dbcRirekiInsert.Parameters("��������").Value = dtNow
                    dbcRirekiInsert.Parameters("���o�ɋ敪").Value = cmbShoriKubun(i).SelectedValue
                    dbcRirekiInsert.Parameters("����").Value = num
                    dbcRirekiInsert.ExecuteNonQuery()
                End If
            Next
            trans.Commit()
        Catch ex As Exception
            trans.Rollback()
            MsgBox("���o�ɓ��͂̓o�^�ŃG���[���������܂����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '�o�^�������b�Z�[�W
        MsgBox("���o�ɓ��͂̓o�^���������܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)

        '�\�����e�N���A
        For i = 0 To 4
            cmbPartsCode(i).SelectedItem = Nothing
            cmbPartsCode(i).Text = Nothing
            cmbShoriKubun(i).SelectedIndex = 0
            txtZaikoSuu(i).Text = ""
            txtSuuryou(i).Text = ""
        Next
    End Sub

    '*** [����]�{�^���N���b�N�C�x���g
    Private Sub btnCancel_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        Dim existFlag As Boolean = False
        Dim i As Integer

        '���̓`�F�b�N
        For i = 0 To 4
            If cmbPartsCode(i).Text <> "" Then
                existFlag = True
            End If
        Next

        '�f�[�^���͗L���`�F�b�N
        If existFlag Then
            If MsgBox("�f�[�^�����͂���Ă��܂��B�o�^���Ȃ��ŕ��Ă�낵���ł����H", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If

        Me.DialogResult = DialogResult.Cancel
    End Sub

    '*** �p�[�c���̃h���b�v�_�E���C�x���g
    Private Shared Sub cmbPartsCode_DropDown(ByVal sender As Object, ByVal e As System.EventArgs)
        Dim cmb As ComboBox = sender
        Dim dataAdapter As New OleDb.OleDbDataAdapter("SELECT �p�[�c���� FROM �p�[�c�݌Ƀ}�X�^ WHERE �p�[�c���� Like ? ORDER BY �p�[�c����", dbConnection)
        Dim dataSet As New dsTable()

        dataAdapter.SelectCommand.Parameters.Add("�p�[�c����", cmb.Text & "%")
        dataAdapter.Fill(dataSet, "�p�[�c�݌Ƀ}�X�^")
        cmb.DataSource = dataSet.�p�[�c�݌Ƀ}�X�^
        cmb.DisplayMember = "�p�[�c����"
    End Sub

    '*** �p�[�c���̓��͓��e�`�F�b�N�C�x���g
    Private Sub cmbPartsCode_Validating(ByVal sender As Object, ByVal e As System.ComponentModel.CancelEventArgs)
        Dim cmb As ComboBox = sender
        Dim index As Integer = Array.IndexOf(cmbPartsCode, sender)

        If cmb.Text <> "" Then
            '�p�[�c�݌Ƀ}�X�^�o�^�`�F�b�NSQL
            Dim dbCmd As New OleDb.OleDbCommand()
            dbCmd.Connection = Common.dbConnection
            dbCmd.CommandType = CommandType.Text
            dbCmd.CommandText = "SELECT ���݌ɐ� FROM �p�[�c�݌Ƀ}�X�^ WHERE �p�[�c����=?"
            dbCmd.Parameters.Add("�p�[�c����", cmb.Text)

            Dim zaikosuu As Object = dbCmd.ExecuteScalar()
            If zaikosuu Is Nothing Then
                MsgBox("���͂����p�[�c���͓̂o�^����Ă��܂���B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                e.Cancel = True
            Else
                Me.txtZaikoSuu(index).Text = zaikosuu
            End If
        Else
            Me.txtZaikoSuu(index).Text = ""
        End If
    End Sub

    '*** ���ʓ��͓��e�`�F�b�N�C�x���g
    Private Shared Sub txtSuuryou_Validating(ByVal sender As Object, ByVal e As System.ComponentModel.CancelEventArgs)
        Dim txt As TextBox = sender
        If txt.Text <> "" Then
            If Not Char.IsDigit(txt.Text) Then
                MsgBox("��������͂��Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                e.Cancel = True
            End If
        End If
    End Sub

    '*** �p�[�c���̕������̓C�x���g�i�����ƃn�C�t���ȊO�͓��͂ł��Ȃ��悤�ɂ���j
    Private Shared Sub cmbPartsCode_TextChanged(ByVal sender As Object, ByVal e As System.EventArgs)
        Dim cmb As ComboBox = sender
        If cmb.Text.Length > 0 Then
            Dim c As Char = cmb.Text.Chars(cmb.Text.Length - 1)
            If Not (c >= "0" And c <= "9" _
                    Or c >= "A" And c <= "Z" _
                    Or c >= "a" And c <= "z" _
                    Or c = "-") Then
                cmb.Text = cmb.Text.Substring(0, cmb.Text.Length - 1)
                cmb.Select(cmb.Text.Length, 0)
            End If
        End If
    End Sub
End Class
