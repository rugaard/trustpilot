<?php
declare(strict_types=1);

namespace Rugaard\Trustpilot\Exceptions;

use GuzzleHttp\Exception\ClientException as GuzzleClientException;

/**
 * Class ClientException
 *
 * @package Rugaard\Trustpilot\Exceptions
 */
class ClientException extends GuzzleClientException {}
