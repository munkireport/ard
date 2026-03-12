<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/ard/ard" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/ard/ard_report" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2><i class="fa fa-desktop"></i> <span data-i18n="ard.ard"></span></h2>
<div id="ard-tab"></div>

<div id="ard-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
    // Handle ARD Tab data
    var $ardMsg = $('#ard-msg');
    var $ardTab = $('#ard-tab');

    $.getJSON(appUrl + '/module/ard/get_tab_data/' + serialNumber, function(data){
        
        if (!data) {
            $ardMsg.text(i18n.t('no_data'));
        } else {
            $ardMsg.text('');
            $('#ard-count-view').removeClass('hide');

            var skipThese = ['id', 'serial_number', 'admin_machines'];
            var rows = '';
            var administratorsRows = '';
            var taskServersRows = '';
            var textRows = {}; // Store text rows in an object keyed by text number

            $.each(data, function(i, d){
                for (var prop in d) {
                    if (skipThese.indexOf(prop) !== -1 || d[prop] === '' || d[prop] === null || d[prop] === "{}") {
                        continue;
                    }

                    var value = d[prop];
                    var translatedProp = i18n.t('ard.' + prop);
                    
                    // Store text1-4 fields separately to add them at the end
                    if (/^text([1-4])$/.test(prop)) {
                        var textNum = prop.match(/^text([1-4])$/)[1]; // Extract the number
                        textRows[textNum] = '<tr><th style="width: 165px;">' + translatedProp + '</th><td style="max-width: 500px;">' + value + '</td></tr>';
                        continue; // Skip further processing of this property
                    }

                    if (['screensharing_request_permission', 'AdminConsoleAllowsRemoteControl', 'console_allows_remote', 'allow_all_local_users', 'directory_login'].includes(prop)) {
                        value = value == "yes" || value == 1 ? 1 : 0;

                        // Special handling for remote control console fields
                        if (prop === 'AdminConsoleAllowsRemoteControl' || prop === 'console_allows_remote') {
                            translatedProp = i18n.t('ard.remote_control_enabled');
                        }

                        rows += '<tr><th>' + translatedProp + '</th><td>' + 
                            (value == 1 ? 
                                '<span class="label label-danger">' + i18n.t('yes') + '</span>' : 
                                '<span class="label label-success">' + i18n.t('no') + '</span>') + 
                            '</td></tr>';
                    } else if (prop === 'load_menu_extra') {
                        value = value == "yes" || value == 1 ? 1 : 0;
                        rows += '<tr><th>' + translatedProp + '</th><td>' + i18n.t(value == 1 ? 'yes' : 'no') + '</td></tr>';
                    } else if (prop === 'vnc_enabled') {
                        value = value == "yes" || value == 1 ? 1 : 0;
                        rows += '<tr><th>' + translatedProp + '</th><td>' + 
                            (value == 1 ? 
                                '<span class="label label-danger">' + i18n.t('enabled') + '</span>' : 
                                '<span class="label label-success">' + i18n.t('disabled') + '</span>') + 
                            '</td></tr>';
                    } else if (prop === 'administrators') {
                        administratorsRows = buildAdministratorsTable(JSON.parse(value));
                    } else if (prop === 'task_servers') {
                        taskServersRows = buildTaskServersTable(JSON.parse(value));
                    } else {
                        rows += '<tr><th style="width: 165px;">' + translatedProp + '</th><td style="max-width: 500px;">' + value + '</td></tr>';
                    }
                }
            });
            
            // Add text rows at the end in order
            for (var i = 1; i <= 4; i++) {
                if (textRows[i]) {
                    rows += textRows[i];
                }
            }

            $ardTab.append(createTable(rows, 500));

            if (administratorsRows) {
                $ardTab.append('<h4>' + i18n.t('ard.administrators') + '</h4>')
                       .append(createTable(administratorsRows, 985));
            } else {
                $ardTab.append('<br>');
            }

            if (taskServersRows) {
                $ardTab.append('<h4>' + i18n.t('ard.task_servers') + '</h4>')
                       .append(createTable(taskServersRows, 600));
            } else {
                $ardTab.append('<br>');
            }
        }
    });

    // Handle ARD Detail Widget data
    // The displayArdData function is in ard.js

    // Also add ARD data display functionality directly in tab if needed
    $.getJSON(appUrl + '/module/ard/get_data/' + serialNumber, function(data) {
        $.each(data, function(index, item) {
            if (/^text[\d]$/.test(index)) {
                // If the ard-data table exists in the tab, populate it
                if ($('#ard-tab #ard-data table').length) {
                    $('#ard-tab #ard-data table')
                        .append($('<tr>')
                            .append($('<th>')
                                .text(index.replace("text", "ARD " + i18n.t("text") + " ")))
                            .append($('<td>')
                                .text(item)));
                }
            }
        });
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
