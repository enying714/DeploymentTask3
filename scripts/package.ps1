param([string]$Configuration = 'Release')
$ErrorActionPreference = 'Stop'
$taskRoot = Split-Path $PSScriptRoot -Parent
$output = Join-Path $taskRoot 'artifacts'
New-Item -ItemType Directory -Force $output | Out-Null
dotnet publish (Join-Path $taskRoot 'Task3_2_GradeCalculator\GradeWeb.csproj') -c $Configuration -o (Join-Path $output 'csharp')
if ($LASTEXITCODE -ne 0) { throw 'C# publish failed.' }
Add-Type -AssemblyName System.IO.Compression, System.IO.Compression.FileSystem
function Write-DeploymentZip([string]$Folder, [string]$Destination) {
    $basePath = (Resolve-Path -LiteralPath $Folder).Path
    $stream = [IO.File]::Open($Destination, [IO.FileMode]::Create)
    $archive = New-Object IO.Compression.ZipArchive($stream, [IO.Compression.ZipArchiveMode]::Create)
    try {
        Get-ChildItem -LiteralPath $basePath -File -Recurse -Force | ForEach-Object {
            # Forward slashes are portable to Azure Linux; -Force includes .user.ini.
            $relative = $_.FullName.Substring($basePath.Length + 1).Replace('\', '/')
            [IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, $_.FullName, $relative) | Out-Null
        }
    } finally { $archive.Dispose(); $stream.Dispose() }
}
Write-DeploymentZip (Join-Path $output 'csharp') (Join-Path $output 'csharp.zip')
Write-DeploymentZip (Join-Path $taskRoot 'Task3_3_PHPGradeCalculator') (Join-Path $output 'php.zip')
Write-Host "Packages ready in $output"

