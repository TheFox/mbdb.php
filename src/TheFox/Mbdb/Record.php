<?php

namespace TheFox\Mbdb;

class Record
{
    /**
     * @var string
     */
    private $domain = '';

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $linkTarget = '';

    /**
     * @var string
     */
    private $dataHash = '';

    /**
     * @var string
     */
    private $encryptionKey = '';

    /**
     * @var int
     */
    private $fileSize = 0;

    /**
     * @var string
     */
    private $fileName = '';

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $linkTarget
     */
    public function setLinkTarget($linkTarget)
    {
        $this->linkTarget = $linkTarget;
    }

    /**
     * @return string
     */
    public function getLinkTarget()
    {
        return $this->linkTarget;
    }

    /**
     * @param $dataHash
     */
    public function setDataHash($dataHash)
    {
        $this->dataHash = $dataHash;
    }

    /**
     * @return string
     */
    public function getDataHash()
    {
        return $this->dataHash;
    }

    /**
     * @param $encryptionKey
     */
    public function setEncryptionKey($encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * @return string
     */
    public function getEncryptionKey()
    {
        return $this->encryptionKey;
    }

    /**
     * @param int $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        if (!$this->fileName) {
            $this->fileName = sha1($this->getDomain() . '-' . $this->getPath());
        }

        return $this->fileName;
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return $this->getFileSize() > 0;
    }
}
