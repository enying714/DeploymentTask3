# SWE40006 Deployment Activity 3 – Azure Web Deployment

**Student:** Cheong En Ying (105965515)  
**Unit:** SWE40006 Software Deployment and Evolution  
**Assessment:** Deployment Activity 3 – Azure Web Deployment  
**Task level attempted:** Task 3.3 – High Distinction, including Tasks 3.1 and 3.2

## Project Overview

This repository provides the application source code, tests and packaging scripts for **Deployment Activity 3** in **SWE40006 Software Deployment and Evolution**.

The activity covers deployment of an existing ASP.NET Core application, a C# grade calculator and a PHP grade calculator to Azure App Service. The submitted report contains screenshots of deployment, public verification, C# application deactivation and troubleshooting.

| Task | Implementation |
|---|---|
| 3.1 – Pass | Create an Azure account, set up the development environment and deploy an existing web application. |
| 3.2 – Credit | Develop and deploy a C# web application, then demonstrate deactivation. |
| 3.3 – High Distinction | Set up PHP, develop a PHP web application and deploy it to Azure. |

## Application Features

The C# and PHP applications are named **Grade Studio**. Both accept three assessment marks between 0 and 100 and calculate a weighted result:

- Deployment activities: 20%.
- Project: 40%.
- Examination: 40%.

The total is rounded to two decimal places before classification: High Distinction (80 or above), Distinction (70–79.99), Credit (60–69.99), Pass (50–59.99), or Fail (below 50). These are demonstration rules, not a statement of the unit's official assessment policy.

The interface includes input validation, a clear action, an empty result state and a responsive pastel layout. On wider screens, the form and result appear side by side. Neither calculator stores student records or requires a database.

## Repository Structure

```text
Task3_1_ExistingApp/       Existing ASP.NET Core Razor Pages sample
Task3_2_GradeCalculator/  C# Grade Studio application
Task3_3_PHPGradeCalculator/ PHP Grade Studio application
scripts/                 Test and deployment-packaging scripts
tests/                   C# and PHP automated checks
README.md                Project and verification instructions
.gitignore               Local/generated file exclusions
task3.code-workspace     Optional VS Code workspace
```

Task 3.1 uses the Microsoft ASP.NET Core Razor Pages template, generated with:

```powershell
dotnet new webapp --framework net8.0 --name ExistingSample
```

The existing sample is attributed to Microsoft. The calculator implementations and supporting scripts were developed with generative AI assistance, as acknowledged in the report.

## Development and Hosting Environment

- Visual Studio 2022 for ASP.NET Core development and publishing.
- Visual Studio Code with Microsoft's Azure App Service extension for PHP deployment.
- .NET 8 target framework; a compatible .NET SDK and .NET 8 runtime are required locally.
- Local PHP verification recorded PHP 8.5.10 CLI; Azure PHP hosting was configured for PHP 8.5.
- Azure for Students subscription and Azure App Service in Malaysia West.
- Windows hosting for the ASP.NET Core applications; Linux hosting for PHP.

The portable PHP runtime under `tools/php` is a local dependency and is not included in the repository. On another computer, install PHP separately and make `php` available on PATH. Installing a VS Code extension alone does not install the PHP runtime.

## Run Locally

Open PowerShell in the repository root. No Azure account is needed for local execution.

### Existing Application

```powershell
dotnet run --project .\Task3_1_ExistingApp\ExistingSample.csproj --no-launch-profile --urls http://localhost:5080
```

Open <http://localhost:5080>.

### C# Grade Studio

```powershell
dotnet run --project .\Task3_2_GradeCalculator\GradeWeb.csproj --no-launch-profile --urls http://localhost:5081
```

Open <http://localhost:5081>. The smoke-check endpoint is <http://localhost:5081/health>.

### PHP Grade Studio

With PHP installed on PATH:

```powershell
php -v
php -S localhost:5082 -t .\Task3_3_PHPGradeCalculator
```

If using the local portable runtime instead:

```powershell
.\tools\php\php.exe -v
.\tools\php\php.exe -S localhost:5082 -t .\Task3_3_PHPGradeCalculator
```

Open <http://localhost:5082>. The smoke-check endpoint is <http://localhost:5082/health.php>.

Press **Ctrl+C** in the corresponding terminal to stop a local server. PHP's built-in development server is used only for local testing.

## Automated Verification

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\test.ps1
```

The script uses `tools/php/php.exe` when present and otherwise uses `php` on PATH. To specify a different installation:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\test.ps1 -PhpPath "C:\path\to\php.exe"
```

The recorded verification run passed **23 C# checks and 28 PHP checks**. The script also checks PHP syntax. Tests cover weighting, grade boundaries, rounding and invalid input; PHP checks additionally cover output escaping. The script stops if a command fails.

