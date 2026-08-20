<?php

namespace App\Controllers;

use App\Models\CekSpkModel;
use App\Models\EquipmentModel;

class CekSpk extends BaseController
{
    protected $cekSpkModel;
    protected $equipmentModel;

    public function __construct()
    {
        $this->cekSpkModel = new CekSpkModel();
        $this->equipmentModel = new EquipmentModel();
    }

    public function index()
    {
        // 
        $mixer = $this->request->getGet('mixer');

        $cekSpk = $this->cekSpkModel->where('mixer', $mixer)->orderBy('created_at', 'DESC')->first();

        if (!$cekSpk) {
            $result = [
                'code' => 200,
                'status' => 'NO',
            ];

            return $this->response->setStatusCode(200)->setJSON($result);
        } else {
            $result = [
                'code' => 200,
                'status' => $cekSpk['status_cek_spk'],
            ];

            return $this->response->setStatusCode(200)->setJSON($result);
        }
    }

    public function create()
    {
        $vars = json_decode(json_encode($this->request->getVar()), true);

        $spk = $vars['spk'];

        $cekSpk = $this->equipmentModel->where('no_spk', $spk)->first();

        $cekSpkData = [
            'no_spk' => $spk,
            'mixer' => $vars['mixer'],
            'status_cek_spk' => $cekSpk ? 'NO' : 'YES',
        ];

        $save = $this->cekSpkModel->save($cekSpkData);

        if (!$save) {
            $result = [
                'code' => 400,
                'status' => 'failed',
                'msg' => "Cek spk not saved",
                'detail' => $this->cekSpkModel->errors(),
            ];

            return $this->response->setStatusCode(400)->setJSON($result);
        } else {
            $result = [
                'code' => 200,
                'status' => 'ok',
                'msg' => "Cek spk saved succesfully",
            ];

            return $this->response->setStatusCode(200)->setJSON($result);
        }
    }
}
