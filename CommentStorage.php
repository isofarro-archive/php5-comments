<?php

class SqliteCommentStorage {

	

}

class XmlCommentStorage {
	protected $xmlFile = 'comments.xml';
	protected $dom = NULL;	

	public function __construct() {
		$this->load();
	}
	
	public function __destruct() {
		if (!is_null($this->dom)) {
			$this->save();
		}
	}
	
	public function addComment($comment) {

	}
	
	
	
	protected function load() {
		if (file_exists($this->xmlFile)) {
			$this->dom = new DOMDocument('1.0', 'UTF-8');
			$this->dom->load($this->xmlFile);
		} else {
			$this->initXml();
		}
	}
	
	protected function save() {
		$this->dom->save($this->xmlFile);
	}
	
	protected function initXml() {
		$this->dom = new DOMDocument('1.0', 'UTF-8');
		$el = $this->dom->createElement('comments');
		$this->dom->appendChild($el);
	}
}

?>