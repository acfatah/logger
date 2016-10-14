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

namespace Acfatah\Logger\Handler;

use Acfatah\Logger\FormatterInterface;
use Acfatah\Logger\HandlerInterface;
use Acfatah\Logger\Formatter\DefaultFormatter;

/**
 * Uses php **error_log** function with option **3** to log error to destination.
 *
 * Read more about `error_log` function at [php.net][1].
 *
 * [1]: http://php.net/manual/en/function.error-log.php
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 *
 * @codeCoverageIgnore
 */
class FileHandler implements HandlerInterface
{
    /**
     * @var string The log destination
     */
    private $destination;

    /**
     * @var \Acfatah\Logger\FormatterInterface
     */
    private $formatter;

    /**
     * Constructor.
     *
     * @param string $destination
     */
    public function __construct($destination, FormatterInterface $formatter = null)
    {
        $this
            ->setFormatter($formatter)
            ->setDestination($destination);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, $context)
    {
        return error_log(
            $this->getFormatter()->format($level, $message, $context),
            3,
            $this->getDestination()
        );
    }

    /**
     * Formatter setter.
     *
     * @param \Acfatah\Logger\FormatterInterface $formatter
     * @return static
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Formatter getter.
     *
     * @return \Acfatah\Logger\FormatterInterface
     */
    public function getFormatter()
    {
        if (null === $this->formatter) {
            $this->setFormatter(new DefaultFormatter);
        }
        return $this->formatter;
    }

    /**
     * Destination setter.
     *
     * @param string $destination
     * @return static
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Destination getter.
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }
}
