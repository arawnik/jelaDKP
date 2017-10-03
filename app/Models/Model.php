<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Validator;
use jelaDkp\Models\Fields;

/**
 * Base class for each of the other model classes.
 */
class Model {
	/**
	 * The query that will be used for data reading.
	 */
	protected $selectQuery;
	
	/**
	 * Fields of the database table that corresponds to the model class.
	 */
	protected $fields;
	
	/**
	 * Instance of the validator used for validating the given data.
	 */
	private $validator;
	
	/**
	 * table name that will be base for the default functions.
	 */
	public $tablename;
	
	/**
	 * Table slug that will be used to point to the table.
	 */
	public $tableslug;
	
	/**
	 * Table's primary key.
	 */
	public $tablepk;
	
	/**
     * Create a new model instance.
     *
     * @return	Model	New instance of the model.
     */
    public function __construct() {
		$this->fields = new Fields();
    }
	
	/**
	 * Set the table name for the base queries.
	 *
	 * @param	string	$name	Name of the database table.
	 * @param	string	$slug	Short name given for the table for queries. Defaults to 'table'.
	 * @param	string	$pk		Primary key of the table. Defaults to 'id'.
	 *
	 * @return	Void
	 */
	protected function setTable($name, $slug = 'table', $pk = 'id') {
		$this->tablename = $name;
		$this->tableslug = $slug;
		$this->tablepk = $pk;
	}
	
	/**
	 * Add field to collection of fields.
	 *
	 * @param	string	$name			Name of the field.
	 * @param	string	$validatorStr	Laravel validator string for the field.
	 * @param	string	$postAlias		If set, defines the alias used for the field in POST requests.
	 *
	 * @return	Void
	 */
	protected function addField($name, $validatorStr = '', $postAlias = '') {
		$this->fields->add($name, $validatorStr, $postAlias);
	}
	
	/**
	 * Template method style create function for data.
	 *
	 * @param	Request	$request	The request sent for creating new row of the model.
	 *
	 * @return	Mixed	new id on successful create, false otherwise.
	 */
	public function create(Request $request) {
		return $this->attemptValidatedQuery($request, 'tableInsert');
	}
	
	/**
	 * Template method style read function for data.
	 *
	 * @param	integer	$id	Id of the row of the model.
	 * @param	string	$queryFunction		If set, defines different function for actual data query.
	 *
	 * @return	Object	Object with the data.
	 */
	public function read($id, $queryFunction = false) {
		//Initialize query.
		if(($queryFunction !== false) && method_exists($this, $queryFunction))
			$this->$queryFunction();
		else
			$this->readBaseQuery();
		//Select the unit.
		$this->selectSpecificCondition($id);
		//And return
		return $this->selectQuery->get()->first();
	}
	
	/**
	 * Template method style read function for data. Reads all rows.
	 *
	 * @param	string	$queryFunction		If set, defines different function for actual data query.
	 *
	 * @return	Collection	Collection of the data.
	 */
	public function readAll($queryFunction = false) {
		//Initialize query.
		if(($queryFunction !== false) && method_exists ($this, $queryFunction))
			$this->$queryFunction();
		else
			$this->readBaseQuery();
		
		//And return
		return $this->selectQuery->get();
	}
	
	/**
	 * Template method style update function for data.
	 *
	 * @param	integer	$id			Id of the row of the model.
	 * @param	Request	$request	The request sent for editing an existing row of the model.
	 *
	 * @return	Boolean	true on successful edit, false otherwise.
	 */
	public function update($id, $request) {
		return $this->attemptValidatedQuery($request, 'tableUpdate', $id);
	}
	
	/**
	 * Template method style delete function for data.
	 *
	 * @param	integer	$id	Id of the row of the model.
	 *
	 * @return	Boolean	true on successful delete, false otherwise.
	 */
	public function delete($id) {
		try {
			//Attempt delete.
			$query = DB::table($this->tablename)
				->where($this->tablepk, '=', $id)
				->delete();
			
			//And return info of success.
			return true;
		} catch (QueryException $e) {
			//The delete query failed.
			return false;
		}
	}
	
	/**
	 * Get the total count of rows of the model.
	 *
	 * @return	integer	Count of rows of the model.
	 */
	public function count() {
		return DB::table($this->tablename)->count();
	}
	
