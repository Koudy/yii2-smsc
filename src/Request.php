<?php

namespace koudy\yii2\smsc;

class Request implements interfaces\Request
{
	const RESPONSE_JSON_FORMAT = 3;

	/**
	 * @var string|array
	 */
	private $phones;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var string
	 */
	private $login;

	/**
	 * @var string
	 */
	private $password;

	public function __construct(
		$phones,
		string $text,
		string $login,
		string $password
	)
	{
		$this->phones = $phones;
		$this->text = $text;
		$this->login = $login;
		$this->password = $password;
	}

	public function getRequestParams(): array
	{
		if (is_array($this->phones)) {
			$phones = implode(',', $this->phones);
		} else {
			$phones = $this->phones;
		}

		return [
			'login' => $this->login,
			'psw' => $this->password,
			'phones' => $phones,
			'mes' => $this->text,
			'fmt' => self::RESPONSE_JSON_FORMAT
		];
	}
}
