<?php
declare(strict_types=1);

namespace Rugaard\Trustpilot\Exceptions;

use GuzzleHttp\Exception\ServerException as GuzzleServerException;

/**
 * Class ServerException
 *
 * @package Rugaard\Trustpilot\Exceptions
 */
class ServerException extends GuzzleServerException {}
