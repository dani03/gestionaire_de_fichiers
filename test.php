<div class="modal fade" id="modalDeplacement_<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">deplacez le fichier: "<i><small><?= $fich ?></small></i>" dans :</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php foreach ($directory as $key => $ledossier) :
          if (is_dir("$chemin_cloud/$ledossier")) : ?>
            <label class="btn btn-outline-info">
              <input type="radio" name="options" id="option2" autocomplete="off"> <?= $ledossier ?>
            </label>
        <?php endif;
        endforeach ?>

      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">annuler</button>
        <button class="btn btn-info" name="deplacement" type="submit"><i style="color:green;" class="fas fa-file-export"></i>deplacer</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------------ -->


<div class="modal fade" id="modalDeplacement_<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">deplacez le fichier: "<i><small><?= $fich ?></small></i>" dans :</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php foreach ($directory as $key => $ledossier) :
          if (is_dir("$chemin_cloud/$ledossier")) : ?>
            <label class="btn btn-outline-info">
              <input type="radio" name="options" id="option2" autocomplete="off"> <?= $ledossier ?>
            </label>
        <?php endif;
        endforeach ?>

      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">annuler</button>
        <button class="btn btn-info" name="deplacement" type="submit"><i style="color:green;" class="fas fa-file-export"></i>deplacer</button>
      </div>
    </div>
  </div>
</div>