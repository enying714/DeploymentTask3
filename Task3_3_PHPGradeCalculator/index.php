<?php
declare(strict_types=1);
require_once __DIR__ . '/calculator.php';
header('Content-Type: text/html; charset=utf-8');
$errors = [];
$result = null;
$input = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST;
    $errors = validate_marks($input);
    if ($errors) {
        http_response_code(400);
        error_log('GradePHP: calculation rejected; invalid fields: ' . implode(', ', array_keys($errors)));
    } else {
        $result = calculate_grade($input);
        error_log('GradePHP: calculation completed successfully');
    }
}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Grade Studio | PHP</title><link rel="stylesheet" href="style.css?v=<?= escape_html(hash_file('sha256', __DIR__ . '/style.css')) ?>"></head>
<body><main>
<header><span class="eyebrow">SWE40006 / TASK 3.3 / PHP</span><h1>Grade Studio<span>.</span></h1><p>Turn your assessment marks into a clear result.</p></header>
<div class="calculator-layout">
<section class="card"><h2>Calculate your result</h2><p class="muted">Enter marks from 0 to 100. Activities contribute 20%, project 40%, and examination 40%.</p>
<?php if ($errors): ?><div class="errors" role="alert"><ul><?php foreach ($errors as $error): ?><li><?= escape_html($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" action="index.php">
<?php foreach (['activities' => ['Deployment activities', '20%'], 'project' => ['Project', '40%'], 'examination' => ['Examination', '40%']] as $field => [$label, $weight]): ?>
<label for="<?= $field ?>"><?= $label ?> <small><?= $weight ?></small></label>
<input id="<?= $field ?>" name="<?= $field ?>" type="number" min="0" max="100" step="any" required placeholder="Enter mark" value="<?= escape_html($input[$field] ?? '') ?>">
<?php endforeach; ?>
<div class="actions"><button type="submit">Calculate grade &rarr;</button><a href="index.php">Clear</a></div>
</form></section>
<aside class="result" aria-labelledby="result-heading" aria-live="polite">
<span class="eyebrow">YOUR WEIGHTED RESULT</span>
<?php if ($result): ?>
    <div class="result-content">
        <strong class="score"><?= number_format($result['total'], 2, '.', '') ?><small> / 100</small></strong>
        <h2 id="result-heading"><?= escape_html($result['grade']) ?></h2>
        <p>Your result combines activities (20%), project (40%) and examination (40%).</p>
    </div>
<?php else: ?>
    <div class="result-content empty-result">
        <h2 id="result-heading">Your result appears here</h2>
        <p>Enter your three assessment marks and select Calculate grade to see your weighted result.</p>
    </div>
<?php endif; ?>
<div class="grade-guide"><span>GRADE GUIDE</span><p>HD 80+ &middot; D 70+ &middot; C 60+ &middot; P 50+</p></div>
</aside>
</div>
<footer>Demonstration calculator &middot; Rounded to two decimal places before classification.<br>HD 80+ &middot; D 70+ &middot; C 60+ &middot; P 50+ &middot; This is not an official results service.<br><a href="health.php">Application health</a></footer>
</main></body></html>
