$(document).on('appReady', function(){

	// Get ARD data
	$.getJSON( appUrl + '/module/ard/get_data/' + serialNumber, function( data ) {
		$.each(data, function(index, item){
			if(/^text[\d]$/.test(index))
			{
				$('#ard-data table')
					.append($('<tr>')
						.append($('<th>')
							.text(index.replace("text","ARD "+i18n.t("text")+" ")))
						.append($('<td>')
							.text(item)));
			}
		});
    });
});
