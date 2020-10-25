Module KeyCheck

    '*** 数字項目入力制限用イベントハンドラ
    Public Sub KeyPress_NumOnly(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** 英数字項目入力制限用イベントハンドラ
    Public Sub KeyPress_AlphaNum(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** 商品名称入力制限用イベントハンドラ
    Public Sub KeyPress_ShouhinMeishou(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or e.KeyChar = "-" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** パーツ名称入力制限用イベントハンドラ
    Public Sub KeyPress_PartsMeishou(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or e.KeyChar = "-" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** 顧客コード入力制限用イベントハンドラ
    Public Sub KeyPress_KokyakuCode(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar >= "A" And e.KeyChar <= "Z" _
                Or e.KeyChar >= "a" And e.KeyChar <= "z" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

    '*** 日付入力制限用イベントハンドラ
    Public Sub KeyPress_Date(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not (e.KeyChar >= "0" And e.KeyChar <= "9" _
                Or e.KeyChar = "/" _
                Or Char.IsControl(e.KeyChar)) Then
            Beep()
            e.Handled = True
        End If
    End Sub

End Module
