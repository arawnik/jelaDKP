<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use jelaDkp\Models\Fields;

/**
 *  PointsUsedModel that handles database functions for points that characters use in raids.
 */
class PointsUsedModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	PointsUsedModel	New instance of the PointsUsedModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('points_used', 'pous', 'use_id');
		//Add table's fields.
		$this->addField('use_raid', 'required|integer', 'raid_id');
		$this->addField('use_character', 'required|integer', 'character');
		$this->addField('use_amount', 'required|numeric', 'use_amount');
		$this->addField('use_desc', 'required|max:255', 'use_desc');
    }
	
	/**
	 * Function for reading one raid's points used.
	 *
	 * @param	integer	$raidId	Id of the raid.
	 *
	 * @return	Collection	Collection of the data.
	 */
	public function readRaid($raidId) {
		$raidQuery = $this->readBaseQuery()
			->where($this->tableslug . '.use_raid', '=', $raidId);
			
		return $raidQuery->get();
	}
	
	/**
	 * Template method style read function for data. Reads the points used by character
	 *
	 * @param	integer	$id	Id of the character of which points used we check.
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
	 * Add the condition to query where we search by specific character.
	 *
	 * @param	integer	$id	Id of the character.
	 *
	 * @return	Void
	 */
	protected function selectCharCondition($id) {
		$this->selectQuery->where($this->tableslug . '.use_character', '=', $id);
	}
	
	/**
	 * Select the tables where this model reads the data.
	 *
	 * @return	Void
	 */
	public function selectTables() {
		parent::selectTables();
		$this->selectQuery
			->leftJoin('characters as c', $this->tableslug . '.use_character', '=', 'c.char_id')
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
}
