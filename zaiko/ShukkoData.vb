Public Class frmShukkoData
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
    Friend WithEvents btnFileDialog As System.Windows.Forms.Button
    Friend WithEvents txtFileName As System.Windows.Forms.TextBox
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents btnClose As System.Windows.Forms.Button
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents cmbKokyaku As System.Windows.Forms.ComboBox
    Friend WithEvents Label5 As System.Windows.Forms.Label
    Friend WithEvents radShouhin As System.Windows.Forms.RadioButton
    Friend WithEvents radParts As System.Windows.Forms.RadioButton
    Friend WithEvents GroupBox3 As System.Windows.Forms.GroupBox
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents radShukko As System.Windows.Forms.RadioButton
    Friend WithEvents radJuchuu As System.Windows.Forms.RadioButton
    Friend WithEvents dtpDate As System.Windows.Forms.DateTimePicker
    Friend WithEvents nudMonth As System.Windows.Forms.NumericUpDown
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.btnOK = New System.Windows.Forms.Button()
        Me.btnFileDialog = New System.Windows.Forms.Button()
        Me.txtFileName = New System.Windows.Forms.TextBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.btnClose = New System.Windows.Forms.Button()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.cmbKokyaku = New System.Windows.Forms.ComboBox()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.radShouhin = New System.Windows.Forms.RadioButton()
        Me.radParts = New System.Windows.Forms.RadioButton()
        Me.GroupBox3 = New System.Windows.Forms.GroupBox()
        Me.nudMonth = New System.Windows.Forms.NumericUpDown()
        Me.dtpDate = New System.Windows.Forms.DateTimePicker()
        Me.radShukko = New System.Windows.Forms.RadioButton()
        Me.radJuchuu = New System.Windows.Forms.RadioButton()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.GroupBox2.SuspendLayout()
        Me.GroupBox3.SuspendLayout()
        CType(Me.nudMonth, System.ComponentModel.ISupportInitialize).BeginInit()
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
        Me.lblTitle.Text = "月別出庫データ出力"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(174, 372)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 20
        Me.btnOK.Text = "出力(&O)"
        '
        'btnFileDialog
        '
        Me.btnFileDialog.Location = New System.Drawing.Point(429, 316)
        Me.btnFileDialog.Name = "btnFileDialog"
        Me.btnFileDialog.Size = New System.Drawing.Size(76, 24)
        Me.btnFileDialog.TabIndex = 18
        Me.btnFileDialog.Text = "参照(&R)..."
        '
        'txtFileName
        '
        Me.txtFileName.Location = New System.Drawing.Point(137, 288)
        Me.txtFileName.Name = "txtFileName"
        Me.txtFileName.Size = New System.Drawing.Size(368, 19)
        Me.txtFileName.TabIndex = 17
        Me.txtFileName.Text = ""
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label1.Location = New System.Drawing.Point(21, 292)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(109, 15)
        Me.Label1.TabIndex = 16
        Me.Label1.Text = "出力CSVファイル"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(282, 372)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 21
        Me.btnClose.Text = "閉じる(&C)"
        '
        'GroupBox1
        '
        Me.GroupBox1.Anchor = ((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
                    Or System.Windows.Forms.AnchorStyles.Right)
        Me.GroupBox1.Location = New System.Drawing.Point(7, 356)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(516, 4)
        Me.GroupBox1.TabIndex = 19
        Me.GroupBox1.TabStop = False
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.AddRange(New System.Windows.Forms.Control() {Me.cmbKokyaku, Me.Label5, Me.radShouhin, Me.radParts})
        Me.GroupBox2.Location = New System.Drawing.Point(36, 44)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(460, 92)
        Me.GroupBox2.TabIndex = 2
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "商品／パーツ選択"
        '
        'cmbKokyaku
        '
        Me.cmbKokyaku.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cmbKokyaku.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.cmbKokyaku.Location = New System.Drawing.Point(228, 24)
        Me.cmbKokyaku.Name = "cmbKokyaku"
        Me.cmbKokyaku.Size = New System.Drawing.Size(124, 23)
        Me.cmbKokyaku.TabIndex = 5
        '
        'Label5
        '
        Me.Label5.AutoSize = True
        Me.Label5.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label5.Location = New System.Drawing.Point(188, 28)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(36, 15)
        Me.Label5.TabIndex = 4
        Me.Label5.Text = "顧客"
        '
        'radShouhin
        '
        Me.radShouhin.Checked = True
        Me.radShouhin.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.radShouhin.Location = New System.Drawing.Point(52, 24)
        Me.radShouhin.Name = "radShouhin"
        Me.radShouhin.Size = New System.Drawing.Size(116, 24)
        Me.radShouhin.TabIndex = 3
        Me.radShouhin.TabStop = True
        Me.radShouhin.Text = "商品"
        '
        'radParts
        '
        Me.radParts.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.radParts.Location = New System.Drawing.Point(52, 56)
        Me.radParts.Name = "radParts"
        Me.radParts.Size = New System.Drawing.Size(116, 24)
        Me.radParts.TabIndex = 6
        Me.radParts.Text = "パーツ"
        '
        'GroupBox3
        '
        Me.GroupBox3.Controls.AddRange(New System.Windows.Forms.Control() {Me.nudMonth, Me.dtpDate, Me.radShukko, Me.radJuchuu, Me.Label4, Me.Label3})
        Me.GroupBox3.Location = New System.Drawing.Point(36, 152)
        Me.GroupBox3.Name = "GroupBox3"
        Me.GroupBox3.Size = New System.Drawing.Size(460, 112)
        Me.GroupBox3.TabIndex = 7
        Me.GroupBox3.TabStop = False
        Me.GroupBox3.Text = "出力条件"
        '
        'nudMonth
        '
        Me.nudMonth.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.nudMonth.Location = New System.Drawing.Point(232, 28)
        Me.nudMonth.Name = "nudMonth"
        Me.nudMonth.Size = New System.Drawing.Size(44, 22)
        Me.nudMonth.TabIndex = 10
        Me.nudMonth.Value = New Decimal(New Integer() {13, 0, 0, 0})
        '
        'dtpDate
        '
        Me.dtpDate.CustomFormat = "yyyy 年 MM 月"
        Me.dtpDate.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.dtpDate.Format = System.Windows.Forms.DateTimePickerFormat.Custom
        Me.dtpDate.Location = New System.Drawing.Point(52, 28)
        Me.dtpDate.Name = "dtpDate"
        Me.dtpDate.ShowUpDown = True
        Me.dtpDate.Size = New System.Drawing.Size(120, 22)
        Me.dtpDate.TabIndex = 8
        '
        'radShukko
        '
        Me.radShukko.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.radShukko.Location = New System.Drawing.Point(177, 72)
        Me.radShukko.Name = "radShukko"
        Me.radShukko.Size = New System.Drawing.Size(96, 24)
        Me.radShukko.TabIndex = 15
        Me.radShukko.Text = "全出庫数"
        '
        'radJuchuu
        '
        Me.radJuchuu.Checked = True
        Me.radJuchuu.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.radJuchuu.Location = New System.Drawing.Point(52, 72)
        Me.radJuchuu.Name = "radJuchuu"
        Me.radJuchuu.Size = New System.Drawing.Size(100, 24)
        Me.radJuchuu.TabIndex = 14
        Me.radJuchuu.TabStop = True
        Me.radJuchuu.Text = "受注分のみ"
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(280, 32)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(47, 15)
        Me.Label4.TabIndex = 11
        Me.Label4.Text = "ヶ月間"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(180, 32)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(42, 15)
        Me.Label3.TabIndex = 9
        Me.Label3.Text = "までの"
        '
        'frmShukkoData
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 12)
        Me.ClientSize = New System.Drawing.Size(530, 407)
        Me.Controls.AddRange(New System.Windows.Forms.Control() {Me.GroupBox3, Me.GroupBox2, Me.GroupBox1, Me.btnOK, Me.btnFileDialog, Me.txtFileName, Me.Label1, Me.btnClose, Me.lblTitle})
        Me.DockPadding.Top = 2
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle
        Me.MaximizeBox = False
        Me.MinimizeBox = False
        Me.Name = "frmShukkoData"
        Me.ShowInTaskbar = False
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "ShukkoData"
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox3.ResumeLayout(False)
        CType(Me.nudMonth, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region

    Private outputFile As IO.StreamWriter

    '*** フォームロードイベント
    Private Sub frmShukkoData_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        KokyakuSelect(cmbKokyaku)

        '翌月以降は選択させない
        dtpDate.MaxDate = Now
    End Sub

    '*** [参照]ボタンクリックイベント
    Private Sub btnFileDialog_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnFileDialog.Click
        Dim dlg As New SaveFileDialog()
        dlg.Filter = "CSV ファイル (*.csv)|*.txt|すべてのファイル (*.*)|*.*"
        dlg.FileName = "*.csv"
        If dlg.ShowDialog() = DialogResult.OK Then
            Me.txtFileName.Text = dlg.FileName
        End If
    End Sub

    '*** [出力]ボタンクリックイベント
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '顧客選択有無チェック
        If Me.radShouhin.Checked And Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("顧客を選択してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        'CSVファイル入力チェック
        If Me.txtFileName.Text = "" Then
            MsgBox("CSVファイルを入力してください。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.txtFileName.Focus()
            Return
        End If

        '開始終了年月
        Dim fromDate, toDate As DateTime
        toDate = dtpDate.Value.AddMonths(1).ToString("yyyy/MM/01")
        fromDate = toDate.AddMonths(-nudMonth.Text)

        '出力ファイルオープン
        Try
            outputFile = New IO.StreamWriter(txtFileName.Text, False, System.Text.Encoding.Default)
        Catch ex As Exception
            MsgBox("ファイルを作成できませんでした。" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Critical, APPNAME)
            Return
        End Try

        '商品のCSV出力
        If Me.radShouhin.Checked Then
            CsvShouhin(fromDate, toDate)
        End If

        'パーツのCSV出力
        If Me.radParts.Checked Then
            CsvParts(fromDate, toDate)
        End If

        outputFile.Close()

        MsgBox("月別出庫データCSVファイルを出力しました。", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
    End Sub

    '*** [商品]ラジオボタンクリックイベント
    Private Sub radShouhin_CheckedChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles radShouhin.CheckedChanged
        cmbKokyaku.Enabled = True
        radJuchuu.Checked = True
        radShukko.Checked = False
        radShukko.Enabled = False
    End Sub

    '*** [パーツ]ラジオボタンクリックイベント
    Private Sub radParts_CheckedChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles radParts.CheckedChanged
        cmbKokyaku.Enabled = False
        radShukko.Enabled = True
    End Sub

    '*** 商品のCSV出力
    Private Sub CsvShouhin(ByVal fromDate As DateTime, ByVal toDate As DateTime)
        Dim csv As String
        Dim i As Integer

        'ヘッダ部出力（１行目）
        CsvAddColumn(csv, "出力年月日：")
        CsvAddColumn(csv, Now.ToString("yyyy/MM/dd HH:mm"))
        CsvOutput(csv)

        'ヘッダ部出力（２行目）
        CsvAddColumn(csv, "選択条件：")
        CsvAddColumn(csv, "商品")
        CsvAddColumn(csv, cmbKokyaku.Text)
        CsvAddColumn(csv, "受注分")
        CsvOutput(csv)

        'ヘッダ部出力（３行目）
        CsvAddColumn(csv, "対象期間：")
        CsvAddColumn(csv, dtpDate.Value.ToString("yyyy") & "年")
        CsvAddColumn(csv, dtpDate.Value.ToString("MM") & "月")
        CsvAddColumn(csv, nudMonth.Text & "ヶ月分")
        CsvOutput(csv)

        'ヘッダ部出力（４行目）
        CsvAddColumn(csv, "商品名称")
        CsvAddColumn(csv, "平均")
        Dim m As Integer = dtpDate.Value.AddMonths(-nudMonth.Value).Month
        For i = nudMonth.Value To 1 Step -1
            m += 1
            If m > 12 Then
                m = 1
            End If
            CsvAddColumn(csv, m & "月分")
        Next
        CsvOutput(csv)

        'データ部出力
        Dim dbcSelect As New OleDb.OleDbCommand( _
            "SELECT 商品名称, sum(受注数量) AS sum受注数量, format(在庫引落日時, 'YYYYMM') AS 在庫引落年月" & _
            " FROM 受注データ" & _
            " WHERE 在庫引落日時 >= ? AND 在庫引落日時 < ?" & _
            " GROUP BY 商品名称, format(在庫引落日時, 'YYYYMM')" & _
            " ORDER BY 商品名称, format(在庫引落日時, 'YYYYMM')", _
            dbConnection)
        dbcSelect.Parameters.Add("from在庫引落日時", Data.OleDb.OleDbType.DBDate)
        dbcSelect.Parameters.Add("to在庫引落日時", Data.OleDb.OleDbType.DBDate)

        dbcSelect.Parameters("from在庫引落日時").Value = fromDate
        dbcSelect.Parameters("to在庫引落日時").Value = toDate

        Dim shouhin As String
        Dim total As Integer
        Dim count As Integer
        Dim ThisYM As String = Now.ToString("yyyyMM")
        Dim ym As String
        Dim dtrShukkoData As OleDb.OleDbDataReader = dbcSelect.ExecuteReader()
        Dim dtShukkoData As dsWork.月別出庫データDataTable
        Dim rowShokoData As dsWork.月別出庫データRow
        If dtrShukkoData.Read() Then
            Dim loopFlag As Boolean = True
            While loopFlag
                total = 0
                count = 0

                '商品名称出力
                shouhin = dtrShukkoData("商品名称")
                CsvAddColumn(csv, shouhin)

                '月別出庫数の初期化
                dtShukkoData = New dsWork.月別出庫データDataTable()
                For i = 0 To nudMonth.Value - 1
                    ym = fromDate.AddMonths(i).ToString("yyyyMM")
                    dtShukkoData.Add月別出庫データRow(ym, 0)
                    If ym < ThisYM Then
                        count += 1
                    End If
                Next

                '月別出庫数の取得
                While shouhin = dtrShukkoData("商品名称")
                    rowShokoData = dtShukkoData.FindBy年月(dtrShukkoData("在庫引落年月"))
                    rowShokoData("出庫数") = dtrShukkoData("sum受注数量")
                    If dtrShukkoData("在庫引落年月") < ThisYM Then
                        total += dtrShukkoData("sum受注数量")
                    End If

                    If Not dtrShukkoData.Read() Then
                        loopFlag = False
                        Exit While
                    End If
                End While

                '平均値出力
                If count > 0 Then
                    CsvAddColumn(csv, CInt(total / count))
                Else
                    CsvAddColumn(csv, 0)
                End If

                '月別出庫数出力
                For Each rowShokoData In dtShukkoData.Rows
                    CsvAddColumn(csv, rowShokoData("出庫数"))
                Next
                CsvOutput(csv)
            End While
        End If

        dtrShukkoData.Close()
    End Sub

    '*** パーツのCSV出力
    Private Sub CsvParts(ByVal fromDate As DateTime, ByVal toDate As DateTime)
        Dim csv As String
        Dim i As Integer

        'ヘッダ部出力（１行目）
        CsvAddColumn(csv, "出力年月日：")
        CsvAddColumn(csv, Now.ToString("yyyy/MM/dd HH:mm"))
        CsvOutput(csv)

        'ヘッダ部出力（２行目）
        CsvAddColumn(csv, "選択条件：")
        CsvAddColumn(csv, "パーツ")
        If radJuchuu.Checked Then
            CsvAddColumn(csv, "受注分")
        Else
            CsvAddColumn(csv, "全出庫数")
        End If
        CsvOutput(csv)

        'ヘッダ部出力（３行目）
        CsvAddColumn(csv, "対象期間：")
        CsvAddColumn(csv, dtpDate.Value.ToString("yyyy") & "年")
        CsvAddColumn(csv, dtpDate.Value.ToString("MM") & "月")
        CsvAddColumn(csv, nudMonth.Text & "ヶ月分")
        CsvOutput(csv)

        'ヘッダ部出力（４行目）
        CsvAddColumn(csv, "パーツ種別名称")
        CsvAddColumn(csv, "パーツ名称")
        CsvAddColumn(csv, "現在庫数")
        CsvAddColumn(csv, "平均")
        Dim m As Integer = dtpDate.Value.AddMonths(-nudMonth.Value).Month
        For i = nudMonth.Value To 1 Step -1
            m += 1
            If m > 12 Then
                m = 1
            End If
            CsvAddColumn(csv, m & "月分")
        Next
        CsvOutput(csv)

        '入出庫区分の条件
        Dim kubun As String
        If radJuchuu.Checked Then
            '受注分のみの場合は"02:引当"のみ
            kubun = "入出庫区分='02'"
        Else
            '全出庫数の場合は"02引当","03:出庫","04:廃棄"
            kubun = "(入出庫区分='02' OR 入出庫区分='03' OR 入出庫区分='04')"
        End If

        'データ部出力
        Dim dbcSelect As New OleDb.OleDbCommand( _
            "SELECT パーツ種別マスタ.パーツ種別名, パーツ在庫マスタ.パーツ名称, パーツ在庫マスタ.現在庫数, sum(-在庫変更履歴.数量) AS sum数量, format(在庫変更履歴.処理日時, 'YYYYMM') AS 処理年月" & _
            " FROM (在庫変更履歴" & _
            " INNER JOIN パーツ在庫マスタ ON 在庫変更履歴.パーツ名称=パーツ在庫マスタ.パーツ名称)" & _
            " INNER JOIN パーツ種別マスタ ON パーツ在庫マスタ.パーツ種別=パーツ種別マスタ.パーツ種別コード" & _
            " WHERE 処理日時 >= ? AND 処理日時 < ? AND " & kubun & _
            " GROUP BY パーツ種別マスタ.パーツ種別名, パーツ在庫マスタ.パーツ名称, パーツ在庫マスタ.現在庫数, format(処理日時, 'YYYYMM')" & _
            " ORDER BY パーツ在庫マスタ.パーツ名称, format(処理日時, 'YYYYMM')", _
            dbConnection)
        dbcSelect.Parameters.Add("from処理日時", Data.OleDb.OleDbType.DBDate)
        dbcSelect.Parameters.Add("to処理日時", Data.OleDb.OleDbType.DBDate)

        dbcSelect.Parameters("from処理日時").Value = fromDate
        dbcSelect.Parameters("to処理日時").Value = toDate

        Dim parts As String
        Dim total As Integer
        Dim count As Integer
        Dim ThisYM As String = Now.ToString("yyyyMM")
        Dim ym As String
        Dim dtrShukkoData As OleDb.OleDbDataReader = dbcSelect.ExecuteReader()
        Dim dtShukkoData As dsWork.月別出庫データDataTable
        Dim rowShokoData As dsWork.月別出庫データRow
        If dtrShukkoData.Read() Then
            Dim loopFlag As Boolean = True
            While loopFlag
                total = 0
                count = 0

                'パーツ種別名称出力
                CsvAddColumn(csv, dtrShukkoData("パーツ種別名"))

                'パーツ名称出力
                parts = dtrShukkoData("パーツ名称")
                CsvAddColumn(csv, parts)

                '現在庫数出力
                CsvAddColumn(csv, dtrShukkoData("現在庫数"))

                '月別出庫数の初期化
                dtShukkoData = New dsWork.月別出庫データDataTable()
                For i = 0 To nudMonth.Value - 1
                    ym = fromDate.AddMonths(i).ToString("yyyyMM")
                    dtShukkoData.Add月別出庫データRow(ym, 0)
                    If ym < ThisYM Then
                        count += 1
                    End If
                Next

                '月別出庫数の取得
                While parts = dtrShukkoData("パーツ名称")
                    rowShokoData = dtShukkoData.FindBy年月(dtrShukkoData("処理年月"))
                    rowShokoData("出庫数") = dtrShukkoData("sum数量")
                    If dtrShukkoData("処理年月") < ThisYM Then
                        total += dtrShukkoData("sum数量")
                    End If

                    If Not dtrShukkoData.Read() Then
                        loopFlag = False
                        Exit While
                    End If
                End While

                '平均値出力
                If count > 0 Then
                    CsvAddColumn(csv, CInt(total / count))
                Else
                    CsvAddColumn(csv, 0)
                End If

                '月別出庫数出力
                For Each rowShokoData In dtShukkoData.Rows
                    CsvAddColumn(csv, rowShokoData("出庫数"))
                Next
                CsvOutput(csv)
            End While
        End If

        dtrShukkoData.Close()
    End Sub

    '*** カラムデータをCSVに追加
    Private Sub CsvAddColumn(ByRef csv As String, ByVal column As String)
        If csv <> "" Then
            csv &= ","
        End If

        csv &= column
    End Sub

    '*** CSVデータをファイルに出力
    Private Sub CsvOutput(ByRef csv As String)
        outputFile.WriteLine(csv)
        csv = ""
    End Sub
End Class
