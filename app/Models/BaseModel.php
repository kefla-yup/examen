<?php
namespace App\Models;

use Flight;

class BaseModel {
    protected $db;
    
    public function __construct() {
        $this->db = Flight::db();
    }
}
?>
