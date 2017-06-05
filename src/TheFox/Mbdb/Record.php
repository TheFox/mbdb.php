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
    public function setDomain(string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $linkTarget
     */
    public function setLinkTarget(string $linkTarget)
    {
        $this->linkTarget = $linkTarget;
    }

    /**
     * @return string
     */
    public function getLinkTarget(): string
    {
        return $this->linkTarget;
    }

    /**
     * @param string $dataHash
     */
    public function setDataHash(string $dataHash)
    {
        $this->dataHash = $dataHash;
    }

    /**
     * @return string
     */
    public function getDataHash(): string
    {
        return $this->dataHash;
    }

    /**
     * @param string $encryptionKey
     */
    public function setEncryptionKey(string $encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }

    /**
     * @param int $fileSize
     */
    public function setFileSize(int $fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        if (!$this->fileName) {
            $this->fileName = sha1($this->getDomain() . '-' . $this->getPath());
        }

        return $this->fileName;
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return $this->getFileSize() > 0;
    }
}
