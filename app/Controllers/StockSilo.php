<?php

namespace App\Controllers;

use App\Models\EquipmentModel;
use App\Models\PlantModel;
use App\Models\StockSiloModel;
use PHPUnit\TextUI\Configuration\GroupCollection;

use Mpdf\Mpdf;

class StockSilo extends BaseController
{
    protected $stockSiloModel;
    protected $equipmentModel;

    public function __construct()
    {
        $this->plantModel = new PlantModel();
        $this->stockSiloModel = new StockSiloModel();
        $this->equipmentModel = new EquipmentModel();
    }

    public function index()
    {
        $data['title'] = 'Stock Silo';
        $data['menuGroup'] = '';
        $data['menu'] = 'StockSilo';

        return view('StockSilo/Index', $data);
    }

    public function get()
    {
        $dataStock = [
            "1101" => $this->getStockByCode("1101"),
            "1102" => $this->getStockByCode("1102"),
            "1103" => $this->getStockByCode("1103"),
            "1104" => $this->getStockByCode("1104"),
            "1201" => $this->getStockByCode("1201"),
            "1202" => $this->getStockByCode("1202"),
            "1203" => $this->getStockByCode("1203"),
            "1204" => $this->getStockByCode("1204"),
            "1205" => $this->getStockByCode("1205"),
            "2203" => $this->getStockByCode("2203"),
            "2204" => $this->getStockByCode("2204"),
            "2205" => $this->getStockByCode("2205"),
        ];

        return $this->response->setStatusCode(200)->setJSON($dataStock);
    }

    public function getStockByCode($code_stock_silo)
    {
        $stockSilo = $this->stockSiloModel->select('SUM(val_stock_silo) AS val_stock_silo')->where('code_stock_silo', $code_stock_silo)->where('status_stock_silo', 'IN')->first();

        $name_equipment = "";

        switch ($code_stock_silo) {
            case "1101":
                $name_equipment = "FEEDING PASIR SEDANG";
                break;
            case "1102":
                $name_equipment = "FEEDING PASIR KASAR";
                break;
            case "1103":
                $name_equipment = "FEEDING SEMEN PUTIH";
                break;
            case "1104":
                $name_equipment = "FEEDING CACO3";
                break;
            case "1201":
                $name_equipment = "FEEDING PASIR HALUS";
                break;
            case "1202":
                $name_equipment = "FEEDING SEMEN PUTIH";
                break;
            case "1203":
                $name_equipment = "FEEDING SEMEN GREY";
                break;
            case "1204":
                $name_equipment = "FEEDING CACO3";
                break;
            case "1205":
                $name_equipment = "FEEDING SEMEN PUTIH";
                break;
            default:
                $name_equipment = "";
                break;
        }

        $getStockOut = $this->equipmentModel->select('SUM(actual_equipment) AS total_actual')->where('name_equipment', $name_equipment)->where('type_equipment', 'FEEDING')->where('status_equipment', 'OFF')->first();

        $stockOut = $getStockOut ? $getStockOut['total_actual'] : 0;

        return (!empty($stockSilo['val_stock_silo'])) ? $stockSilo['val_stock_silo'] - $stockOut : 0 . "";
    }

    public function print()
    {
        $vars = $this->request->getVar();

        $daterange = $vars['daterange'];
        $dates = explode('-', $daterange);

        $dateFrom = date('Y-m-d', strtotime($dates[0]));
        $dateTo = date('Y-m-d', strtotime($dates[1]));

        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['materials'] = $this->getMaterials();
        $data['stockSiloModel'] = $this->stockSiloModel;
        $data['equipmentModel'] = $this->equipmentModel;

        $view = view('StockSilo/Print', $data);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
        ]);

        $mpdf->WriteHTML($view);

        // Output sebagai browser PDF
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline;filename=' . 'Laporan_StockSilo' . $dateFrom . '-' . $dateTo . '.pdf')
            ->setBody($mpdf->Output('', 'S')); // 'S' = return as string
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
                    'msg' => "Stock not saved",
                    'detail' => $this->equipmentModel->errors(),
                ];

                return $this->response->setStatusCode(400)->setJSON($result);
            } else {
                $result = [
                    'code' => 200,
                    'status' => 'ok',
                    'msg' => "Stock saved succesfully",
                ];

                return $this->response->setStatusCode(200)->setJSON($result);
            }
        }
    }

    public function getMaterials()
    {
        $materials = [
            [
                "code" => "1101",
                "name" => "FEEDING PASIR SEDANG",
            ],
            [
                "code" => "1101",
                "name" => "FEEDING PASIR SEDANG",
            ],
            [
                "code" => "1101",
                "name" => "FEEDING PASIR SEDANG",
            ],
            [
                "code" => "1102",
                "name" => "FEEDING PASIR KASAR",
            ],
            [
                "code" => "1103",
                "name" => "FEEDING SEMEN PUTIH",
            ],
            [
                "code" => "1104",
                "name" => "FEEDING CACO3",
            ],
            [
                "code" => "1201",
                "name" => "FEEDING PASIR HALUS",
            ],
            [
                "code" => "1202",
                "name" => "FEEDING SEMEN PUTIH",
            ],
            [
                "code" => "1204",
                "name" => "FEEDING CACO3",
            ],
            [
                "code" => "1205",
                "name" => "FEEDING SEMEN PUTIH",
            ],
        ];

        return $materials;
    }
}
