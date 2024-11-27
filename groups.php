<?php
require 'includes/db.php';

// グループのデータを取得
$stmt = $pdo->query("SELECT id, name FROM groups");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>グループ一覧</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/group_handler.js" defer></script> <!-- JavaScriptを読み込み -->
</head>

<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main>
        <h1>グループ一覧</h1>

        <!-- グループリスト -->
        <ul>
            <?php foreach ($groups as $group): ?>
                <li>
                    <button class="group-button" data-group-id="<?= htmlspecialchars($group['id']) ?>">
                        <?= htmlspecialchars($group['name']) ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- モーダルウィンドウ -->
        <div id="password-modal" class="modal">
            <div class="modal-content">
                <span id="modal-close" class="close">&times;</span>
                <h2>グループパスワード確認</h2>
                <form id="password-form">
                    <input type="hidden" name="group_id" id="group-id">
                    <label>パスワード: <input type="password" name="password" id="group-password" required></label><br>
                    <button type="submit">確認</button>
                </form>
                <p id="error-message" style="color: red; display: none;"></p>
            </div>
        </div>
    </main>
</body>

</html>