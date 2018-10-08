<?php

namespace koudy\yii2\smsc;

use yii\base\Component;

class Request extends Component
{
	const RESPONSE_JSON_FORMAT = 3;

	/**
	 * @var string|array
	 */
	public $phones;

	/**
	 * @var string
	 */
	public $text;

	/**
	 * @var string
	 */
	public $login;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @return array
	 */
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
