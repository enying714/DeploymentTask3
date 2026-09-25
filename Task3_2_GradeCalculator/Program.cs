using GradeWeb;

var builder = WebApplication.CreateBuilder(args);
builder.Services.AddRazorPages();
var app = builder.Build();
if (!app.Environment.IsDevelopment()) app.UseExceptionHandler("/Error");
app.UseStaticFiles();
app.MapRazorPages();
app.MapGet("/health", () => Results.Ok(new { status = "healthy", app = "GradeWeb", version = "1.0.0" }));
app.MapPost("/api/calculate", (Marks marks, ILogger<Program> logger) =>
{
    var errors = Calculator.Validate(marks);
    if (errors.Count > 0)
    {
        logger.LogWarning("Calculation rejected: {Fields}", string.Join(", ", errors.Keys));
        return Results.ValidationProblem(errors);
    }
    logger.LogInformation("Calculation completed successfully");
    return Results.Ok(Calculator.Calculate(marks));
});
app.Run();
