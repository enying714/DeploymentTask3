<?php
require __DIR__ . '/../Task3_3_PHPGradeCalculator/calculator.php';
$count = 0;
function check(bool $ok, string $name): void {
    global $count;
    if (!$ok) { fwrite(STDERR, "FAIL $name\n"); exit(1); }
    $count++; echo "PASS $name\n";
}
function marks(mixed $a, mixed $p, mixed $e): array { return ['activities'=>$a, 'project'=>$p, 'examination'=>$e]; }
check(calculate_grade(marks(85,80,75)) === ['total'=>79.0, 'grade'=>'Distinction'], 'weighted result');
foreach ([[0,'Fail'],[49.99,'Fail'],[50,'Pass'],[59.99,'Pass'],[60,'Credit'],[69.99,'Credit'],[70,'Distinction'],[79.99,'Distinction'],[80,'High Distinction'],[100,'High Distinction'],[79.995,'High Distinction']] as [$mark,$grade]) {
    check(calculate_grade(marks($mark,$mark,$mark))['grade'] === $grade, "boundary $mark");
}
foreach ([[100,0,0,20.0],[0,100,0,40.0],[0,0,100,40.0]] as [$a,$p,$e,$total]) check(calculate_grade(marks($a,$p,$e))['total'] === $total, 'weighting');
foreach (['',null,-1,101,'hello','NaN','INF','1e999',[],true] as $bad) {
    check(count(validate_marks(marks($bad,50,50))) > 0, 'invalid input rejected');
}
check(count(validate_marks([])) === 3, 'missing fields');
check(escape_html('<script>') === '&lt;script&gt;', 'HTML escaped');
try { calculate_grade(marks(-1,0,0)); check(false,'invalid calculation'); } catch (InvalidArgumentException $e) { check(true,'calculation guard'); }
echo "PHP: $count checks passed.\n";
