Public Class frmMasterMaintenance
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
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents btnPartsZaiko As System.Windows.Forms.Button
    Friend WithEvents btnPartsTenkai As System.Windows.Forms.Button
    Friend WithEvents btnPartsShubetsu As System.Windows.Forms.Button
    Friend WithEvents btnKokyaku As System.Windows.Forms.Button
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents btnUser As System.Windows.Forms.Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.btnPartsZaiko = New System.Windows.Forms.Button()
        Me.btnPartsTenkai = New System.Windows.Forms.Button()
        Me.btnPartsShubetsu = New System.Windows.Forms.Button()
        Me.btnKokyaku = New System.Windows.Forms.Button()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.btnUser = New System.Windows.Forms.Button()
        Me.SuspendLayout()
        '
        'lblTitle
        '
        Me.lblTitle.BackColor = System.Drawing.Color.LightSteelBlue
        Me.lblTitle.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D
        Me.lblTitle.Dock = System.Windows.Forms.DockStyle.Top
        Me.lblTitle.Font = New System.Drawing.Font("MS UI Gothic", 16.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblTitle.ForeColor = System.Drawing.Color.Maroon
        Me.lblTitle.Location = New System.Drawing.Point(0, 2)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(530, 28)
        Me.lblTitle.TabIndex = 1
        Me.lblTitle.Text = "マスターメンテナンス"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(219, 216)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "閉じる(&C)"
        '
        'btnPartsZaiko
        '
        Me.btnPartsZaiko.Location = New System.Drawing.Point(60, 60)
        Me.btnPartsZaiko.Name = "btnPartsZaiko"
        Me.btnPartsZaiko.Size = New System.Drawing.Size(188, 28)
        Me.btnPartsZaiko.TabIndex = 2
        Me.btnPartsZaiko.Text = "パーツ在庫マスター"
        '
        'btnPartsTenkai
        '
        Me.btnPartsTenkai.Location = New System.Drawing.Point(60, 104)
        Me.btnPartsTenkai.Name = "btnPartsTenkai"
        Me.btnPartsTenkai.Size = New System.Drawing.Size(188, 28)
        Me.btnPartsTenkai.TabIndex = 3
        Me.btnPartsTenkai.Text = "パーツ展開マスター"
        '
        'btnPartsShubetsu
        '
        Me.btnPartsShubetsu.Location = New System.Drawing.Point(60, 148)
        Me.btnPartsShubetsu.Name = "btnPartsShubetsu"
        Me.btnPartsShubetsu.Size = New System.Drawing.Size(188, 28)
        Me.btnPartsShubetsu.TabIndex = 4
        Me.btnPartsShubetsu.Text = "パーツ種別マスター"
        '
        'btnKokyaku
        '
        Me.btnKokyaku.Location = New System.Drawing.Point(276, 60)
        Me.btnKokyaku.Name = "btnKokyaku"
        Me.btnKokyaku.Size = New System.Drawing.Size(188, 28)
        Me.btnKokyaku.TabIndex = 5
        Me.btnKokyaku.Text = "顧客マスター"
        '
        'GroupBox2
        '
        Me.GroupBox2.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox2.Location = New System.Drawing.Point(7, 200)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox2.TabIndex = 7
        Me.GroupBox2.TabStop = False
        '
        'btnUser
        '
        Me.btnUser.Location = New System.Drawing.Point(276, 104)
        Me.btnUser.Name = "btnUser"
        Me.btnUser.Size = New System.Drawing.Size(188, 28)
        Me.btnUser.TabIndex = 6
        Me.btnUser.Text = "ユーザマスター"
        '
        'frmMasterMaintenance
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 251)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox2, Me.btnClose, Me.lblTitle, Me.btnPartsTenkai, Me.btnPartsShubetsu, Me.btnKokyaku, Me.btnPartsZaiko, Me.btnUser})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmMasterMaintenance"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "MasterMaintenance"
        Me.ResumeLayout(False)

    End Sub

#End Region

    '*** フォームロードイベント
    Private Sub frmMasterMaintenance_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
    End Sub

    '*** フォーム表示処理
    Private Sub ExecMenu(ByRef frm As Form)
        frm.ShowDialog()
        frm.Dispose()
    End Sub

    '*** [パーツ在庫マスタ]ボタンクリックイベント
    Private Sub btnPartsZaiko_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPartsZaiko.Click
        ExecMenu(New PartsZaiko())
    End Sub

    '*** [パーツ展開マスタボ]タンクリックイベント
    Private Sub btnPartsTenkai_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPartsTenkai.Click
        ExecMenu(New PartsTenkai())
    End Sub

    '*** [パーツ種別マスタ]ボタンクリックイベント
    Private Sub btnPartsShubetsu_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPartsShubetsu.Click
        ExecMenu(New PartsShubetsu())
    End Sub

    '*** [顧客マスタ]ボタンクリックイベント
    Private Sub btnKokyaku_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnKokyaku.Click
        ExecMenu(New Kokyaku())
    End Sub

    '*** [ユーザマスタ]ボタンクリックイベント
    Private Sub btnUser_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnUser.Click
        ExecMenu(New User())
    End Sub
End Class
