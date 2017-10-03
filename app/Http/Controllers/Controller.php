<?php

namespace jelaDkp\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Auth;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	/**
	 * Data array to be passed for views.
	 *
	 * @example "$this->data['title'] = 'new title';" Add title value to $data.
	 */
	public $data = array();
	
	/**
	 * Site name that will be displayed at the page title.
	 */
	public $sitename;
	
	/**
     * Create a new controller instance. Set default values to $data.
     *
     * @return void
     */
    public function __construct() {
		app()->setLocale('en');
		$this->sitename = trans('common.default_title');
		
        //Set default values for Controller data.
		$this->data = array(
			'title' => $this->sitename,
		);
    }
	
	/**
	 * Sets page prefix/postfix into variable that includes page title.
	 *
	 * @param	integer	$pageName Name of the current page.
	 *
	 * @return	void
	 */
	protected function setTitlePage($pageName) {
		$this->data['title'] = $this->sitename .' - '. $pageName;
	}
	
	/**
	 * Checks if $id includes valid ID, if not redirects to 404.
	 *
	 * @param	integer	$id	Id that we want to check.
	 *
	 * @return	integer	Returns the int value of ID if it was present, otherwise abort(404)
	 */
	public function checkId($id) {
		$idInt = intval($id, 10);
		if(!is_int($idInt) || $idInt <= 0) { //If there wasnt positive ID, 404.
			abort(404);
		} else {
			return $idInt;
		}
	}
	
	/**
	 * Sets flash into session that will display the success status told by $status.
	 *
	 * @param	Mixed	$request		The request where data will be set.
	 * @param	Mixed	$status			Variable that tells if the status was successful. Expects false on fail and Mixed on success.
	 * @param	String	$successTrans	Translation code of the text that will be flashed on successful event.
	 * @param	String	$failTrans		Translation code of the text that will be flashed on failed event.
	 *
	 * @return	Void
	 */
	public function setStatusFlash($request, $status, $successTrans = 'management.action_done', $failTrans = 'management.unable_to_do_action') {
		if($status !== false)
			$request->session()->flash('alert-success', trans($successTrans));
		else
			$request->session()->flash('alert-danger', trans($failTrans));
	}
	
	/**
	 * Checks if the database row was found. If not, go 404
	 *
	 * @param	Collection	$data	Collection returned by model that should hold the data.
	 *
	 * @return	Mixed	true on success, Abort(404) on false.
	 */
	public function checkRowExists($data) {
		if(!$data) {
			abort(404);
		} else {
			return true;
		}
	}
}
