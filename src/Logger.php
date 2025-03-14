<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file.
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright (c) 2016, Achmad F. Ibrahim
 * @link https://github.com/acfatah/logger
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 */

namespace Acfatah\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Acfatah\Logger\HandlerInterface;
use Acfatah\Logger\Handler\FileHandler;

/**
 * Logs data using handler(s).
 *
 * This class implements [`\Psr\Log\LoggerInterface`][1] class.
 *
 * For more advanced error handling and logging, see [Monolog][2] logger.
 *
 * [1]: https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php
 * [2]: https://github.com/Seldaek/monolog Monolog
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class Logger extends AbstractLogger implements LoggerInterface
{
    /**
     * @var \Acfatah\Logger\HandlerInterface
     */
    private $defaultHandler;

    /**
     * @var \Acfatah\ErrorHandler\Logger\HandlerInterface[]
     */
    protected $handlers = [];

    /**
     * Constructor.
     *
     * @param \Acfatah\Logger\HandlerInterface $defaultHandler Default log handler
     */
    public function __construct(HandlerInterface $defaultHandler = null)
    {
        $this->setDefaultHandler($defaultHandler);
    }

    /**
     * Add an additional handler to a log level stack.
     *
     * @param string $level See [`\Psr\Log\LogLevel`][psr].
     *     [psr]: https://github.com/php-fig/log/blob/master/Psr/Log/LogLevel.php
     * @param \Acfatah\Logger\HandlerInterface $handler Log handler
     */
    public function push($level, HandlerInterface $handler)
    {
        $this->handlers[$level][] = $handler;

        return $this;
    }

    /**
     * Add an additional handler to the beginning of a log level stack.
     *
     * @param string $level See [`\Psr\Log\LogLevel`][psr].
     *     [psr]: https://github.com/php-fig/log/blob/master/Psr/Log/LogLevel.php
     * @param \Acfatah\Logger\HandlerInterface $handler Log handler
     */
    public function unshift($level, HandlerInterface $handler)
    {
        if (!isset($this->handlers[$level])) {
            $this->handlers[$level] = [];
        }
        array_unshift($this->handlers[$level], $handler);

        return $this;
    }

    /**
     * Pop a handler from a log level stack.
     *
     * @param string $level See [`\Psr\Log\LogLevel`][psr].
     *     [psr]: https://github.com/php-fig/log/blob/master/Psr/Log/LogLevel.php
     */
    public function pop($level)
    {
        array_pop($this->handlers[$level]);

        return $this;
    }

    /**
     * Shift a handler from a log level stack.
     *
     * @param string $level See [`\Psr\Log\LogLevel`][psr].
     *     [psr]: https://github.com/php-fig/log/blob/master/Psr/Log/LogLevel.php
     */
    public function shift($level)
    {
        array_shift($this->handlers[$level]);

        return $this;
    }

    /**
     * Logs the error level, message and context.
     *
     * This method implements [`\Psr\Log\LoggerInterface::log()`][psr].
     *
     * [psr]: https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php#L122
     *
     * @param mixed $level Error level
     * @param string $message Error message
     * @param array $context Error context if any
     */
    public function log($level, $message, array $context = [])
    {
        $this->getDefaultHandler()->log($level, $message, $context);

        if (isset($this->handlers[$level])) {
            foreach ($this->handlers[$level] as $logger) {
                $logger->log($level, $message, $context);
            }
        }
    }

    /**
     * Sets the default log handler.
     *
     * @param \Acfatah\Logger\HandlerInterface $defaultHandler Default log handler
     * @return \Acfatah\Logger\Logger
     */
    public function setDefaultHandler(HandlerInterface $defaultHandler)
    {
        $this->defaultHandler = $defaultHandler;

        return $this;
    }

    /**
     * Gets the default log handler.
     *
     * @return \Acfatah\ErrorHandler\Logger\HandlerInterface
     */
    public function getDefaultHandler()
    {
        if (is_null($this->defaultHandler)) {
            $this->setDefaultHandler(
                new FileHandler(new DefaultFormatter, ini_get('error_log'))
            );
        }
        return $this->defaultHandler;
    }
}
