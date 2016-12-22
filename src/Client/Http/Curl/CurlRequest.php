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
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
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
     */
    public function close()
    {
        curl_close($this->handle);

        return $this;
    }
}
