<?php

namespace jelaDkp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

use jelaDkp\Models\RaidModel;
use jelaDkp\Models\CharacterModel;
use jelaDkp\Models\PointsUsedModel;
use jelaDkp\Models\RaidAdjustmentModel;
use jelaDkp\Models\RaidAttendanceModel;

/**
 * RaidController is the Controller that handles all of the raid related requests.
 *
 * @author Jere Junttila <junttila.jere@gmail.com>
 * @license GPL
 */
class RaidController extends Controller {
	private $raidModel;
	private $characterModel;
	private $pointsUsedModel;
	private $raidAdjustmentModel;
	private $raidAttendanceModel;
	
	/**
     * Create a new controller instance. Do parent constructs and initialize models.
     *
     * @return void
     */
    public function __construct() {
		parent::__construct();
		
		//Initialize the models
		$this->raidModel = new RaidModel();
		$this->characterModel = new CharacterModel();
		$this->pointsUsedModel = new PointsUsedModel();
		$this->raidAdjustmentModel = new RaidAdjustmentModel();
		$this->raidAttendanceModel = new RaidAttendanceModel();
    }
	
    /**
     * Show the raid listing.
     *
     * @return	Response	Returns view with the specified options.
     */
    public function index() {
		//Fix title.
		$this->setTitlePage(trans('common.raids'));
		//Fetch raid data
		$this->data['raids'] = $this->raidModel->readAll();
		
		//And return view with the data.
		return view('raid.raids', $this->data);
    }
	
	/**
     * Show information of specific raid.
	 *
	 * @param	integer	$id	Id of the raid that will be displayed.
	 * @return	Response	Returns view with the specified options.
     */
    public function raid($id) {
		//Fix title.
		$this->setTitlePage(trans('common.raid'));
		
		//Parse the id.
		$this->data['raid_id'] = $this->checkId($id);
		//Read raid data.
		$this->data['raid_data'] = $this->raidModel->read($this->data['raid_id']);
		//If raid doesnt exist, show 404.
		$this->checkRowExists($this->data['raid_data']);
		
		//Fetch the data related to the raid.
		$this->data['raid_attends'] = $this->raidAttendanceModel->read($this->data['raid_id']);
		$this->data['raid_items'] = $this->pointsUsedModel->readRaid($this->data['raid_id']);
		$this->data['raid_adjustments'] = $this->raidAdjustmentModel->read($this->data['raid_id']);
		
		//And return view with the data.
        return view('raid.raid', $this->data);
    }
	
	/**
	 * Shows list of raids that user can manage. Also has option to create new raid.
	 *
	 * @return	Response 	Returns view with the specified options.
	 */
	public function raidManagement() {
		//Fix title.
		$this->setTitlePage(trans('common.raid_management'));
		
		//Read raid data.
		$this->data['raids'] = $this->raidModel->readAll();

		//And return view with the data.
		return view('raid.manage.raid_management', $this->data);
	}
	
	/**
	 * Shows the information of specified raid in a way it can be modified.  Also has option to create new items and adjustments and attendants to the raid.
	 *
	 * @param	integer	$id		Specifies the ID of the modified raid.
	 *
	 * @return	Response 	Returns view with the specified options.
	 */
    public function modifyRaid($id) {
		//Fix title.
		$this->setTitlePage(trans('management.modify_raid'));
		
		//Parse the id.
		$this->data['raid_id'] = $this->checkId($id);
		//Read raid data.
		$this->data['raid_data'] = $this->raidModel->read($this->data['raid_id']);
		//If raid doesnt exist, show 404.
		$this->checkRowExists($this->data['raid_data']);
		
		//Also get list of all characters and their attendance status.
		$this->data['characters'] = $this->characterModel->readAll();
		$this->data['raid_attends'] = $this->raidAttendanceModel->read($this->data['raid_id']);
		//Combine to get list of which characters arent selected.
		$this->data['not_in_raid'] = array();
		foreach ($this->data['characters'] as $char) {
			$found = false;
			foreach ($this->data['raid_attends'] as $attend) {
				if ($char->char_id == $attend->attend_character) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$this->data['not_in_raid'][] = $char;
			}
		}
		
		//Lets also get raid related data.
		$this->data['raid_items'] = $this->pointsUsedModel->readRaid($this->data['raid_id']);
		$this->data['raid_adjustments'] = $this->raidAdjustmentModel->read($this->data['raid_id']);
		
		//And return the view with the data.
		return view('raid.manage.modify_raid', $this->data);
	}
	
