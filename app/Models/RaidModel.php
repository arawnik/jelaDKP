<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use jelaDkp\Models\Fields;

/**
 * RaidModel that handles database functions for raids.
 */
class RaidModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	RaidModel	New instance of the RaidModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('raids', 'r', 'raid_id');
		//Add table's fields.
		$this->addField('raid_value', 'required|numeric', 'value');
		$this->addField('raid_comment', 'required|max:255', 'comment');
		$this->addField('raid_date', 'required|date_format:Y-m-d', 'date');
    }
	
	/**
	 * Select the tables where this model reads the data.
	 *
	 * @return	Void
	 */
	public function selectTables() {
		parent::selectTables();
		$this->selectQuery
			->leftJoin('raid_attends as ra', 'r.raid_id', '=', 'ra.attend_raid');
	}
	
	/**
	 * Select the columns that display the data of this model.
	 *
	 * @return	Void
	 */
	public function selectColumns() {
		parent::selectColumns();
		$this->selectQuery
			->addSelect(DB::raw('COUNT(ra.attend_character) as raid_attendees_count'))
			->addSelect(DB::raw('DATE(raid_date) as formed_raid_date'));
	}
	
	/**
	 * Add the required conditions for select.
	 *
	 * @return	Void
	 */
	public function selectConditions() {
		$this->selectQuery->groupBy('r.raid_id');
		$this->selectQuery->orderBy('r.raid_date', 'desc');
	}
}
