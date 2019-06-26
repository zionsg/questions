# Questions
My attempted solutions to coding questions.

## Convention
In the interest of simplicity, here are some conventions that this repository will be keeping to:
- One folder per question. The folder shall use the `StudlyCase` naming convention.
- There will be exactly 4 files in the folder: `README.md`, `Solver.php`, `run.php`, `tests.php`.
- `README.md`:
    + Description of question in Markdown format.
- `Solver.php`:
    + This contains the solution.
    + The file shall contain exactly 1 class, `Solver`. No namespace is required.
    + The class shall have an empty constructor.
    + The class shall implement `__invoke()` which reads in from `STDIN` and returns the output.
    + The output will always be a string, as it needs to be displayed in the console.
    + Sample of how the solution will be run:

      ```
      $solver = new Solver();
      $output = $solver();
      ```
- `run.php`:
    + This is the main file for running the solution against an input.
    + Usage in terminal: `php run.php < input.txt`.
    + Internally, the file simply instantiates `Solver`, invokes it and echoes the output. Sample as follows:

      ```
      include 'Solver.php';
      $solver = new Solver();
      echo $solver(); // output result
      ```
- `tests.php`
    + This runs a test suite of test inputs against `run.php`.
    + It shall contain an array of tests.
    + Each test will be an array of 3 elements (all strings) - the test name, the input and the expected output.
    + The file shall end with a simple loop to run thru the tests. The input will be stored as `input.txt` and
      fed into `run.php`, i.e. `php run.php < input.txt`.
    + To run the tests, type `php tests.php` in the folder for the question.
