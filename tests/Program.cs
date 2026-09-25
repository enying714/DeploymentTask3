using GradeWeb;
var count = 0;
void Check(bool value, string name) { if (!value) throw new Exception(name); count++; Console.WriteLine($"PASS {name}"); }
Check(Calculator.Calculate(new(85, 80, 75)) == new GradeResult(79, "Distinction"), "weighted marks 85/80/75 => 79 D");
foreach (var (mark, grade) in new (decimal, string)[] { (0,"Fail"),(49.99m,"Fail"),(50,"Pass"),(59.99m,"Pass"),(60,"Credit"),(69.99m,"Credit"),(70,"Distinction"),(79.99m,"Distinction"),(80,"High Distinction"),(100,"High Distinction"),(79.995m,"High Distinction") })
    Check(Calculator.Calculate(new(mark,mark,mark)).Grade == grade, $"grade boundary {mark}");
Check(Calculator.Calculate(new(0,0,100)).Total == 40, "exam weighting");
Check(Calculator.Calculate(new(0,100,0)).Total == 40, "project weighting");
Check(Calculator.Calculate(new(100,0,0)).Total == 20, "activity weighting");
foreach (var marks in new[] { new Marks(null,50,50),new Marks(-1,50,50),new Marks(50,101,50),new Marks(50,50,null) }) {
    Check(Calculator.Validate(marks).Count > 0, "invalid marks rejected");
    try { Calculator.Calculate(marks); throw new Exception("Invalid marks accepted"); } catch (ArgumentException) { count++; }
}
Console.WriteLine($"C#: {count} checks passed.");
