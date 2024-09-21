<?php

date_default_timezone_set("Asia/Tokyo");

$comment_array = array();
$error_messages = array();

// CSVファイルのパス
$csv_file = 'comments.csv';

// フォームが送信されたとき
if (isset($_POST["submitButton"])) {

    // 名前のチェック
    if(empty($_POST["username"])){
        $error_messages["username"] = "名前を入力してください";
    }

    // コメントのチェック
    if(empty($_POST["comment"])){
        $error_messages["comment"] = "内容を入力してください";
    }

    // エラーメッセージがない場合、データを保存
    if (empty($error_messages)) {
        $postDate = date("Y-m-d H:i:s");

        // 保存するデータを準備
        $new_entry = array($_POST["username"], $_POST["comment"], $postDate);

        // CSVファイルに追記
        if ($file = fopen($csv_file, 'a')) {
            fputcsv($file, $new_entry);
            fclose($file);
        } else {
            echo "ファイルに書き込めませんでした。";
        }
    }
}

// CSVファイルからコメントデータを取得
if (file_exists($csv_file)) {
    if ($file = fopen($csv_file, 'r')) {
        while (($data = fgetcsv($file)) !== FALSE) {
            $comment_array[] = array(
                'username' => $data[0],
                'comment' => $data[1],
                'postDate' => $data[2]
            );
        }
        fclose($file);
    } else {
        echo "ファイルを読み込めませんでした。";
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="title">PHPで掲示板アプリ</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php if (!empty($comment_array)): ?>
                <?php foreach ($comment_array as $comment): ?>
                <article>
                    <div class="wrapper">
                        <div class="nameArea">
                            <span>名前：</span>
                            <p class="username"><?php echo htmlspecialchars($comment["username"], ENT_QUOTES, 'UTF-8'); ?></p>
                            <time>：<?php echo htmlspecialchars($comment["postDate"], ENT_QUOTES, 'UTF-8'); ?></time>
                        </div>
                        <p class="comment"><?php echo htmlspecialchars($comment["comment"], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <form class="formwrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="">名前：</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
    </div>
</body>
</html>
