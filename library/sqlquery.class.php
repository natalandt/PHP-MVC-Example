<?php

class SQLQuery {
    protected $_dbHandle;
    protected $_result;
	protected $_query;
	protected $_table;

	protected $_describe = array();

    /** Connects to database **/
    function connect($address, $account, $pwd, $name) 
	{
        $this->_dbHandle = @mysql_connect($address, $account, $pwd);
        if ($this->_dbHandle != 0) 
		{
            if (mysql_select_db($name, $this->_dbHandle)) 
			{
                return 1;
            }
            else 
			{
                return 0;
            }
        }
        else 
		{
            return 0;
        }
    }
 
    /** Disconnects from database **/

    function disconnect() 
	{
        if (@mysql_close($this->_dbHandle) != 0) 
		{
            return 1;
        } 
		else 
		{
            return 0;
        }
    }

    /** Barebones SQL Query Loader **/

	protected function _query($query) 
	{

		global $inflect;

		$this->_result = mysql_query($query, $this->_dbHandle);

		$result = array();
		$table = array();
		$field = array();
		$tempResults = array();

		if(substr_count(strtoupper($query),"SELECT")>0) 
		{
			if (mysql_num_rows($this->_result) > 0) 
			{
				// Get number of fields from query
				$numOfFields = mysql_num_fields($this->_result);
				for ($i = 0; $i < $numOfFields; ++$i) 
				{
					array_push($table,mysql_field_table($this->_result, $i));
					array_push($field,mysql_field_name($this->_result, $i));
				}
				while ($row = mysql_fetch_row($this->_result)) 
				{
					for ($i = 0;$i < $numOfFields; ++$i) 
					{
						$table[$i] = ucfirst($inflect->singularize($table[$i]));
						$tempResults[$table[$i]][$field[$i]] = $row[$i];
					}
					array_push($result,$tempResults);
				}
			}
			mysql_free_result($this->_result);
		}	
		$this->clear();
		return($result);
	}

    /** Describes columns from a Table **/

	protected function _describe() 
	{
		// Load description of table and push column names to array
		if (!$this->_describe) 
		{
			$this->_describe = array();
			$query = 'DESCRIBE ' . $this->_table;
			$this->_result = mysql_query($query, $this->_dbHandle);
			while ($row = mysql_fetch_row($this->_result)) 
			{
				 array_push($this->_describe,$row[0]);
			}

			mysql_free_result($this->_result);
		}
		
		// Create table fields for model; set as null
		foreach ($this->_describe as $field) 
		{
			$this->$field = null;
		}
	}

    /** Delete an Object **/

	function delete() 
	{
		if ($this->id) 
		{
			$query = 'DELETE FROM ' . $this->_table . ' WHERE `id`=\'' . mysql_real_escape_string($this->id) . '\'';		
			$this->_result = mysql_query($query, $this->_dbHandle);
			$this->clear();
			if ($this->_result == 0) 
			{
				return -1;
			}
		} 
		else 
		{
			return -1;
		}
		
	}

    /** Update/Insert Queries **/

	function save() 
	{
		$query = '';
		if (isset($this->id)) 
		{
			$updates = '';
			foreach ($this->_describe as $field) 
			{
				if ($this->$field) 
				{
					$updates .= '`'.$field.'` = \''.mysql_real_escape_string($this->$field).'\',';
				}
			}
			$updates = substr($updates,0,-1);

			$query = 'UPDATE ' . $this->_table . ' SET ' . $updates . ' WHERE `id`=\'' . mysql_real_escape_string($this->id) . '\'';			
		} 
		else 
		{
			$fields = '';
			$values = '';
			foreach ($this->_describe as $field) 
			{
				if ($this->$field) 
				{
					$fields .= '`' . $field . '`,';
					$values .= '\'' . mysql_real_escape_string($this->$field) . '\',';
				}
			}
			$values = substr($values,0,-1);
			$fields = substr($fields,0,-1);

			$query = 'INSERT INTO ' . $this->_table . ' (' . $fields . ') VALUES (' . $values . ')';
		}
		$this->_result = mysql_query($query, $this->_dbHandle);
		$this->clear();
		if ($this->_result == 0) 
		{
			return -1;
        }
	}
 
	/** Clear All Variables **/

	function clear() 
	{
		foreach($this->_describe as $field) 
		{
			$this->$field = null;
		}
	}


    /** Get error string **/

    function getError() 
	{
        return mysql_error($this->_dbHandle);
    }
}