	/**
	 * Creates a new raid.
	 *
	 * @param	Request	$request	The request that specifies the information of raid.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function createRaid(Request $request) {
		//Attempt the create.
		$success = $this->raidModel->create($request);
		//Redirect depending on status.
		if($success !== false) {
			//If create was successful, route to created raid.
			return redirect()->route('modify_raid/{id}', ['id' => $success]);
		} else {
			//If create failed, redirect back with status of creation.
			$this->setStatusFlash($request, false);
			return redirect()->route('raid_management')->withInput();
		}
	}
	
	/**
	 * Updates the information of raid. Doesnt include items/adjustments/attendance.
	 *
	 * @param	Request	$request	The request that specifies the information of raid.
	 *
	 * @return	Redirect 	Redirects back with success status flash.
	 */
	public function updateRaid(Request $request) {
		//Parse the id.
		$raidId = $this->checkId($request->get('raid_id'));
		//Attempt the update.
		$success = $this->raidModel->update($raidId, $request);
		//Display the status of update.
		$this->setStatusFlash($request, $success, 'management.modified_raid_info');
		
		//And redirect back.
		return redirect()->back()->withInput();
	}
	
	/**
	 * Deletes the raid specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function deleteRaid(Request $request) {
		//Parse the id.
		$charId = $this->checkId($request->get('raid_id'));
		//Attempt to delete raid.
		$success = $this->raidModel->delete($charId);
		//Display the status of delete.
		$this->setStatusFlash($request, $success, 'management.deleted_raid');
		
		//And redirect back.
		return redirect()->back()->withInput();
	}
	
	/**
	 * Creates an item to linked raid specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information of raid item.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function createRaidItem(Request $request) {
		//Attempt the create.
		$success = $this->pointsUsedModel->create($request);
		//Display the status of creating an item.
		$this->setStatusFlash($request, $success, 'management.added_raid_item');
		
		//And redirect back.
		return redirect()->back();
	}
	
	/**
	 * Deletes item specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function deleteRaidItem(Request $request) {
		//Parse the id.
		$deleteId = $this->checkId($request->get('item_id'));
		//Attempt the delete.
		$success = $this->pointsUsedModel->delete($deleteId);
		//Display the status of deleting item.
		$this->setStatusFlash($request, $success, 'management.deleted_raid_item');
		
		//And redirect back.
		return redirect()->back()->withInput();
	}
	
	/**
	 * Creates an adjustment to linked raid specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function createRaidAdjustment(Request $request) {
		//Attempt the create.
		$success = $this->raidAdjustmentModel->create($request);
		//Display the status of creation.
		$this->setStatusFlash($request, $success, 'management.added_raid_adjustment');
		
		//And redirect back.
		return redirect()->back();
	}
	
	/**
	 * Deletes the adjustment specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function deleteRaidAdjustment(Request $request) {
		//Parse the ids.
		$raidId = $this->checkId($request->get('raid_id'));
		$charId = $this->checkId($request->get('char_id'));
		//Attempt the delete.
		$success = $this->raidAdjustmentModel->delete($raidId, $charId);
		//Display the status of delete.
		$this->setStatusFlash($request, $success, 'management.deleted_raid_adjustment');
		
		//And redirect back.
		return redirect()->back()->withInput();
	}
	
	/**
	 * Updates the attendance linked to raid specified in $request
	 *
	 * @param	Request	$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function updateRaidAttendance(Request $request) {
		//Parse the id.
		$raidId = $this->checkId($request->get('raid_id'));
		//Attempt the update.
		$success = $this->raidAttendanceModel->update($raidId, $request);
		//Display the status of update.
		$this->setStatusFlash($request, $success, 'management.modified_raid_attendance');
		
		//And redirect back.
		return redirect()->back();
	}
}
