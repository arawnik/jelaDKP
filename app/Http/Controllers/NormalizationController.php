<?php

namespace jelaDkp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use Validator;

use jelaDkp\Models\NormalizationModel;
use jelaDkp\Models\NormalizationPointModel;

/**
 * NormalizationController is the Controller that handles all of the normalization related requests.
 *
 * @author Jere Junttila <junttila.jere@gmail.com>
 * @license GPL
 */
class NormalizationController extends Controller {
	private $normalizationModel;
	private $normalizationPointModel;
	
	/**
     * Create a new controller instance. Set default values to $data.
     *
     * @return void
     */
    public function __construct() {
		parent::__construct();
		
		//Initialize the models
		$this->normalizationModel = new NormalizationModel();
		$this->normalizationPointModel = new NormalizationPointModel();
    }
	
   /**
     * Redirect to normalization management.
     *
     * @return	Redirect	Redirect to normalization management.
     */
    public function index() {
		//No functionality done, redirect to normalization management.
		return redirect()->route('normalization_management');
    }
	
	/**
	 * Shows list of normalizations that user can manage. Also has option to create new normalizations.
	 *
	 * @return	Response 	Returns view with the specified options.
	 */
	public function normalizationManagement() {
		//Fix title.
		$this->setTitlePage(trans('common.normalization_management'));
		
		//Fetch user and normalization data.
		$this->data['id'] = Auth::id();
		$this->data['name'] = Auth::user()->name;
		$this->data['normalizations'] = $this->normalizationModel->readAll();

		//And return view with the data.
		return view('normalization.manage.normalization_management', $this->data);
	}
	
	/**
	 * Fetches the latest normalization information and displays interface to recalculate characters.
	 *
	 * @return	Response 	Returns view with the specified options.
	 */
	public function modifyLatestNormalization(Request $request) {
		//Fix title.
		$this->setTitlePage(trans('management.modify_latest_normalization'));
		
		//Read latest normalization data.
		$this->data['normalization'] = $this->normalizationModel->readLatest();
		
		//If there is no data, we go back with error.
		if(!$this->data['normalization']) {
			$request->session()->flash('alert-danger', trans('management.no_normalization'));
			return redirect()->back();
		}
		
		return view('normalization.manage.modify_latest_normalization', $this->data);
	}
	
	/**
	 * Creates normalization instance and creates given percent normalization points to every character in table.
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function createNormalization(Request $request) {
		//Attempt the create.
		$success = $this->normalizationModel->create($request);
		//Display the status of create.
		$this->setStatusFlash($request, $success, 'management.added_normalization');
		
		//And redirect back.
		return redirect()->back();
	}
	
	/**
	 * Updates the points for specific character in normalization.
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect 	Redirects back with success status flash.
	 */
	public function updateNormalizationPoints(Request $request) {
		//Attempt the update.
		$success = $this->normalizationPointModel->update(-1, $request);
		//Display the status of update.
		$this->setStatusFlash($request, $success, 'management.recalculated_normalization');
		
		//And redirect back.
		return redirect()->back()->withInput();
	}
	
	/**
	 * Deletes the normalization specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function deleteNormalization(Request $request) {
		//Parse the id.
		$normalizationId = $this->checkId($request->get('normalization_id'));
		//Attempt to delete normalization.
		$success = $this->normalizationModel->delete($normalizationId);
		//Display the status of delete.
		$this->setStatusFlash($request, $success, 'management.deleted_normalization');
		
		//And redirect back.
		return redirect()->back();
	}
}
