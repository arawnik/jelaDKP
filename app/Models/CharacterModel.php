<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use jelaDkp\Models\Fields;

use jelaDkp\Models\DkpModel;
use jelaDkp\Models\StatsModel;

/**
 * CharacterModel that handles database functions for characters.
 */
class CharacterModel extends Model {
	private $dkpModel;
	private $statsModel;
	
	/**
     * Create a new model instance.
     *
     * @return	CharacterModel	New instance of the CharacterModel.
     */
    public function __construct(DkpModel $dkp = null, StatsModel $stats = null) {
		parent::__construct();
		//Set the table name.
		$this->setTable('characters', 'c', 'char_id');
		//Add table's fields.
		$this->addField('char_name', 'required|max:255', 'name');
		$this->addField('char_class', 'required|integer', 'class');
		$this->addField('char_role', 'required|integer', 'role');
		
		//Initialize the models.
		$this->dkpModel = is_null($dkp) ? new DkpModel() : $dkp;
		$this->statsModel = is_null($stats) ? new StatsModel() : $stats;
    }
	
	/**
	 * Template method style read function for data. Reads all rows.
	 * In addition to the typical data, reads the characters dkp stats and combines the data.
	 *
	 * @return	Collection	Collection of the combined character and dkp data.
	 */
	public function readAllWithDkp() {
		//Return readAll query with our custom DKP query.
		return $this->readAll('readWithDkpQuery');
	}
	
	/**
	 * Template method style read function for data. Reads one row.
	 * In addition to the typical data, reads the characters dkp stats and combines the data.
	 *
	 * @param	integer	$id	Id of the character.
	 *
	 * @return	Object	Object with character and dkp data.
	 */
	public function readWithDkp($id) {
		//Return read query with our custom DKP query.
		return $this->read($id, 'readWithDkpQuery');
	}
	
	/**
	 * Template method style read function for data. Reads all rows.
	 * In addition to the typical data, reads the attendance stats and combines the data.
	 *
	 * @return	Collection	Collection of the combined character and stats data.
	 */
	public function readAllWithStats() {
		//Return readAll query with our custom Stats query.
		return $this->readAll('readWithStatsQuery');
	}
	
	/**
	 * Template method style read function for data. Reads one row.
	 * In addition to the typical data, reads the characters stats and combines the data.
	 *
	 * @param	integer	$id	Id of the character.
	 *
	 * @return	Object	Object with character and stats data.
	 */
	public function readWithStats($id) {
		//Return read query with our custom DKP query.
		return $this->read($id, 'readWithStatsQuery');
	}
	
	/**
	 * Select the tables where this model reads the data.
	 *
	 * @return void
	 */
	public function selectTables() {
		parent::selectTables();
		$this->selectQuery
			->leftJoin('classes as cl', 'c.char_class', '=', 'cl.class_id')
            ->leftJoin('roles as ro', 'c.char_role', '=', 'ro.role_id');
	}
	
	/**
	 * Select the columns that display the data of this model.
	 *
	 * @return void
	 */
	public function selectColumns() {
		parent::selectColumns();
		$this->selectQuery
			->addSelect('cl.class_name')
			->addSelect('cl.class_color')
			->addSelect('c.char_role')
			->addSelect('ro.role_name');
	}
	
	/**
	 * Add the required conditions for select.
	 *
	 * @return void
	 */
	public function selectConditions() {
		$this->selectQuery->groupBy('c.char_id');
	}
	
	/**
	 * Template method style read function for data. Reads all rows.
	 * In addition to the typical data, reads the characters dkp stats and combines the data.
	 *
	 * @return	Collection	Collection of the combined character and dkp data.
	 */
	protected function readWithDkpQuery() {
		return $this->readBaseQuery('joinDkpTables', 'addDkpColumns');
	}
	
	/**
	 * Template method style read function for data. Reads all rows.
	 * In addition to the typical data, reads the characters statistics and combines the data.
	 *
	 * @return	Collection	Collection of the combined character and dkp data.
	 */
	public function readWithStatsQuery() {
		return $this->readBaseQuery('joinStatTables', 'addStatColumns');
	}
	
	/**
	 * Join the dkp data with the original character table data.
	 *
	 * @return void
	 */
	protected function joinDkpTables() {
		$dkpQuery = $this->dkpModel->readBaseQuery()->toSql();
		
		$this->selectQuery
			->leftJoin(DB::raw('(' . $dkpQuery . ') as dkp_stats'), 'c.char_id', '=', 'dkp_stats.char_id');
	}
	
	/**
	 * Join the stats data with the original character table data.
	 *
	 * @return void
	 */
	protected function joinStatTables() {
		$statsQuery = $this->statsModel->readBaseQuery()->toSql();
		
		$this->selectQuery
			->leftJoin(DB::raw('(' . $statsQuery . ') as raid_stats'), 'c.char_id', '=', 'raid_stats.char_id');
	}
	
	/**
	 * Add dkp data columns to the original select query columns.
	 *
	 * @return void
	 */
	protected function addDkpColumns() {
		$this->selectQuery
			//Add the flat data from tables.
			->addSelect('dkp_stats.spent')
			->addSelect('dkp_stats.added')
			->addSelect('dkp_stats.adjusted')
			->addSelect('dkp_stats.normalized')
			//Add the calculated rows of data.
			->addSelect(DB::raw('(ifnull(dkp_stats.added, 0) + ifnull(dkp_stats.adjusted, 0)) as earned'))
			->addSelect(DB::raw('(ifnull(dkp_stats.added, 0) + ifnull(dkp_stats.adjusted, 0) - ifnull(dkp_stats.spent, 0) - ifnull(dkp_stats.normalized, 0)) as current'));
	}
	
	/**
	 * Add dkp data columns to the original select query columns.
	 *
	 * @return void
	 */
	protected function addStatColumns() {
		$this->selectQuery
			//Add the flat data from tables.
			->addSelect('raid_stats.attendance_lifetime')
			->addSelect('raid_stats.attendance_last_ten');
	}
}
