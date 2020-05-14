ARD module
==========

Reports information Apple Remote Desktop and it's configuration


Table Schema
---

* id - Unique ID
* serial_number - varchar(255) - machine's serial number
* text1 - varchar(255) - Text field 1
* text2 - varchar(255) - Text field 2
* text3 - varchar(255) - Text field 3
* text4 - varchar(255) - Text field 4
* console_allows_remote - boolean - If remote control of ARD console is allowed
* load_menu_extra - boolean - If the menu extra is set to load
* screensharing_request_permission - boolean - If Screen Sharing will request permission from end user
* vnc_enabled - boolean - If legacy VNC is enabled
* allow_all_local_users - boolean - If ARD will grant all local users access
* directory_login - boolean - If directory logins are enabled
* admin_machines - medium text - List of admin ARD computers that have connected to client
* administrators - medium text - JSON string containing information about ARD admins
* task_servers - medium text - JSON string containing information about ARD Task Servers
