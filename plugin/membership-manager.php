<?php
/**
 * Plugin Name: Membership Manager
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A membership management plugin written for Wright-B-Flyer.org
 * Version: 1.0
 * Author: Southwestern Ohio GiveCamp 2013
 */

WBF_Membership::initialize();
 
class WBF_Membership {
    
    public static $table = 'wp-wbf-members';
    
    static public function initialize()
    {
        add_action('init', array(__CLASS__, 'init'));
        add_action('admin_menu', array(__CLASS__, 'admin_menu'));
        add_action('admin_init', array(__CLASS__, 'admin_init'));
        
        register_activation_hook(__FILE__, array(__CLASS__, 'on_activate'));
        register_deactivation_hook(__FILE__, array(__CLASS__, 'on_deactivate'));
        register_uninstall_hook(__FILE__, array(__CLASS__, 'on_uninstall'));
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
        global $wpdb;
        
        // fresh install: install the tables if they don't exist
        if ( $wpdb->get_var('show tables like "'.self::$table.'"') != self::$table )
        {
            $sql = "
                CREATE TABLE `" . self::$table . "` (
                    `id` char(36) NOT NULL,
                    `FirstName` varchar(256) NOT NULL,
                    `LastName` varchar(256) DEFAULT NULL,
                    `MemberType` varchar(64) NOT NULL,
                    `MemberSince` datetime DEFAULT NULL,
                    `RenewalDate` datetime DEFAULT NULL,
                    `Address` char(256) DEFAULT NULL,
                    `City` char(64) DEFAULT NULL,
                    `State` varchar(64) DEFAULT NULL,
                    `Zip` varchar(32) DEFAULT NULL,
                    `Country` varchar(64) DEFAULT NULL,
                    `HomePhone` varchar(32) DEFAULT NULL,
                    `MobilePhone` varchar(32) DEFAULT NULL,
                    `Email` varchar(155) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `id_UNIQUE` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
            $wpdb->query($sql);
        }
    }

    /**
     * Do nothing like removing settings, etc.
     * The user could reactivate the plugin and wants everything in the state before activation.
     * Take a constant to remove everything, so you can develop & test easier.
     */
    static public function on_deactivate()
    {
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     */
    static public function on_uninstall()
    {
    }
}




