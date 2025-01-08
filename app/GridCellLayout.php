<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GridCellLayout extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'grid_cell_layouts';
	protected $primaryKey = 'id';
		
}
