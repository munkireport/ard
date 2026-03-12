// ARD module functions

/**
 * Format ARD binary values with appropriate classes
 */
var formatArdBinary = function(col, row){
    var cell = $('td:eq('+col+')', row),
        value = cell.text()
    value = value == '1' ? mr.label(i18n.t('yes'), 'danger') :
        (value === '0' ? mr.label(i18n.t('no'), 'success') : '')
    cell.html(value)
}

/**
 * Format ARD binary values with inverted classes (success for yes)
 */
var formatArdBinaryInverted = function(col, row){
    var cell = $('td:eq('+col+')', row),
        value = cell.text()
    value = value == '1' ? mr.label(i18n.t('yes'), 'success') :
        (value === '0' ? mr.label(i18n.t('no'), 'danger') : '')
    cell.html(value)
}

/**
 * Remote control enabled filter
 */
var console_allows_remote_filter = function(colNumber, d) {
    if (d.search.value.match(/^console_remote_yes$/)) {
        d.columns[colNumber].search.value = '1';
        d.search.value = '';
    }
    
    if (d.search.value.match(/^console_remote_no$/)) {
        d.columns[colNumber].search.value = '0';
        d.search.value = '';
    }
}

/**
 * Directory login filter
 */
var directory_login_filter = function(colNumber, d) {
    if (d.search.value.match(/^directory_yes$/)) {
        d.columns[colNumber].search.value = '1';
        d.search.value = '';
    }
    
    if (d.search.value.match(/^directory_no$/)) {
        d.columns[colNumber].search.value = '0';
        d.search.value = '';
    }
}

/**
 * VNC enabled filter
 */
var vnc_enabled_filter = function(colNumber, d) {
    if (d.search.value.match(/^vnc_yes$/)) {
        d.columns[colNumber].search.value = '1';
        d.search.value = '';
    }
    
    if (d.search.value.match(/^vnc_no$/)) {
        d.columns[colNumber].search.value = '0';
        d.search.value = '';
    }
}

/**
 * Allow all local users filter
 */
var allow_all_local_users_filter = function(colNumber, d) {
    if (d.search.value.match(/^local_users_yes$/)) {
        d.columns[colNumber].search.value = '1';
        d.search.value = '';
    }
    
    if (d.search.value.match(/^local_users_no$/)) {
        d.columns[colNumber].search.value = '0';
        d.search.value = '';
    }
}

/**
 * Screensharing request permission filter
 */
var screensharing_request_permission_filter = function(colNumber, d) {
    if (d.search.value.match(/^screensharing_yes$/)) {
        d.columns[colNumber].search.value = '1';
        d.search.value = '';
    }
    
    if (d.search.value.match(/^screensharing_no$/)) {
        d.columns[colNumber].search.value = '0';
        d.search.value = '';
    }
}

/**
 * Load menu extra filter
 */
var load_menu_extra_filter = function(colNumber, d) {
    if (d.search.value.match(/^menu_extra_yes$/)) {
        d.columns[colNumber].search.value = '1';
        d.search.value = '';
    }
    
    if (d.search.value.match(/^menu_extra_no$/)) {
        d.columns[colNumber].search.value = '0';
        d.search.value = '';
    }
}

