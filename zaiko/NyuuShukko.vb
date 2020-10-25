Public Class frmNyuuShukko
    Inherits System.Windows.Forms.Form

#Region " Windows フォーム デザイナで生成されたコード "

    Public Sub New()
        MyBase.New()

        ' この呼び出しは Windows フォーム デザイナで必要です。
        InitializeComponent()

        ' InitializeComponent() 呼び出しの後に初期化を追加します。

    End Sub

    ' Form は dispose をオーバーライドしてコンポーネント一覧を消去します。
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub

    ' Windows フォーム デザイナで必要です。
    Private components As System.ComponentModel.IContainer

    ' メモ : 以下のプロシージャは、Windows フォーム デザイナで必要です。
    ' Windows フォーム デザイナを使って変更してください。  
    ' コード エディタは使用しないでください。
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
        Me.lblTitle.Text = "入庫・出庫入力"
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
        Me.btnClose.Text = "閉じる(&C)"
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
        Me.Label1.Text = "パーツ名称"
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label2.Location = New System.Drawing.Point(188, 12)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(83, 15)
        Me.Label2.TabIndex = 4
        Me.Label2.Text = "入出庫区分"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(408, 12)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(83, 15)
        Me.Label3.TabIndex = 6
        Me.Label3.Text = "入出庫数量"
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(324, 12)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(67, 15)
        Me.Label4.TabIndex = 5
        Me.Label4.Text = "現在庫数"
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

    'コントロール配列
    Private cmbPartsCode() As ComboBox
    Private cmbShoriKubun() As ComboBox
    Private txtZaikoSuu() As TextBox
    Private txtSuuryou() As TextBox

    '*** フォームロードイベント
    Private Sub frmNyuuShukko_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Dim i As Integer

        'タイトル設定
        Me.Text = Common.TITLE

        'コントロール配列設定
        cmbPartsCode = New ComboBox() {cmbPartsCode_0, cmbPartsCode_1, cmbPartsCode_2, cmbPartsCode_3, cmbPartsCode_4}
        cmbShoriKubun = New ComboBox() {cmbShoriKubun_0, cmbShoriKubun_1, cmbShoriKubun_2, cmbShoriKubun_3, cmbShoriKubun_4}
        txtZaikoSuu = New TextBox() {txtZaikoSuu_0, txtZaikoSuu_1, txtZaikoSuu_2, txtZaikoSuu_3, txtZaikoSuu_4}
        txtSuuryou = New TextBox() {txtSuuryou_0, txtSuuryou_1, txtSuuryou_2, txtSuuryou_3, txtSuuryou_4}

        '処理区分ドロップダウン
        For i = 0 To 4
            Dim listSource As New ArrayList()
            listSource.Add(New ListSource("", ""))
            listSource.Add(New ListSource("入庫", "01"))
            listSource.Add(New ListSource("出庫", "03"))
            listSource.Add(New ListSource("廃棄", "04"))
            cmbShoriKubun(i).DataSource = listSource
            cmbShoriKubun(i).DisplayMember = "DisplayMember"
            cmbShoriKubun(i).ValueMember = "ValueMember"
        Next

        'イベントハンドラ設定（ドロップダウンでパーツ名称をセット）
        AddHandler cmbPartsCode_0.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_1.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_2.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_3.DropDown, AddressOf cmbPartsCode_DropDown
        AddHandler cmbPartsCode_4.DropDown, AddressOf cmbPartsCode_DropDown

        'イベントハンドラ設定（パーツ名称の入力文字制限）
        AddHandler cmbPartsCode_0.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_1.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_2.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_3.TextChanged, AddressOf cmbPartsCode_TextChanged
        AddHandler cmbPartsCode_4.TextChanged, AddressOf cmbPartsCode_TextChanged

        'イベントハンドラ設定（パーツ名称チェックと在庫数の表示）
        AddHandler cmbPartsCode_0.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_1.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_2.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_3.Validating, AddressOf cmbPartsCode_Validating
        AddHandler cmbPartsCode_4.Validating, AddressOf cmbPartsCode_Validating

        'イベントハンドラ設定（入出庫数のチェック）
        AddHandler txtSuuryou_0.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_1.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_2.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_3.Validating, AddressOf txtSuuryou_Validating
        AddHandler txtSuuryou_4.Validating, AddressOf txtSuuryou_Validating

        'キー入力制限用イベントハンドラ設定
        AddHandler txtSuuryou_0.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_1.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_2.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_3.KeyPress, AddressOf KeyPress_NumOnly
        AddHandler txtSuuryou_4.KeyPress, AddressOf KeyPress_NumOnly
    End Sub

    '*** [OK]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        Dim i As Integer
        Dim existFlag As Boolean = False

        '入力チェック
        For i = 0 To 4
            If cmbPartsCode(i).Text <> "" Then
                existFlag = True

                If cmbShoriKubun(i).SelectedIndex = 0 Then
                    MsgBox("入出庫区分を選択してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                    cmbShoriKubun(i).Focus()
                    Return
                End If

                If txtSuuryou(i).Text = "" Then
                    MsgBox("入出庫数量を入力してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                    txtSuuryou(i).Focus()
                    Return
                ElseIf txtSuuryou(i).Text = 0 Then
                    MsgBox("入出庫数量は1以上を入力してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                    txtSuuryou(i).Focus()
                    Return
                End If
            End If
        Next
        If Not existFlag Then
            MsgBox("データが入力されていません。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Return
        End If

        '実行確認
        If MsgBox("入出庫入力を登録します。よろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
            Return
        End If

        Dim trans As OleDb.OleDbTransaction
        trans = dbConnection.BeginTransaction()

        'パーツ在庫マスタ選択SQL
        Dim dbcZaikoSelect As New OleDb.OleDbCommand("SELECT 現在庫数, 発注点数量, 発注点警告日, 欠品になった日 FROM パーツ在庫マスタ WHERE パーツ名称=?", dbConnection, trans)
        dbcZaikoSelect.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)

        'パーツ在庫マスタ更新SQL
        Dim dbcZaikoUpdate As New OleDb.OleDbCommand("UPDATE パーツ在庫マスタ SET 現在庫数=?, 発注点警告日 = ?, 欠品になった日=? WHERE パーツ名称=?", dbConnection, trans)
        dbcZaikoUpdate.Parameters.Add("現在庫数", Data.OleDb.OleDbType.Integer)
        dbcZaikoUpdate.Parameters.Add("発注点警告日", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("欠品になった日", Data.OleDb.OleDbType.DBDate)
        dbcZaikoUpdate.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)

        '在庫変更履歴追加SQL
        Dim dbcRirekiInsert As New OleDb.OleDbCommand("INSERT INTO 在庫変更履歴 (パーツ名称, 処理日時, 入出庫区分, 数量) VALUES(?, ?, ?, ?)", dbConnection, trans)
        dbcRirekiInsert.Parameters.Add("パーツ名称", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("処理日時", Data.OleDb.OleDbType.DBDate)
        dbcRirekiInsert.Parameters.Add("入出庫区分", Data.OleDb.OleDbType.VarWChar)
        dbcRirekiInsert.Parameters.Add("数量", Data.OleDb.OleDbType.Integer)

        'DB更新
        Try
            Dim dtNow As DateTime = Now
            Dim row As dsWork.入出庫入力Row
            Dim num As Integer
            Dim zaikoOld, zaikoNew, hacchuuten As Integer
            Dim dtrZaiko As OleDb.OleDbDataReader
            For i = 0 To 4
                If cmbPartsCode(i).Text <> "" Then
                    '入出庫数量取得
                    If cmbShoriKubun(i).SelectedValue = "01" Then
                        num = txtSuuryou(i).Text
                    Else
                        num = -txtSuuryou(i).Text
                    End If

                    '在庫数取得
                    dbcZaikoSelect.Parameters("パーツ名称").Value = cmbPartsCode(i).Text
                    dtrZaiko = dbcZaikoSelect.ExecuteReader()
                    dtrZaiko.Read()
                    zaikoOld = dtrZaiko("現在庫数")
                    zaikoNew = zaikoOld + num

                    '発注点警告チェック
                    If Not dtrZaiko("発注点数量") Is DBNull.Value Then
                        hacchuuten = dtrZaiko("発注点数量")
                        If zaikoNew >= hacchuuten Then
                            dbcZaikoUpdate.Parameters("発注点警告日").Value = DBNull.Value
                        ElseIf dtrZaiko("発注点警告日") Is DBNull.Value Or (zaikoOld >= hacchuuten And zaikoNew < hacchuuten) Then
                            dbcZaikoUpdate.Parameters("発注点警告日").Value = dtNow
                        Else
                            dbcZaikoUpdate.Parameters("発注点警告日").Value = dtrZaiko("発注点警告日")
                        End If
                    End If

                    '欠品チェック
                    If zaikoNew >= 0 Then
                        dbcZaikoUpdate.Parameters("欠品になった日").Value = DBNull.Value
                    ElseIf dtrZaiko("欠品になった日") Is DBNull.Value Or (zaikoOld >= 0 And zaikoNew < 0) Then
                        dbcZaikoUpdate.Parameters("欠品になった日").Value = dtNow
                    Else
                        dbcZaikoUpdate.Parameters("欠品になった日").Value = dtrZaiko("欠品になった日")
                    End If

                    dtrZaiko.Close()

                    'パーツ在庫マスタ更新
                    dbcZaikoUpdate.Parameters("パーツ名称").Value = cmbPartsCode(i).Text
                    dbcZaikoUpdate.Parameters("現在庫数").Value = zaikoNew
                    dbcZaikoUpdate.ExecuteNonQuery()

                    dbcRirekiInsert.Parameters("パーツ名称").Value = cmbPartsCode(i).Text
                    dbcRirekiInsert.Parameters("処理日時").Value = dtNow
                    dbcRirekiInsert.Parameters("入出庫区分").Value = cmbShoriKubun(i).SelectedValue
                    dbcRirekiInsert.Parameters("数量").Value = num
                    dbcRirekiInsert.ExecuteNonQuery()
                End If
            Next
            trans.Commit()
        Catch ex As Exception
            trans.Rollback()
            MsgBox("入出庫入力の登録でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        '登録完了メッセージ
        MsgBox("入出庫入力の登録が完了しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)

        '表示内容クリア
        For i = 0 To 4
            cmbPartsCode(i).SelectedItem = Nothing
            cmbPartsCode(i).Text = Nothing
            cmbShoriKubun(i).SelectedIndex = 0
            txtZaikoSuu(i).Text = ""
            txtSuuryou(i).Text = ""
        Next
    End Sub

    '*** [閉じる]ボタンクリックイベント
    Private Sub btnCancel_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        Dim existFlag As Boolean = False
        Dim i As Integer

        '入力チェック
        For i = 0 To 4
            If cmbPartsCode(i).Text <> "" Then
                existFlag = True
            End If
        Next

        'データ入力有無チェック
        If existFlag Then
            If MsgBox("データが入力されています。登録しないで閉じてよろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If

        Me.DialogResult = DialogResult.Cancel
    End Sub

    '*** パーツ名称ドロップダウンイベント
    Private Shared Sub cmbPartsCode_DropDown(ByVal sender As Object, ByVal e As System.EventArgs)
        Dim cmb As ComboBox = sender
        Dim dataAdapter As New OleDb.OleDbDataAdapter("SELECT パーツ名称 FROM パーツ在庫マスタ WHERE パーツ名称 Like ? ORDER BY パーツ名称", dbConnection)
        Dim dataSet As New dsTable()

        dataAdapter.SelectCommand.Parameters.Add("パーツ名称", cmb.Text & "%")
        dataAdapter.Fill(dataSet, "パーツ在庫マスタ")
        cmb.DataSource = dataSet.パーツ在庫マスタ
        cmb.DisplayMember = "パーツ名称"
    End Sub

    '*** パーツ名称入力内容チェックイベント
    Private Sub cmbPartsCode_Validating(ByVal sender As Object, ByVal e As System.ComponentModel.CancelEventArgs)
        Dim cmb As ComboBox = sender
        Dim index As Integer = Array.IndexOf(cmbPartsCode, sender)

        If cmb.Text <> "" Then
            'パーツ在庫マスタ登録チェックSQL
            Dim dbCmd As New OleDb.OleDbCommand()
            dbCmd.Connection = Common.dbConnection
            dbCmd.CommandType = CommandType.Text
            dbCmd.CommandText = "SELECT 現在庫数 FROM パーツ在庫マスタ WHERE パーツ名称=?"
            dbCmd.Parameters.Add("パーツ名称", cmb.Text)

            Dim zaikosuu As Object = dbCmd.ExecuteScalar()
            If zaikosuu Is Nothing Then
                MsgBox("入力したパーツ名称は登録されていません。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                e.Cancel = True
            Else
                Me.txtZaikoSuu(index).Text = zaikosuu
            End If
        Else
            Me.txtZaikoSuu(index).Text = ""
        End If
    End Sub

    '*** 数量入力内容チェックイベント
    Private Shared Sub txtSuuryou_Validating(ByVal sender As Object, ByVal e As System.ComponentModel.CancelEventArgs)
        Dim txt As TextBox = sender
        If txt.Text <> "" Then
            If Not Char.IsDigit(txt.Text) Then
                MsgBox("数字を入力してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
                e.Cancel = True
            End If
        End If
    End Sub

    '*** パーツ名称文字入力イベント（数字とハイフン以外は入力できないようにする）
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
