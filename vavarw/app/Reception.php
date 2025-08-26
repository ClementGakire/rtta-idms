<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    // Use the numeric `id` primary key so Eloquent->find()/findOrFail($id) works
    // (the controller expects to look up receptions by the numeric `id` column).
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    // fillable fields used by controller
    protected $fillable = [
        'user_id','purchase_order','roadmap_number','contractor','institution','number_of_days','starting_date','ending_date','operator','destination','plate_number','ebm','files'
    ];
}
