<?php
namespace Virgil\Sdk\Client\Http;


class CurlRequest implements RequestInterface
{
    private $handle;
    private $options;


    /**
     * CurlRequest constructor.
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->handle = $url !== null ? curl_init($url) : curl_init();
    }


    /**
     * Execute curl request.
     *
     * @return mixed
     */
    public function execute()
    {
        curl_setopt_array($this->handle, $this->options);

        return curl_exec($this->handle);
    }


    /**
     * Get info from request.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getInfo($name = null)
    {
        return $name !== null ? curl_getinfo($this->handle, $name) : curl_getinfo($this->handle);
    }


    /**
     * Set request option.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }


    /**
     * Set request options.
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }


    /**
     * Get all request options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Close a curl session.
     *
     * @return void
     */
    public function close()
    {
        curl_close($this->handle);
    }
}
