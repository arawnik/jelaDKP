<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use jelaDkp\Models\Fields;

use jelaDkp\Models\NormalizationModel;
use jelaDkp\Models\CharacterModel;

/**
 * CharacterModel that handles database functions for normalization points.
 */
class NormalizationPointModel extends Model {
	private $normalizationModel;
	private $characterModel;
	
	/**
     * Create a new model instance.
     *
     * @return	NormalizationPointModel	New instance of the NormalizationPointModel.
     */
    public function __construct(NormalizationModel $normalization = null, CharacterModel $character = null) {
		parent::__construct();
		//Set the table name.
		$this->setTable('normalization_points', 'nopo', 'normalization_id');
		//Add table's fields.
		$this->addField('normalization_id', 'required|integer', '');
		$this->addField('char_id', 'required|integer', '');
		$this->addField('normalization_amount', 'numeric', '');
		
		//Initialize the models.
		$this->normalizationModel = is_null($normalization) ? new NormalizationModel($this) : $normalization;
		$this->characterModel = is_null($character) ? new CharacterModel() : $character;
    }
	
	/**
	 * Template method style create function for data.
	 *
	 * @param	Request	$request			The request sent for creating new row of the model. HOX! Only present because php requires to be compatible with parent.
	 * @param	integer	$normalizationId	The id of the normalization that the points belong to.
	 * @param	Object	$charWithDkp		Object with character data with DKP details.
	 *
	 * @return	Mixed	new id on successful create, false otherwise.
	 */
	public function create(Request $request, $normalizationId = -1, $charWithDkp = false) {
		if(($charWithDkp === false) || ($normalizationId <= 0))
			return false;
		
		try {
			//First get the amount of normalization.
			$normalizationAmount = $this->getNormalizationAmount($normalizationId, $charWithDkp->current);
			
			//Then perform insert.
			$newId = DB::table($this->tablename)->insert(
				[
					'normalization_id' => $normalizationId,
					'char_id' => $charWithDkp->char_id,
					'normalization_amount' => $normalizationAmount
				]
			);
			//And return the created id.
			return $newId;
		} catch (Exception $e) {
			//The query failed.
			return false;
		}
	}
	
	/**
	 * Template method style read function for data.
	 *
	 * @param	integer	$id	Id of the row of the model.
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
		return $this->selectQuery->get(); //Override because we want all instead of first.
	}
	
	/**
	 * Function that handles the update into the table. OVERRIDE
	 *
	 * @param	Request	$request		The request sent for the action.
	 * @param	integer	$extraParams	Extra params sent to function. Not used on override.
	 *
	 * @throws QueryException if the query fails.
	 *
	 * @return	Boolean true on success, throws QueryException on fail.
	 */
	protected function tableUpdate($request, $extraParams = -1) {
		//Fetch the data.
		$values = $this->fields->getValuesFromRequest($request);
		
		//First set normalization amount of this one to zero.
		$query = DB::table($this->tablename)
			->where([
				['normalization_id', '=', $values['normalization_id']],
				['char_id', '=', $values['char_id']],
			])
			->update(['normalization_amount' => 0]);
		
		//Fetch char data.
		$character = $this->characterModel->readWithDkp($values['char_id']);
		
		//Then get amount of normalization.
		$normalizationAmount = $this->getNormalizationAmount($values['normalization_id'], $character->current);
		
		//Perform update.
		$query = DB::table($this->tablename)
			->where([
				['normalization_id', '=', $values['normalization_id']],
				['char_id', '=', $values['char_id']],
			])
			->update(['normalization_amount' => $normalizationAmount]);
		
		//And return info of success.
		return true;
	}
	
	/**
	 * Create the points for each of the characters in database.
	 *
	 * @param	Request	$request	The request sent ny the user.
	 * @param	integer	$id			Id of the normalization that points link to.
	 *
	 * @return	Void
	 */
	public function calculateForAll($request, $id) {
		//List all of the characters.
		$characters = $this->characterModel->readAllWithDkp();
		//And calculate the decay for each of the characters.
		foreach ($characters as $char) {
			$newPointId = $this->create($request, $id, $char);
		}
	}
	
	/**
	 * Select the tables where this model reads the data.
	 *
	 * @return	Void
	 */
	public function selectTables() {
		parent::selectTables();
		$this->selectQuery
			->leftJoin('characters as c', 'nopo.char_id', '=', 'c.char_id')
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
			->addSelect('cl.class_name')
			->addSelect('cl.class_color')
			->addSelect('c.char_name')
			->addSelect('c.char_role')
			->addSelect('ro.role_name');
	}
	
	/**
	 * Get the amount of points that should be normalized.
	 *
	 * @param	integer	$normalizationId	Id of the normalization.
	 * @param	integer	$charDkp			Amount of DKP that character has.
	 *
	 * @return	integer	Amount of points that should be normalized.
	 */
	private function getNormalizationAmount($normalizationId, $charDkp) {
		//First fetch details of normalization
		$normalization = $this->normalizationModel->read($normalizationId);
		//Then calculate multiplier.
		$multiplier = ($normalization->normalization_percent / 100);
		//Then calculate the amount of normalization.
		$normalizationAmount = ($charDkp * $multiplier);
		//And return it.
		return $normalizationAmount;
	}
}
