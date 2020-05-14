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
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ard_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `directory_login` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `directory_login` = 0 THEN 1 END) AS 'no'
                        from ard
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
           
    /**
    * Retrieve allow_all_local_users in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_allow_all_local_users()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ard_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `allow_all_local_users` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `allow_all_local_users` = 0 THEN 1 END) AS 'no'
                        from ard
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
           
    /**
    * Retrieve vnc_enabled in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_vnc_enabled()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ard_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `vnc_enabled` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `vnc_enabled` = 0 THEN 1 END) AS 'no'
                        from ard
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
            
    /**
    * Retrieve screensharing_request_permission in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_screensharing_request_permission()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ard_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `screensharing_request_permission` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `screensharing_request_permission` = 0 THEN 1 END) AS 'no'
                        from ard
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }
    
    /**
    * Retrieve load_menu_extra in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_load_menu_extra()
    {
        $obj = new View();
        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
  
        $queryobj = new Ard_model();
        $sql = "SELECT COUNT(1) as total,
                        COUNT(CASE WHEN `load_menu_extra` = 1 THEN 1 END) AS 'yes',
                        COUNT(CASE WHEN `load_menu_extra` = 0 THEN 1 END) AS 'no'
                        from ard
                        LEFT JOIN reportdata USING (serial_number)
                        WHERE
                            ".get_machine_group_filter('');
        $obj->view('json', array('msg' => current($queryobj->query($sql))));
    }

    /**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $ard = new Ard_model($serial_number);
        $obj->view('json', array('msg' => $ard->rs));
    }
    
    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
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
