<?php
namespace Lima\Movie\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class AbstractData
 * @package Lima\Movie\Helper
 */
class AbstractData extends AbstractHelper
{
	const CONFIG_SECTION_ID = 'movie';
	const CONFIG_GENERAL_GROUP = 'general';
	const CONFIG_MOVIE_GROUP = 'movie_default';

    /**
     * @var ScopeConfigInterface
     */
	protected $_scopeConfig;

    /**
     * AbstractData constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
	public function __construct(
		ScopeConfigInterface $scopeConfig
	)
	{
		$this->_scopeConfig = $scopeConfig;
	}

    /**
     * @param string $group
     * @param string $config
     * @return mixed
     */
	public function getConfig(string $group, string $config)
	{
		$configPath = $this->_getConfigPath($group, $config);
		return $this->_scopeConfig->getValue($configPath);
	}

    /**
     * @param string $group
     * @param string $config
     * @return string
     */
	private function _getConfigPath(string $group, string $config)
	{
		$configPathArr = [self::CONFIG_SECTION_ID, $group, $config];
		return implode('/', $configPathArr);
	}

    /**
     * @return bool
     */
	public function isEnable()
	{
		return (bool) $this->getConfig(self::CONFIG_GENERAL_GROUP, 'enable');
	}

    /**
     * @return string
     */
	public function getLanguage()
	{
		return (string) $this->getConfig(self::CONFIG_GENERAL_GROUP, 'language');
	}

    /**
     * @return string
     */
	public function getIncludeAdult()
	{
		$includeAdult = boolval($this->getConfig(self::CONFIG_GENERAL_GROUP, 'include_adult'));
		return (string) $includeAdult;
	}

    /**
     * @param string $img
     * @return string|null
     */
	public function getMoviePoster(string $img)
	{
		if($img) {
			return $this->getConfig(self::CONFIG_GENERAL_GROUP, 'image_base_path') . $img;
		} else {
			return null;
		}
	}

    /**
     * @return float
     */
	public function getDefaultPrice()
	{
		return (float) $this->getConfig(self::CONFIG_MOVIE_GROUP, 'price');
	}

    /**
     * @return int
     */
	public function getDefaultStock()
	{
		return (int) $this->getConfig(self::CONFIG_MOVIE_GROUP, 'stock');
	}

    /**
     * @return int
     */
	public function getAttributeSetId()
	{
		return (int) $this->getConfig(self::CONFIG_MOVIE_GROUP, 'attribute_set_id');
	}

}
