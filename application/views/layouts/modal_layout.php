<?php echo $template['partials']['styles'] ?>
<div class="modal-dialog" style="z-index: inherit;">
<div class="modal-content panel-warning">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?php echo $page_title; ?></h4>
  </div>
  <div class="modal-body">
    <?php echo $template['body'] ?>
  </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script src="<?php echo site_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?php echo site_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<?php echo $template['partials']['footer'] ?>
