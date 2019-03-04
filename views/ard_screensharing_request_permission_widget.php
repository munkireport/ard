<div class="col-lg-4 col-md-6">
    <div class="panel panel-default" id="ard_screensharing_request_permission-widget">
        <div id="ard_screensharing_request_permission-widget" class="panel-heading" data-container="body">
            <h3 class="panel-title"><i class="fa fa-clone"></i> 
                <span data-i18n="ard.screensharing_request_permission"></span>
                <list-link data-url="/show/listing/ard/ard"></list-link>
            </h3>
        </div>
        <div class="panel-body text-center"></div>
    </div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/ard/get_screensharing_request_permission', function( data ) {
        if(data.error){
            //alert(data.error);
            return;
        }

        var panel = $('#ard_screensharing_request_permission-widget div.panel-body'),
        baseUrl = appUrl + '/show/listing/ard/ard/';
        panel.empty();
        // Set blocks, disable if zero
        if(data.no != "0"){
            panel.append(' <a href="'+baseUrl+'" class="btn btn-danger"><span class="bigger-150">'+data.no+'</span><br>&nbsp;&nbsp;&nbsp;'+i18n.t('no')+'&nbsp;&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-danger disabled"><span class="bigger-150">'+data.no+'</span><br>&nbsp;&nbsp;&nbsp;'+i18n.t('no')+'&nbsp;&nbsp;&nbsp;</a>');
        }
        if(data.yes != "0"){
            panel.append(' <a href="'+baseUrl+'" class="btn btn-success"><span class="bigger-150">'+data.yes+'</span><br>&nbsp;&nbsp;'+i18n.t('yes')+'&nbsp;&nbsp;</a>');
        } else {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-success disabled"><span class="bigger-150">'+data.yes+'</span><br>&nbsp;&nbsp;'+i18n.t('yes')+'&nbsp;&nbsp;</a>');
        }
    });
});

</script>
