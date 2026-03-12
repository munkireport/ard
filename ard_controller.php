<?php

/**
 * Ard_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Ard_controller extends Module_controller
{
    public function __construct()
    {
        $this->module_path = dirname(__FILE__);
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the ard module!";
    }
        
    /**
    * Retrieve directory_login in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_directory_login()
    {
        if (! $this->authorized()) {
            jsonView(['error' => 'Not authorized']);
            return;
        }
  
        $sql = "SELECT COUNT(CASE WHEN `directory_login` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `directory_login` = 0 THEN 1 END) AS 'no'
                FROM ard
                LEFT JOIN reportdata USING (serial_number)
                WHERE ".get_machine_group_filter('');
        
        $out = [];
        $queryobj = new Ard_model();
        foreach(current($queryobj->query($sql)) as $label => $value){
            $out[] = ['label' => $label, 'count' => intval($value)];
        }
        
        jsonView($out);
    }
           
    /**
    * Retrieve allow_all_local_users in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_allow_all_local_users()
    {
        if (! $this->authorized()) {
            jsonView(['error' => 'Not authorized']);
            return;
        }
  
        $sql = "SELECT COUNT(CASE WHEN `allow_all_local_users` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `allow_all_local_users` = 0 THEN 1 END) AS 'no'
                FROM ard
                LEFT JOIN reportdata USING (serial_number)
                WHERE ".get_machine_group_filter('');
        
        $out = [];
        $queryobj = new Ard_model();
        foreach(current($queryobj->query($sql)) as $label => $value){
            $out[] = ['label' => $label, 'count' => intval($value)];
        }
        
        jsonView($out);
    }
           
    /**
    * Retrieve vnc_enabled in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_vnc_enabled()
    {
        if (! $this->authorized()) {
            jsonView(['error' => 'Not authorized']);
            return;
        }
  
        $sql = "SELECT COUNT(CASE WHEN `vnc_enabled` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `vnc_enabled` = 0 THEN 1 END) AS 'no'
                FROM ard
                LEFT JOIN reportdata USING (serial_number)
                WHERE ".get_machine_group_filter('');
        
        $out = [];
        $queryobj = new Ard_model();
        foreach(current($queryobj->query($sql)) as $label => $value){
            $out[] = ['label' => $label, 'count' => intval($value)];
        }
        
        jsonView($out);
    }
            
    /**
    * Retrieve screensharing_request_permission in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_screensharing_request_permission()
    {
        if (! $this->authorized()) {
            jsonView(['error' => 'Not authorized']);
            return;
        }
  
        $sql = "SELECT COUNT(CASE WHEN `screensharing_request_permission` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `screensharing_request_permission` = 0 THEN 1 END) AS 'no'
                FROM ard
                LEFT JOIN reportdata USING (serial_number)
                WHERE ".get_machine_group_filter('');
        
        $out = [];
        $queryobj = new Ard_model();
        foreach(current($queryobj->query($sql)) as $label => $value){
            $out[] = ['label' => $label, 'count' => intval($value)];
        }
        
        jsonView($out);
    }
    
    /**
    * Retrieve load_menu_extra in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_load_menu_extra()
    {
        if (! $this->authorized()) {
            jsonView(['error' => 'Not authorized']);
            return;
        }
  
        $sql = "SELECT COUNT(CASE WHEN `load_menu_extra` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `load_menu_extra` = 0 THEN 1 END) AS 'no'
                FROM ard
                LEFT JOIN reportdata USING (serial_number)
                WHERE ".get_machine_group_filter('');
        
        $out = [];
        $queryobj = new Ard_model();
        foreach(current($queryobj->query($sql)) as $label => $value){
            $out[] = ['label' => $label, 'count' => intval($value)];
        }
        
        jsonView($out);
    }

    /**
    * Retrieve console_allows_remote in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_console_allows_remote()
    {
        if (! $this->authorized()) {
            jsonView(['error' => 'Not authorized']);
            return;
        }
  
        $sql = "SELECT COUNT(CASE WHEN `console_allows_remote` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `console_allows_remote` = 0 THEN 1 END) AS 'no'
                FROM ard
                LEFT JOIN reportdata USING (serial_number)
                WHERE ".get_machine_group_filter('');
        
        $out = [];
        $queryobj = new Ard_model();
        foreach(current($queryobj->query($sql)) as $label => $value){
            $out[] = ['label' => $label, 'count' => intval($value)];
        }
        
        jsonView($out);
    }

    /**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial_number = '')
    {
        // Remove non-serial number characters
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]/", '', $serial_number);
        
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $sql = "SELECT *
                FROM ard 
                WHERE serial_number = '$serial_number'";
        
        $queryobj = new Ard_model();
        $ard_data = $queryobj->query($sql);
        
        if (empty($ard_data)) {
            $obj->view('json', array('msg' => array())); 
        } else {
            $obj->view('json', array('msg' => current($ard_data)));
        }
    }
    
    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        // Remove non-serial number characters
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]/", '', $serial_number);

        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT *
                FROM ard 
                WHERE serial_number = '$serial_number'";
        
        $queryobj = new Ard_model();
        $ard_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $ard_tab)))); 
    }
} // End class Ard_controller
