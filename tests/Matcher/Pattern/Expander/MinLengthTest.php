<?php

namespace Coduo\PHPMatcher\Tests\Matcher\Pattern\Expander;

use Coduo\PHPMatcher\Matcher;
use Coduo\PHPMatcher\Matcher\Pattern\Expander\MinLength;

class MinLengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider examplesProvider
     */
    public function test_matching_values($min, $haystack, $expectedResult)
    {
        $expander = new MinLength($min);
        $this->assertEquals($expectedResult, $expander->match($haystack));
    }

    public static function examplesProvider()
    {
        return array(
            array(1, 'a', true),
            array(1, 'ab', true),

            array(1, array("ipsum"), true),
            array(1, array("foo", 1), true),
        );
    }

    /**
     * @dataProvider invalidCasesProvider
     */
    public function test_error_when_matching_fail($min, $value, $errorMessage)
    {
        $expander = new MinLength($min);
        $this->assertFalse($expander->match($value));
        $this->assertEquals($errorMessage, $expander->getError());
    }

    public static function invalidCasesProvider()
    {
        return array(
            array(2, 'a', 'a is less than "2" characters long.'),
            array(2, array(1), "Array(1) has less than \"2\" elements."),
            array(2, new \DateTime(), "MinLength expander requires \"array\" or \"string\", got \"\DateTime\"."),
        );
    }
}
