<?php
class Txty
{
	private static $url = 'https://login.txty.dk/api/4/';
	private static $ua = 'EventSignup';
	private static $user;
	private static $key;

	public function __construct($user, $key)
	{
		self::$user = $user;
		self::$key = $key;
	}



	private static function call($url, $data = null)
	{

		if($url)
		{
			$data['user'] = self::$user;
			$data['key'] = self::$key;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, self::$url . $url . '/api.json' . '?' . http_build_query($data));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, self::$ua);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);

			return $result;
		}

	}



	public function getDialcodes()
	{
		return self::call('get/dialcodes');
	}

	public function viewGroups()
	{
		return self::call('view/groups');
	}



	public function createContact($fullname, $msisdn, $groups)
	{

		$contact = self::call('contact/create', array('fullname' => $fullname, 'msisdn' => $msisdn));
		if($contact['status'] == 'success')
		{
			if($groups)
			{
				foreach($groups as $value)
					self::assignGroup($contact['contact']['controlcontact'], $value);
			}

			return true;

		} else {

			return false;

		}

	}

	public function assignGroup($contact, $group)
	{
		self::call('contact/addgroup', array('contact' => $contact, 'group' => $group));
	}
}