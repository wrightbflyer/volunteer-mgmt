<?php
/**
 * Plugin Name: Membership Manager
 * Description: A membership management plugin written for Wright-B-Flyer.org
 * Version: 1.0
 * Author: Southwest Ohio GiveCamp 2013
 */
define('WBF_MEMBERSHIP_PLUGIN_PATH', WP_PLUGIN_DIR . '/membership-manager/');

register_activation_hook(WBF_MEMBERSHIP_PLUGIN_PATH . 'membership-manager.php', array('WBF_Membership','on_activate'));
register_deactivation_hook(WBF_MEMBERSHIP_PLUGIN_PATH . 'membership-manager.php', array('WBF_Membership','on_deactivate'));
register_uninstall_hook(WBF_MEMBERSHIP_PLUGIN_PATH . 'membership-manager.php', array('WBF_Membership','on_uninstall'));

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
      if ( isset($_GET["download"]) )
      {
        self::downloadcsv($_GET["page"]);
      }
    }
    
    static function delimit($accum,$next)
    {
      return "$accum" . json_encode($next) . ",";
    }

    static function downloadcsv($p)
    {
        global $wpdb;
        
        $parts = explode('-', $_GET['page']);
        $list = array_pop($parts);
        $filename = $list . '_' . date("Y-m-d") . ".csv";
        
        switch($list)
        {
            case "renewals":
            {
                $members = self::get_member_renewal_list($wpdb);
                break;
            }
            case "snailmail":
            {
                $members = self::get_member_snailmail_list($wpdb);
                break;
            }
            case "membership_list":
            default:
            {
                $members = self::get_members($wpdb);
                break;
            }
        }
        ob_start();
        $fieldnames = $wpdb->get_col_info("name");
        echo array_reduce($fieldnames, "self::delimit") . "\n";
        foreach($members as $member) {
          $values = get_object_vars($member);
          echo array_reduce($values, "self::delimit" ) . "\n";
        }
        $contents = ob_get_clean();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Length: ' . strlen($contents));
        header("Cache-Control: no-store, no-cache");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $contents;
        exit;
    }
    
    static public function admin_menu()
    {
        add_menu_page(
            "Membership Manager",
            "Membership Manager", 
            '', 
            "membership-manager",
            array(__CLASS__, 'include_admin_file'),
            null,
            58
        );
        add_submenu_page(
                'membership-manager',
                "Membership List",
                "Membership List",
                'MM-WBF: Manage Membership Database',
                'membership-manager-membership_list',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Add New Member",
                "Add New Member",
                'MM-WBF: Manage Membership Database',
                'membership-manager-new_member',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                null,
                null,
                'manage_options',
                'membership-manager-member',
                array(__CLASS__, 'include_admin_file')
        );
         add_submenu_page(
                'membership-manager',
                "Renewals",
                "Renewals",
                'MM-WBF: Manage Membership Database',
                'membership-manager-renewals',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Snail Mail",
                "Snail Mail",
                'MM-WBF: Manage Membership Database',
                'membership-manager-snailmail',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Member Types",
                "Member Types",
                'MM-WBF: Manage Membership Database',
                'membership-manager-manage_membership_types',
                array(__CLASS__, 'include_admin_file')
        );
        add_submenu_page(
                'membership-manager',
                "Import CSV",
                "Import CSV",
                'MM-WBF: Manage Membership Database',
                'membership-manager-import_csv',
                array(__CLASS__, 'include_admin_file')
        );
        /*add_submenu_page(
                'membership-manager',
                "Settings",
                "Settings",
                'MM-WBF: Manage Membership Database',
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
        ?>
        <div id="icon-generic" class="icon32"><br></div>
        <div class="wrap"><h2>Membership Manager</h2></div>
        <?php
        include 'screens/' . $file . '.php';
    }
    
    static public function on_activate()
    {
        global $wp_roles;
        if(is_object($wp_roles)) {
          foreach($wp_roles->roles as $role => $role_data)
          {
            if (   is_array($role_data['capabilities'])
                && array_key_exists('edit_users', $role_data['capabilities'])
                && !array_key_exists('MM-WBF: Manage Membership Database',$role_data['capabilities'])
               )
            {
              $wp_roles->add_cap($role, 'MM-WBF: Manage Membership Database', true);
            }
          }
        }
        self::initSql();
    }

    /**
     * Do nothing like removing settings, etc.
     * The user could reactivate the plugin and wants everything in the state before activation.
     */
    static public function on_deactivate()
    {
        //self::dropSql();
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     */
    static public function on_uninstall()
    {
        //self::dropSql();
    }
    
    static private function db_string($input)
    {
        // XXX: This /may/ not be working exactly as desired...
        return "'" . mysql_real_escape_string(htmlspecialchars(stripslashes($input), ENT_QUOTES, 'utf-8')) . "'";
    }
    
    static private function db_date($input)
    {
        if(!empty($input)) {
            return mysql_real_escape_string(date("Y-m-d H:i:s",strtotime($input)));
        }

        return null;
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
                    `MemberSince` date DEFAULT NULL,
                    `RenewalDate` date DEFAULT NULL,
                    `FlightDate`  date DEFAULT NULL,
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
            `idx`       int not null default 0,
            PRIMARY KEY  (`MemberType`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        dbDelta($sql);
    }

    static private function dropSql()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE `" . self::$member_table . "`");
        $wpdb->query("DROP TABLE `" . self::$member_type_table . "`");
    }

    static private function th($label, $sortable) 
    {
        //icon-sort icon-sort-down icon-sort-up
        $icon = '<i class="icon icon-sort" style="color:#CCC"></i>';
        if (   (isset($_POST['sort']) && ($_POST['sort'] == $sortable))
            || (!isset($_POST['sort']) && ($sortable == 'lastname'))
           )
        {
            $sortable .= " DESC";
            $icon = '<i class="icon icon-arrow-down"></i>';
        }
        elseif (isset($_POST['sort']) && ($_POST['sort'] == ($sortable . " DESC")))
        {
            $icon = '<i class="icon icon-arrow-up"></i>';
        }
        //$url = add_query_arg( array( 'sort' => $sortable ) );
        //return "<th><a href=\"$url\">$label $icon</a></th>";
		return "<th><a href=\"javascript:setSort('$sortable');\">$label $icon</a></th>";
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
      $orderBy = isset($_POST["sort"]) ? $_POST["sort"] : "LastName";
      $where = isset($clause) ? "WHERE $clause" : "";
      $sql = "SELECT * FROM " . self::$member_table . " $where ORDER BY $orderBy";
      return $db->get_results($sql);
    }
    
    static private function get_member_renewal_list($db)
    {
        // Calculate dates for start and end of this month
        //$startDate = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),1,date("Y")));
        $endDate = date("Y-m-d H:i:s",mktime(0,0,-1,date("m")+1,1,date("Y")));
        
        $where = "RenewalDate is not null and RenewalDate <= '$endDate'";
        return self::get_members($db, $where);
    }
    
    static private function get_member_snailmail_list($db)
    {
        $where = "Email IS NULL OR Email <=''";
        return self::get_members($db, $where);
    }

    static private function get_member_types($db)
    {
        return $db->get_results("select * from " . self::$member_type_table . " order by membertype asc");
    }
}
