<?php

class Controller {
	
	protected $_controller;
	protected $_action;
	protected $_view;


	function __construct($controller, $action) 
	{
		global $inflect;

		$this->_controller = ucfirst($controller);
		$this->_action = $action;
		
		$model = ucfirst($inflect->singularize($controller));
		$this->$model =& new $model;
		$this->_view =& new View($controller,$action);
	}

	function set($name,$value) 
	{
		$this->_view->set($name,$value);
	}

	function __destruct() 
	{
		$this->_view->render();
	}
		
}