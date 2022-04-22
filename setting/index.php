<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/check_session.php';
$title = "Pengaturan";
?>
<?php
require '../template/header.php';
require '../template/header-dashboard.php';

$data_user = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
?>

        <div class="dashboard_contents section--padding">
            <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="information_module">
                                <div class="information__set profile_images">
                                    <form action="<?= $config['web']['base_url']; ?>edit-photo/<?= $data_user['rows']['id'] ?>" method="post" enctype="multipart/form-data"> 
                                    <div class="information_wrapper">
                                        <div class="profile_image_area">
                                            <div>
                                                <?php
                                                if($data_user['rows']['photo'] == null){
                                                ?>
                                                <img src="<?= $config['web']['base_url']; ?>img/avatar.png" alt="<?= $data_user['rows']['username'] ?>" width="120px" height="120px">
                                                <?
                                                } else {
                                                ?>
                                                <img src="<?= $config['web']['base_url']; ?>user-photo/<?= $data_user['rows']['photo'] ?>" alt="<?= $data_user['rows']['username'] ?>" width="120px" height="120px">
                                                <?
                                                }
                                                ?>
                                                <div class="img_info">
                                                    <p class="bold">Profile Image</p>
                                                    <p class="subtitle">JPG, GIF or PNG Max 1MB</p>
                                                </div>
                                            </div>
                                            <label for="cover_photo" class="upload_btn">
                                                <input type="file" name="file" id="real-file" hidden="hidden" />
                                                <button type="button" id="custom-button" class="btn btn-sm btn-primary" aria-hidden="true">Upload Avatar</button>
                                                <p>
                                                    <span id="custom-text">(Tidak ada file.. )</span>
                                                </p>
                                            </label>
                                            <div class="col-md-12">
                                            <div class="dashboard_setting_btn">
                                                <button type="submit" name="upload" class="btn btn--md btn-primary">Simpan Foto</button>
                                            </div>
                                            </div><!-- ends: .col-md-12 -->
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div><!-- ends: .information_module -->
                        </div><!-- ends: .col-md-12 -->
                        <div class="col-md-12">
                            <div class="information_module">
                                <div class="toggle_title">
                                    <h4>informasi Pribadi</h4>
                                </div>
                                <div class="information__set">
                                    <form action="<?= $config['web']['base_url']; ?>edit-user/<?= $data_user['rows']['id'] ?>" class="setting_form" method="post">
                                    <div class="information_wrapper form--fields row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="acname">Nama
                                                    <sup>*</sup>
                                                </label>
                                                <input type="text" name="nama" id="acname" class="text_field" placeholder="First Name" value="<?= $data_user['rows']['nama'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="usrname">Username
                                                    <sup>*</sup>
                                                </label>
                                                <input type="text" name="username" id="usrname" class="text_field" placeholder="Account name" value="<?= $data_user['rows']['username'] ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="emailad">Email Address
                                                    <sup>*</sup>
                                                </label>
                                                <input type="text" name="email" id="emailad" class="text_field" value="<?= decrypt($data_user['rows']['email']) ?>" <?if($login['role'] != '2'){?> readonly<?}?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="prohead">Profesi</label>
                                                <input type="text" name="profesi" id="prohead" class="text_field" value="<?= $data_user['rows']['profesi'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="authbio">Bio</label>
                                                <textarea name="author_bio" id="authbio" class="text_field"><?= $data_user['rows']['bio'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="authbio">ID Telegram</label>
                                                <input type="number" name="telegram" value="<?= decrypt($data_user['rows']['telegram_id']) ?>" class="text_field">
                                                <span>Untuk Mendapatkan ID Telegram anda, Silahkan chat ke <a href="https://t.me/Gubukdigitalbot">Gubuk Digital Bot</a> dengan mengetik <code>/start</code></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <?
                                            if($data_user['rows']['terima_tele_pesan'] == '1'){
                                                $tampil = "Aktif";
                                                $value_selain = '0';
                                                $tampil_selain = 'Tidak Aktif';
                                            } else {
                                                $tampil = "Tidak Aktif";
                                                $value_selain = '1';
                                                $tampil_selain = 'Aktif';
                                            }
                                            ?>
                                                <label for="category">Notif Telegram Jika Ada Pesan Masuk</label>
                                                <div class="select-wrap select-wrap2">
                                                    <select name="terima_tele_pesan" class="text_field" required>
                                                        <option value="<?= $data_user['rows']['terima_tele_pesan']; ?>"><?= $tampil?></option>
                                                        <option value="<?= $value_selain; ?>"><?= $tampil_selain?></option>
                                                    </select>
                                                    <span class="lnr icon-arrow-down"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <?
                                            if($data_user['rows']['terima_tele_orderan'] == '1'){
                                                $tampil = "Aktif";
                                                $value_selain = '0';
                                                $tampil_selain = 'Tidak Aktif';
                                            } else {
                                                $tampil = "Tidak Aktif";
                                                $value_selain = '1';
                                                $tampil_selain = 'Aktif';
                                            }
                                            ?>
                                                <label for="category">Notif Telegram Jika Ada Orderan Masuk</label>
                                                <div class="select-wrap select-wrap2">
                                                    <select name="terima_tele_orderan" class="text_field" required>
                                                        <option value="<?= $data_user['rows']['terima_tele_orderan']; ?>"><?= $tampil?></option>
                                                        <option value="<?= $value_selain; ?>"><?= $tampil_selain?></option>
                                                    </select>
                                                    <span class="lnr icon-arrow-down"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="password">Password
                                                    <sup>*</sup>
                                                </label>
                                                <input type="password" name="password" id="password" class="text_field" placeholder="Di isi Jika Ingin Merubah Data">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="dashboard_setting_btn">
                                                <button type="submit" class="btn btn--md btn-primary">Save Change</button>
                                                <button type="reset" class="btn btn-md btn-danger">Reset</button>
                                            </div>
                                        </div><!-- ends: .col-md-12 -->
                                    </div><!-- ends: .information_wrapper -->
                                    </form>
                                </div><!-- ends: .information__set -->
                            </div><!-- ends: .information_module -->
                        </div><!-- ends: .col-md-12 -->
                        <div class="col-md-12">
                            <div class="information_module">
                                <div class="toggle_title">
                                    <h4>Ubah Password</h4>
                                </div>
                                <div class="information__set">
                                    <form action="<?= $config['web']['base_url']; ?>reset-pasword/<?= $data_user['rows']['id'] ?>" class="setting_form" method="post">
                                    <div class="information_wrapper form--fields row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="password">Password Lama
                                                    <sup>*</sup>
                                                </label>
                                                <input type="password" name="old_password" id="password" class="text_field" placeholder="Old Password">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="password">Password Baru
                                                    <sup>*</sup>
                                                </label>
                                                <input type="password" name="new_password" id="password" class="text_field" placeholder="New Passowrd">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="password">Konfirmasi Password
                                                    <sup>*</sup>
                                                </label>
                                                <input type="password" name="con_password" id="con_password" class="text_field" placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                        <div class="dashboard_setting_btn">
                                            <button type="submit" class="btn btn--md btn-primary">Save Change</button>
                                            <button type="reset" class="btn btn-md btn-danger">Cancel</button>
                                        </div>
                                    </div><!-- ends: .col-md-12 -->
                                    </div><!-- ends: .information_wrapper -->
                                    </form>
                                </div><!-- ends: .information__set -->
                                
                            </div><!-- ends: .information_module -->
                        </div><!-- ends: .col-md-12 -->
                    </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
    </section><!-- ends: .dashboard-area -->
    <script>
    const realFileBtn = document.getElementById("real-file");
    const customBtn = document.getElementById("custom-button");
    const customTxt = document.getElementById("custom-text");
    
    customBtn.addEventListener("click", function() {
      realFileBtn.click();
    });
    
    realFileBtn.addEventListener("change", function() {
      if (realFileBtn.value) {
        customTxt.innerHTML = realFileBtn.value.match(
          /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1];
      } else {
        customTxt.innerHTML = "(Tidak ada file.. )";
      }
    });

    
</script>

   <?php
require '../template/footer.php';

?>