These checks exercise application logic. They do not replace opening the deployed Azure applications and verifying public responses.

## Azure Deployment

The deployment workflow used for this activity was:

1. Create the Azure App Service resources under the Azure for Students subscription.
2. Publish the existing sample and C# calculator from Visual Studio to their Windows App Services.
3. Open the public addresses and verify the deployed content and calculator behaviour.
4. Stop the C# Web App in Azure and capture the stopped state and unavailable application response for Task 3.2.
5. Deploy the `Task3_3_PHPGradeCalculator` folder from VS Code to the PHP Linux Web App.
6. Verify the public PHP calculator and `health.php`, and inspect Azure Log stream.

The C# application was subsequently republished during interface refinement. The report retains the earlier deactivation evidence. Hosting status can change after evidence capture.

### Deployment Addresses

| Application | Azure address |
|---|---|
| Existing sample | https://enying-deploymenttask3-frg6eudqbmhyc9bv.malaysiawest-01.azurewebsites.net/ |
| C# Grade Studio | https://enying-task3-csharp-adbpa2c4edage2ch.malaysiawest-01.azurewebsites.net/ |
| PHP Grade Studio | https://enying-task3-php-g3h9fbahf0a7gacr.malaysiawest-01.azurewebsites.net/ |

These are the deployment addresses recorded for the assignment, not a guarantee of continuous availability. The C# and PHP smoke-check paths are `/health` and `/health.php` respectively. A successful JSON response checks route/runtime availability; it does not test every calculation path.

### Optional Deployment Packages

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\package.ps1
```

This publishes the C# calculator in Release configuration and creates:

```text
artifacts/csharp/
artifacts/csharp.zip
artifacts/php.zip
```

The PHP archive includes `.user.ini`, and application files are placed at the archive root. This script prepares packages; it does not upload them to Azure. Generated packages are excluded from the source repository.

## Important Source Files

| File | Purpose |
|---|---|
| `Task3_2_GradeCalculator/Calculator.cs` | Validates marks, applies weighting and classifies the rounded result using decimal arithmetic. |
| `Task3_2_GradeCalculator/Pages/Index.cshtml.cs` | Handles form submission and server-side model validation. |
| `Task3_2_GradeCalculator/Pages/Index.cshtml` | Renders the form, validation messages and result. |
| `Task3_2_GradeCalculator/Program.cs` | Configures Razor Pages, static assets, logging and health/calculation endpoints. |
| `Task3_3_PHPGradeCalculator/calculator.php` | Validates input, calculates the result and provides HTML output escaping. |
| `Task3_3_PHPGradeCalculator/index.php` | Processes submissions and renders the calculator. |
| `Task3_3_PHPGradeCalculator/health.php` | Returns the PHP smoke-check response. |
| `Task3_3_PHPGradeCalculator/.user.ini` | Enables error logging while disabling direct display of PHP errors. |
| `tests/` | Contains automated calculation and validation checks. |
| `scripts/test.ps1` | Runs automated checks and PHP syntax validation. |
| `scripts/package.ps1` | Prepares C# and PHP deployment packages. |

C# uses decimal arithmetic and midpoint rounding away from zero. PHP uses floating-point arithmetic and `PHP_ROUND_HALF_UP`. Both round to two decimal places before classification. Razor encodes displayed values, and PHP uses `htmlspecialchars` when redisplaying submitted content.

## Troubleshooting Encountered

### Missing Local PHP Executable

Running `.\tools\php\php.exe -v` initially returned `CommandNotFoundException`. `Test-Path` returned `False`, identifying a missing executable at the expected location. The portable runtime was restored and its version verified. This was a runtime/path problem, not a failure to compile PHP source.

### Windows/Linux Publishing Target Mismatch

An existing Windows App Service did not appear under the Linux publishing target in Visual Studio. Selecting Azure App Service (Windows) made the intended resource selectable. The correction changed the publishing target; it did not migrate the application to Linux.

### Subscription Selection

Azure for Students was temporarily unavailable in the Visual Studio selection. The subscription was checked in Azure Portal and was subsequently selected successfully. The exact cause of the temporary selection issue was not confirmed.

### PHP Startup Log Messages

The PHP log contained certificate warnings, a missing Oryx manifest message and a missing `robots933456.txt` request. Investigation considered the subsequent startup and request messages, including PHP-FPM readiness and HTTP 200 responses. The public calculator and smoke-check endpoint provided additional verification.

These messages are not presented as application defects that were all removed. The report distinguishes platform startup messages from failures that required correction, and records the supporting screenshots.

## Repository Purpose

This repository provides source code and scripts for assignment verification. The submitted report contains the detailed evidence, analysis and references. Generated build output, local runtimes, Azure publishing credentials and deployment packages do not belong in the public source repository.

