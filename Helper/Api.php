<?php
namespace Lima\Movie\Helper;

/**
 * Class Api
 * @package Lima\Movie\Helper
 */
class Api extends AbstractData
{
    /**
     * @return string
     */
	private function _getApiKey()
	{
		return (string) $this->getConfig(self::CONFIG_GENERAL_GROUP, 'api_key');
	}

    /**
     * @param $data
     * @param $endpoint
     * @return string
     */
	public function buildCall($data, $endpoint)
	{
		if(isset($data['form_key'])) {
			unset($data['form_key']);
		}

		$data = array_filter($data);
		$this->_addAditionalData($data);

		$params = http_build_query($data);
		$urlWithEndpoint = $this->_getUrlWithEndpoint($endpoint);
		$url = $urlWithEndpoint . "?{$params}";

		return (string) $url;
	}

    /**
     * @param $data
     */
	private function _addAditionalData(&$data)
	{
		$data['api_key']        = $this->_getApiKey();
		$data['include_adult']  = isset($data['include_adult']) ? $data['include_adult'] : $this->getIncludeAdult();
		$data['language']       = isset($data['language']) ? $data['language'] : $this->getLanguage();
	}

    /**
     * @param $endpoint
     * @return string
     */
	private function _getUrlWithEndpoint($endpoint)
	{
		$url = [
			$this->getConfig(self::CONFIG_GENERAL_GROUP, 'api_url'),
			$this->getConfig(self::CONFIG_GENERAL_GROUP, 'api_version'),
			$endpoint,
		];

		return implode('/', $url);
	}

}
