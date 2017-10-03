<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use jelaDkp\Models\Fields;
use jelaDkp\Models\NormalizationPointModel;

/**
 * NormalizationModel that handles database functions for normalizations.
 */
class NormalizationModel extends Model {
	private $normalizationPointModel;
	
	/**
     * Create a new model instance.
     *
     * @return	NormalizationModel	New instance of the NormalizationModel.
     */
    public function __construct(NormalizationPointModel $normalizationPoints = null) {
		parent::__construct();
		//Set the table name.
		$this->setTable('normalization', 'nor', 'normalization_id');
		//Add table's fields.
		$this->addField('normalization_adder', 'required|integer', 'user');
		$this->addField('normalization_percent', 'required|integer', 'percent');
		$this->addField('normalization_comment', 'required|max:255', 'comment');
		
		//Initialize the models.
		$this->normalizationPointModel = is_null($normalizationPoints) ? new NormalizationPointModel($this) : $normalizationPoints;
    }
	
	/**
	 * Template method style create function for data. Also adds points to created normalization for each character in database.
	 *
	 * @param	Request	$request	The request sent for creating new row of the model.
	 *
	 * @return	Mixed	new id on successful create, false otherwise.
	 */
	public function create(Request $request) {
		//First create the row as usual.
		$newId = parent::create($request);
		
		//And calculate points for all characters.
		$this->normalizationPointModel->calculateForAll($request, $newId);
	}
	
	/**
	 * Template method style read function for data.
	 *
	 * @return	Object	Object with the data.
	 */
	public function readLatest() {
		//Initialize query.
		$this->readBaseQuery();
		//Select the unit.
		$this->selectLatestCondition();
		//Get the normalization.
		$normalization = $this->selectQuery->get()->first();
		if(!$normalization) {
			//Normalization wasnt found. Return false.
			return false;
		}
		
		//Fetch points.
		$normalization->points = $this->normalizationPointModel->read($normalization->normalization_id);
		
		//And teturn
		return $normalization;
	}
	
	/**
	 * Select the columns that display the data of this model.
	 *
	 * @return	Void
	 */
	public function selectColumns() {
		parent::selectColumns();
		$this->selectQuery
			->addSelect(DB::raw('DATE(normalization_date) as formed_normalization_date'));
	}
	
	/**
	 * Add the condition to query where specific instance will be selected based on the id.
	 *
	 * @return	Void
	 */
	protected function selectLatestCondition() {
		$this->selectQuery->where($this->tableslug . '.normalization_date', '=', DB::table('normalization')->max('normalization_date'));
	}
}
