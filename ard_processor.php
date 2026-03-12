<?php

use CFPropertyList\CFPropertyList;
use munkireport\processors\Processor;

class Ard_processor extends Processor
{
    /**
     * Process data sent by postflight
     *
     * @param string data
     **/
    public function run($data)
    {
        // Check if we have data
        if (!$data) {
            throw new Exception("Error Processing Request: No property list found", 1);
        }

        $parser = new CFPropertyList();
        $parser->parse($data, CFPropertyList::FORMAT_XML);
        $plist = $parser->toArray();

        // Delete previous entry and create new one
        $model = new Ard_model();
        $model->serial_number = $this->serial_number;
        $model->deleteWhere('serial_number=?', $this->serial_number);

        // Process fields
        foreach (array('text1', 'text2', 'text3', 'text4', 'console_allows_remote', 'load_menu_extra', 
                       'screensharing_request_permission', 'vnc_enabled', 'allow_all_local_users', 
                       'directory_login', 'admin_machines', 'administrators', 'task_servers') as $item) {
            
            // If key exists and is zero, set it to zero
            if (array_key_exists($item, $plist) && $plist[$item] === 0) {
                $model->$item = 0;
            // Else if key does not exist in $plist, null it
            } else if (!array_key_exists($item, $plist) || $plist[$item] == '' || $plist[$item] == "{}") {
                $model->$item = null;
            // Set the db fields to be the same as those in the preference file
            } else {
                $model->$item = $plist[$item];
            }
        }
        
        // Save the model
        $model->save();
    }
} 