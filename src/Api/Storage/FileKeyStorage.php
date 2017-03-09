<?php
namespace Virgil\Sdk\Api\Storage;


use Virgil\Sdk\Contracts\KeyStorageInterface;

use Virgil\Sdk\Exceptions\VirgilException;

/**
 * Class describes a file storage facility for cryptographic keys.
 */
class FileKeyStorage implements KeyStorageInterface
{
    /** @var string */
    private $keysPath;


    /**
     * Class constructor.
     *
     * @param $keysPath
     *
     * @throws VirgilException
     */
    public function __construct($keysPath)
    {
        if (!file_exists($keysPath)) {
            mkdir($keysPath, 0755, true);
        }

        if (!is_dir($keysPath)) {
            throw new VirgilException('Provided keys storage path should be directory');
        }

        if (!is_readable($keysPath)) {
            throw new VirgilException('Provided keys storage path should be readable');
        }

        if (!is_writeable($keysPath)) {
            throw new VirgilException('Provided keys storage path should be writable');
        }

        $this->keysPath = $keysPath;
    }


    /**
     * @inheritdoc
     */
    public function store(KeyEntry $keyEntry)
    {
        $file = $this->buildFilePath($keyEntry->getName());

        $wroteBytes = file_put_contents($file, $keyEntry->getValue());

        if ($wroteBytes === false) {
            throw new VirgilException('Could not write key.');
        }

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function load($keyName)
    {
        $file = $this->buildFilePath($keyName);

        $keyValue = file_get_contents($file);

        if ($keyValue === false) {
            throw new VirgilException('Could not read key.');
        }

        return new KeyEntry($keyName, $keyValue);
    }


    /**
     * @inheritdoc
     */
    public function exists($keyName)
    {
        $file = $this->buildFilePath($keyName);

        return file_exists($file);
    }


    /**
     * @inheritdoc
     */
    public function delete($keyName)
    {
        $file = $this->buildFilePath($keyName);

        $isKeyDeleted = unlink($file);

        if (!$isKeyDeleted) {
            throw new VirgilException('Could not delete key.');
        }

        return $this;
    }


    /**
     * Builds file path to virgil key by name.
     *
     * @param $keyName
     *
     * @return string
     */
    protected function buildFilePath($keyName)
    {
        return rtrim($this->keysPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . md5($keyName);
    }
}
