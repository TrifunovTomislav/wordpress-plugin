<div class="wrap">
    <h1>Taxonomy manager</h1>
    <?php settings_errors(); ?>    
    <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST['edit_taxonomy']) ? 'active' : ''; ?>"><a href="#tab-1">Your Custom Taxonomyes</a></li>
        <li class="<?php echo isset($_POST['edit_taxonomy']) ? 'active' : ''; ?>">
            <a href="#tab-2">
            <?php echo isset($_POST['edit_taxonomy']) ? 'Edit' : 'Add'; ?> Taxonomy
            </a></li>
        <li><a href="#tab-3">Export</a></li>
    </ul>
    <div class="tab-content">
        <div id="tab-1" class="tab-panel <?php echo !isset($_POST['edit_taxonomy']) ? 'active' : ''; ?>">
            <h3>Manage Your Custom Taxonomyes</h3>
            <?php
                        
                $options = get_option( 'sparky_plagin_tax' ) ?: array();

                echo '<table class="tax-table">
                        <tr>
                           <th>ID</th>
                           <th>Singular Name</th>
                           <th class="text-center">Hierarchical</th>
                           <th class="text-center">Actions</th>
                        </tr>';

                foreach ($options as $option){
                    $hierarchical = isset($option['hierarchical']) ? "TRUE" : "FALSE";
                    
                    echo "<tr>
                              <td>{$option['taxonomy']}</td>
                              <td>{$option['singular_name']}</td>
                              <td class=\"text-center\">{$hierarchical}</td>
                              <td class=\"text-center\">";

                    echo '<form method="post" action="" class="inline-block">';            
                    echo '<input type="hidden" name="edit_taxonomy" value="' . $option['taxonomy'] . '" >';
                    submit_button('Edit', 'primary small', 'submit', false);
                    echo "</form> ";

                    echo '<form method="post" action="options.php" class="inline-block">';
                    settings_fields('sparky_plagin_tax_settings');
                    echo '<input type="hidden" name="remove" value="' . $option['taxonomy'] . '" >';
                    submit_button('Delete', 'delete small', 'submit', false, array(
                        'onclick' => 'return confirm("Are you sure you want to delete this awsome taxonomy u sick bastard? Why do you like killing stuff :(");'
                    ));
                    echo "</form></td></tr>";
				
                }
                echo '</table>';
            ?>
        </div>
        <div id="tab-2" class="tab-panel <?php echo isset($_POST['edit_taxonomy']) ? 'active' : ''; ?>">  
            <form method="post" action="options.php">
            <?php
                settings_fields('sparky_plagin_tax_settings');
                do_settings_sections('sparky_taxonomy');
                submit_button();
            ?>
        </form>
        </div>
        <div id="tab-3" class="tab-panel">
            <h3>Export Your Taxonomyes</h3>
        </div>
    </div> 
</div>