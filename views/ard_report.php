<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">
    
  <div class="row">
    <?php $widget->view($this, 'ard_directory_login'); ?>
    <?php $widget->view($this, 'ard_allow_all_local_users'); ?>
    <?php $widget->view($this, 'ard_vnc_enabled'); ?>
  </div> <!-- /row -->
    
  <div class="row">
    <?php $widget->view($this, 'ard_screensharing_request_permission'); ?>
    <?php $widget->view($this, 'ard_load_menu_extra'); ?>
  </div> <!-- /row -->

</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
