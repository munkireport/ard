<?php

use CFPropertyList\CFPropertyList;

class Ard_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'ard'); //primary key, tablename
        $this->rs['id'] = "";
        $this->rs['serial_number'] = $serial;
        $this->rs['text1'] = '';
        $this->rs['text2'] = '';
        $this->rs['text3'] = '';
        $this->rs['text4'] = '';
        $this->rs['console_allows_remote'] = ''; // True/False
        $this->rs['load_menu_extra'] = ''; // True/False
        $this->rs['screensharing_request_permission'] = ''; // True/False
        $this->rs['vnc_enabled'] = ''; // True/False
        $this->rs['allow_all_local_users'] = ''; // True/False
        $this->rs['directory_login'] = ''; // True/False
        $this->rs['admin_machines'] = '';
        $this->rs['administrators'] = '';
        $this->rs['task_servers'] = '';
        
        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial_number = $serial;
    }
}
