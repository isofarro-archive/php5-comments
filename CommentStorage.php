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

	/**
	* Add a comment and return it's comment id
	**/	
	public function addComment($comment) {
		if (empty($comment['comment_id'])) {
			$comment['comment_id'] = $this->getNewCommentId();
		} else {
			// TODO check this comment id doesn't already exist.
		}
		//echo "Index: {$comment['comment_id']}\n";
		
		// Set default values
		$comment = $this->setDefaultValues(
			$comment, array(
				'thumbsUp'   => 0,
				'thumbsDown' => 0,
				'rejected'   => 0
		));
	
		$el = $this->dom->createElement('comment');
		foreach($comment as $name=>$value) {
			//echo "INFO $name: $value\n";
			
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
		
		return $comment['comment_id'];
	}
	
	public function updateComment($comment) {
		$comment_id = $comment['comment_id'];
		$oldComment = $this->getComment($comment_id);
		if (!empty($oldComment)) {
			$newComment = array_merge($oldComment, $comment);
			
			// Delete the old comment
			if ($this->deleteComment($comment_id)) {
				return $this->addComment($newComment);
			} else {
				echo "ERROR: Could not remove the previous comment\n";
			}
		} else {
			echo "ERROR: Cannot find original comment to update\n";
		}
		return false;
	}

	/**
	* Get a single comment by its comment_id
	**/
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
		//echo "INFO: One comment found\n";
		return $this->commentToArray($commentEls->item(0));
	}
	
	/**
	* get multiple comments by a query. The query is an array
	* of field matches, and this method returns all comments
	* that match all the conditions
	**/
	public function getComments($query) {
		// Create XPath selector for this query
		$clause = array();

		foreach($query as $name=>$value) {
			// if the name ends with _id then its an attribute selector
			if (preg_match('/_id$/', $name)) {
				$clause[] = "@{$name}='$value'";
			} else {
				// element selector
				$clause[] = "$name='$value'";;
			}
		}
		
		$selector = "/comments/comment";
		if (!empty($clause)) {
			$selector .= '[' . implode(' and ', $clause) . ']';
		}
		
		//echo "SELECTOR: $selector\n";

		$xpath      = new DOMXPath($this->dom);
		$commentEls = $xpath->query($selector);
		//echo "Number of comments: ", $commentEls->length, "\n";

		if ($commentEls->length == 0) {
			echo "INFO: No comments found\n";
		} elseif ($commentEls->length==1) {
			//echo "INFO: One comment found\n";
			return $this->commentToArray($commentEls->item(0));
		} else {
			echo "INFO: Multiple comments found\n";
			return $this->commentsToArray($commentEls);
		}
		return NULL;
	}
	
	/**
	* Get a single comment by its comment_id
	**/
	public function deleteComment($comment_id) {
		$xpath = new DOMXPath($this->dom);
		
		$commentEls = $xpath->query("/comments/comment[@comment_id='$comment_id']");
		//echo $commentEls->length, " nodes returned\n";

		if ($commentEls->length != 1) {
			if ($commentEls->length ==0 ) {
				echo "ERROR: no comment with comment_id $comment_id found.\n";
				return false;
			} else {
				echo "ERROR: multiple comments with the same ID returned\n";
				return false;
			}
		}
		
		// Remove element
		$commentEl = $commentEls->item(0);
		$commentEl->parentNode->removeChild($commentEl);
		$this->save();

		return true;
	}
	
	/**
	* Return the next comment id to use
	**/
	protected function getNewCommentId() {
		$commentsEl = $this->dom->documentElement;
		
		$index = 0;
		if ($commentsEl->hasAttribute('index')) {
			$index = $commentsEl->getAttribute('index');
		}
		$index++;
		
		$commentsEl->setAttribute('index', $index);
		
		return $index;
	}

	/**
	* Returns a NodeList of comment elements as an array of comments
	**/
	protected function commentsToArray($commentEls) {
		$comments = array();
		
		foreach($commentEls->childNodes as $commentEl) {
			$comments[] = $this->commentToArray($commentEl);
		}
		
		return $comments;
	}	
	
	/**
	* Returns one comment node as a comment array
	**/
	protected function commentToArray($commentEl) {
		$comment = array();
		//echo $commentEl->nodeName;
		
		//Put the attributes in
		foreach($commentEl->attributes as $attr) {
			$comment[$attr->name] = $attr->value;
		}
		
		// Get each element
		foreach($commentEl->childNodes as $child) {
			//echo $child->nodeName, ": ", $child->textContent, "\n";
			$comment[$child->nodeName] = $child->textContent;
		}

		return $comment;	
	}
	
	protected function setDefaultValues($comment, $defaults) {
		foreach($defaults as $name=>$value) {
			if (empty($comment[$name])) {
				$comment[$name] = $value;
			}
		}
		return $comment;
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