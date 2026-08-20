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
                                                <select name="no_spk" id="" class="form-control select2bs41">
                                                    <option value="all">Semua</option>
                                                    <?php foreach ($spks as $spk): ?>
                                                        <option value="<?= $spk['no_spk'] ?>"><?= $spk['no_spk'] ?></option>
                                                    <?php endforeach; ?>
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
                                            <th>SPK</th>
                                            <th>Batch</th>
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
                                        <?php
                                        $no = 1;

                                        $batchNumbers = array_column($batchs, 'no_batch');
                                        // dd($batchs);
                                        $rawDosingData = $equipmentModel->getActualDosingByBatches($batchNumbers);

                                        $dosingData = [];

                                        foreach ($rawDosingData as $row) {
                                            $dosingData[$row['no_batch']][$row['name_equipment']][$row['line_equipment']] = $row['actual_equipment'];
                                        }

                                        // dd($dosingData);

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
                                            ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $batch['created_at'] ?></td>
                                                <td><?= $batch['no_spk'] ?></td>
                                                <td><?= $batch['no_batch'] ?></td>
                                                <td><?= $mat1101 ?></td>
                                                <td><?= $mat1102  ?></td>
                                                <td><?= $mat1103  ?></td>
                                                <td><?= $mat1104  ?></td>
                                                <td><?= $mat1201  ?></td>
                                                <td><?= $mat1202  ?></td>
                                                <td><?= $mat2203  ?></td>
                                                <td><?= $mat2204  ?></td>
                                                <td><?= $mat2205  ?></td>
                                            </tr>
                                        <?php endforeach; ?>
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