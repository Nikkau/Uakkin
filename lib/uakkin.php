<?php

class Uakkin implements arrayaccess,iterator {
	private $controllers = array();
	private $fallback = null;

	//ArrayAccess methods
	public function offsetSet($offset, $value) {
		$this->controllers[$offset] = $value;
	}
	public function offsetExists($offset) {
		return isset($this->controllers[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->controllers[$offset]);
	}
	public function offsetGet($offset) {
		return $this->controllers[$offset] ?: null;
	}
	
	//Iterator methods
	function rewind() {
		return reset($this->controllers);
	}
	function current() {
		return current($this->controllers);
	}
	function key() {
		return key($this->controllers);
	}
	function next() {
		return next($this->controllers);
	}
	function valid() {
		return key($this->controllers) !== null;
	}
	
	//Setter
	public function __set($name, $value) {
		if ($name == 'fallback')
			$this->fallback = $value;
	}
	
	//Destructor
	public function __destruct() {
		self::dispatch($this, $this->fallback);
	}
	
	//Custom methods
	public static function dispatch($controllers, $fallback = null) {
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$x = $fallback ?: function () { header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); };
		$matches = array();
		foreach ($controllers as $i => $v) {
			if (preg_match($i, $path, $matches)) {
				$x = $v;
				break;
			}
		}
		$x($matches);
	}
}

?>