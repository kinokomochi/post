<?php 
    session_start();
    

// $_POSTが空でないかを確認することでフォームが送信されたことを確認できる
//empty配列を作成し、それぞれの項目がからであるか否かを確認する。
//あとでメッセージを出力する際に使う
    if(!empty($_POST)){
        
        if($_POST['name'] === ''){
            $error['name'] = 'brank';
        }
        if($_POST['email'] === ''){
            $error['email'] = 'brank';
        }
        if(strlen($_POST['password']) < 4){
            $error['password'] = 'length';
        }
        if($_POST['password'] === ''){
            $error['password'] = 'brank';
        }
        $fileName = $_FILES['image']['name'];
        if(!empty($fileName)){
            $ext = substr($fileName, -3);
            if($ext != 'jpg' && $ext != 'gif') {
                $error['image'] = 'type' ;
            }
        }
        
        if(empty($error)){
            //画像をアップロードする
            $image = date('YmdHis') . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/'.$image);
            $_SESSION['join'] = $_POST;
            $_SESSION['join']['image'] = $image;
            header('Location: check.php');
            exit();
        }
    }
    //全ての確認が終わったら、配列の中身がからか否かを確認する。
    //空ならばエラー無しで登録確認画面へ移行できる
    if($_REQUEST['action'] == 'rewrite'){
        $_POST = $_SESSION['join'];
        $error['rewrite'] = true;
    }
?>

<!DOCTYPE html>


<html>
<head>
</head>
<body>

<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data"> 
<!-- action属性が空っぽ→自分自身に送信する（つまりindex.phpに) -->
<dl>
    <dt>ニックネーム<span class="required">必須</span></dt>
    <dd>
        <input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
        <?php if($error['name'] === 'brank'): ?>
        <p class="error">ニックネームを入力してください</p>
        <?php endif; ?>
    </dd>
    <dt>メールアドレス<span class="required">必須</span></dt>
    <dd>
        <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
        <?php if($error['email'] == 'blank'): ?>
        <p class="error">メールアドレスを入力してください</p>
        <?php endif; ?> 
     </dd>
    <dt>パスワード<span class="required">必須</span></dt>
    <dd>
        <input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
        <?php if($error['password'] === 'brank'): ?>
        <p class="error">パスワードを入力してください</p>
        <?php endif; ?>
        <?php if($error['password'] === 'length'): ?>
        <p class="error">パスワードは４文字以上で入力してください</p>
        <?php endif; ?>
    </dd>
    <dt>写真など</dt>
    <dd><input type="file" name="image" size="35" /></dd>
    <?php if($error['image'] === 'type'): ?>
    <p class="error">画像は「.gif」または「.jpg」の形式で指定してください。</p>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
    <p class="error">もう一度画像を指定してください。</p>
    <?php endif; ?>
</dl>
<div><input type="submit" value="入力内容を確認する" /></div>
</form>

</body>
</html>