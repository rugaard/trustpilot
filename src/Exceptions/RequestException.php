<?php
declare(strict_types=1);

namespace Rugaard\Trustpilot\Exceptions;

use GuzzleHttp\Exception\TransferException as GuzzleTransferException;

/**
 * Class RequestException
 *
 * @package Rugaard\Trustpilot\Exceptions
 */
class RequestException extends GuzzleTransferException {}
