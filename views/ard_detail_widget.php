<div class="col-lg-4">
    <h4><i class="fa fa-apple"></i> <span data-i18n="ard.ard"></span></h4>
    <table id="ard-data" class="table"></table>
</div>

<script>

$(document).on('appReady', function(){

	// Get ARD data
	$.getJSON( appUrl + '/module/ard/get_data/' + serialNumber, function( data ) {
		$.each(data, function(index, item){
			if(/^text[\d]$/.test(index))
			{
				$('#ard-data')
					.append($('<tr>')
						.append($('<th>')
							.text(index.replace("text","ARD "+i18n.t("text")+" ")))
						.append($('<td>')
							.text(item)));
			}
		});
    });
});

</script>