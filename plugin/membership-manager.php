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
    
    public static $member_table = 'wp_wbf_members';
    public static $member_type_table = 'wp_wbf_member_type';
    
    static public function initialize()
    {
        add_action('init', array(__CLASS__, 'init'));
        add_action('admin_menu', array(__CLASS__, 'admin_menu'));
        add_action('admin_init', array(__CLASS__, 'admin_init'));
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
                "Renewals",
                "Renewals",
                'manage_options',
                'membership-manager-renewals',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Snail Mail",
                "Snail Mail",
                'manage_options',
                'membership-manager-snailmail',
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
                "Manage Member Types",
                "Manage Member Types",
                'manage_options',
                'membership-manager-manage_membership_types',
                array(__CLASS__, 'include_admin_file')
        );
        /*add_submenu_page(
                'membership-manager',
                "Settings",
                "Settings",
                'manage_options',
                'membership-manager-settings',
                array(__CLASS__, 'include_admin_file')
              );*/
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
        self::initSql("members_table");
        self::initSql("member_type_table");
    }

    /**
     * Do nothing like removing settings, etc.
     * The user could reactivate the plugin and wants everything in the state before activation.
     */
    static public function on_deactivate()
    {
        self::dropSql("member_type_table");
        self::dropSql("members_table");
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     */
    static public function on_uninstall()
    {
        self::dropSql("member_type_table");
        self::dropSql("members_table");
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

    static private function partialFile($path, $partial, $suffix)
    {
        return dirname(__FILE__) . $path . $partial . $suffix;
    }

    static private function partial($partial, $data)
    {
        global $wpdb;
        $file = self::partialFile("/partials/", $partial, ".php");
        if (file_exists($file)) include $file;
    }

    static private function initSql()
    {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $sql = 
            "CREATE TABLE IF NOT EXISTS `" . self::$member_table . "` (
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
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `" . self::$member_type_table . "` (
            `MemberType`  varchar(64) NOT NULL,
            PRIMARY KEY  (`MemberType`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        dbDelta($sql);
    }

    static private function dropSql($tableName)
    {
        global $wpdb;
        $wpdb->query("DROP TABLE `" . self::$member_table . "`");
        $wpdb->query("DROP TABLE `" . self::$member_type_table . "`");
    }

    static private function th($label, $sortable) 
    {
      $url = add_query_arg( array( 'sort' => $sortable ) );

      return "<th><a href=\"$url\">$label</a></th>";
    }

    static private function text_editor_for($field, $label, $args = null) 
    {
      $req = "";

      if( isset($args) && isset($args["required"]) && $args["required"] == true ) {
        $req = "<span class=\"req\">*</span>";
      }
      return "<div>
                <label for=\"$field\">$label $req</label>
                <input type=\"text\" name=\"$field\" id=\"$field\"/>
              </div>";
    }

    static private function get_members($db, $clause = null)
    {
      $orderBy = isset($_GET["sort"]) ? $_GET["sort"] : "LastName";

      $where = isset($clause) ? "WHERE $clause" : "";

      $sql = "SELECT * FROM " . self::$member_table . " $where ORDER BY $orderBy";

      return $db->get_results($sql);
    }
	
    static private function get_member_types($db)
    {
      $sql = "SELECT * FROM " . self::$member_type_table;

      return $db->get_results($sql);
    }
}





