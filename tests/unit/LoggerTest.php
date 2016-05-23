<?php

class LoggerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function testLog()
    {
        $stub = $this->getMock('Acfatah\Logger\HandlerInterface');
        $stub
            ->expects($this->once())
            ->method('log');

        $logger = new Acfatah\Logger\Logger($stub);
        $logger->log('INFO', 'A test...');
    }

    public function testPushHandler()
    {
        $stub = $this->getMock('Acfatah\Logger\HandlerInterface');
        $stub
            ->expects($this->exactly(2))
            ->method('log');


        $logger = new Acfatah\Logger\Logger($stub);
        $logger
            ->pushHandler('INFO', $stub);
        $logger->log('INFO', 'A test...');
    }

    public function testUnshiftHandler()
    {
        $stub = $this->getMock('Acfatah\Logger\HandlerInterface');
        $stub
            ->expects($this->exactly(2))
            ->method('log');

        $logger = new Acfatah\Logger\Logger($stub);
        $logger
            ->unshiftHandler('INFO', $stub);
        $logger->log('INFO', 'A test...');
    }

    public function testPopHandler()
    {
        $stub = $this->getMock('Acfatah\Logger\HandlerInterface');
        $stub
            ->expects($this->exactly(2))
            ->method('log');

        $popedHandler = $this->getMock('Acfatah\Logger\HandlerInterface');
        $popedHandler
            ->expects($this->once())
            ->method('log');

        $logger = new Acfatah\Logger\Logger($stub);
        $logger
            ->pushHandler('INFO', $popedHandler)
            ->log('INFO', 'First test...');
        $logger
            ->popHandler('INFO')
            ->log('INFO', 'Second test...');
    }

    public function testShiftHandler()
    {
        $stub = $this->getMock('Acfatah\Logger\HandlerInterface');
        $stub
            ->expects($this->exactly(2))
            ->method('log');

        $popedHandler = $this->getMock('Acfatah\Logger\HandlerInterface');
        $popedHandler
            ->expects($this->once())
            ->method('log');

        $logger = new Acfatah\Logger\Logger($stub);
        $logger
            ->pushHandler('INFO', $popedHandler)
            ->log('INFO', 'First test...');
        $logger
            ->shiftHandler('INFO')
            ->log('INFO', 'Second test...');
    }

    public function testSetDefaultHandler()
    {
        $default = $this->getMock('Acfatah\Logger\HandlerInterface');
        $default
            ->expects($this->never())
            ->method('log');
        $stub = $this->getMock('Acfatah\Logger\HandlerInterface');
        $stub
            ->expects($this->once())
            ->method('log');

        $logger = new Acfatah\Logger\Logger($default);
        $logger
            ->setDefaultHandler($stub)
            ->log('INFO', 'A test...');
    }
}
