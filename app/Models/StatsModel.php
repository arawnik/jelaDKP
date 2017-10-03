<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;

/**
 * StatsModel that handles database functions for character stats.
 */
class StatsModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	StatsModel	New instance of the StatsModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('characters', 'stat_c');
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
		$lifetimeQuery = $this->lifetimeQuery()->toSql();
		$lastTenQuery =  $this->lastXQuery(10, 'attendance_last_ten')->toSql();
		
		//Combine the parts into one table.
		$this->selectQuery
			->leftJoin(DB::raw('(' . $lifetimeQuery . ') as stat_lifetime'), $this->tableslug . '.char_id', '=', 'stat_lifetime.char_id')
			->leftJoin(DB::raw('(' . $lastTenQuery . ') as stat_last_ten'), $this->tableslug . '.char_id', '=', 'stat_last_ten.char_id');
	}
	
	/**
	 * Select the columns that display the data of this model.
	 *
	 * @return	Void
	 */
	public function selectColumns() {
		$this->selectQuery
			->select(
				$this->tableslug . '.char_id',
				'stat_lifetime.attendance_lifetime',
				DB::raw('ifnull(stat_last_ten.count, 0) as attendance_last_ten')
			);
	}
	
	/**
	 * Add the required conditions for select.
	 *
	 * @return	Void
	 */
	public function selectConditions() {
		$this->selectQuery->groupBy($this->tableslug . '.char_id');
	}
	
	/**
	 * The subquery for character's lifetime attendance
	 *
	 * @return	DB::table	The table with char id and count of attended raids during lifetime.
	 */
	private function lifetimeQuery() {
		return DB::table('characters')
			->leftJoin('raid_attends', 'characters.char_id', '=', 'raid_attends.attend_character')
			->select(
				'characters.char_id',
				DB::raw('count(raid_attends.attend_raid) as attendance_lifetime')
			)
			->groupBy('characters.char_id');
	}
	
	/**
	 * The subquery for character's attendance to last $amount raids.
	 *
	 * @param	integer	$amount	Amount of raids that are taken into account.
	 *
	 * @return	DB::table	The table with char id and count of attended raids withing last $amount raids.
	 */
	private function lastXQuery($amount) {
		//First fetch list of the last X raids.
		$lastXRaidsQuery = DB::table('raids')
			->select(
				'raids.raid_id'
			)
			->orderBy('raids.raid_date', 'desc')
			->limit($amount)
			->toSql();
		
		//Then combine the query to get the attendance, and return it.
		return DB::table('characters')
			->leftJoin('raid_attends', 'characters.char_id', '=', 'raid_attends.attend_character')
			->join(DB::raw('(' . $lastXRaidsQuery . ') as last_x_raids'), 'raid_attends.attend_raid', '=', 'last_x_raids.raid_id')
			->select(
				'characters.char_id',
				DB::raw('count(raid_attends.attend_raid) as count')
			)
			->groupBy('characters.char_id');
	}
}
