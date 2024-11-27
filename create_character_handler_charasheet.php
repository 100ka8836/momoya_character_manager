<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['form_type'] ?? '') !== 'charasheet') {
    die("不正なアクセスです。");
}

// キャラクター保管所のURLまたはIDを取得
$charasheetInput = $_POST['charasheet_url'] ?? null;
$groupId = $_POST['group_id'] ?? null;

if (!$charasheetInput || !$groupId) {
    die("キャラクター保管所URLまたは所属グループが指定されていません。");
}

// IDまたはURLを正規化
if (filter_var($charasheetInput, FILTER_VALIDATE_URL)) {
    $parsedUrl = parse_url($charasheetInput);
    $charasheetId = basename($parsedUrl['path'], ".js");
} elseif (is_numeric($charasheetInput)) {
    $charasheetId = $charasheetInput;
} else {
    die("キャラクター保管所URLが無効です。");
}

// キャラクター保管所URLを生成
$charasheetUrl = "http://charasheet.vampire-blood.net/{$charasheetId}.js";

try {
    // キャラクターJSONを取得
    $characterJson = file_get_contents($charasheetUrl);

    if (!$characterJson) {
        throw new Exception("キャラクターデータを取得できませんでした。");
    }

    // JSONデコード
    $characterData = json_decode($characterJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("キャラクターデータの解析に失敗しました。");
    }

    // 必要なデータを抽出し、存在しない場合はデフォルト値として `-` を設定
    $name = $characterData['character_name'] ?? '-';
    $age = $characterData['age'] ?? '-';
    $occupation = $characterData['job'] ?? '-';
    $birthplace = $characterData['birthplace'] ?? '-';
    $degree = $characterData['degree'] ?? '-';
    $sex = $characterData['sex'] ?? '-';

    $attributes = [
        'str' => $characterData['STR'] ?? '-',
        'con' => $characterData['CON'] ?? '-',
        'pow' => $characterData['POW'] ?? '-',
        'dex' => $characterData['DEX'] ?? '-',
        'app' => $characterData['APP'] ?? '-',
        'siz' => $characterData['SIZ'] ?? '-',
        'int_value' => $characterData['INT'] ?? '-',
        'edu' => $characterData['EDU'] ?? '-',
        'hp' => $characterData['HP'] ?? '-',
        'mp' => $characterData['MP'] ?? '-',
        'db' => $characterData['DB'] ?? '-',
        'san_current' => $characterData['SAN'] ?? '-',
        'san_max' => $characterData['SAN_MAX'] ?? '-'
    ];

    // データベースに保存
    $pdo->beginTransaction();

    // キャラクターテーブルに保存
    $stmt = $pdo->prepare("
        INSERT INTO characters (name, age, occupation, birthplace, degree, sex, group_id)
        VALUES (:name, :age, :occupation, :birthplace, :degree, :sex, :group_id)
    ");
    $stmt->execute([
        ':name' => $name,
        ':age' => $age,
        ':occupation' => $occupation,
        ':birthplace' => $birthplace,
        ':degree' => $degree,
        ':sex' => $sex,
        ':group_id' => $groupId
    ]);

    // キャラクターIDを取得
    $characterId = $pdo->lastInsertId();

    // 属性を保存
    $stmt = $pdo->prepare("
        INSERT INTO character_attributes 
        (character_id, str, con, pow, dex, app, siz, int_value, edu, hp, mp, db, san_current, san_max)
        VALUES (:character_id, :str, :con, :pow, :dex, :app, :siz, :int_value, :edu, :hp, :mp, :db, :san_current, :san_max)
    ");
    $attributes[':character_id'] = $characterId;
    $stmt->execute($attributes);

    $pdo->commit();

    echo "キャラクター「{$name}」が正常に登録されました。";

} catch (Exception $e) {
    $pdo->rollBack();
    die("エラー: " . $e->getMessage());
}
