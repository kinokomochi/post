<?php //ヘッダとボディの間は空行１つはさむ 空行はヘッダの終了を意味する
//てことは空行があるとボディ部がスタートしてしまう。
//ボディ部＝出力＝HTMLの記述が始まったら？　print_rは出力するからボディ部→ヘッダ部の終了を意味する
//phpのコードを書くときは、ここまではヘッダ部、ここからはボディ部、というのが分かっていないといけない？
error_reporting(E_ALL);//ヘッダ部？
session_start();
require('../dbconnect.php');
//もしこの時$_SESSION['join']に何も入ってなければ、入力画面を経ずに直接check.phpにアクセスされた可能性がある。
//ので、それ以上処理を続けられないようにしておく
if(!isset($_SESSION['join'])){
    header('Location: index.php');
    exit();
}

if(!empty($_POST)){
    $stmt = $db->prepare('INSERT INTO members 
    SET name=?, email=?, password=?, picture=?, created=NOW()');
    echo $ret = $stmt->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));
    unset($_SESSION['join']);

    header('Location: thanks.php');
    exit();
}

//print_r($_SESSION);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<form action="" method="post">
<input type="hidden" name="action" value="submit" />
<dl>
    <dt>ニックネーム</dt>
    <dd>
        <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES);?>
    </dd>
    <dt>メールアドレス</dt>
    <dd>
        <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?>
        </dd>
    <dt>パスワード</dt>
    <dd>[表示されません]</dd>
    <dt>写真など</dt>
    <dd>
    <?php if($_SESSION['join']['image'] !== ''): ?>
    <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES); ?>"
    width="300" height="300" alt="" />
    <?php endif; ?>
    </dd>
</dl>
<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
| <input type="submit" value="登録する" /></div>
</form>

</body>
</html>