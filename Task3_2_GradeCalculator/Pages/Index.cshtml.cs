using System.ComponentModel.DataAnnotations;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace GradeWeb.Pages;

public class IndexModel(ILogger<IndexModel> logger) : PageModel
{
    [BindProperty, Required, Range(0, 100)] public decimal? Activities { get; set; }
    [BindProperty, Required, Range(0, 100)] public decimal? Project { get; set; }
    [BindProperty, Required, Range(0, 100)] public decimal? Examination { get; set; }
    public GradeResult? Result { get; private set; }
    public void OnGet() { }
    public void OnPost()
    {
        if (!ModelState.IsValid)
        {
            logger.LogWarning("Form calculation rejected: invalid marks");
            Response.StatusCode = 400;
            return;
        }
        Result = Calculator.Calculate(new(Activities, Project, Examination));
        logger.LogInformation("Form calculation completed successfully");
    }
}
