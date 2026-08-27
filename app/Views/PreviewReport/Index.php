<?= $this->extend('Layout/Template_Main') ?>
<?= $this->section('content') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#"><?= $menuGroup ?></a></li> -->
                        <li class="breadcrumb-item active"><?= $menu ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-10">
                                    <form action="<?= env('BASE_URL') ?>preview-report" method="GET">
                                        <div class="row">
                                            <label>SPK:</label>
                                            <div class="form-group">
                                                <select name="no_spk" id="" class="form-control select2bs4">
                                                    <option value="all">Semua</option>
                                                    <?php foreach ($spks as $spk): ?>
                                                        <option value="<?= $spk['no_spk'] ?>" <?= $spk['no_spk'] == $no_spk ? 'selected' : '' ?>><?= $spk['no_spk'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <label class="ml-3">Mixer:</label>
                                            <div class="form-group">
                                                <select name="mixer" id="" class="form-control select2bs4">
                                                    <option value="all">Semua</option>
                                                    <option value="1" <?= 1 == $mixer ? 'selected' : '' ?>>Mixer 1</option>
                                                    <option value="2" <?= 2 == $mixer ? 'selected' : '' ?>>Mixer 2</option>
                                                </select>
                                            </div>
                                            <label class="ml-3">Date range:</label>
                                            <div class="form-group ml-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control float-right daterange" name="daterange" value="<?= date('m/d/Y', strtotime($dateFrom)) . ' - ' . date('m/d/Y', strtotime($dateTo)) ?>">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <div class="form-group ml-3">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm table-bordered" id="table-print">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Tgl/Jam</th>
                                            <th>Prd</th>
                                            <th>SPK</th>
                                            <th>Batch</th>
                                            <th>Feeding Time</th>
                                            <th>Discharge Weigh</th>
                                            <th>Mix Time</th>
                                            <th>Discharge UH</th>
                                            <th>Cycle Time</th>
                                            <th>1101</th>
                                            <th>1102</th>
                                            <th>1103</th>
                                            <th>1104</th>
                                            <th>1201</th>
                                            <th>1202</th>
                                            <th>1203/2203</th>
                                            <th>1204/2204</th>
                                            <th>1205/2205</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($batchs): ?>
                                            <?php
                                            $no = 1;

                                            $batchNumbers = array_column($batchs, 'no_batch');
                                            // dd($batchs);
                                            $rawDosingData = $quipmentModel->getActualDosingByBatches($batchNumbers);

                                            $dosingData = [];
                                            $adjustDosingData = [];
                                            $durationData = [];

                                            foreach ($rawDosingData as $row) {
                                                $strName = trim(preg_replace('/\s*\d+\s*$/', '', $row['name_equipment']));

                                                $dosingData[$row['no_batch']][$row['name_equipment']][$row['line_equipment']] = $row['actual_equipment'];

                                                $adjustDosingData[$row['no_batch']][$row['name_equipment']][$row['line_equipment']] = $row['is_adjusted'];

                                                $durationData[$row['no_batch']][$strName] = $row['duration_equipment'];

                                                if ($row['type_equipment'] == 'DOSSING' && $row['status_equipment'] == 'ON') {
                                                    // Save dossing first;
                                                }
                                            }

                                            // dd($durationData);

                                            foreach ($batchs as $batch): ?>
                                                <?php
                                                $no_batch = $batch['no_batch'];

                                                $mat1101 = $dosingData[$no_batch]["FEEDING PASIR SEDANG"]["L1"] ?? 0;

                                                $mat1102 = $dosingData[$no_batch]["FEEDING PASIR HALUS"]["L1"] ?? 0;

                                                $mat1103 = $dosingData[$no_batch]["FEEDING SEMEN PUTIH"]["L1"] ?? 0;

                                                $mat1104 = $dosingData[$no_batch]["FEEDING KALSIUM"]["L1"] ?? 0;

                                                $mat1201 = $dosingData[$no_batch]["FEEDING PASIR KASAR"]["L1-2"] ?? 0;

                                                $mat1202 = $dosingData[$no_batch]["FEEDING SEMEN PUTIH"]["L1-2"] ?? 0;

                                                $mat2203 = ($dosingData[$no_batch]["FEEDING SEMEN ABU"]["L1-2"] ?? 0) + ($dosingData[$no_batch]["FEEDING SEMEN ABU"]["L2"] ?? 0);

                                                $mat2204 = ($dosingData[$no_batch]["FEEDING KALSIUM"]["L1-2"] ?? 0) + ($dosingData[$no_batch]["FEEDING KALSIUM"]["L2"] ?? 0);

                                                $mat2205 = ($dosingData[$no_batch]["FEEDING SEMEN PUTIH"]["L1-2"] ?? 0) + ($dosingData[$no_batch]["FEEDING SEMEN PUTIH"]["L2"] ?? 0);

                                                $adjustMat1101 = $adjustDosingData[$no_batch]["FEEDING PASIR SEDANG"]["L1"] ?? 0;

                                                $adjustMat1102 = $adjustDosingData[$no_batch]["FEEDING PASIR HALUS"]["L1"] ?? 0;

                                                $adjustMat1103 = $adjustDosingData[$no_batch]["FEEDING SEMEN PUTIH"]["L1"] ?? 0;

                                                $adjustMat1104 = $adjustDosingData[$no_batch]["FEEDING KALSIUM"]["L1"] ?? 0;

                                                $adjustMat1201 = $adjustDosingData[$no_batch]["FEEDING PASIR KASAR"]["L1-2"] ?? 0;

                                                $adjustMat1202 = $adjustDosingData[$no_batch]["FEEDING SEMEN PUTIH"]["L1-2"] ?? 0;

                                                $adjustMat2203 = ($adjustDosingData[$no_batch]["FEEDING SEMEN ABU"]["L1-2"] ?? 0);

                                                $adjustMat2204 = ($adjustDosingData[$no_batch]["FEEDING KALSIUM"]["L1-2"] ?? 0);

                                                $adjustMat2205 = ($adjustDosingData[$no_batch]["FEEDING SEMEN PUTIH"]["L1-2"] ?? 0);

                                                $totalMat = $mat1101 + $mat1102 + $mat1103 + $mat1104 + $mat1201 + $mat1202 + $mat2203 + $mat2204 + $mat2205;

                                                $mixingTime = $durationData[$no_batch]['MIXING'] ?? '00:00:00';

                                                // $weighingDischargeTime = $durationData[$no_batch]['WEIGHING DISCHARGE'] ?? '00:00:30';

                                                $underhopperDischargeTime = $durationData[$no_batch]['UNDERHOPPER DISCHARGE'] ?? '00:00:40';

                                                $feedingTime = '00:00:00';

                                                foreach ($durationData[$no_batch] ?? [] as $name => $duration) {
                                                    if (stripos($name, 'FEEDING') !== false && $duration > $feedingTime) {
                                                        $feedingTime = $duration;
                                                    }
                                                }

                                                $weighingDischargeTime = '00:00:30';

                                                foreach ($durationData[$no_batch] ?? [] as $name => $duration) {
                                                    if (stripos($name, 'WEIGHING DISCHARGE') !== false && $duration > $weighingDischargeTime) {
                                                        $weighingDischargeTime = $duration;
                                                    }
                                                }

                                                $totalSecondsDischargeTime =
                                                    strtotime($weighingDischargeTime) -
                                                    strtotime('00:00:00') +
                                                    strtotime($underhopperDischargeTime) -
                                                    strtotime('00:00:00');

                                                $dischargeTime = gmdate('H:i:s', $totalSecondsDischargeTime);

                                                $totalSecondsCycletime =
                                                    strtotime($weighingDischargeTime) -
                                                    strtotime('00:00:00') +
                                                    strtotime($underhopperDischargeTime) -
                                                    strtotime('00:00:00') +
                                                    strtotime($feedingTime) -
                                                    strtotime('00:00:00');

                                                $cycleTime = gmdate('H:i:s', $totalSecondsCycletime);
                                                ?>

                                                <?php if ($totalMat > 0): ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $batch['created_at'] ?></td>
                                                        <td><?= $batch['code_formula'] ?></td>
                                                        <td><?= $batch['no_spk'] ?></td>
                                                        <td><?= $batch['no_batch'] ?></td>
                                                        <td><?= $feedingTime ?></td>
                                                        <td><?= $weighingDischargeTime ?></td>
                                                        <td><?= $mixingTime ?></td>
                                                        <td><?= $underhopperDischargeTime ?></td>
                                                        <td><?= $cycleTime ?></td>
                                                        <td><?= $mat1101 == 0 || $adjustMat1101 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal1101$no_batch'>$mat1101</a>" : $mat1101 ?></td>

                                                        <td><?= $mat1102 == 0 || $adjustMat1102 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal1102$no_batch'>$mat1102</a>" : $mat1102 ?></td>

                                                        <td><?= $mat1103 == 0 || $adjustMat1103 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal1103$no_batch'>$mat1103</a>" : $mat1103 ?></td>

                                                        <td><?= $mat1104 == 0 || $adjustMat1104 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal1104$no_batch'>$mat1104</a>" : $mat1104 ?></td>

                                                        <td><?= $mat1201 == 0 || $adjustMat1201 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal1201$no_batch'>$mat1201</a>" : $mat1201 ?></td>

                                                        <td><?= $mat1202 == 0 || $adjustMat1202 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal1202$no_batch'>$mat1202</a>" : $mat1202 ?></td>

                                                        <td><?= $mat2203 == 0 || $adjustMat2203 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal2203$no_batch'>$mat2203</a>" : $mat2203 ?></td>

                                                        <td><?= $mat2204 == 0 || $adjustMat2204 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal2204$no_batch'>$mat2204</a>" : $mat2204 ?></td>

                                                        <td><?= $mat2205 == 0 || $adjustMat2205 == 1 ? "<a href='#' data-toggle='modal' data-target='#modal2205$no_batch'>$mat2205</a>" : $mat2205 ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <!-- Modal 1101 -->
                                                <div class="modal fade" id="modal1101<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1101</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING PASIR SEDANG">
                                                                            <input type="hidden" name="line_equipment" value="L1">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat1101 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 1102 -->
                                                <div class="modal fade" id="modal1102<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1102</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING PASIR HALUS">
                                                                            <input type="hidden" name="line_equipment" value="L1">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat1102 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 1103 -->
                                                <div class="modal fade" id="modal1103<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1103</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING SEMEN PUTIH">
                                                                            <input type="hidden" name="line_equipment" value="L1">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat1103 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 1104 -->
                                                <div class="modal fade" id="modal1104<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1104</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING KALSIUM">
                                                                            <input type="hidden" name="line_equipment" value="L1">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat1104 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 1201 -->
                                                <div class="modal fade" id="modal1201<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1201</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING PASIR KASAR">
                                                                            <input type="hidden" name="line_equipment" value="L1-2">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat1201 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 1202 -->
                                                <div class="modal fade" id="modal1202<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1202</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING SEMEN PUTIH">
                                                                            <input type="hidden" name="line_equipment" value="L1-2">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat1202 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 2203 -->
                                                <div class="modal fade" id="modal2203<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1203/2203</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING SEMEN ABU">
                                                                            <input type="hidden" name="line_equipment" value="L2">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat2203 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 2204 -->
                                                <div class="modal fade" id="modal2204<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1204/2204</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING KALSIUM">
                                                                            <input type="hidden" name="line_equipment" value="L2">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat2204 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal 2205 -->
                                                <div class="modal fade" id="modal2205<?= $no_batch ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Actual 1205/2205</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?= env('BASE_URL') ?>/equipment/update/actual" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <?php if (session()->getFlashdata('failed')) : ?>
                                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                            <strong><i class="fas fa-exclamation-triangle"></i></strong> &nbsp; <?= session()->getFlashdata('failed') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <input type="hidden" name="filterSpk" value="<?= $no_spk ?>">
                                                                            <input type="hidden" name="filterMixer" value="<?= $mixer ?>">
                                                                            <input type="hidden" name="filterDaterange" value="<?= $daterange ?>">
                                                                            <input type="hidden" name="name_equipment" value="FEEDING SEMEN PUTIH">
                                                                            <input type="hidden" name="line_equipment" value="L2">
                                                                            <div class="form-group">
                                                                                <label for="no_spk" class="col-form-label">No SPK</label>
                                                                                <input type="text" name="no_spk" id="no_spk" class="form-control" value="<?= $batch['no_spk'] ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_spk_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_spk') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="no_batch" class="col-form-label">No Batch</label>
                                                                                <input type="text" name="no_batch" id="no_batch" class="form-control" value="<?= $no_batch ?>" readonly>
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="no_batch_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('no_batch') ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="actual_equipment" class="col-form-label">Actual Feeding</label>
                                                                                <input type="number" name="actual_equipment" id="actual_equipment" class="form-control <?= (validation_show_error('actual_equipment')) ? 'is-invalid' : '' ?>" value="<?= $mat2205 ?>">
                                                                                <!-- Validation Error Msg -->
                                                                                <div id="actual_equipment_error" class="invalid-feedback">
                                                                                    <?= validation_show_error('actual_equipment') ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn bg-navy btn-block rounded-pill">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<?= $this->endSection() ?>