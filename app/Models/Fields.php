<?php
namespace jelaDkp\Models;

use Illuminate\Http\Request;

/**
 * Base class for storing fields for Model classes.
 */
class Fields {
	/**
	 * Array of instances of the Field class.
	 */
	protected $fields;
	
	/**
     * Create a new instance of fields.
     *
     * @return void
     */
    public function __construct() {
		$this->fields = array();
    }
	
	/**
	 * Add field to collection of fields.
	 *
	 * @param	string	$name			Name of the field in database.
	 * @param	string	$validatorStr	Laravel validator string for the field.
	 * @param	string	$postAlias		If set, defines the alias used for the field in POST requests.
	 *
	 * @return	Void
	 */
	public function add($name, $validatorStr, $postAlias = '') {
		$this->fields[] = new Field($name, $validatorStr, $postAlias);
	}
	
	/**
	 * Return the collection of Fields as an array suited for Validator.
	 *
	 * @return	Array	Array in suitable format for the Validator.
	 */
	public function toValidatorArr() {
		//Intiialize the array.
		$retArr = array();
		//Loop through the fields to get them into array.
		foreach ($this->fields as $field) {
			$retArr[$field->getPostAlias()] = $field->getValidatorStr();
		}
		//Return the ready array.
		return $retArr;
	}
	
	/**
	 * Return an array of Fields with values for each field  from request
	 *
	 * @param	Request	$request	The request sent with the data of fields collection.
	 *
	 * @return	Array	Array of fields with their values from request.
	 */
	public function getValuesFromRequest(Request $request) {
		//Intiialize the array.
		$retArr = array();
		//Loop through the fields to get them into array.
		foreach ($this->fields as $field) {
			$retArr[$field->getName()] = $request->get($field->getPostAlias());
		}
		//Return the ready array.
		return $retArr;
	}
}

/**
 * Class that holds the information for single database field.
 */
class Field {
	/**
	 * Name of the database field.
	 */
	private $name;
	/**
	 * Alias that is used for the field in POST requests.
	 */
	private $postAlias;
	/**
	 * Validator suitable string for validating the given array.
	 */
	private $validatorStr;
	
	/**
     * Create a new Field instance.
	 *
	 * @param	string	$name			Name of the database field.
	 * @param	string	$validatorStr	Validator suitable string for validating the given array.
	 * @param	string	$postAlias		If set, defines the alias used for the field in POST requests.
     *
     * @return	Field	New field instance.
	*/
    public function __construct($name, $validatorStr, $postAlias = '') {
		$this->name = $name;
		$this->postAlias = (empty($postAlias) ? $name : $postAlias);
		$this->validatorStr = $validatorStr;
    }
	
	/**
     * Return the name of the database field.
	 *
     * @return	string	Name of the database field.
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
     * Return the alias used in post requests for the field.
	 *
     * @return	string	Post alias of the field.
	 */
	public function getPostAlias() {
		return $this->postAlias;
	}
	
	/**
     * Return the validator suitable string for validating the given array.
	 *
     * @return	string	Validator suitable string for validating the given array.
	 */
	public function getValidatorStr() {
		return $this->validatorStr;
	}
}
