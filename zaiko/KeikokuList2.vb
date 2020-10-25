Public Class frmKeikokuList2
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
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents btnOK As System.Windows.Forms.Button
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents DsZaiko1 As zaiko.dsWork
    Friend WithEvents DataGridTableStyle1 As System.Windows.Forms.DataGridTableStyle
    Friend WithEvents lblSeizouKubun As System.Windows.Forms.Label
    Friend WithEvents dtgKeikokuList As System.Windows.Forms.DataGrid
    Friend WithEvents 警告日 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents パーツ名称 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 発注点 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 在庫比率 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 在庫数 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents 備考 As System.Windows.Forms.DataGridTextBoxColumn
    Friend WithEvents btnPrint As System.Windows.Forms.Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.lblSeizouKubun = New System.Windows.Forms.Label()
        Me.dtgKeikokuList = New System.Windows.Forms.DataGrid()
        Me.DsZaiko1 = New zaiko.dsWork()
        Me.DataGridTableStyle1 = New System.Windows.Forms.DataGridTableStyle()
        Me.警告日 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.パーツ名称 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.発注点 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.在庫比率 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.在庫数 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.備考 = New System.Windows.Forms.DataGridTextBoxColumn()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.btnPrint = New System.Windows.Forms.Button()
        CType(Me.dtgKeikokuList, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.DsZaiko1, System.ComponentModel.ISupportInitialize).BeginInit()
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
        Me.lblTitle.Size = New System.Drawing.Size(718, 28)
        Me.lblTitle.TabIndex = 2
        Me.lblTitle.Text = "発注点警告リスト一覧表示"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'lblSeizouKubun
        '
        Me.lblSeizouKubun.AutoSize = True
        Me.lblSeizouKubun.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.lblSeizouKubun.Location = New System.Drawing.Point(20, 40)
        Me.lblSeizouKubun.Name = "lblSeizouKubun"
        Me.lblSeizouKubun.Size = New System.Drawing.Size(52, 15)
        Me.lblSeizouKubun.TabIndex = 3
        Me.lblSeizouKubun.Text = "製造分"
        '
        'dtgKeikokuList
        '
        Me.dtgKeikokuList.Anchor = (((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
                    Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.dtgKeikokuList.CaptionVisible = False
        Me.dtgKeikokuList.DataMember = ""
        Me.dtgKeikokuList.DataSource = Me.DsZaiko1.発注点警告リスト
        Me.dtgKeikokuList.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dtgKeikokuList.Location = New System.Drawing.Point(16, 64)
        Me.dtgKeikokuList.Name = "dtgKeikokuList"
        Me.dtgKeikokuList.Size = New System.Drawing.Size(688, 384)
        Me.dtgKeikokuList.TabIndex = 4
        Me.dtgKeikokuList.TableStyles.AddRange(New System.Windows.Forms.DataGridTableStyle() {Me.DataGridTableStyle1})
        '
        'DsZaiko1
        '
        Me.DsZaiko1.DataSetName = "dsZaiko"
        Me.DsZaiko1.Locale = New System.Globalization.CultureInfo("ja-JP")
        Me.DsZaiko1.Namespace = "http://www.tempuri.org/dsZaiko.xsd"
        '
        'DataGridTableStyle1
        '
        Me.DataGridTableStyle1.AlternatingBackColor = System.Drawing.Color.FromArgb(CType(224, Byte), CType(255, Byte), CType(224, Byte))
        Me.DataGridTableStyle1.DataGrid = Me.dtgKeikokuList
        Me.DataGridTableStyle1.GridColumnStyles.AddRange(New System.Windows.Forms.DataGridColumnStyle() {Me.警告日, Me.パーツ名称, Me.発注点, Me.在庫比率, Me.在庫数, Me.備考})
        Me.DataGridTableStyle1.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.DataGridTableStyle1.MappingName = "発注点警告リスト"
        '
        '警告日
        '
        Me.警告日.Format = ""
        Me.警告日.FormatInfo = Nothing
        Me.警告日.HeaderText = "警告日"
        Me.警告日.MappingName = "発注点警告日"
        Me.警告日.NullText = ""
        Me.警告日.ReadOnly = True
        Me.警告日.Width = 75
        '
        'パーツ名称
        '
        Me.パーツ名称.Format = ""
        Me.パーツ名称.FormatInfo = Nothing
        Me.パーツ名称.HeaderText = "パーツ名称"
        Me.パーツ名称.MappingName = "パーツ名称"
        Me.パーツ名称.NullText = ""
        Me.パーツ名称.ReadOnly = True
        Me.パーツ名称.Width = 90
        '
        '発注点
        '
        Me.発注点.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.発注点.Format = ""
        Me.発注点.FormatInfo = Nothing
        Me.発注点.HeaderText = "発注点"
        Me.発注点.MappingName = "発注点数量"
        Me.発注点.NullText = ""
        Me.発注点.ReadOnly = True
        Me.発注点.Width = 75
        '
        '在庫比率
        '
        Me.在庫比率.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.在庫比率.Format = "0\%"
        Me.在庫比率.FormatInfo = Nothing
        Me.在庫比率.HeaderText = "在庫比率"
        Me.在庫比率.MappingName = "在庫比率"
        Me.在庫比率.NullText = ""
        Me.在庫比率.ReadOnly = True
        Me.在庫比率.Width = 75
        '
        '在庫数
        '
        Me.在庫数.Alignment = System.Windows.Forms.HorizontalAlignment.Right
        Me.在庫数.Format = ""
        Me.在庫数.FormatInfo = Nothing
        Me.在庫数.HeaderText = "在庫数"
        Me.在庫数.MappingName = "現在庫数"
        Me.在庫数.NullText = ""
        Me.在庫数.ReadOnly = True
        Me.在庫数.Width = 75
        '
        '備考
        '
        Me.備考.Format = ""
        Me.備考.FormatInfo = Nothing
        Me.備考.HeaderText = "備考（メモ）"
        Me.備考.MappingName = "備考"
        Me.備考.NullText = ""
        Me.備考.Width = 240
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(12, 460)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(698, 4)
        Me.GroupBox1.TabIndex = 5
        Me.GroupBox1.TabStop = False
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(205, 476)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 6
        Me.btnOK.Text = "OK"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.Location = New System.Drawing.Point(421, 476)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 8
        Me.btnClose.Text = "閉じる(&C)"
        '
        'btnPrint
        '
        Me.btnPrint.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnPrint.Location = New System.Drawing.Point(313, 476)
        Me.btnPrint.Name = "btnPrint"
        Me.btnPrint.Size = New System.Drawing.Size(92, 24)
        Me.btnPrint.TabIndex = 7
        Me.btnPrint.Text = "印刷(&P)..."
        '
        'frmKeikokuList2
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(718, 509)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.btnPrint, Me.lblTitle, Me.lblSeizouKubun, Me.GroupBox1, Me.btnOK, Me.btnClose, Me.dtgKeikokuList})
        Me.DockPadding.Top = 2
        Me.MinimizeBox = False
        Me.Name = "frmKeikokuList2"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "KeikokuList2"
        CType(Me.dtgKeikokuList, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.DsZaiko1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    Public seizouKubunCode As String
    Public seizouKubunText As String

    '*** フォームロードイベント
    Private Sub frmKeikokuList2_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        Me.lblSeizouKubun.Text = seizouKubunText

        DsZaiko1.Clear()
        DsZaiko1.発注点警告リスト.DefaultView.AllowNew = False
        DsZaiko1.発注点警告リスト.DefaultView.AllowDelete = False

        Dim dataAdapter As New OleDb.OleDbDataAdapter( _
            "SELECT '" & seizouKubunText & "' AS 製造区分, 発注点警告日, パーツ名称, 発注点数量, round(現在庫数 / 発注点数量 * 100) AS 在庫比率, 現在庫数, 備考" & _
            " FROM パーツ在庫マスタ" & _
            " WHERE 製造区分='" & seizouKubunCode & "' AND 現在庫数 < 発注点数量" & _
            " ORDER BY 発注点警告日,パーツ名称", _
            dbConnection)
        Dim rcKeikokuList As ReportClass = New KeikokuList()

        dataAdapter.Fill(DsZaiko1, "発注点警告リスト")

        With dtgKeikokuList.TableStyles(0)
            '最大入力文字数設定
            CType(.GridColumnStyles("備考"), DataGridTextBoxColumn).TextBox.MaxLength = 50
        End With
    End Sub

    '*** [OK]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        Dim dataAdapter As New OleDb.OleDbDataAdapter()
        dataAdapter.UpdateCommand = New OleDb.OleDbCommand( _
            "UPDATE パーツ在庫マスタ SET 備考=? WHERE パーツ名称=?", _
            dbConnection)
        dataAdapter.UpdateCommand.Parameters.Add("備考", OleDb.OleDbType.VarWChar, 50, "備考")
        dataAdapter.UpdateCommand.Parameters.Add("パーツ名称", OleDb.OleDbType.VarWChar, 9, "パーツ名称")

        Try
            dataAdapter.Update(DsZaiko1, "発注点警告リスト")
        Catch ex As Exception
            MsgBox("パーツ在庫マスタの更新でエラーが発生しました。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Exclamation, APPNAME)
            Return
        End Try

        MsgBox("発注点警告リストの登録が完了しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
        DialogResult = DialogResult.OK
    End Sub

    '*** [印刷]ボタンクリックイベント
    Private Sub btnPrint_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPrint.Click
        Dim rcKeikokuList As ReportClass = New KeikokuList()
        ShowPrintForm(lblTitle.Text, rcKeikokuList, DsZaiko1)
    End Sub

    Private Sub btnClose_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnClose.Click
        'データ変更チェック
        If CheckTableUpdate(DsZaiko1.発注点警告リスト) Then
            If MsgBox("データが変更されています。登録しないで閉じてよろしいですか？", MsgBoxStyle.OKCancel Or MsgBoxStyle.Question, APPNAME) = MsgBoxResult.Cancel Then
                Return
            End If
        End If
        DialogResult = DialogResult.Cancel
    End Sub
End Class
