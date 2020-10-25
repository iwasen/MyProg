Public Class MyDataGrid
    Inherits System.Windows.Forms.DataGrid

    Protected Overrides Function ProcessCmdKey(ByRef msg As Message, ByVal keyData As Keys) As Boolean
        Dim ret As Boolean

        Select Case keyData
            Case Keys.Tab
                Dim dt As DataTable = DataSource
                If CurrentRowIndex < dt.Rows.Count - 1 Then
                    CurrentCell = New DataGridCell(CurrentCell.RowNumber + 1, 2)
                    ret = True
                Else
                    CurrentCell = New DataGridCell(CurrentCell.RowNumber, 3)
                End If
            Case Keys.Shift Or Keys.Tab
                If CurrentRowIndex > 0 Then
                    CurrentCell = New DataGridCell(CurrentCell.RowNumber - 1, 2)
                    ret = True
                Else
                    CurrentCell = New DataGridCell(CurrentCell.RowNumber, 0)
                End If
        End Select

        Return ret
    End Function
End Class
