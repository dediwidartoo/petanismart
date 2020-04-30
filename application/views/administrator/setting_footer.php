<?php echo $this->session->flashdata('upload'); ?>

<!-- Begin Page Content -->
<div class="container-fluid mb-5">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800 mb-4">Pengaturan</h1>

	<div class="row">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                <div class="list-group">
					<a href="<?= base_url(); ?>administrator/setting/banner" class="list-group-item list-group-item-action">Banner Slider</a>
                    <a href="<?= base_url(); ?>administrator/setting/description" class="list-group-item list-group-item-action">Deskripsi Singkat</a>
                    <a href="<?= base_url(); ?>administrator/setting/rekening" class="list-group-item list-group-item-action">Rekening</a>
					<a href="<?= base_url(); ?>administrator/setting/sosmed" class="list-group-item list-group-item-action">Sosial Media</a>
					<a href="<?= base_url(); ?>administrator/setting/address" class="list-group-item list-group-item-action">Alamat</a>
					<a href="<?= base_url(); ?>administrator/setting/delivery" class="list-group-item list-group-item-action">Biaya Antar</a>
					<a href="<?= base_url(); ?>administrator/setting/footer" class="list-group-item list-group-item-action">Footer</a>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="lead text-dark mb-0">Footer Navigasi</h2>
                </div>
                <div class="card-body">
                   <a href="<?= base_url(); ?>administrator/setting/footer/add" class="btn btn-sm btn-info" data-toggle="modal" data-target="#addNav">Tambah Navigasi</a>
                   <hr>
									 <ul class="list-group mb-2">
										 <li class="list-group-item"> <strong>Info <?= $this->config->item('app_name'); ?></strong> </li>
										<?php foreach($footerinfo->result_array() as $f): ?>
											<li class="list-group-item"><?= $f['title']; ?> </li>
									 	<?php endforeach; ?>
									 </ul>
									 <ul class="list-group">
										 <li class="list-group-item"> <strong>Bantuan</strong> </li>
										<?php foreach($footerhelp->result_array() as $f): ?>
											<li class="list-group-item"><?= $f['title']; ?></li>
									 	<?php endforeach; ?>
									 </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal -->
<div class="modal fade" id="addNav" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Navigasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
	  </div>
	  <form action="<?= base_url(); ?>administrator/setting/footer" method="post">
      <div class="modal-body">
				<div class="form-group">
					<label for="page">Pilih Halaman</label>
					<select class="form-control" name="page" id="page">
						<?php foreach($pages->result_array() as $p): ?>
								<option value="<?= $p['id']; ?>"><?= $p['title']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="type">Pilih Kategori</label>
					<select class="form-control" name="type" id="type">
							<option value="1">Info <?= $this->config->item('app_name'); ?></option>
							<option value="2">Bantuan</option>
					</select>
				</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Tambah</button>
	  </div>
	  </form>
    </div>
  </div>
</div>
