Public Class frmShukkoData
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
        Me.lblTitle.Text = "���ʏo�Ƀf�[�^�o��"
        Me.lblTitle.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
        '
        'btnOK
        '
        Me.btnOK.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnOK.Location = New System.Drawing.Point(174, 372)
        Me.btnOK.Name = "btnOK"
        Me.btnOK.Size = New System.Drawing.Size(92, 24)
        Me.btnOK.TabIndex = 20
        Me.btnOK.Text = "�o��(&O)"
        '
        'btnFileDialog
        '
        Me.btnFileDialog.Location = New System.Drawing.Point(429, 316)
        Me.btnFileDialog.Name = "btnFileDialog"
        Me.btnFileDialog.Size = New System.Drawing.Size(76, 24)
        Me.btnFileDialog.TabIndex = 18
        Me.btnFileDialog.Text = "�Q��(&R)..."
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
        Me.Label1.Text = "�o��CSV�t�@�C��"
        '
        'btnClose
        '
        Me.btnClose.Anchor = System.Windows.Forms.AnchorStyles.Bottom
        Me.btnClose.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.btnClose.Location = New System.Drawing.Point(282, 372)
        Me.btnClose.Name = "btnClose"
        Me.btnClose.Size = New System.Drawing.Size(92, 24)
        Me.btnClose.TabIndex = 21
        Me.btnClose.Text = "����(&C)"
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
        Me.GroupBox2.Text = "���i�^�p�[�c�I��"
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
        Me.Label5.Text = "�ڋq"
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
        Me.radShouhin.Text = "���i"
        '
        'radParts
        '
        Me.radParts.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.radParts.Location = New System.Drawing.Point(52, 56)
        Me.radParts.Name = "radParts"
        Me.radParts.Size = New System.Drawing.Size(116, 24)
        Me.radParts.TabIndex = 6
        Me.radParts.Text = "�p�[�c"
        '
        'GroupBox3
        '
        Me.GroupBox3.Controls.AddRange(New System.Windows.Forms.Control() {Me.nudMonth, Me.dtpDate, Me.radShukko, Me.radJuchuu, Me.Label4, Me.Label3})
        Me.GroupBox3.Location = New System.Drawing.Point(36, 152)
        Me.GroupBox3.Name = "GroupBox3"
        Me.GroupBox3.Size = New System.Drawing.Size(460, 112)
        Me.GroupBox3.TabIndex = 7
        Me.GroupBox3.TabStop = False
        Me.GroupBox3.Text = "�o�͏���"
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
        Me.dtpDate.CustomFormat = "yyyy �N MM ��"
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
        Me.radShukko.Text = "�S�o�ɐ�"
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
        Me.radJuchuu.Text = "�󒍕��̂�"
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label4.Location = New System.Drawing.Point(280, 32)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(47, 15)
        Me.Label4.TabIndex = 11
        Me.Label4.Text = "������"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("MS UI Gothic", 11.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(128, Byte))
        Me.Label3.Location = New System.Drawing.Point(180, 32)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(42, 15)
        Me.Label3.TabIndex = 9
        Me.Label3.Text = "�܂ł�"
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

    '*** �t�H�[�����[�h�C�x���g
    Private Sub frmShukkoData_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        Me.Text = Common.TITLE
        KokyakuSelect(cmbKokyaku)

        '�����ȍ~�͑I�������Ȃ�
        dtpDate.MaxDate = Now
    End Sub

    '*** [�Q��]�{�^���N���b�N�C�x���g
    Private Sub btnFileDialog_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnFileDialog.Click
        Dim dlg As New SaveFileDialog()
        dlg.Filter = "CSV �t�@�C�� (*.csv)|*.txt|���ׂẴt�@�C�� (*.*)|*.*"
        dlg.FileName = "*.csv"
        If dlg.ShowDialog() = DialogResult.OK Then
            Me.txtFileName.Text = dlg.FileName
        End If
    End Sub

    '*** [�o��]�{�^���N���b�N�C�x���g
    Private Sub btnOK_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnOK.Click
        '�ڋq�I��L���`�F�b�N
        If Me.radShouhin.Checked And Me.cmbKokyaku.SelectedIndex = 0 Then
            MsgBox("�ڋq��I�����Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.cmbKokyaku.Focus()
            Return
        End If

        'CSV�t�@�C�����̓`�F�b�N
        If Me.txtFileName.Text = "" Then
            MsgBox("CSV�t�@�C������͂��Ă��������B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
            Me.txtFileName.Focus()
            Return
        End If

        '�J�n�I���N��
        Dim fromDate, toDate As DateTime
        toDate = dtpDate.Value.AddMonths(1).ToString("yyyy/MM/01")
        fromDate = toDate.AddMonths(-nudMonth.Text)

        '�o�̓t�@�C���I�[�v��
        Try
            outputFile = New IO.StreamWriter(txtFileName.Text, False, System.Text.Encoding.Default)
        Catch ex As Exception
            MsgBox("�t�@�C�����쐬�ł��܂���ł����B" & vbCrLf & vbCrLf & ex.Message, MsgBoxStyle.OKOnly Or MsgBoxStyle.Critical, APPNAME)
            Return
        End Try

        '���i��CSV�o��
        If Me.radShouhin.Checked Then
            CsvShouhin(fromDate, toDate)
        End If

        '�p�[�c��CSV�o��
        If Me.radParts.Checked Then
            CsvParts(fromDate, toDate)
        End If

        outputFile.Close()

        MsgBox("���ʏo�Ƀf�[�^CSV�t�@�C�����o�͂��܂����B", MsgBoxStyle.OKOnly Or MsgBoxStyle.Information, APPNAME)
    End Sub

    '*** [���i]���W�I�{�^���N���b�N�C�x���g
    Private Sub radShouhin_CheckedChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles radShouhin.CheckedChanged
        cmbKokyaku.Enabled = True
        radJuchuu.Checked = True
        radShukko.Checked = False
        radShukko.Enabled = False
    End Sub

    '*** [�p�[�c]���W�I�{�^���N���b�N�C�x���g
    Private Sub radParts_CheckedChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles radParts.CheckedChanged
        cmbKokyaku.Enabled = False
        radShukko.Enabled = True
    End Sub

    '*** ���i��CSV�o��
    Private Sub CsvShouhin(ByVal fromDate As DateTime, ByVal toDate As DateTime)
        Dim csv As String
        Dim i As Integer

        '�w�b�_���o�́i�P�s�ځj
        CsvAddColumn(csv, "�o�͔N�����F")
        CsvAddColumn(csv, Now.ToString("yyyy/MM/dd HH:mm"))
        CsvOutput(csv)

        '�w�b�_���o�́i�Q�s�ځj
        CsvAddColumn(csv, "�I�������F")
        CsvAddColumn(csv, "���i")
        CsvAddColumn(csv, cmbKokyaku.Text)
        CsvAddColumn(csv, "�󒍕�")
        CsvOutput(csv)

        '�w�b�_���o�́i�R�s�ځj
        CsvAddColumn(csv, "�Ώۊ��ԁF")
        CsvAddColumn(csv, dtpDate.Value.ToString("yyyy") & "�N")
        CsvAddColumn(csv, dtpDate.Value.ToString("MM") & "��")
        CsvAddColumn(csv, nudMonth.Text & "������")
        CsvOutput(csv)

        '�w�b�_���o�́i�S�s�ځj
        CsvAddColumn(csv, "���i����")
        CsvAddColumn(csv, "����")
        Dim m As Integer = dtpDate.Value.AddMonths(-nudMonth.Value).Month
        For i = nudMonth.Value To 1 Step -1
            m += 1
            If m > 12 Then
                m = 1
            End If
            CsvAddColumn(csv, m & "����")
        Next
        CsvOutput(csv)

        '�f�[�^���o��
        Dim dbcSelect As New OleDb.OleDbCommand( _
            "SELECT ���i����, sum(�󒍐���) AS sum�󒍐���, format(�݌Ɉ�������, 'YYYYMM') AS �݌Ɉ����N��" & _
            " FROM �󒍃f�[�^" & _
            " WHERE �݌Ɉ������� >= ? AND �݌Ɉ������� < ?" & _
            " GROUP BY ���i����, format(�݌Ɉ�������, 'YYYYMM')" & _
            " ORDER BY ���i����, format(�݌Ɉ�������, 'YYYYMM')", _
            dbConnection)
        dbcSelect.Parameters.Add("from�݌Ɉ�������", Data.OleDb.OleDbType.DBDate)
        dbcSelect.Parameters.Add("to�݌Ɉ�������", Data.OleDb.OleDbType.DBDate)

        dbcSelect.Parameters("from�݌Ɉ�������").Value = fromDate
        dbcSelect.Parameters("to�݌Ɉ�������").Value = toDate

        Dim shouhin As String
        Dim total As Integer
        Dim count As Integer
        Dim ThisYM As String = Now.ToString("yyyyMM")
        Dim ym As String
        Dim dtrShukkoData As OleDb.OleDbDataReader = dbcSelect.ExecuteReader()
        Dim dtShukkoData As dsWork.���ʏo�Ƀf�[�^DataTable
        Dim rowShokoData As dsWork.���ʏo�Ƀf�[�^Row
        If dtrShukkoData.Read() Then
            Dim loopFlag As Boolean = True
            While loopFlag
                total = 0
                count = 0

                '���i���̏o��
                shouhin = dtrShukkoData("���i����")
                CsvAddColumn(csv, shouhin)

                '���ʏo�ɐ��̏�����
                dtShukkoData = New dsWork.���ʏo�Ƀf�[�^DataTable()
                For i = 0 To nudMonth.Value - 1
                    ym = fromDate.AddMonths(i).ToString("yyyyMM")
                    dtShukkoData.Add���ʏo�Ƀf�[�^Row(ym, 0)
                    If ym < ThisYM Then
                        count += 1
                    End If
                Next

                '���ʏo�ɐ��̎擾
                While shouhin = dtrShukkoData("���i����")
                    rowShokoData = dtShukkoData.FindBy�N��(dtrShukkoData("�݌Ɉ����N��"))
                    rowShokoData("�o�ɐ�") = dtrShukkoData("sum�󒍐���")
                    If dtrShukkoData("�݌Ɉ����N��") < ThisYM Then
                        total += dtrShukkoData("sum�󒍐���")
                    End If

                    If Not dtrShukkoData.Read() Then
                        loopFlag = False
                        Exit While
                    End If
                End While

                '���ϒl�o��
                If count > 0 Then
                    CsvAddColumn(csv, CInt(total / count))
                Else
                    CsvAddColumn(csv, 0)
                End If

                '���ʏo�ɐ��o��
                For Each rowShokoData In dtShukkoData.Rows
                    CsvAddColumn(csv, rowShokoData("�o�ɐ�"))
                Next
                CsvOutput(csv)
            End While
        End If

        dtrShukkoData.Close()
    End Sub

    '*** �p�[�c��CSV�o��
    Private Sub CsvParts(ByVal fromDate As DateTime, ByVal toDate As DateTime)
        Dim csv As String
        Dim i As Integer

        '�w�b�_���o�́i�P�s�ځj
        CsvAddColumn(csv, "�o�͔N�����F")
        CsvAddColumn(csv, Now.ToString("yyyy/MM/dd HH:mm"))
        CsvOutput(csv)

        '�w�b�_���o�́i�Q�s�ځj
        CsvAddColumn(csv, "�I�������F")
        CsvAddColumn(csv, "�p�[�c")
        If radJuchuu.Checked Then
            CsvAddColumn(csv, "�󒍕�")
        Else
            CsvAddColumn(csv, "�S�o�ɐ�")
        End If
        CsvOutput(csv)

        '�w�b�_���o�́i�R�s�ځj
        CsvAddColumn(csv, "�Ώۊ��ԁF")
        CsvAddColumn(csv, dtpDate.Value.ToString("yyyy") & "�N")
        CsvAddColumn(csv, dtpDate.Value.ToString("MM") & "��")
        CsvAddColumn(csv, nudMonth.Text & "������")
        CsvOutput(csv)

        '�w�b�_���o�́i�S�s�ځj
        CsvAddColumn(csv, "�p�[�c��ʖ���")
        CsvAddColumn(csv, "�p�[�c����")
        CsvAddColumn(csv, "���݌ɐ�")
        CsvAddColumn(csv, "����")
        Dim m As Integer = dtpDate.Value.AddMonths(-nudMonth.Value).Month
        For i = nudMonth.Value To 1 Step -1
            m += 1
            If m > 12 Then
                m = 1
            End If
            CsvAddColumn(csv, m & "����")
        Next
        CsvOutput(csv)

        '���o�ɋ敪�̏���
        Dim kubun As String
        If radJuchuu.Checked Then
            '�󒍕��݂̂̏ꍇ��"02:����"�̂�
            kubun = "���o�ɋ敪='02'"
        Else
            '�S�o�ɐ��̏ꍇ��"02����","03:�o��","04:�p��"
            kubun = "(���o�ɋ敪='02' OR ���o�ɋ敪='03' OR ���o�ɋ敪='04')"
        End If

        '�f�[�^���o��
        Dim dbcSelect As New OleDb.OleDbCommand( _
            "SELECT �p�[�c��ʃ}�X�^.�p�[�c��ʖ�, �p�[�c�݌Ƀ}�X�^.�p�[�c����, �p�[�c�݌Ƀ}�X�^.���݌ɐ�, sum(-�݌ɕύX����.����) AS sum����, format(�݌ɕύX����.��������, 'YYYYMM') AS �����N��" & _
            " FROM (�݌ɕύX����" & _
            " INNER JOIN �p�[�c�݌Ƀ}�X�^ ON �݌ɕύX����.�p�[�c����=�p�[�c�݌Ƀ}�X�^.�p�[�c����)" & _
            " INNER JOIN �p�[�c��ʃ}�X�^ ON �p�[�c�݌Ƀ}�X�^.�p�[�c���=�p�[�c��ʃ}�X�^.�p�[�c��ʃR�[�h" & _
            " WHERE �������� >= ? AND �������� < ? AND " & kubun & _
            " GROUP BY �p�[�c��ʃ}�X�^.�p�[�c��ʖ�, �p�[�c�݌Ƀ}�X�^.�p�[�c����, �p�[�c�݌Ƀ}�X�^.���݌ɐ�, format(��������, 'YYYYMM')" & _
            " ORDER BY �p�[�c�݌Ƀ}�X�^.�p�[�c����, format(��������, 'YYYYMM')", _
            dbConnection)
        dbcSelect.Parameters.Add("from��������", Data.OleDb.OleDbType.DBDate)
        dbcSelect.Parameters.Add("to��������", Data.OleDb.OleDbType.DBDate)

        dbcSelect.Parameters("from��������").Value = fromDate
        dbcSelect.Parameters("to��������").Value = toDate

        Dim parts As String
        Dim total As Integer
        Dim count As Integer
        Dim ThisYM As String = Now.ToString("yyyyMM")
        Dim ym As String
        Dim dtrShukkoData As OleDb.OleDbDataReader = dbcSelect.ExecuteReader()
        Dim dtShukkoData As dsWork.���ʏo�Ƀf�[�^DataTable
        Dim rowShokoData As dsWork.���ʏo�Ƀf�[�^Row
        If dtrShukkoData.Read() Then
            Dim loopFlag As Boolean = True
            While loopFlag
                total = 0
                count = 0

                '�p�[�c��ʖ��̏o��
                CsvAddColumn(csv, dtrShukkoData("�p�[�c��ʖ�"))

                '�p�[�c���̏o��
                parts = dtrShukkoData("�p�[�c����")
                CsvAddColumn(csv, parts)

                '���݌ɐ��o��
                CsvAddColumn(csv, dtrShukkoData("���݌ɐ�"))

                '���ʏo�ɐ��̏�����
                dtShukkoData = New dsWork.���ʏo�Ƀf�[�^DataTable()
                For i = 0 To nudMonth.Value - 1
                    ym = fromDate.AddMonths(i).ToString("yyyyMM")
                    dtShukkoData.Add���ʏo�Ƀf�[�^Row(ym, 0)
                    If ym < ThisYM Then
                        count += 1
                    End If
                Next

                '���ʏo�ɐ��̎擾
                While parts = dtrShukkoData("�p�[�c����")
                    rowShokoData = dtShukkoData.FindBy�N��(dtrShukkoData("�����N��"))
                    rowShokoData("�o�ɐ�") = dtrShukkoData("sum����")
                    If dtrShukkoData("�����N��") < ThisYM Then
                        total += dtrShukkoData("sum����")
                    End If

                    If Not dtrShukkoData.Read() Then
                        loopFlag = False
                        Exit While
                    End If
                End While

                '���ϒl�o��
                If count > 0 Then
                    CsvAddColumn(csv, CInt(total / count))
                Else
                    CsvAddColumn(csv, 0)
                End If

                '���ʏo�ɐ��o��
                For Each rowShokoData In dtShukkoData.Rows
                    CsvAddColumn(csv, rowShokoData("�o�ɐ�"))
                Next
                CsvOutput(csv)
            End While
        End If

        dtrShukkoData.Close()
    End Sub

    '*** �J�����f�[�^��CSV�ɒǉ�
    Private Sub CsvAddColumn(ByRef csv As String, ByVal column As String)
        If csv <> "" Then
            csv &= ","
        End If

        csv &= column
    End Sub

    '*** CSV�f�[�^���t�@�C���ɏo��
    Private Sub CsvOutput(ByRef csv As String)
        outputFile.WriteLine(csv)
        csv = ""
    End Sub
End Class
