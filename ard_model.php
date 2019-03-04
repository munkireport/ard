<?php

use CFPropertyList\CFPropertyList;

class Ard_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'ard'); //primary key, tablename
        $this->rs['id'] = 0;
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

    public function process($data)
    {
        $parser = new CFPropertyList();
        $parser->parse($data);
        $plist = $parser->toArray();

        foreach (array('text1', 'text2', 'text3', 'text4', 'console_allows_remote', 'load_menu_extra', 'screensharing_request_permission', 'vnc_enabled', 'allow_all_local_users', 'directory_login', 'admin_machines', 'administrators', 'task_servers') as $item) {
            // If key exists and is zero, set it to zero
            if ( array_key_exists($item, $plist) && $plist[$item] === 0) {
                $this->$item = 0;
            // Else if key does not exist in $plist, null it
            } else if (! array_key_exists($item, $plist) || $plist[$item] == '' || $plist[$item] == "{}") {
                $this->$item = null;

            // Set the db fields to be the same as those in the preference file
            } else {
                $this->$item = $plist[$item];
            }
        }
        
        // Save the data. Just like Lassie saved Timmy
        $this->save();
    }
}
