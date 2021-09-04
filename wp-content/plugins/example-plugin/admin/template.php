<?php 

namespace ExamplePlugin\Admin\Template;

const MENU_TYPE = 'menu';
const SUBMENU_TYPE = 'submenu';

class Template
{
    private $menu_slug = 'my-menu';

    function __construct()
    {
        add_action( 'admin_menu', [$this, 'create_menu'], 10);
        add_filter( 'plugin_action_links', [$this, 'my_plugin_action_links'], 10 , 4);
    }

    public function create_menu()
    {
        $items = $this->get_menu();

        if(!is_array($items)){
            return;
        }

        foreach($items as $item)
        {
            if(empty($item['function'])){
                $item['function'] = [&$this, "my_plugin_routes"];
            }
            $this->create_new_item($item);
        }

        /**
         * Removes the first menu item, created automatically by wordpress
         */
        remove_submenu_page($this->menu_slug, $this->menu_slug);
    }

    private function create_new_item(array $item)
    {
        if($item['type'] === MENU_TYPE)
        {
            add_menu_page( 
                $item['page_title'],
                $item['menu_title'],
                $item['capability'],
                $item['menu_slug'],
                $item['function'], 
                $item['icon_url'], 
                $item['position']
            );

            return;
        }

        add_submenu_page(
            $item['parent_slug'],
            $item['page_title'],
            $item['menu_title'],
            $item['capability'],
            $item['menu_slug'],
            $item['function'], 
            $item['position']
        );

        return;
    }

    private function get_menu()
    {
        return [
            [
                'type' => MENU_TYPE,
                'page_title' => __( 'Page Title', 'pbpm' ),
                'menu_title' => __( 'Menu Title', 'pbpm' ),
                'capability' => 'manage_options',
                'menu_slug' => $this->menu_slug,
                'function' => '',
                'icon_url' => 'dashicons-welcome-widgets-menus',
                'position' => 30
            ],
            [
                'type' => SUBMENU_TYPE,
                'parent_slug' => $this->menu_slug,
                'page_title' => __( 'Manager Database', 'pbpm' ),
                'menu_title' =>__( 'Manager Database', 'pbpm' ),
                'capability' => 'manage_options',
                'menu_slug' => 'my-menu-manager-database',
                'function' => '',
                'position' => 0
            ],
            [
                'type' => SUBMENU_TYPE,
                'parent_slug' => $this->menu_slug,
                'page_title' => __( 'Add New Item', 'pbpm' ),
                'menu_title' =>__( 'Add New', 'pbpm' ),
                'capability' => 'manage_options',
                'menu_slug' => 'my-menu-add',
                'function' => '',
                'position' => 0
            ],
            [
                'type' => SUBMENU_TYPE,
                'parent_slug' => $this->menu_slug,
                'page_title' => __( 'Edit Items', 'pbpm' ),
                'menu_title' =>__( 'Edit items', 'pbpm' ),
                'capability' => 'manage_options',
                'menu_slug' => 'my-menu-edit',
                'function' => '',
                'position' => 1
            ],
            [
                'type' => SUBMENU_TYPE,
                'parent_slug' => $this->menu_slug,
                'page_title' => __( 'Configurations', 'pbpm' ),
                'menu_title' =>__( 'Configurations', 'pbpm' ),
                'capability' => 'manage_options',
                'menu_slug' => 'my-menu-config',
                'function' => '',
                'position' => 2
            ],
            [
                'type' => SUBMENU_TYPE,
                'parent_slug' => $this->menu_slug,
                'page_title' => __( 'About', 'pbpm' ),
                'menu_title' =>__( 'About', 'pbpm' ),
                'capability' => 'manage_options',
                'menu_slug' => 'my-menu-about',
                'function' => '',
                'position' => 2
            ],
        ];
    }

    /**
     * 
     * Add a link to the settings page on the plugins.php page.
     * @since 1.0.0 
     * @param array $links
     * @param string $file 
     * @return array      
     * 
     */
    public function my_plugin_action_links( $links, $file )
    {
        if(basename($file) === PLUGIN_SHORT_PATH)
        {
            $newLinks = [
                '<a href="' . esc_url( admin_url( '/options-general.php?page=ESB%20Help' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
            ];
            
            return array_merge($newLinks, $links);
        }   
   }

   public function my_plugin_routes():void {
        $link = trim($_GET['page']);
        $file = PLUGIN_PATH_DIR . "admin/view/$link.html";

        $class_methods = get_class_methods(new Template());

        foreach ($class_methods as $method_name) {
            if($link == $method_name){
                $this->$method_name();
            }
        }

        if(!is_file($file) && !file_exists($file)){
            include_once PLUGIN_PATH_DIR . 'admin/view/error-404.html';
            wp_die();
        }

        include_once $file;
    }
}