	/**
	 * Return the base of read as query
	 *
	 * @param	string	$optionalJoinsFunc		If set, defines function with extra joins.
	 * @param	string	$optionalSelectsFunc	If set, defines function with extra selects.
	 *
	 * @return	Query	Finished query.
	 */
	protected function readBaseQuery($optionalJoinsFunc = false, $optionalSelectsFunc = false) {
		//First select the tables.
		$this->selectTables();
		//Join extras when needed.
		if(($optionalJoinsFunc !== false) && method_exists ($this,$optionalJoinsFunc))
			$this->$optionalJoinsFunc();
		//Then select the base values from table.
		$this->selectColumns();
		//Select extras when needed.
		if(($optionalSelectsFunc !== false) && method_exists ($this,$optionalSelectsFunc))
			$this->$optionalSelectsFunc();
		//And add the required select conditions.
		$this->selectConditions();
		
		//And return finished query.
		return $this->selectQuery;
	}
	
	/**
	 * Select the tables where this model reads the data. Override and call parent if normal query should include joins.
	 *
	 * @return void
	 */
	protected function selectTables() {
		$this->selectQuery = DB::table($this->tablename . ' as ' . $this->tableslug);
	}
	
	/**
	 * Select the columns that display the data of this model. Override and call parent if normal query should include more columns.
	 *
	 * @return void
	 */
	protected function selectColumns() {
		$this->selectQuery->select(
			$this->tableslug . '.*'
		);
	}
	
	/**
	 * Add the required conditions for select.
	 *
	 * @return void
	 */
	protected function selectConditions() {}
	
	/**
	 * Add the condition to query where specific instance will be selected based on the id.
	 *
	 * @param	integer	$id	Id of the selected row.
	 *
	 * @return	Void
	 */
	protected function selectSpecificCondition($id) {
		$this->selectQuery->where($this->tableslug . '.' . $this->tablepk, '=', $id);
	}
	
	/**
	 * Attempt to perform validated query to database. will perform $this->$queryFunction($request, $extraParams) for query.
	 *
	 * @param	Request	$request		The request sent for the action.
	 * @param	string	$queryFunction	Function name of the query that we perform.
	 * @param	Mixed	$extraParams	Extra params sent to function. For multiple params use array.
	 *
	 * @return Mixed	Return the query functions return on success, false on fail.
	 */
	protected function attemptValidatedQuery($request, $queryFunction, $extraParams = false) {
		//First validate the request inputs.
		$valid = $this->validateRequest($request);
		if($valid === true) {
			//The inputs are valid.
			try {
				//Attempt the query and return its status.
				return $this->$queryFunction($request, $extraParams);
			} catch (QueryException $e) {
				//The query failed.
				return false;
			}
		} else {
			//Validation failed, query failed.
			return false;
		}
	}
	
	/**
	 * Function that handles the insert into the table.
	 *
	 * @param	Request	$request		The request sent for the action.
	 * @param	Mixed	$extraParams	Extra params sent to function. These are not used for inserts.
	 *
	 * @throws QueryException if the query fails.
	 *
	 * @return	Integer	Return created id on success, throws QueryException on fail.
	 */
	protected function tableInsert($request, $extraParams = false) {
		//Fetch the data.
		$values = $this->fields->getValuesFromRequest($request);
		//Attempt query
		$newId = DB::table($this->tablename)->insertGetId(
			$values,
			$this->tablepk
		);
		//And return the created id.
		return $newId;
	}
	
	/**
	 * Function that handles the update into the table.
	 *
	 * @param	Request	$request		The request sent for the action.
	 * @param	integer	$extraParams	Extra params sent to function. For update this is the id we update.
	 *
	 * @throws QueryException if the query fails.
	 *
	 * @return	Boolean true on success, throws QueryException on fail.
	 */
	protected function tableUpdate($request, $extraParams = -1) {
		//Parse the id.
		$id = intval($extraParams);
		//Fetch the data.
		$values = $this->fields->getValuesFromRequest($request);
		//Perform update.
		$query = DB::table($this->tablename)
			->where($this->tablepk, '=', $id)
			->update($values);
		
		//And return info of success.
		return true;
	}
	
	/**
	 * Validate the given request. If the request fails, redirects back with errors and input.
	 *
	 * @param	Request	$request	The request sent for the action.
	 * @param	Array	$validArr	The array that will be used to validate the request. If not set, will use auto generated array from model fields.
	 *
	 * @return	Mixed	Return redirect back with data if fails, true if success.
	 */
	public function validateRequest(Request $request, $validArr = false) {
		//Check which array will be used for validation.
		$validatorArr = is_array($validArr) ? $validArr : $this->fields->toValidatorArr();
		
		//Validate the request.
		$this->validator = Validator::make($request->all(), $validatorArr);
		
		//If we failed, redirect, else return true.
		if ($this->validator->fails()) return redirect()->back()->withErrors($this->validator)->withInput();
		else return true;
	}
}
