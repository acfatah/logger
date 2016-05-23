<?php

class DefaultFormatterTest extends \PHPUnit_Framework_TestCase
{
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new Acfatah\Logger\Formatter\DefaultFormatter();
    }

    public function testFormat()
    {
        $level = 'TEST';
        $message = 'This is a test message!';
        $context = ['first'=>1, 'second'=>2];
        $timeFormat = 'Y-m-d H:i:s T';

        $datePattern = '2[01][0-9]{2}-(0[1-9]|1[012])-([012][0-9]|3[01])';
        $timePattern = '([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]) [A-Z]{3}';
        $pattern = "/\[$datePattern $timePattern\] \[TEST] $message.*/";

        $this->formatter->setTimeFormat($timeFormat);
        $this->assertRegExp(
            $pattern,
            $this->formatter->format($level, $message, $context)
        );
    }

}
