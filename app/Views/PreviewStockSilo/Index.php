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
                                    <form action="<?= env('BASE_URL') ?>preview-stocksilo" method="GET">
                                        <div class="row">
                                            <label for="">Silo</label>
                                            <div class="form-group ml-3">
                                                <select name="silo" id="" class="form-control select2bs4">
                                                    <?php foreach ($materials as $material): ?>
                                                        <option value="<?= $material['code'] ?>" <?= $material['code'] == $silo ? 'selected' : '' ?>><?= $material['code'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="ml-3"></div>
                                            <label>Date range:</label>
                                            <div class="form-group ml-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control float-right daterange" name="daterange" value="">
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
                                        <th class="border">No</th>
                                        <th class="border">Time</th>
                                        <th class="border">No</th>
                                        <th class="border">IN</th>
                                        <th class="border">OUT</th>
                                        <th class="border">STOK</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $stok_in = 0;
                                        $stok_out = 0;
                                        $currentStok = $controller->getStockByCode($silo);

                                        $arrayNumbers = [];

                                        foreach ($stoks as $stok): ?>
                                            <?php
                                            $number = $stok['number'];

                                            $ok = true;
                                            ?>

                                            <?php if (!array_search($number, $arrayNumbers)): ?>
                                                <?php
                                                if ($no > 1) {
                                                    $stok['status'] == 'IN' ? $stok_in += (int)$stok['value'] : $stok_in += 0;

                                                    $stok['status'] == 'OUT' ? $stok_out += (int)$stok['value'] : $stok_out += 0;
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td class="border-l text-center"><?= $stok['timestamp'] ?></td>
                                                    <td class="border-l text-center"><?= $stok['number'] ?></td>
                                                    <td class="border-l text-center"><?= $stok['status'] == 'IN' ? $stok['value'] : '-' ?></td>
                                                    <td class="border-l text-center"><?= $stok['status'] == 'OUT' ? ($stok['value']) : '-' ?></td>
                                                    <td class="border-l text-center">
                                                        <?php
                                                        if ($no == 2) {
                                                            echo number_format($currentStok, 0, ',', '.');
                                                        } else {
                                                            echo number_format($currentStok + (($stok_out) - $stok_in), 0, ',', '.');
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                array_push($arrayNumbers, $number);
                                                ?>
                                            <?php endif; ?>
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