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
    public function __construct(FormatterInterface $formatter, $destination)
    {
        $this->formatter = $formatter;
        $this->destination = $destination;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, $context)
    {
        return error_log(
            $this->formatter->format($level, $message, $context),
            3,
            $this->destination
        );
    }
}
