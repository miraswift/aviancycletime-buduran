<?php

namespace App\Models;

use CodeIgniter\Model;

class StockSiloModel extends Model
{
    protected $table = 'tb_stock_silo';
    protected $primaryKey = 'id_stock_silo';

    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'code_plant',
        'code_stock_silo',
        'supplier_stock_silo',
        'val_stock_silo',
        'status_stock_silo',
        'date_stock_silo',
        'time_stock_silo',
        'deleted_at',
    ];

    protected $useTimestamps = true;
}
