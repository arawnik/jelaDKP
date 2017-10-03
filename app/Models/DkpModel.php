<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;

/**
 * Dkp model class that displays the dkp status by characters.
 */
class DkpModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	DkpModel	New instance of the DkpModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('characters', 'dkp_c');
    }
	
	/**
	 * Select the tables where this model reads the data.
	 *
	 * @return	Void
	 */
	public function selectTables() {
		//Initialize query at parent.
		parent::selectTables();
		
		//Define the partial calculation tables.
		$spentQuery = $this->spentQuery()->toSql();
		$addedQuery =  $this->addedQuery()->toSql();
		$adjustedQuery =  $this->adjustedQuery()->toSql();
		$normalizedQuery =  $this->normalizedQuery()->toSql();
		
		//Combine the parts into one table.
		$this->selectQuery
			->leftJoin(DB::raw('(' . $spentQuery . ') as dkp_spent'), 'dkp_c.char_id', '=', 'dkp_spent.char_id')
			->leftJoin(DB::raw('(' . $addedQuery . ') as dkp_added'), 'dkp_c.char_id', '=', 'dkp_added.char_id')
			->leftJoin(DB::raw('(' . $adjustedQuery . ') as dkp_adjusted'), 'dkp_c.char_id', '=', 'dkp_adjusted.char_id')
			->leftJoin(DB::raw('(' . $normalizedQuery . ') as dkp_normalized'), 'dkp_c.char_id', '=', 'dkp_normalized.char_id');
	}
	
	/**
	 * Select the columns that display the data of this model.
	 *
	 * @return	Void
	 */
	public function selectColumns() {
		$this->selectQuery
			->select(
				'dkp_c.char_id',
				'dkp_spent.spent',
				'dkp_added.added',
				'dkp_adjusted.adjusted',
				'dkp_normalized.normalized',
				DB::raw('(ifnull(dkp_added.added, 0) + ifnull(dkp_adjusted.adjusted, 0)) as earned'),
				DB::raw('(ifnull(dkp_added.added, 0) + ifnull(dkp_adjusted.adjusted, 0) - ifnull(dkp_spent.spent, 0) - ifnull(dkp_normalized.normalized, 0)) as current')
			);
	}
	
	/**
	 * Add the required conditions for select.
	 *
	 * @return	Void
	 */
	public function selectConditions() {
		$this->selectQuery->groupBy('dkp_c.char_id');
	}
	
	/**
	 * The subquery for character's dkp spent.
	 *
	 * @return	DB::table	The table with char id and dkp spent.
	 */
	private function spentQuery() {
		return DB::table('characters')
			->leftJoin('points_used', 'characters.char_id', '=', 'points_used.use_character')
			->select(
				'characters.char_id',
				DB::raw('SUM(ifnull(points_used.use_amount, 0)) as spent')
			)
			->groupBy('characters.char_id');
	}
	
	/**
	 * The subquery for character's dkp added.
	 *
	 * @return	DB::table	The table with char id and dkp added.
	 */
	private function addedQuery() {
		return DB::table('characters')
			->leftJoin('raid_attends', 'characters.char_id', '=', 'raid_attends.attend_character')
			->leftJoin('raids', 'raid_attends.attend_raid', '=', 'raids.raid_id')
			 ->select(
				'characters.char_id',
				DB::raw('SUM(ifnull(raids.raid_value, 0)) as added')
			)
			->groupBy('characters.char_id');
	}
	
	/**
	 * The subquery for character's dkp adjusted.
	 *
	 * @return	DB::table	The table with char id and dkp adjusted.
	 */
	private function adjustedQuery() {
		return DB::table('characters')
			->leftJoin('raid_adjustments', 'characters.char_id', '=', 'raid_adjustments.adjust_character')
			 ->select(
				'characters.char_id',
				DB::raw('SUM(ifnull(raid_adjustments.adjust_value, 0)) as adjusted')
			)
			->groupBy('characters.char_id');
	}
	
	/**
	 * The subquery for character's dkp normalized.
	 *
	 * @return	DB::table	The table with char id and dkp normalized.
	 */
	private function normalizedQuery() {
		return DB::table('characters')
			->leftJoin('normalization_points', 'characters.char_id', '=', 'normalization_points.char_id')
			 ->select(
				'characters.char_id',
				DB::raw('SUM(ifnull(normalization_points.normalization_amount, 0)) as normalized')
			)
			->groupBy('characters.char_id');
	}
}
