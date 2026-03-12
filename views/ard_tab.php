
<h2 data-i18n="ard.ard"></h2>
<div id="ard-tab"></div>

<div id="ard-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
    // Handle ARD Tab data
    $.getJSON(appUrl + '/module/ard/get_tab_data/' + serialNumber, function(data){
        // Check if we have valid data
        if (! data ) {
            $('#ard-msg').text(i18n.t('no_data'));
        } else {
            $('#ard-msg').text('');
            $('#ard-count-view').removeClass('hide');

            var skipThese = ['id', 'serial_number', 'admin_machines'];
            var rows = '';
            var administratorsRows = '';
            var taskServersRows = '';
            var textRows = {}; // Store text rows in an object keyed by text number
            var textRowsClient = ""; // Store text rows for the client detail

            $.each(data, function(i, d){
                for (var prop in d) {
                    if (skipThese.indexOf(prop) !== -1 || d[prop] === '' || d[prop] === null || d[prop] === "{}") {
                        continue;
                    }

                    // Store text1-4 fields separately to add them at the end
                    if (/^text([1-4])$/.test(prop)) {
                        var textNum = prop.match(/^text([1-4])$/)[1]; // Extract the number
                        textRows[textNum] = '<tr><th style="width: 165px;">' + i18n.t('ard.' + prop) + '</th><td style="max-width: 500px;">' + d[prop] + '</td></tr>';

                        // Handle ARD Detail Widget data
                        if(/^text[\d]$/.test(prop)){
                            $('#ard-data table')
                                .append($('<tr>')
                                    .append($('<th>')
                                        .text(prop.replace("text","ARD "+i18n.t("text")+" ")))
                                    .append($('<td>')
                                        .text(d[prop])));
                        }

                        continue; // Skip further processing of this property
                    }

                    if (['screensharing_request_permission', 'console_allows_remote', 'allow_all_local_users', 'directory_login', 'load_menu_extra'].includes(prop)) {
                        d[prop] = d[prop] == "yes" || d[prop] == 1 ? 1 : 0;

                        rows += '<tr><th>' + i18n.t('ard.' + prop) + '</th><td>' + 
                            (d[prop] == 1 ? 
                                '<span class="label label-success">' + i18n.t('yes') + '</span>' : 
                                '<span class="label label-danger">' + i18n.t('no') + '</span>') + 
                            '</td></tr>';
                    } else if (prop === 'load_menu_extra') {
                        d[prop] = d[prop] == "yes" || d[prop] == 1 ? 1 : 0;
                        rows += '<tr><th>' + i18n.t('ard.' + prop) + '</th><td>' + i18n.t(d[prop] == 1 ? 'yes' : 'no') + '</td></tr>';
                    } else if (prop === 'vnc_enabled') {
                        d[prop] = d[prop] == "yes" || d[prop] == 1 ? 1 : 0;
                        rows += '<tr><th>' + i18n.t('ard.' + prop) + '</th><td>' + 
                            (d[prop] == 1 ? 
                                '<span class="label label-success">' + i18n.t('enabled') + '</span>' : 
                                '<span class="label label-danger">' + i18n.t('disabled') + '</span>') + 
                            '</td></tr>';
                    } else if (prop === 'administrators') {
                        administratorsRows = buildAdministratorsTable(JSON.parse(d[prop]));
                    } else if (prop === 'task_servers') {
                        taskServersRows = buildTaskServersTable(JSON.parse(d[prop]));
                    } else {
                        rows += '<tr><th style="width: 165px;">' + i18n.t('ard.' + prop) + '</th><td style="max-width: 500px;">' + d[prop] + '</td></tr>';
                    }
                }
            });

            // Add text rows at the end in order
            for (var i = 1; i <= 4; i++) {
                if (textRows[i]) {
                    rows += textRows[i];
                }
            }

            $('#ard-tab').append(createTable(rows, 500));

            if (administratorsRows) {
                $('#ard-tab').append('<h4>' + i18n.t('ard.administrators') + '</h4>')
                       .append(createTable(administratorsRows, 985));
            } else {
                $('#ard-tab').append('<br>');
            }

            if (taskServersRows) {
                $('#ard-tab').append('<h4>' + i18n.t('ard.task_servers') + '</h4>')
                       .append(createTable(taskServersRows, 600));
            } else {
               $('#ard-tab').append('<br>');
            }
        }
    });

    function createTable(rows, width) {
        return $('<div>').append($('<table style="width: ' + width + 'px;">')
            .addClass('table table-striped table-condensed')
            .append($('<tbody>').append(rows)));
    }

    function buildAdministratorsTable(data) {
        var rows = '<tr><th style="max-width: 140px;">' + i18n.t('ard.computername') + '</th><th style="max-width: 85px;">' + i18n.t('ard.has_latest_reporting_info') + '</th><th style="max-width: 60px;">' + i18n.t('ard.mac_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.task_server_id') + '</th><th style="max-width: 300px;">' + i18n.t('ard.ip_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.port') + '</th><th style="max-width: 300px;">' + i18n.t('ard.last_contact') + '</th></tr>';
        $.each(data, function(i, d) {
            var hasLatestReportingInfo = d['HasLatestReportingInfo'] == 1 ? i18n.t('yes') : i18n.t('no');
            var lastContact = d['LastContact'] ? '<span title="' + moment(new Date(d['LastContact'] * 1000)).fromNow() + '">' + moment(new Date(d['LastContact'] * 1000)).format('llll') + '</span>' : '';
            rows += '<tr><td>' + (d['ComputerName'] || '') + '</td><td>' + hasLatestReportingInfo + '</td><td>' + (d['MAC_address'] || '') + '</td><td>' + (d['TaskServerID'] || '') + '</td><td>' + (d['IPAddress'] || '') + '</td><td>' + (d['Port'] || '') + '</td><td>' + lastContact + '</td></tr>';
        });
        return rows;
    }

    function buildTaskServersTable(data) {
        var rows = '<tr><th style="max-width: 240px;">' + i18n.t('ard.dns_name') + '</th><th style="max-width: 60px;">' + i18n.t('ard.mac_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.ip_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.port') + '</th></tr>';
        $.each(data, function(i, d) {
            rows += '<tr><td>' + (d['DNSName'] || '') + '</td><td>' + (d['MAC_address'] || '') + '</td><td>' + (d['IPAddress'] || '') + '</td><td>' + (d['Port'] || '') + '</td></tr>';
        });
        return rows;
    }
});
</script>
