<?php
class View {
	
	protected $variables = array();
	protected $_controller;
	protected $_action;
	
	function __construct($controller,$action) {
		$this->_controller = $controller;
		$this->_action = $action;
	}

	/** Set Variables **/
	function set($name,$value) {
		$this->variables[$name] = $value;
	}

	/** Display View **/
    function render() {
		
		$html = new HTML;
		extract($this->variables);
			
		include (ROOT . '/application/views/header.php');
		
		// Load view according to current controller and action
		if (file_exists(ROOT . '/application/views/' . $this->_controller . '/' . $this->_action . '.php')) {
			include (ROOT . '/application/views/' . $this->_controller . '/' . $this->_action . '.php');		 
		}
			
		include (ROOT . '/application/views/footer.php');
    }

}