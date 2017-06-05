<?php

namespace TheFox\Mbdb;

use Exception;
use RuntimeException;

class Mbdb
{
    const NAME = 'MBDB';
    const VERSION = '0.2.0-dev.2';

    const LOOP_MAX = 10000;

    /**
     * @var string
     */
    private $filePath = '';

    /**
     * @var resource
     */
    private $fileHandle;

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var array
     */
    private $records = [];

    /**
     * Mbdb constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath = '')
    {
        if ($filePath) {
            $this->read($filePath);
        }
    }

    /**
     * @return array
     */
    public function getRecords(): array
    {
        return $this->records;
    }

    /**
     * @param Record $record
     * @return Record
     */
    private function addRecord(Record $record)
    {
        $this->records[] = $record;

        return $record;
    }

    /**
     * @return string
     */
    private function parseStr(): string
    {
        $len = $this->parseInt16();
        $str = '';

        if ($len != 0xffff) {
            $str = substr($this->buffer, 0, $len);
            if ($str !== false) {
                $this->buffer = substr($this->buffer, $len);
                $this->offset += $len;
            }
        }
        return $str;
    }

    /**
     * @return int
     */
    private function parseInt8()
    {
        $i = unpack('C', $this->buffer[0]);
        $this->buffer = substr($this->buffer, 1);
        $this->offset += 1;
        return $i[1];
    }

    /**
     * @return int
     */
    private function parseInt16(): int
    {
        $i = unpack('n', substr($this->buffer, 0, 2));
        $this->buffer = substr($this->buffer, 2);
        $this->offset += 2;
        return $i[1];
    }

    /**
     * @return int
     */
    private function parseInt32(): int
    {
        $i = unpack('N', substr($this->buffer, 0, 4));
        $this->buffer = substr($this->buffer, 4);
        $this->offset += 4;
        return $i[1];
    }

    /**
     * @return int
     */
    private function parseInt64(): int
    {
        list($higher, $lower) = array_values(unpack('N2', substr($this->buffer, 0, 8)));
        $i = ($higher << 32) | $lower;

        $this->buffer = substr($this->buffer, 8);
        $this->offset += 8;

        return $i;
    }

    /**
     * @return bool
     */
    public function bufferCheckRead(): bool
    {
        $bLen = strlen($this->buffer);
        if (feof($this->fileHandle)) {
            return $bLen > 0;
        } else {
            if ($bLen < 0xffff) {
                $this->buffer .= fread($this->fileHandle, 0xffff);
            }

            return true;
        }
    }

    /**
     * @param string $filePath
     * @throws Exception
     */
    public function read(string $filePath)
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            throw new Exception('File not found: ' . $filePath);
        }

        $this->fileHandle = fopen($this->filePath, 'rb');
        if (!$this->fileHandle) {
            return;
        }

        $header = fread($this->fileHandle, 6);
        if ($header != "mbdb\x05\x00") {
            throw new RuntimeException('Bad MBDB signature');
        }
        $this->offset = 6;

        $loops = 0;
        $this->buffer = '';
        while ($this->bufferCheckRead() && $loops < self::LOOP_MAX) {
            $loops++;

            // $offsetStart = $this->offset;
            $domain = $this->parseStr();
            $path = $this->parseStr();
            $this->parseStr();
            $this->parseStr();
            $this->parseStr();
            $this->parseInt16();

            $this->parseInt32();
            $this->parseInt32();
            $this->parseInt32();
            $this->parseInt32();
            $this->parseInt32();
            $this->parseInt32();
            $this->parseInt32();

            $fileSize = $this->parseInt64();

            $this->parseInt8();
            $propertyCount = $this->parseInt8();
            for ($n = $propertyCount; $n > 0; $n--) {
                if (!$this->bufferCheckRead()) {
                    break;
                }
                $this->parseStr();

                if (!$this->bufferCheckRead()) {
                    break;
                }
                $this->parseStr();
            }

            $record = new Record();
            $record->setDomain($domain);
            $record->setPath($path);
            $record->setFileSize($fileSize);
            $this->addRecord($record);
        }

        unset($this->buffer);
        fclose($this->fileHandle);

        if ($loops == self::LOOP_MAX) {
            throw new Exception('Main loop has reached ' . $loops);
        }
    }

}
