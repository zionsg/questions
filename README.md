# Questions
My attempted solutions to coding questions.

## Convention
In the interest of simplicity, here are some conventions that this repository will be keeping to:
- One folder per question.
- The solution shall be saved as `Solver.php` in the root of the folder for the question.
    + The file shall contain exactly 1 class, `Solver`. No namespace is required.
    + The class shall have an empty constructor.
    + The class shall implement `__invoke()` which takes in the input and returns the output.
    + Sample of how the solution will be run:

      ```
      $solver = new Solver();
      $output = $solver($input);
      ```
- The tests shall be saved in a single file, `tests.php`, in the root of the folder for the question.
    + It shall contain an array of tests.
    + Each test will be an array of 3 elements - the test name, the input and the expected output.
    + The file shall end with a simple loop to run thru the tests.
    + To run the tests, type `php tests.php` in the folder for the question.
