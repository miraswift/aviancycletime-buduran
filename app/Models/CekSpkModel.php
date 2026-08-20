<?php

namespace App\Models;

use CodeIgniter\Model;

class CekSpkModel extends Model
{
    protected $table = 'tb_cek_spk';
    protected $primaryKey = 'id_cek_spk';

    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'no_spk',
        'mixer',
        'deleted_at',
        'status_cek_spk',
    ];

    protected $useTimestamps = true;
}
