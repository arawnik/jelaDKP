<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use jelaDkp\Models\Fields;

/**
 * RaidAttendanceModel that handles database functions for raid attendance.
 */
class RaidAttendanceModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	RaidAttendanceModel	New instance of the RaidAttendanceModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('raid_attends', 'raat', 'attend_raid');
		//Add table's fields.
		$this->addField('attend_raid', 'required|integer', '');
		$this->addField('attend_character', 'required|integer', '');
    }
	
	/**
	 * Template method style create function for data.
	 *
	 * @param	Request	$request	The request sent for creating new row of the model. HOX! Only present because php requires to be compatible with parent.
	 * @param	integer	$raidId		The id of the raid that attendance is linked to.
	 * @param	Object	$charId		The id of the character that attendance is linked to.
	 *
	 * @return	Mixed	new id on successful create, false otherwise.
	 */
	public function create(Request $request, $raidId = -1, $charId = -1) {
		//Make sure we got the details we use.
		if(($raidId <= 0) || ($charId <= 0))
			return false;
		
		try {
			//Fetch the data.
			$values = $this->fields->getValuesFromRequest($request);
			//Then perform insert.
			$newId = DB::table($this->tablename)->insert(
				[
					'attend_raid' => $raidId,
					'attend_character' => $charId
				]
			);
			//And return the created id.
			return $newId;
		} catch (QueryException $e) {
			//The query failed.
			return false;
		}
	}
	
	/**
	 * Template method style read function for data.
	 *
	 * @param	integer	$id	Id of the raid of which attendance we check.
	 * @param	string	$queryFunction		If set, defines different function for actual data query.
	 *
	 * @return	Collection	Collection of the data.
	 */
	public function read($id, $queryFunction = false) {
		//Initialize query.
		$this->readBaseQuery();
		//Select the unit.
		$this->selectSpecificCondition($id);
		//And teturn
		return $this->selectQuery->get();
	}
	
	/**
	 * Template method style read function for data. Reads the attendance by character
	 *
	 * @param	integer	$id	Id of the character of which attendance we check.
	 *
	 * @return	Collection	Collection of the data.
	 */
	public function readChar($id) {
		//Initialize query.
		$this->readBaseQuery();
		//Select the unit.
		$this->selectCharCondition($id);
		//And teturn
		return $this->selectQuery->get();
	}
	
	/**
	 * Template method style update function for data.
	 *
	 * @param	integer	$id			Id of the raid of which attendance we update.
	 * @param	Request	$request	The request sent for editing an existing row of the model.
	 *
	 * @return	Boolean	true on successful edit, false otherwise.
	 */
	public function update($id, $request) {
		try {
			//Fetch the data.
			$values = $this->fields->getValuesFromRequest($request);
			
			//Start transaction to give chance to rollback if something goes wrong.
			DB::beginTransaction();
			
			//First delete all of the existing attends for specified raid.
			$this->delete($id);
			
			//After that add the new ones.
			$selectedChars = $request->get('selected_chars');
			if(!empty($selectedChars)) {
				foreach ($selectedChars as $charId) {
					$this->create($request, $id, $charId);
				}
			}
			
			//If everything went right, commit transaction.
			DB::commit();
			
			//And return info of success.
			return true;
		} catch (Exception $e) {
			//The query failed, rollback.
			DB::rollBack();
			//And return false to tell it failed.
			return false;
		}
	}
	
	/**
	 * Add the condition to query where we search by specific character.
	 *
	 * @param	integer	$id	Id of the character.
	 *
	 * @return	Void
	 */
	protected function selectCharCondition($id) {
		$this->selectQuery->where($this->tableslug . '.attend_character', '=', $id);
	}
	
	/**
	 * Select the tables where this model reads the data.
	 *
	 * @return	Void
	 */
	public function selectTables() {
		parent::selectTables();
		$this->selectQuery
			->leftJoin('raids as r', $this->tableslug . '.attend_raid', '=', 'r.raid_id')
			->leftJoin('characters as c', $this->tableslug . '.attend_character', '=', 'c.char_id')
			->leftJoin('classes as cl', 'c.char_class', '=', 'cl.class_id')
            ->leftJoin('roles as ro', 'c.char_role', '=', 'ro.role_id');
	}
	
	/**
	 * Select the columns that display the data of this model.
	 *
	 * @return	Void
	 */
	public function selectColumns() {
		parent::selectColumns();
		$this->selectQuery
			//Raid columns.
			->addSelect('r.raid_value')
			->addSelect('r.raid_comment')
			->addSelect('r.raid_date')
			->addSelect('r.raid_added')
			->addSelect(DB::raw('DATE(raid_date) as formed_raid_date'))
			//Char columns.
			->addSelect('c.char_name')
			->addSelect('c.char_role')	
			->addSelect('c.char_class')
			->addSelect('cl.class_name')
			->addSelect('cl.class_color')
			->addSelect('ro.role_name');
	}
}
