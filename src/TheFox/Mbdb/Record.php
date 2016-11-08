<?php

namespace TheFox\Mbdb;

class Record{
	
	private $domain = '';
	private $path = '';
	private $linkTarget = '';
	private $dataHash = '';
	private $encryptionKey = '';
	private $fileSize = 0;
	private $fileName = '';
	
	public function setDomain($domain){
		$this->domain = $domain;
	}
	
	public function getDomain(){
		return $this->domain;
	}
	
	public function setPath($path){
		$this->path = $path;
	}
	
	public function getPath(){
		return $this->path;
	}
	
	public function setLinkTarget($linkTarget){
		$this->linkTarget = $linkTarget;
	}
	
	public function getLinkTarget(){
		return $this->linkTarget;
	}
	
	public function setDataHash($dataHash){
		$this->dataHash = $dataHash;
	}
	
	public function getDataHash(){
		return $this->dataHash;
	}
	
	public function setEncryptionKey($encryptionKey){
		$this->encryptionKey = $encryptionKey;
	}
	
	public function getEncryptionKey(){
		return $this->encryptionKey;
	}
	
	public function setFileSize($fileSize){
		$this->fileSize = $fileSize;
	}
	
	public function getFileSize(){
		return $this->fileSize;
	}
	
	public function getFileName(){
		if(!$this->fileName){
			$this->fileName = sha1($this->getDomain().'-'.$this->getPath());
		}
		
		return $this->fileName;
	}
	
	public function isFile(){
		return $this->getFileSize() > 0;
	}
	
}
