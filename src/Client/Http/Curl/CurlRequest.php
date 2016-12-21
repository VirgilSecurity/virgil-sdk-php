<?php
namespace Virgil\Sdk\Client\Http\Curl;


/**
 * Class aims initialize cURL session and provides necessary methods to perform configuration, execution and closing
 * the session.
 */
class CurlRequest implements RequestInterface
{
    /** @var resource $handle */
    private $handle;

    /** @var $options */
    private $options;


    /**
     * Class constructor.
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->handle = $url !== null ? curl_init($url) : curl_init();
    }


    /**
     * @inheritdoc
     */
    public function execute()
    {
        curl_setopt_array($this->handle, $this->options);

        return curl_exec($this->handle);
    }


    /**
     * @inheritdoc
     */
    public function getInfo($option = null)
    {
        return $option !== null ? curl_getinfo($this->handle, $option) : curl_getinfo($this->handle);
    }


    /**
     * @inheritdoc
     * TODO better variable name for $name and $options variables will be $option, $value. CHeck please PHPDoc for curl_setopt() function.
     */
    public function setOption($name, $option)
    {
        $this->options[$name] = $option;
    }


    /**
     * @inheritdoc
     * TODO Return $this to use chains call
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }


    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * @inheritdoc
     * TODO Good idea to check if $this->handle has opened resource only in this case close the CURL session
     * TODO Return $this to use chains call
     */
    public function close()
    {
        curl_close($this->handle);
    }
}
