<?php
require('dbconnect.php');
session_start();

if($_COOKIE['email'] != ''){//クッキーに値が入っていたら...
    $_POST['email'] = $_COOKIE['email'];//cookie保存をしたとして$_postに代入
    $_POST['password'] = $_COOKIE['password'];//これによりログイン操作をしたのと同じ状態になる
    $_POST['save'] = 'on';//この時から改めて有効期限を１４日間設定=「最後のログインから２週間保存される」

}

if(!empty($_POST)){
    //ログイン処理
    if($_POST['email'] != '' && $_POST['password'] != '') { //両方のフォームに値が入っているか。なければ入力を促す。
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])//入力時に暗号化されているので取り出すときも暗号化する
        ));
        $member = $login->fetch();
    //ユーザーが入力したアドレスとパスをDBから探して$memberに入れる。
        if($member){
            //ログイン成功
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
            //取得したレコードのidをセッションに格納

            //ログイン情報を記録する
            if($_POST['save'] == 'on'){
                setcookie('email', $_POST['email'], time()+60*60*24*14);//保存期間が１４日間ってこと
                setcookie('password', $_POST['password'], time()+60*60*24*14);
            }
        
            header('Location: index.php');
            exit();
        }else{
            $error['login'] = 'failed';
        }
    }else{
        $error['login'] = 'brank';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div id="lead">
<p>メールアドレスとパスワードを入力してログインしてください。</p>
<p>入会手続きがまだの方はこちらからどうぞ</p>
<p>&raquo;<a href="join/">入会手続きをする</a></p>
</div>
<form action="" method="post">
<dl>
    <dt>メールアドレス</dt>
    <dd>
    <input type="text" name="email" size="35" maxlength="255"
    value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
    <?php if($error['login'] == 'brank'): ?>
    <p class="error">メールアドレスとパスワードを入力してください</p>
    <?php endif; ?>
    <?php if($error['login'] == 'failed'): ?>
    <p class="error">ログインに失敗しました。パスワード正しくを入力してください</p>
    <?php endif; ?>
    </dd>
    <dt>パスワード</dt>
    <dd>
    <input type="password" name="password" size="35" maxlength="255"
    value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
    </dd>
    <dt>ログイン情報の記録</dt>
    <dd>
    <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
    </dd>
</dl>
<div><input type="submit" value="ログインする" /></div>
</form>
</body>
</html>
