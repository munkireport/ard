
<h2 data-i18n="ard.ard"></h2>
<div id="ard-tab"></div>

<div id="ard-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/ard/get_tab_data/' + serialNumber, function(data){
        
        if( ! data ){
            $('#ard-msg').text(i18n.t('no_data'));
        } else {
            
            // Hide
            $('#ard-msg').text('');
            $('#ard-count-view').removeClass('hide');

            var skipThese = ['id', 'serial_number','admin_machines'];
            $.each(data, function(i,d){

                // Generate rows from data
                var rows = ''
                var administrators_rows = ''
                var task_servers_rows = ''
                for (var prop in d){
                    // Skip skipThese
                    if(skipThese.indexOf(prop) == -1){
                        if (d[prop] == '' || d[prop] == null || d[prop] == "{}"){
                        // Do nothing for empty values to blank them

                        } else if((prop == 'screensharing_request_permission' || prop == 'load_menu_extra' || prop == 'console_allows_remote' || prop == 'allow_all_local_users' || prop == 'directory_login') && (d[prop] == "yes" || d[prop] == 1)){
                           rows = rows + '<tr><th>'+i18n.t('ard.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                        }
                        else if((prop == 'screensharing_request_permission' || prop == 'load_menu_extra' || prop == 'console_allows_remote' || prop == 'allow_all_local_users' || prop == 'directory_login') && (d[prop] == "no" || d[prop] == 0)){
                           rows = rows + '<tr><th>'+i18n.t('ard.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';
                        }
                        
                        else if((prop == 'vnc_enabled') && (d[prop] == "yes" || d[prop] == 1)){
                           rows = rows + '<tr><th>'+i18n.t('ard.'+prop)+'</th><td>'+i18n.t('enabled')+'</td></tr>';
                        }
                        else if((prop == 'vnc_enabled') && (d[prop] == "no" || d[prop] == 0)){
                           rows = rows + '<tr><th>'+i18n.t('ard.'+prop)+'</th><td>'+i18n.t('disabled')+'</td></tr>';
                        }
                        
                        // Else if build out the administrators table
                        else if(prop == "administrators"){
                            var administrators_data = JSON.parse(d['administrators']);
                            administrators_rows = '<tr><th style="max-width: 140px;">'+i18n.t('ard.computername')+'</th><th style="max-width: 85px;">'+i18n.t('ard.has_latest_reporting_info')+'</th><th style="max-width: 60px;">'+i18n.t('ard.mac_address')+'</th><th style="max-width: 300px;">'+i18n.t('ard.task_server_id')+'</th><th style="max-width: 300px;">'+i18n.t('ard.ip_address')+'</th><th style="max-width: 300px;">'+i18n.t('ard.port')+'</th><th style="max-width: 300px;">'+i18n.t('ard.last_contact')+'</th></tr>'
                            $.each(administrators_data, function(i,d){
                                if (typeof d['HasLatestReportingInfo'] !== "undefined" && d['HasLatestReportingInfo'] == 1) {
                                        var has_latest_reporting_info = i18n.t('yes')
                                    } else if (typeof d['HasLatestReportingInfo'] !== "undefined" && d['HasLatestReportingInfo'] == 0) {
                                        var has_latest_reporting_info = i18n.t('yes')
                                    } else {
                                        var has_latest_reporting_info = ""
                                    }
                                if (typeof d['LastContact'] !== "undefined") {
                                        var last_contact = d['LastContact']
                                    } else {
                                        var last_contact = ""
                                    }
                                
                                if (typeof d['LastContact'] !== "undefined") {var date_1 = new Date(d['LastContact'] * 1000); last_contact = '<span title="'+moment(date_1).fromNow()+'">'+moment(date_1).format('llll')} else {var last_contact = ""}
                                if (typeof d['ComputerName'] !== "undefined") {var ard_name = d['ComputerName']} else {var ard_name = ""}
                                if (typeof d['MAC_address'] !== "undefined") {var mac_address = d['MAC_address']} else {var mac_address = ""}
                                if (typeof d['TaskServerID'] !== "undefined") {var task_server_id = d['TaskServerID']} else {var task_server_id = ""}
                                if (typeof d['IPAddress'] !== "undefined") {var ip_address = d['IPAddress']} else {var ip_address = ""}
                                if (typeof d['Port'] !== "undefined") {var port = d['Port']} else {var port = ""}
                                // Generate rows from data
                                administrators_rows = administrators_rows + '<tr><td>'+ard_name+'</td><td>'+has_latest_reporting_info+'</td><td>'+mac_address+'</td><td>'+task_server_id+'</td><td>'+ip_address+'</td><td>'+port+'</td><td>'+last_contact+'</td></tr>';
                            })
                            administrators_rows = administrators_rows // Close administrators table framework
                        }
                        
                        // Else if build out the task servers table
                        else if(prop == "task_servers"){
                            var task_servers_data = JSON.parse(d['task_servers']);
                            task_servers_rows = '<tr><th style="max-width: 240px;">'+i18n.t('ard.dns_name')+'</th><th style="max-width: 60px;">'+i18n.t('ard.mac_address')+'</th><th style="max-width: 300px;">'+i18n.t('ard.ip_address')+'</th><th style="max-width: 300px;">'+i18n.t('ard.port')+'</th></tr>'
                            $.each(task_servers_data, function(i,d){
                                if (typeof d['DNSName'] !== "undefined") {var dns_name = d['DNSName']} else {var dns_name = ""}
                                if (typeof d['MAC_address'] !== "undefined") {var mac_address = d['MAC_address']} else {var mac_address = ""}
                                if (typeof d['IPAddress'] !== "undefined") {var ip_address = d['IPAddress']} else {var ip_address = ""}
                                if (typeof d['Port'] !== "undefined") {var port = d['Port']} else {var port = ""}
                                // Generate rows from data
                                task_servers_rows = task_servers_rows + '<tr><td>'+dns_name+'</td><td>'+mac_address+'</td><td>'+ip_address+'</td><td>'+port+'</td></tr>';
                            })
                            task_servers_rows = task_servers_rows // Close task servers table framework
                        }

                        else {
                        rows = rows + '<tr><th style="width: 165px;">'+i18n.t('ard.'+prop)+'</th><td style="max-width: 500px;">'+d[prop]+'</td></tr>';
                        }
                    }
                }
                
                $('#ard-tab')
                    .append($('<div>')
                        .append($('<table style="width: 500px;">')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
                
                // Only draw the administrators table if there is something in it
                if (administrators_rows !== ""){
                    $('#ard-tab')
                        .append($('<h4>')
                            .append(" "+i18n.t('ard.administrators')))
                        .append($('<div>')
                            .append($('<table style="width: 985px;">')
                                .addClass('table table-striped table-condensed')
                                .append($('<tbody>')
                                    .append(administrators_rows))))
                } else {
                    $('#ard-tab')
                        .append($('<br>'))
                }
                
                // Only draw the task_servers table if there is something in it
                if (task_servers_rows !== ""){
                    $('#ard-tab')
                        .append($('<h4>')
                            .append(" "+i18n.t('ard.task_servers')))
                        .append($('<div>')
                            .append($('<table style="width: 600px;">')
                                .addClass('table table-striped table-condensed')
                                .append($('<tbody>')
                                    .append(task_servers_rows))))
                } else {
                    $('#ard-tab')
                        .append($('<br>'))
                }
            })
        }
    });
});
</script>
