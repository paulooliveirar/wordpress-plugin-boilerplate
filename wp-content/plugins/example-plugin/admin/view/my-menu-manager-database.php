<?php 

use ExamplePlugin\Admin\Connection as Conn;
use ExamplePlugin\Admin\CustomTemplate;
include_once dirname(__FILE__) . '/../connection.php';
include_once dirname(__FILE__) . '/../custom-template.php';


?>
<h1>Manager Database</h1>

<?php 
$conn = new Conn();
$results = $conn->find('users', [], 'ARRAY_A');
// Columns Fixed
$columns_names = [
    'ID' => 'id',
    'user_login' => 'User Login',
    'user_email' => 'User Email',
    'user_status' => 'User Status',
];

// Using all columns sql
foreach ($results[0] as $key => $value) {
    $columns_names[$key] = ucwords(str_replace(['-','_',], ' ', $key));
}

function select(){
    $conn = new Conn();
    $results = $conn->find('users', [$_POST['id']], 'ARRAY_A');
}


 /**
     * Display the list table page
     *
     * @return Void
     */
    function list_table_page($table_data, $columns_names)
    {
        $names = [
            'singular' => 'user',
            'plural' => 'users'
        ];

        $exampleListTable = new CustomTemplate($names, $table_data, $columns_names);

        $exampleListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>My Table Page</h2>
                <?php $exampleListTable->display(); ?>
            </div>
        <?php
    }

    list_table_page($results, $columns_names);
?>
