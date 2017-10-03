<?php
namespace jelaDkp\Models;

use Illuminate\Support\Facades\DB;

/**
 * Dkp model class that displays the dkp status by characters.
 */
class ClassModel extends Model {
	
	/**
     * Create a new model instance.
     *
     * @return	ClassModel	New instance of the ClassModel.
     */
    public function __construct() {
		parent::__construct();
		//Set the table name.
		$this->setTable('classes', 'cl', 'class_id');
		//Add table's fields.
		$this->addField('class_name', 'required|max:255', 'name');
		$this->addField('class_color', 'required|(regex:/[a-zA-Z0-9]{6})', 'color');
    }
}
