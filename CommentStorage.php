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
		$el = $this->dom->createElement('comment');
		foreach($comment as $name=>$value) {
			echo "INFO $name: $value\n";
			
			// TODO: check if valid QName			
			
			if (strpos($name, '_id')>0) {
				// Treat as an attribute
				$el->setAttribute($name, $value);
			} else {
				// Treat as an element
				$dataEl = $this->dom->createElement($name);
				$dataEl->appendChild($this->dom->createTextNode($value));
				$el->appendChild($dataEl);
			}
		}
		
		// Add the comment node to document
		// TODO: Check there's at least one attribute or element
		$this->dom->documentElement->appendChild($el);
		
		return false;
	}
	
	public function getComment($comment_id) {
		$xpath = new DOMXPath($this->dom);
		
		$commentEls = $xpath->query("*/comment[@comment_id='$comment_id']");
		print_r($commentEls);
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