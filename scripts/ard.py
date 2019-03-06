#!/usr/bin/python

import subprocess
import os
import sys
import json
import time

sys.path.insert(0, '/usr/local/munki')

from munkilib import FoundationPlist
from Foundation import CFPreferencesCopyAppValue

def get_remote_desktop_info():

    try:
        ard_info = {}  
        ard_info['text1'] = get_pref_value('Text1', 'com.apple.RemoteDesktop')
        ard_info['text2'] = get_pref_value('Text2', 'com.apple.RemoteDesktop')
        ard_info['text3'] = get_pref_value('Text3', 'com.apple.RemoteDesktop')
        ard_info['text4'] = get_pref_value('Text4', 'com.apple.RemoteDesktop')
        
        return ard_info

    except Exception:
        return {}
    
def get_remote_management_info():

    try:
        ard_info = {'vnc_enabled':0,'screensharing_request_permission':0,'console_allows_remote':0,'directory_login':0,'load_menu_extra':1}
        
        console_allows_remote = to_bool(get_pref_value('AdminConsoleAllowsRemoteControl', 'com.apple.RemoteManagement'))
        if (console_allows_remote == ""):
            ard_info['console_allows_remote'] = 0
        else:
            ard_info['console_allows_remote'] = console_allows_remote
            
        load_menu_extra = to_bool(get_pref_value('LoadRemoteManagementMenuExtra', 'com.apple.RemoteManagement'))
        if (load_menu_extra == ""):
            ard_info['load_menu_extra'] = 1
        else:
            ard_info['load_menu_extra'] = load_menu_extra
            
        directory_login = to_bool(get_pref_value('DirectoryGroupLoginsEnabled', 'com.apple.RemoteManagement'))
        if (directory_login == ""):
            ard_info['directory_login'] = 0
        else:
            ard_info['directory_login'] = directory_login
            
        screensharing_request_permission = to_bool(get_pref_value('ScreenSharingReqPermEnabled', 'com.apple.RemoteManagement'))
        if (screensharing_request_permission == ""):
            ard_info['screensharing_request_permission'] = 0
        else:
            ard_info['screensharing_request_permission'] = screensharing_request_permission
            
        vnc_enabled = to_bool(get_pref_value('VNCLegacyConnectionsEnabled', 'com.apple.RemoteManagement'))
        if (vnc_enabled == ""):
            ard_info['vnc_enabled'] = 0
        else:
            ard_info['vnc_enabled'] = vnc_enabled
            
        allow_all_local_users = to_bool(get_pref_value('ARD_AllLocalUsers', 'com.apple.RemoteManagement'))
        if (allow_all_local_users == ""):
            ard_info['allow_all_local_users'] = 0
        else:
            ard_info['allow_all_local_users'] = allow_all_local_users
        
        return ard_info

    except Exception:
        return {}
    
def get_ard_info():

    try:
        plist = FoundationPlist.readPlist("/Library/Preferences/com.apple.ARDAgent.plist")
        out = {}
        
        for item in plist:
            if item == 'Administrators':
                admin_json = []
                admin_machines = []
                
                for mac_address_entry in str(plist[item]).split("    };"):
                    machine_entry = {}
                    for mac_address in mac_address_entry.split('\n'):
                        if " = " in mac_address:
                            key = mac_address.split(' = ')[0].strip()
                            if " =     {" in mac_address:
                                machine_entry["MAC_address"] = key
                            elif key == "HasLatestReportingInfo":
                                machine_entry[key] = to_bool(mac_address.split(' =')[1].replace('";', "").replace(';', "").replace(' "', "").strip())
                            elif key == "LastContact":
                                machine_entry[key] = string_to_time(mac_address.split(' =')[1].replace('";', "").replace(';', "").replace(' "', "").strip())
                            elif key == "port":
                                machine_entry[key] = str(mac_address.split(' =')[1].replace('";', "").replace(';', "").replace(' "', "").strip())
                            elif key == "ComputerName":
                                value = mac_address.split(' =')[1].replace('";', "").replace(';', "").replace(' "', "").strip()
                                machine_entry[key] = value
                                admin_machines.append(value)
                            else:
                                machine_entry[key] = mac_address.split(' =')[1].replace('";', "").replace(';', "").replace(' "', "").strip()

                    admin_json.append(machine_entry)
                out['administrators'] = json.dumps(admin_json[:-1])
                try:
                    out['admin_machines'] = ', '.join(admin_machines)
                except Exception:
                    pass
                
            elif item == 'TaskServers':
                task_json = []
                for mac_address_entry in str(plist[item]).split("    };"):
                    task_entry = {}
                    for mac_address in mac_address_entry.split('\n'):
                        if " = " in mac_address:
                            key = mac_address.split(' = ')[0].strip()
                            if " =     {" in mac_address:
                                task_entry["MAC_address"] = key
                            elif key == "port":
                                task_entry[key] = str(mac_address.split(' =')[1].replace('";', "").replace(';', "").replace(' "', "").strip())
                            else:
                                task_entry[key] = mac_address.split(' =')[1].replace('";', "").replace(';', "").replace(' "', "").strip()

                    task_json.append(task_entry)
                out['task_servers'] = json.dumps(task_json[:-1])
            
        return out

    except Exception:
        return {}
    
def get_pref_value(key, domain):
    
    value = CFPreferencesCopyAppValue(key, domain)
    
    if(value is not None):
        return value
    elif(value is not None and len(value) == 0 ):
        return ""
    else:
        return ""
    
def string_to_time(date_time):
    
    if (date_time == "0" or date_time == 0):
        return ""
    else:
        try:
            return str(int(time.mktime(time.strptime(str(date_time).replace(" +0000", ""), '%Y-%m-%d %H:%M:%S'))))
        except Exception:
            try:
                return str(int(time.mktime(time.strptime(str(date_time).replace(" +0000", ""), '%Y-%m-%dT%H:%M:%SZ'))))
            except Exception:
                return date_time
    
def to_bool(s):
    if s == "":
        return ""
    elif s == True:
        return 1
    else:
        return 0
    
def merge_two_dicts(x, y):
    z = x.copy()
    z.update(y)
    return z
    
def main():
    """Main"""
        
    # Remove old init_ard script, if it exists
    if os.path.isfile(os.path.dirname(os.path.realpath(__file__))+'/init_ard'):
        os.remove(os.path.dirname(os.path.realpath(__file__))+'/init_ard')
        
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)
            
    # Set the encoding
    reload(sys)  
    sys.setdefaultencoding('utf8')

    # Get results
    result = dict()
    result = merge_two_dicts(get_remote_desktop_info(), get_remote_management_info())
    result = merge_two_dicts(result, get_ard_info())
    
    # Write ard results to cache
    output_plist = os.path.join(cachedir, 'ard.plist')
    FoundationPlist.writePlist(result, output_plist)
#    print FoundationPlist.writePlistToString(result)

if __name__ == "__main__":
    main()