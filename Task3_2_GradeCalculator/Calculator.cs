namespace GradeWeb;

public record Marks(decimal? Activities, decimal? Project, decimal? Examination);
public record GradeResult(decimal Total, string Grade);

public static class Calculator
{
    public static Dictionary<string, string[]> Validate(Marks marks)
    {
        var errors = new Dictionary<string, string[]>();
        foreach (var (name, value) in new[] { ("Activities", marks.Activities), ("Project", marks.Project), ("Examination", marks.Examination) })
            if (value is null or < 0 or > 100)
                errors[name] = ["Enter a numeric mark from 0 to 100."];
        return errors;
    }

    public static GradeResult Calculate(Marks marks)
    {
        if (Validate(marks).Count != 0) throw new ArgumentException("Marks must be between 0 and 100.");
        var total = decimal.Round(marks.Activities!.Value * .20m + marks.Project!.Value * .40m + marks.Examination!.Value * .40m, 2, MidpointRounding.AwayFromZero);
        var grade = total switch { >= 80 => "High Distinction", >= 70 => "Distinction", >= 60 => "Credit", >= 50 => "Pass", _ => "Fail" };
        return new(total, grade);
    }
}
