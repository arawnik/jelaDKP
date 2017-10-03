<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;

/**
 * RoleModel that handles database functions for roles of characters.
 */
class RoleModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	RoleModel	New instance of the RoleModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('roles', 'ro', 'role_id');
		//Add table's fields.
		$this->addField('role_name', 'required|max:255', 'name');
    }
}
