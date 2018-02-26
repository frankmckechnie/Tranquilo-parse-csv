<?php

namespace Tranquilo\Exceptions;

use \Exception as Exception;

class CsvException extends Exception
{

	public function __construct($message){
		parent::__construct("There was an issue with the csv: ".$message);
	}

}