<?php

namespace App\Controllers;

use App\Models\EquipmentModel;

class PreviewReport extends BaseController
{
    protected $equipmentModel;

    public function __construct()
    {
        $this->equipmentModel = new EquipmentModel();
    }

    public function index()
    {
        $no_spk = $this->request->getGet('no_spk') ?? 'all';
        $mixer = $this->request->getGet('mixer') ?? 'all';
        $daterange = $this->request->getGet('daterange');

        if ($daterange) {
            $dates = explode("-", urldecode($daterange));
            $dateFrom = date("Y-m-d", strtotime($dates[0]));
            $dateTo = date("Y-m-d", strtotime($dates[1]));
        } else {
            $dateFrom = date("Y-m-d");
            $dateTo = date("Y-m-d");
        }

        $data['title'] = 'Report Produksi';
        $data['menuGroup'] = 'ReportProduksi';
        $data['menu'] = 'PreviewReport';
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['no_spk'] = $no_spk;
        $data['mixer'] = $mixer;
        $data['daterange'] = $daterange;

        $data['spks'] = $this->equipmentModel->getSpkGroupAll();
        $data['batchs'] = $this->equipmentModel->getBatchNumberGroupByDateSpk($dateFrom, $dateTo, $no_spk, $mixer);
        // Models
        $data['equipmentModel'] = $this->equipmentModel;

        return view('PreviewReport/Index', $data);
    }
}
