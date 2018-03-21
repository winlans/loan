<?php
namespace App\HttpFoundation\Exception;
/**
 * Class RequestException
 *
 * @package \\${NAMESPACE}
 */
class ApiRequestException extends \Exception
{
	private $data;

	public function __construct($data = array(), $message = '', $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setData($data = array())
	{
		$this->data = $data;
	}
}