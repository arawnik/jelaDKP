<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use jelaDkp\Models\Fields;

/**
 * RaidAdjustmentModel that handles database functions for raids point adjustments.
 */
class RaidAdjustmentModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	RaidAdjustmentModel	New instance of the RaidAdjustmentModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('raid_adjustments', 'raad', 'adjust_raid');
		//Add table's fields.
		$this->addField('adjust_raid', 'required|integer', 'raid_id');
		$this->addField('adjust_character', 'required|integer', 'adjust_character');
		$this->addField('adjust_value', 'required|numeric', 'adjust_value');
		$this->addField('adjust_comment', 'required|max:255', 'adjust_comment');
    }
	
	/**
	 * Template method style read function for data.
	 *
	 * @param	integer	$id	Id of the raid of which adjustments we check.
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
	 * Select the tables where this model reads the data.
	 *
	 * @return	Void
	 */
	public function selectTables() {
		parent::selectTables();
		$this->selectQuery
			->leftJoin('characters as c', $this->tableslug . '.adjust_character', '=', 'c.char_id')
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
			->addSelect('c.char_name')
			->addSelect('c.char_role')	
			->addSelect('c.char_class')
			->addSelect('cl.class_name')
			->addSelect('cl.class_color')
			->addSelect('ro.role_name');
	}
	
	/**
	 * Template method style delete function for data.
	 *
	 * @param	integer	$raidId	Id of the raid related to adjustment.
	 * @param	integer	$charId	Id of the character related to adjustment.
	 *
	 * @return	Boolean	true on successful delete, false otherwise.
	 */
	public function delete($raidId, $charId = -1) {
		try {
			//Attempt delete.
			$query = DB::table($this->tablename)
				->where([
					['adjust_raid', '=', $raidId],
					['adjust_character', '=', $charId],
				])
				->delete();
			
			//And return info of success.
			return true;
		} catch (Exception $e) {
			//The delete query failed.
			return false;
		}
	}
}
