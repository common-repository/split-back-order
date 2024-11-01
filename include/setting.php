<?php 
// Save/Update configuration value
global $wpdb;
if(sanitize_text_field(!empty($_POST['submit']))){
	  $enable_split_back_order = sanitize_text_field($_POST['enable_split_back_order']);
	  $split_back_order	 = sanitize_text_field($_POST['split_back_order']);
	  $split_back_order_salable = sanitize_text_field($_POST['split_back_order_salable']);
	  $split_back_order_instock = sanitize_text_field($_POST['split_back_order_instock']);
	  
      update_option( 'enable_split_back_order', $enable_split_back_order );
      update_option( 'split_back_order', $split_back_order );
      update_option( 'split_back_order_salable', $split_back_order_salable );
      update_option( 'split_back_order_instock', $split_back_order_instock );
    
     echo "<div class='form-save-msg'>Changes Saved!</div>";
}
  $enable_split_back_order_get = get_option( 'enable_split_back_order' );
  $split_back_order_get = get_option( 'split_back_order' );
  $split_back_order_salable_get = get_option( 'split_back_order_salable' );
  $split_back_order_instock_get = get_option( 'split_back_order_instock' );

?>

 <h1>General Configuration</h1>
    <div class="row">
        <div class="form-group">
            <form action="" method="post">
                <div><label for="sort" class="col-sm-2 control-label"> Enable split order </label>
                    <select class="form-control showhide" name="enable_split_back_order" id="sort">
                        <option value="no" <?php
                        if ($enable_split_back_order_get == 'no') {
                            echo 'selected';
                        }
                        ?>>No</option>
                        <option value="yes" <?php
                        if ($enable_split_back_order_get == 'yes') {
                            echo 'selected';
                        }
                        ?>>Yes</option>
                    </select> 
                </div> 
				<br>
				<?php  if ($enable_split_back_order_get == 'yes') { ?>
				 <div class="outerdiv">
				<div><label for="sort" class="col-sm-2 control-label"> Split Backorders </label>
                    <select class="form-control" name="split_back_order" id="sort">
                        <option value="no" <?php
                        if ($split_back_order_get == 'no') {
                            echo 'selected';
                        }
                        ?>>No</option>
                        <option value="yes" <?php
                        if ($split_back_order_get == 'yes') {
                            echo 'selected';
                        }
                        ?>>Yes</option>
                    </select> 
                </div> 
                <br>
				<div><label for="sort" class="col-sm-2 control-label"> Split Backorders by salable quantity</label>
                    <select class="form-control" name="split_back_order_salable" id="sort">
                        <option value="no" <?php
                        if ($split_back_order_salable_get == 'no') {
                            echo 'selected';
                        }
                        ?>>No</option>
                        <option value="yes" <?php
                        if ($split_back_order_salable_get == 'yes') {
                            echo 'selected';
                        }
                        ?>>Yes</option>
                    </select> 
                </div> 
                <br>
				<div><label for="sort" class="col-sm-2 control-label"> Split InStock</label>
                    <select class="form-control" name="split_back_order_instock" id="sort">
                        <option value="no" <?php
                        if ($split_back_order_instock_get == 'no') {
                            echo 'selected';
                        }
                        ?>>No</option>
                        <option value="yes" <?php
                        if ($split_back_order_instock_get == 'yes') {
                            echo 'selected';
                        }
                        ?>>Yes</option>
                    </select> 
                </div> 
                <br>
				</div>
				<?php  } ?>
                <div>
                   
                    <input type="submit" name="submit" value="save config">
                </div>
            </form>
        </div>
    </div>