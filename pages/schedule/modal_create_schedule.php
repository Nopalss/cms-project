 <!-- Modal-->
 <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-labelledby="staticBackdrop" aria-hidden="true">
     <div class="modal-dialog " role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Create New Schedule</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <i aria-hidden="true" class="ki ki-close"></i>
                 </button>
             </div>
             <form method="post" class="form" action="<?= BASE_URL ?>controllers/schedules/create.php">
                 <div class="modal-body">
                     <div class="form-group">
                         <label>Teknisi</label>
                         <select class="form-control selectpicker" id="tech_id" required name="tech_id" data-size=" 7" data-live-search="true">
                             <option value="">Select</option>
                             <?php foreach ($technicians as $t): ?>
                                 <option value="<?= $t['tech_id'] ?>"><?= $t['name'] ?></option>
                             <?php endforeach; ?>
                         </select>
                     </div>
                     <div class="form-group">
                         <label class="text-right">Tanggal</label>
                         <div>
                             <div class="input-group date">
                                 <input type="date" class="form-control" required name="date" id="date" min="<?= date('Y-m-d') ?>" />
                             </div>
                         </div>
                     </div>
                     <div class="form-group">
                         <label class="">Jam</label>
                         <div>
                             <select class="form-control selectpicker" required name="time" data-size="7" id="time">
                             </select>
                         </div>
                     </div>
                     <div class="form-group">
                         <label>Tipe Job</label>
                         <select class="form-control selectpicker" required name="job_type" data-size="7">
                             <option value="">--Select--</option>
                             <option value="Instalasi">Instalasi</option>
                             <option value="Maintenance">Maintenance</option>
                             <option value="Perbaikan">Perbaikan</option>
                         </select>
                     </div>
                     <div class="form-group mb-1">
                         <label for="exampleTextarea">Alamat</label>
                         <textarea class="form-control" id="exampleTextarea" required name="location" rows="3"></textarea>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="reset" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel</button>
                     <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Create</button>
                 </div>
             </form>
         </div>
     </div>
 </div>