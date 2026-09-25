param([string]$PhpPath = '')
$ErrorActionPreference = 'Stop'
$taskRoot = Split-Path $PSScriptRoot -Parent
if (!$PhpPath) {
    $localPhp = Join-Path $taskRoot 'tools\php\php.exe'
    if (Test-Path $localPhp) { $PhpPath = $localPhp } else { $PhpPath = 'php' }
}
dotnet run --project (Join-Path $taskRoot 'tests\GradeWeb.Tests.csproj')
if ($LASTEXITCODE -ne 0) { throw 'C# tests failed.' }
Get-ChildItem (Join-Path $taskRoot 'Task3_3_PHPGradeCalculator') -Filter '*.php' | ForEach-Object {
    & $PhpPath -l $_.FullName
    if ($LASTEXITCODE -ne 0) { throw "PHP syntax failure: $($_.Name)" }
}
& $PhpPath (Join-Path $taskRoot 'tests\php-tests.php')
if ($LASTEXITCODE -ne 0) { throw 'PHP tests failed.' }
