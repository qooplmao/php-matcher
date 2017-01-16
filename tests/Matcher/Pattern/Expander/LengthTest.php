<?php

namespace Coduo\PHPMatcher\Tests\Matcher\Pattern\Expander;

use Coduo\PHPMatcher\Matcher;
use Coduo\PHPMatcher\Matcher\Pattern\Expander\Length;

class LengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider examplesProvider
     */
    public function test_matching_values($range, $haystack, $expectedResult)
    {
        $expander = new Length($range[0], isset($range[1]) ? $range[1] : null);
        $this->assertEquals($expectedResult, $expander->match($haystack));
    }

    public static function examplesProvider()
    {
        return array(
            array(array(1), 'a', true),
            array(array(2), 'ab', true),

            array(array(1, 2), 'a', true),
            array(array(2, 2), 'ab', true),

            array(array(1), array("ipsum"), true),
            array(array(2), array("foo", 1), true),

            array(array(1, 2), array("ipsum"), true),
            array(array(2, 2), array("foo", 1), true),
        );
    }

    /**
     * @dataProvider invalidCasesProvider
     */
    public function test_error_when_matching_fail($range, $value, $errorMessage)
    {
        $expander = new Length($range[0], isset($range[1]) ? $range[1] : null);
        $this->assertFalse($expander->match($value));
        $this->assertEquals($errorMessage, $expander->getError());
    }

    public static function invalidCasesProvider()
    {
        return array(
            array(array(2), 'a', 'a isn\'t "2" characters long.'),
            array(array(2, 5), 'a', 'a isn\'t between "2" and "5" characters long.'),

            array(array(2), array(1, 2, 3, 4), "Array(4) doesn't have \"2\" elements."),
            array(array(2, 3), array(1, 2, 3, 4), "Array(4) doesn't have between \"2\" and \"3\" elements."),

            array(2, new \DateTime(), "Length expander requires \"array\" or \"string\", got \"\DateTime\"."),
        );
    }
}
