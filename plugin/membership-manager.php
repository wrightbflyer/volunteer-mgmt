<?php
/**
 * Plugin Name: Membership Manager
 * Description: A membership management plugin written for Wright-B-Flyer.org
 * Version: 1.0
 * Author: Southwest Ohio GiveCamp 2013
 */

 // XXX: these registration hooks are not working as expected (callbacks aren't getting called)
register_activation_hook(__FILE__, array('WBF_Membership', 'on_activate'));
register_deactivation_hook(__FILE__, array('WBF_Membership', 'on_deactivate'));
register_uninstall_hook(__FILE__, array('WBF_Membership', 'on_uninstall'));

add_action( 'plugins_loaded', array( 'WBF_Membership', 'initialize' ) );
class WBF_Membership {
    
    public static $table = 'wp_wbf_members';
    
    static public function initialize()
    {
        add_action('init', array(__CLASS__, 'init'));
        add_action('admin_menu', array(__CLASS__, 'admin_menu'));
        add_action('admin_init', array(__CLASS__, 'admin_init'));
        
        // create the table if it doesn't exist
        global $wpdb;
        $sql = "
            CREATE TABLE IF NOT EXISTS `" . self::$table . "` (
                `ID`          bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `FirstName`   varchar(256) DEFAULT NULL,
                `LastName`    varchar(256) DEFAULT NULL,
                `MemberType`  varchar(64) DEFAULT NULL,
                `MemberSince` datetime DEFAULT NULL,
                `RenewalDate` datetime DEFAULT NULL,
                `Address`     varchar(256) DEFAULT NULL,
                `City`        varchar(64) DEFAULT NULL,
                `State`       varchar(64) DEFAULT NULL,
                `Zip`         varchar(32) DEFAULT NULL,
                `Country`     varchar(64) DEFAULT NULL,
                `HomePhone`   varchar(32) DEFAULT NULL,
                `MobilePhone` varchar(32) DEFAULT NULL,
                `Email`       varchar(128) DEFAULT NULL,
                PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        $wpdb->query($sql);
    }
    
    static public function init()
    {
    }
    
    static public function admin_menu()
    {
        add_menu_page(
            "Membership Manager",
            "Membership Manager", 
            '', 
            "membership-manager",
            array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Membership List",
                "Membership List",
                'manage_options',
                'membership-manager-membership_list',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Add New Member",
                "Add New Member",
                'manage_options',
                'membership-manager-new_member',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Import CSV",
                "Import CSV",
                'manage_options',
                'membership-manager-import_csv',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Renewals",
                "Renewals",
                'manage_options',
                'membership-manager-renewals',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Settings",
                "Settings",
                'manage_options',
                'membership-manager-settings',
                array(__CLASS__, 'include_admin_file')
        );
    }
    
    static public function admin_init()
    {
    }
    
    static public function include_admin_file()
    {
        global $wpdb;
        $parts = explode('-', $_GET['page']);
        $file = array_pop($parts);
        include 'screens/' . $file . '.php';
    }
    
    static public function on_activate()
    {
        // XXX: registration hooks not working as expected
        throw new Exception('here on_activate', 400);
    }

    /**
     * Do nothing like removing settings, etc.
     * The user could reactivate the plugin and wants everything in the state before activation.
     */
    static public function on_deactivate()
    {
        // XXX: registration hooks not working as expected
        throw new Exception('here on_deactivate', 400);
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     */
    static public function on_uninstall()
    {
        // XXX: registration hooks not working as expected
        throw new Exception('here on_uninstall', 400);
        global $wpdb;
        $wpdb->query("DROP TABLE `" . self::$table . "`;");
    }
    
    static private function db_string($input)
    {
        // XXX: This /may/ not be working exactly as desired...
        return "'" . mysql_real_escape_string(htmlspecialchars(stripslashes($input), ENT_QUOTES, 'utf-8')) . "'";
    }
    
    static private function db_date($input)
    {
        return mysql_real_escape_string(date("Y-m-d H:i:s",strtotime($input)));
    }
    
    static private function db_number($input)
    {
        return sprintf('%d',$input);
    }
    
    static private function partial($partial, $data)
    {
        global $wpdb;
        $file = dirname(__FILE__) . "/partials/" . $partial . ".php";
        if (file_exists($file)) include $file;
    }

    static private function th($label, $sortable) 
    {
      $url = add_query_arg( array( 'sort' => $sortable ) );

      return "<th><a href=\"$url\">$label</a></th>";
    }
}





