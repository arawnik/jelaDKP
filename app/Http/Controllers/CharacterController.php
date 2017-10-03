<?php

namespace jelaDkp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

use jelaDkp\Models\CharacterModel;
use jelaDkp\Models\ClassModel;
use jelaDkp\Models\RoleModel;
use jelaDkp\Models\RaidAttendanceModel;
use jelaDkp\Models\PointsUsedModel;
use jelaDkp\Models\RaidModel;

/**
 * CharacterController is the Controller that handles all of the character related requests.
 *
 * @author Jere Junttila <junttila.jere@gmail.com>
 * @license GPL
 */
class CharacterController extends Controller {
	private $characterModel;
	private $classModel;
	private $roleModel;
	private $raidAttendanceModel;
	private $pointsUsedModel;
	private $raidModel;
	
	/**
     * Create a new controller instance. Do parent constructs and initialize models.
     *
     * @return	void
     */
    public function __construct() {
		parent::__construct();
		
		//Initialize the models
		$this->characterModel = new CharacterModel();
		$this->classModel = new ClassModel();
		$this->roleModel = new RoleModel();
		$this->raidAttendanceModel = new RaidAttendanceModel();
		$this->pointsUsedModel = new PointsUsedModel();
		$this->raidModel = new RaidModel();
    }
	
   /**
     * Show the Dkp dashboard of characters. Dashboard includes the data of all characters with dkp stats.
     *
     * @return	Response	Returns view with the specified options.
     */
    public function index() {
		//Fix title.
		$this->setTitlePage(trans('common.dashboard'));
		
		//Fetch character data
		$this->data['characters'] = $this->characterModel->readAllWithDkp();
		
		//And return view with the data.
		return view('public.dashboard', $this->data);
    }
	
	/**
     * Show the statistics of characters. Statistics includes the data of all characters with their respective attendance stats.
     *
     * @return	Response	Returns view with the specified options.
     */
    public function stats() {
		//Fix title.
		$this->setTitlePage(trans('common.stats'));
		
		//Get amount of lifetime raids.
		$this->data['raids_lifetime_count'] = $this->raidModel->count();
		//Fetch character data.
		$this->data['characters'] = $this->characterModel->readAllWithStats();
		
		//And return view with the data.
		return view('public.stats', $this->data);
    }
	
	/**
     * Show information of specific character.
	 *
	 * @param	integer	$id	Id of the character that will be displayed.
	 * @return	Response	Returns view with the specified options.
     */
    public function char($id) {
		//Fix title.
		$this->setTitlePage(trans('common.character'));
		
		//Parse the id.
		$this->data['char_id'] = $this->checkId($id);
		//Read character data.
		$this->data['char_data'] = $this->characterModel->readWithDkp($this->data['char_id']);
		//If char doesnt exist, show 404.
		$this->checkRowExists($this->data['char_data']);
		
		//Fetch character's raid data.
		$this->data['raids_attended'] = $this->raidAttendanceModel->readChar($this->data['char_id']);
		$this->data['items'] = $this->pointsUsedModel->readChar($this->data['char_id']);
		
		//And return view with the data.
        return view('char.character', $this->data);
    }
	
	/**
	 * Shows list of characters that user can manage. Also has option to create new character.
	 *
	 * @return	Response 	Returns view with the specified options.
	 */
	public function characterManagement() {
		//Fix title.
		$this->setTitlePage(trans('common.character_management'));
		
		//Fetch character data
		$this->data['characters'] = $this->characterModel->readAll();
		
		//Also fetch the data for selecting class and role.
		$this->data['classes'] = $this->classModel->readAll();
		$this->data['roles'] = $this->roleModel->readAll();
		
		//And return view with the data.
		return view('char.manage.character_management', $this->data);
	}
	
	/**
	 * Shows the information of specified character in a way it can be modified.
	 *
	 * @param	integer	$id		Specifies the ID of the modified character.
	 *
	 * @return	Response 	Returns view with the specified options.
	 */
	public function modifyCharacter($id) {
		//Fix title.
		$this->setTitlePage(trans('management.modify_character'));
		
		//Parse the id.
		$this->data['char_id'] = $this->checkId($id);
		//Read character data.
		$this->data['char_data'] = $this->characterModel->read($this->data['char_id']);
		//If char doesnt exist, show 404.
		$this->checkRowExists($this->data['char_data']);
		
		//Also fetch the data for selecting class and role.
		$this->data['classes'] = $this->classModel->readAll();
		$this->data['roles'] = $this->roleModel->readAll();
		
		//And return view with the data.
		return view('char.manage.modify_character', $this->data);
	}
	
	/**
	 * Creates a new character.
	 *
	 * @param	Request		$request	The request that specifies the information of character.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function createCharacter(Request $request) {
		//Attempt the create.
		$success = $this->characterModel->create($request);
		//Display the status of create.
		$this->setStatusFlash($request, $success, 'management.added_character');
		
		//And redirect back.
		return redirect()->back();
	}
	
	/**
	 * Updates the information of character.
	 *
	 * @param	Request		$request	The request that specifies the information of character.
	 *
	 * @return	Redirect 	Redirects back with success status flash.
	 */
	public function updateCharacter(Request $request) {
		//Parse the id.
		$charId = $this->checkId($request->get('char_id'));
		//Attempt the update.
		$success = $this->characterModel->update($charId, $request);
		//Display the status of update.
		$this->setStatusFlash($request, $success, 'management.updated_character');
		
		//And redirect back to character management.
		return redirect()->route('character_management');
	}
	
	/**
	 * Deletes the character specified in $request
	 *
	 * @param	Request		$request	The request that specifies the information.
	 *
	 * @return	Redirect	Redirects back with success status flash.
	 */
	public function deleteCharacter(Request $request) {
		//Parse the id.
		$charId = $this->checkId($request->get('char_id'));
		//Attempt to delete character.
		$success = $this->characterModel->delete($charId);
		//Display the status of delete.
		$this->setStatusFlash($request, $success, 'management.deleted_character');
		
		//And redirect back.
		return redirect()->back();
	}
}
