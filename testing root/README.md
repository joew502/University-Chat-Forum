# Testing Framework summary doc

##Usage

Tests can be created by adding a php file to anywhere within the [tests](./tests) directory this php file should set the following global variables depending on the result of the test:
 * `$test_passed`- set to True if test succeeded and False if there was an error
 * `$test_error` - Set to a string to display this wirth the test result - optional for passed tests mandatory in all error state tests
 * `$test_error_level` - Optional integer, defaults to 3.  Normally doesn't need ot be set.  This allows tests to be assigned different priority's based on their nature. See below for options.

See example test in [/tests/meta/testing_test.php](./tests/meta/testing_test.php)
### Error Levels:
 * 5: CRITICAL - Reserved for failures of the testing framework
 * 4: PRIORITY - Reserved for failures of tests (not the code they test, but the test themselves)
 * 3: ERROR    - The default abd most common failure for a test
 * 2: WARN     - For cases where the code will function but there is still a problem
 * 1: INFO     - For cases where a test failed, but the underling code still functions (rarely used)
 * 0: DEBUG    - Should not be used in production code exists for ease of testing development