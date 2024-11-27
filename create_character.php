<?php
require 'includes/db.php';

// データベースからグループ一覧を取得
$stmt = $pdo->query("SELECT id, name FROM groups");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>キャラクター登録</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/form_handler.js" defer></script>
</head>

<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main>
        <!-- メッセージ表示エリア -->
        <div id="message" style="color: green; margin-bottom: 1em;"></div>

        <!-- キャラエノ登録フォーム -->
        <div id="charaeno_form">
            <h2>キャラエノから登録</h2>
            <form id="charaeno_form_element" method="post" action="create_character_handler.php">
                <label>キャラエノURL: <input type="url" name="charaeno_url" required></label><br>
                <label>所属グループ:
                    <select name="group_id" required>
                        <option value="">選択してください</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= htmlspecialchars($group['id']) ?>">
                                <?= htmlspecialchars($group['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label><br>
                <input type="hidden" name="form_type" value="charaeno">
                <button type="submit">キャラエノ登録</button>
            </form>
        </div>

        <!-- キャラクター保管所登録フォーム -->
        <div id="charasheet_form">
            <h2>キャラクター保管所から登録</h2>
            <form id="charasheet_form_element" method="post" action="create_character_handler.php">
                <label>キャラクター保管所URL: <input type="url" name="charasheet_url" required></label><br>
                <label>所属グループ:
                    <select name="group_id" required>
                        <option value="">選択してください</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= htmlspecialchars($group['id']) ?>">
                                <?= htmlspecialchars($group['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label><br>
                <input type="hidden" name="form_type" value="charasheet">
                <button type="submit">キャラクター保管所登録</button>
            </form>
        </div>

        <!-- いあきゃら登録フォーム -->
        <div id="iachara_form">
            <h2>いあきゃらから登録</h2>
            <form id="iachara_form_element" method="post" action="create_character_handler.php">
                <label>名前: <input type="text" name="name" required></label><br>
                <label>年齢: <input type="number" name="age" required></label><br>
                <label>性別:
                    <select name="sex" required>
                        <option value="">選択してください</option>
                        <option value="男性">男性</option>
                        <option value="女性">女性</option>
                        <option value="その他">その他</option>
                    </select>
                </label><br>
                <label>所属グループ:
                    <select name="group_id" required>
                        <option value="">選択してください</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= htmlspecialchars($group['id']) ?>">
                                <?= htmlspecialchars($group['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label><br>
                <label>説明: <textarea name="description" rows="4" required></textarea></label><br>
                <input type="hidden" name="form_type" value="iachara">
                <button type="submit">いあきゃら登録</button>
            </form>
        </div>
    </main>
</body>

</html>