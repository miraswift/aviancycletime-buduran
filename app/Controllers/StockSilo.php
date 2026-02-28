<?php

namespace App\Controllers;

use App\Models\PlantModel;
use App\Models\StockSiloModel;
use PHPUnit\TextUI\Configuration\GroupCollection;

class StockSilo extends BaseController
{
    protected $stockSiloModel;

    public function __construct()
    {
        $this->plantModel = new PlantModel();
        $this->stockSiloModel = new StockSiloModel();
    }

    public function create()
    {
        $vars = json_decode(json_encode($this->request->getVar()), true);

        $code_plant = $vars['code_plant'];
        $code_stock_silo = $vars['code_stock_silo'];
        $supplier_stock_silo = $vars['supplier_stock_silo'];
        $val_stock_silo = $vars['val_stock_silo'];
        $status_stock_silo = "IN";
        $date_stock_silo = $vars['date_stock_silo'];
        $time_stock_silo = $vars['time_stock_silo'];

        $plant = $this->plantModel->where('code_plant', $code_plant)->first();

        if ($plant == null) {
            $result = [
                'code' => 400,
                'status' => 'failed',
                'msg' => "Failed, plant not found",
            ];

            return $this->response->setStatusCode(400)->setJSON($result);
        } else {
            $stockSiloData = [
                'code_plant' => $code_plant,
                'code_stock_silo' => $code_stock_silo,
                'supplier_stock_silo' => $supplier_stock_silo,
                'val_stock_silo' => $val_stock_silo,
                'status_stock_silo' => $status_stock_silo,
                'date_stock_silo' => $date_stock_silo,
                'time_stock_silo' => $time_stock_silo,
            ];

            $save = $this->stockSiloModel->save($stockSiloData);

            if (!$save) {
                $result = [
                    'code' => 400,
                    'status' => 'failed',
                    'msg' => "Equipment not saved",
                    'detail' => $this->equipmentModel->errors(),
                ];

                return $this->response->setStatusCode(400)->setJSON($result);
            } else {
                $result = [
                    'code' => 200,
                    'status' => 'ok',
                    'msg' => "Equipment saved succesfully",
                ];

                return $this->response->setStatusCode(200)->setJSON($result);
            }
        }
    }
}
