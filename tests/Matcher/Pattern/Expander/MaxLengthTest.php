<?php

namespace Coduo\PHPMatcher\Tests\Matcher\Pattern\Expander;

use Coduo\PHPMatcher\Matcher;
use Coduo\PHPMatcher\Matcher\Pattern\Expander\MaxLength;

class MaxLengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider examplesProvider
     */
    public function test_matching_values($max, $haystack, $expectedResult)
    {
        $expander = new MaxLength($max);
        $this->assertEquals($expectedResult, $expander->match($haystack));
    }

    public static function examplesProvider()
    {
        return array(
            array(1, 'a', true),
            array(2, 'ab', true),

            array(1, array("ipsum"), true),
            array(2, array("foo", 1), true),
        );
    }

    /**
     * @dataProvider invalidCasesProvider
     */
    public function test_error_when_matching_fail($min, $value, $errorMessage)
    {
        $expander = new MaxLength($min);
        $this->assertFalse($expander->match($value));
        $this->assertEquals($errorMessage, $expander->getError());
    }

    public static function invalidCasesProvider()
    {
        return array(
            array(2, 'abcdef', 'abcdef is more than "2" characters long.'),
            array(2, array(1, 2, 3), "Array(3) has more than \"2\" elements."),
            array(2, new \DateTime(), "MaxLength expander requires \"array\" or \"string\", got \"\DateTime\"."),
        );
    }
}
