Public Class frmMenu
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
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents GroupBox3 As System.Windows.Forms.GroupBox
    Friend WithEvents sbpCopyRight As System.Windows.Forms.StatusBarPanel
    Friend WithEvents btnJuchuMisumi As System.Windows.Forms.Button
    Friend WithEvents btnJuchuSankyo As System.Windows.Forms.Button
    Friend WithEvents btnJuchuInput As System.Windows.Forms.Button
    Friend WithEvents btnPartsTenkai As System.Windows.Forms.Button
    Friend WithEvents btnNyuuShukko As System.Windows.Forms.Button
    Friend WithEvents btnTanaoroshi As System.Windows.Forms.Button
    Friend WithEvents btnMasterMaintenance As System.Windows.Forms.Button
    Friend WithEvents btnExit As System.Windows.Forms.Button
    Friend WithEvents lblTitle As System.Windows.Forms.Label
    Friend WithEvents btnPartsZaikoList As System.Windows.Forms.Button
    Friend WithEvents btnKeikokuList As System.Windows.Forms.Button
    Friend WithEvents btnKeppinList As System.Windows.Forms.Button
    Friend WithEvents btnShukkoData As System.Windows.Forms.Button
    Friend WithEvents stbCopyRight As System.Windows.Forms.StatusBar
    Friend WithEvents btnPickingList As System.Windows.Forms.Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.btnPickingList = New System.Windows.Forms.Button()
        Me.btnPartsTenkai = New System.Windows.Forms.Button()
        Me.btnJuchuInput = New System.Windows.Forms.Button()
        Me.btnJuchuSankyo = New System.Windows.Forms.Button()
        Me.btnJuchuMisumi = New System.Windows.Forms.Button()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.btnTanaoroshi = New System.Windows.Forms.Button()
        Me.btnNyuuShukko = New System.Windows.Forms.Button()
        Me.btnPartsZaikoList = New System.Windows.Forms.Button()
        Me.btnKeikokuList = New System.Windows.Forms.Button()
        Me.btnKeppinList = New System.Windows.Forms.Button()
        Me.GroupBox3 = New System.Windows.Forms.GroupBox()
        Me.btnMasterMaintenance = New System.Windows.Forms.Button()
        Me.btnShukkoData = New System.Windows.Forms.Button()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.stbCopyRight = New System.Windows.Forms.StatusBar()
        Me.sbpCopyRight = New System.Windows.Forms.StatusBarPanel()
        Me.btnExit = New System.Windows.Forms.Button()
        Me.GroupBox1.SuspendLayout()
        Me.GroupBox2.SuspendLayout()
        Me.GroupBox3.SuspendLayout()
        CType(Me.sbpCopyRight, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.AddRange(New System.Windows.Forms.Control() {Me.btnPickingList, Me.btnPartsTenkai, Me.btnJuchuInput, Me.btnJuchuSankyo, Me.btnJuchuMisumi})
        Me.GroupBox1.ForeColor = System.Drawing.Color.Navy
        Me.GroupBox1.Location = New System.Drawing.Point(8, 40)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(240, 344)
        Me.GroupBox1.TabIndex = 2
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "日次定型業務"
        '
        'btnPickingList
        '
        Me.btnPickingList.Location = New System.Drawing.Point(20, 216)
        Me.btnPickingList.Name = "btnPickingList"
        Me.btnPickingList.Size = New System.Drawing.Size(200, 32)
        Me.btnPickingList.TabIndex = 67
        Me.btnPickingList.Text = "ピッキングリスト印刷"
        '
        'btnPartsTenkai
        '
        Me.btnPartsTenkai.Location = New System.Drawing.Point(20, 168)
        Me.btnPartsTenkai.Name = "btnPartsTenkai"
        Me.btnPartsTenkai.Size = New System.Drawing.Size(200, 32)
        Me.btnPartsTenkai.TabIndex = 66
        Me.btnPartsTenkai.Text = "パーツ展開・在庫引当"
        '
        'btnJuchuInput
        '
        Me.btnJuchuInput.Location = New System.Drawing.Point(20, 120)
        Me.btnJuchuInput.Name = "btnJuchuInput"
        Me.btnJuchuInput.Size = New System.Drawing.Size(200, 32)
        Me.btnJuchuInput.TabIndex = 5
        Me.btnJuchuInput.Text = "受注データ入力"
        '
        'btnJuchuSankyo
        '
        Me.btnJuchuSankyo.Location = New System.Drawing.Point(20, 72)
        Me.btnJuchuSankyo.Name = "btnJuchuSankyo"
        Me.btnJuchuSankyo.Size = New System.Drawing.Size(200, 32)
        Me.btnJuchuSankyo.TabIndex = 4
        Me.btnJuchuSankyo.Text = "受注データ取り込み（三協）"
        '
        'btnJuchuMisumi
        '
        Me.btnJuchuMisumi.Location = New System.Drawing.Point(20, 24)
        Me.btnJuchuMisumi.Name = "btnJuchuMisumi"
        Me.btnJuchuMisumi.Size = New System.Drawing.Size(200, 32)
        Me.btnJuchuMisumi.TabIndex = 3
        Me.btnJuchuMisumi.Text = "受注データ取り込み（ミスミ）"
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.AddRange(New System.Windows.Forms.Control() {Me.btnTanaoroshi, Me.btnNyuuShukko, Me.btnPartsZaikoList, Me.btnKeikokuList, Me.btnKeppinList})
        Me.GroupBox2.ForeColor = System.Drawing.Color.Green
        Me.GroupBox2.Location = New System.Drawing.Point(260, 40)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(240, 344)
        Me.GroupBox2.TabIndex = 7
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "随時業務"
        '
        'btnTanaoroshi
        '
        Me.btnTanaoroshi.Location = New System.Drawing.Point(20, 72)
        Me.btnTanaoroshi.Name = "btnTanaoroshi"
        Me.btnTanaoroshi.Size = New System.Drawing.Size(200, 32)
        Me.btnTanaoroshi.TabIndex = 8
        Me.btnTanaoroshi.Text = "棚卸入力"
        '
        'btnNyuuShukko
        '
        Me.btnNyuuShukko.Location = New System.Drawing.Point(20, 24)
        Me.btnNyuuShukko.Name = "btnNyuuShukko"
        Me.btnNyuuShukko.Size = New System.Drawing.Size(200, 32)
        Me.btnNyuuShukko.TabIndex = 1
        Me.btnNyuuShukko.Text = "入庫・出庫入力"
        '
        'btnPartsZaikoList
        '
        Me.btnPartsZaikoList.Location = New System.Drawing.Point(20, 120)
        Me.btnPartsZaikoList.Name = "btnPartsZaikoList"
        Me.btnPartsZaikoList.Size = New System.Drawing.Size(200, 32)
        Me.btnPartsZaikoList.TabIndex = 9
        Me.btnPartsZaikoList.Text = "パーツ在庫リスト印刷"
        '
        'btnKeikokuList
        '
        Me.btnKeikokuList.Location = New System.Drawing.Point(20, 168)
        Me.btnKeikokuList.Name = "btnKeikokuList"
        Me.btnKeikokuList.Size = New System.Drawing.Size(200, 32)
        Me.btnKeikokuList.TabIndex = 10
        Me.btnKeikokuList.Text = "発注点警告リスト印刷"
        '
        'btnKeppinList
        '
        Me.btnKeppinList.Location = New System.Drawing.Point(20, 216)
        Me.btnKeppinList.Name = "btnKeppinList"
        Me.btnKeppinList.Size = New System.Drawing.Size(200, 32)
        Me.btnKeppinList.TabIndex = 11
        Me.btnKeppinList.Text = "欠品リスト印刷"
        '
        'GroupBox3
        '
        Me.GroupBox3.Controls.AddRange(New System.Windows.Forms.Control() {Me.btnMasterMaintenance, Me.btnShukkoData})
        Me.GroupBox3.ForeColor = System.Drawing.Color.Maroon
        Me.GroupBox3.Location = New System.Drawing.Point(512, 40)
        Me.GroupBox3.Name = "GroupBox3"
        Me.GroupBox3.Size = New System.Drawing.Size(240, 120)
        Me.GroupBox3.TabIndex = 12
        Me.GroupBox3.TabStop = False
        Me.GroupBox3.Text = "その他"
        '
        'btnMasterMaintenance
        '
        Me.btnMasterMaintenance.Location = New System.Drawing.Point(20, 72)
        Me.btnMasterMaintenance.Name = "btnMasterMaintenance"
        Me.btnMasterMaintenance.Size = New System.Drawing.Size(200, 32)
        Me.btnMasterMaintenance.TabIndex = 13
        Me.btnMasterMaintenance.Text = "マスターメンテナンス"
        '
        'btnShukkoData
        '
        Me.btnShukkoData.Location = New System.Drawing.Point(20, 24)
        Me.btnShukkoData.Name = "btnShukkoData"
        Me.btnShukkoData.Size = New System.Drawing.Size(200, 32)
        Me.btnShukkoData.TabIndex = 3
        Me.btnShukkoData.Text = "月別出庫データ出力"
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
        Me.lblTitle.Size = New System.Drawing.Size(766, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "在庫管理システムメニュー"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'stbCopyRight
        '
        Me.stbCopyRight.Location = New System.Drawing.Point(0, 397)
        Me.stbCopyRight.Name = "stbCopyRight"
        Me.stbCopyRight.Panels.AddRange(New System.Windows.Forms.StatusBarPanel() {Me.sbpCopyRight})
        Me.stbCopyRight.ShowPanels = True
        Me.stbCopyRight.Size = New System.Drawing.Size(766, 22)
        Me.stbCopyRight.SizingGrip = False
        Me.stbCopyRight.TabIndex = 5
        Me.stbCopyRight.Text = "StatusBar1"
        '
        'sbpCopyRight
        '
        Me.sbpCopyRight.Alignment = System.Windows.Forms.HorizontalAlignment.Center
        Me.sbpCopyRight.AutoSize = System.Windows.Forms.StatusBarPanelAutoSize.Spring
        Me.sbpCopyRight.Width = 766
        '
        'btnExit
        '
        Me.btnExit.Location = New System.Drawing.Point(536, 344)
        Me.btnExit.Name = "btnExit"
        Me.btnExit.Size = New System.Drawing.Size(200, 32)
        Me.btnExit.TabIndex = 14
        Me.btnExit.Text = "システム終了(&X)"
        '
        'frmMenu
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(766, 419)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.btnExit, Me.stbCopyRight, Me.GroupBox3, Me.GroupBox2, Me.GroupBox1, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.Name = "frmMenu"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Menu"
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox3.ResumeLayout(False)
        CType(Me.sbpCopyRight, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub frmMenu_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        Me.sbpCopyRight.Text = Common.COPYRIGHT

        Select Case Common.userLevel
            Case 2
                Me.btnMasterMaintenance.Enabled = False
            Case 3
                Me.btnJuchuMisumi.Enabled = False
                Me.btnJuchuSankyo.Enabled = False
                Me.btnJuchuInput.Enabled = False
                Me.btnPartsTenkai.Enabled = False
                Me.btnPickingList.Enabled = False
                Me.btnNyuuShukko.Enabled = False
                Me.btnTanaoroshi.Enabled = False
                Me.btnShukkoData.Enabled = False
                Me.btnMasterMaintenance.Enabled = False
        End Select
    End Sub

    '*** 終了ボタンクリックイベント
    Private Sub btnExit_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnExit.Click
        If MsgBox("システムを終了します。よろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, Common.APPNAME) = MsgBoxResult.OK Then
            Me.DialogResult = DialogResult.OK
        End If
    End Sub

    '*** フォーム表示処理
    Private Sub ExecMenu(ByRef frm As Form)
        frm.ShowDialog()
        frm.Dispose()
    End Sub

    Private Sub btnJuchuMisumi_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnJuchuMisumi.Click
        ExecMenu(New frmJuchuMisumi())
    End Sub

    Private Sub btnJuchuSankyo_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnJuchuSankyo.Click
        ExecMenu(New frmJuchuSankyo())
    End Sub

    Private Sub btnJuchuInput_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnJuchuInput.Click
        ExecMenu(New frmJuchuInput())
    End Sub

    Private Sub btnPartsTenkai_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPartsTenkai.Click
        ExecMenu(New frmPartsTenkai())
    End Sub

    Private Sub btnNyuuShukko_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnNyuuShukko.Click
        ExecMenu(New frmNyuuShukko())
    End Sub

    Private Sub btnTanaoroshi_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnTanaoroshi.Click
        ExecMenu(New frmTanaoroshi())
    End Sub

    Private Sub btnPartsZaikoList_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPartsZaikoList.Click
        ExecMenu(New frmPartsZaikoList())
    End Sub

    Private Sub btnKeikokuList_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnKeikokuList.Click
        ExecMenu(New frmKeikokuList())
    End Sub

    Private Sub btnKeppinList_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnKeppinList.Click
        ExecMenu(New frmKeppinList())
    End Sub

    Private Sub btnShukkoData_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnShukkoData.Click
        ExecMenu(New frmShukkoData())
    End Sub

    Private Sub btnMasterMaintenance_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnMasterMaintenance.Click
        ExecMenu(New frmMasterMaintenance())
    End Sub

    Private Sub btnPickingList_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPickingList.Click
        ExecMenu(New frmPickingList())
    End Sub
End Class
