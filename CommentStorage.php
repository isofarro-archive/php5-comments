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
		//if (!is_null($this->dom)) {
		//	$this->save();
		//}
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
		$this->save();
		
		return false;
	}
	
	public function getComment($comment_id) {
		$xpath = new DOMXPath($this->dom);
		
		$commentEls = $xpath->query("/comments/comment[@comment_id='$comment_id']");
		//echo $commentEls->length, " nodes returned\n";

		if ($commentEls->length != 1) {
			if ($commentEls->length ==0 ) {
				echo "ERROR: no comment with $comment_id found.\n";
				return NULL;
			} else {
				echo "ERROR: multiple comments with the same ID returned\n";
			}
		}
		
		// Return just the first element
		$commentEl =  $commentEls->item(0);
		//echo $commentEl->textContent;
		
		$comment = array();
		
		//Put the attributes in
		foreach($commentEl->attributes as $attr) {
			$comment[$attr->name] = $attr->value;
		}
		
		// Get each element
		foreach($commentEl->childNodes as $child) {
			//echo $child->nodeName, ": ", $child->textContent, "\n";
			$comment[$child->nodeName] = $child->textContent;
		}


		print_r($comment);
		return $comment;
	}
	
	public function getComments($query) {
		// Create XPath selector for this query
		$attrSel = array();
		$elSel   = array();

		foreach($query as $name=>$value) {
			// if the name ends with _id then its an attribute selector
			if (preg_match('/_id$/', $name)) {
				$attrSel[] = "@{$name}='$value'";
			} else {
				// element selector
				$elSel[] = "/$name = '$value'";
			}
		}
		
		$selector = "/comments/comment";
		if (!empty($attrSel)) {
			$selector .= '[' . implode(' ', $attrSel) . ']';
		}
		echo "SELECTOR: $selector\n";

	}
	
	protected function load() {
		if (file_exists($this->xmlFile)) {
			$this->dom = new DOMDocument('1.0', 'UTF-8');
			if ($this->dom->load($this->xmlFile)) {
				//echo "XML doc read in successfully\n";
				//echo "load DOM:", $this->dom->saveXML();
			} else {
				echo "ERROR reading in xml document\n";
			}
			
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