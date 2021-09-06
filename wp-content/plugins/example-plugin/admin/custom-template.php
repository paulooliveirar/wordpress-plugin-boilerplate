<?php 

namespace ExamplePlugin\Admin;

use WP_List_Table;

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class CustomTemplate extends WP_List_Table
{
    private $data;
    private $columns;
    private $orderby;
    private $totalperPage;

    public function __construct(array $name, array $data = [], array $columns = [], array $orderby = [], int $totalperPage = 20)
    {
        // Set parent defaults.
		parent::__construct( array(
			'singular' => $name['singular'],    // Singular name of the listed records.
			'plural'   => $name['plural'],      // Plural name of the listed records.
		) );

        // Set Data Info
        $this->data = $data;

        // Set Columns name
        $this->columns = $columns;

        // Set Order 
        $this->orderby = $orderby;

        // Set Total registers per Page
        $this->totalperPage = $totalperPage;
    }


     /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, [ &$this, 'sort_data' ] );

        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $this->totalperPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$this->totalperPage),$this->totalperPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        return $this->columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return $this->orderby;
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        return $this->data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        return $this->filter_columns($column_name) ? $item[ $column_name ] : false;
    }

    /**
     * Filter column of the table to show
     *
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    private function filter_columns($column_name)
    {
        return isset($this->columns[$column_name]);
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'ID';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}