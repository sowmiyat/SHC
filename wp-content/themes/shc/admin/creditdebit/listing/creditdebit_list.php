<?php
    $creditdebit = new creditdebit();
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Notes <small>Custom design</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                    </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
            <div class="filter-section">
              <div class="row">
                <div class="col-md-1">
                  <select name="ppage" class="ppage">
                    <option value="5" <?php echo ($creditdebit->ppage == 5) ? 'selected' : '' ?>>5</option>
                    <option value="10" <?php echo ($creditdebit->ppage == 10) ? 'selected' : '' ?>>10</option>
                    <option value="20" <?php echo ($creditdebit->ppage == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?php echo ($creditdebit->ppage == 50) ? 'selected' : '' ?>>50</option>
                  </select>
                </div>  
               <!--  <div class="col-md-2">

                <select name="type" class="type">
                    <option value="-" <?php echo ($creditdebit->type == 5) ? 'selected' : '' ?>>All</option>
                    <option value="credit" <?php echo ($creditdebit->type == 10) ? 'selected' : '' ?>>Credit</option>
                    <option value="debit" <?php echo ($creditdebit->type == 20) ? 'selected' : '' ?>>Debit</option>
                  </select>
                </div> -->
               <!--  <div class="col-md-2">
                  <input type="text" name="amount" class="amount" value="<?php echo $creditdebit->amount; ?>" placeholder="Amount">
                </div> -->
                 <div class="col-md-2">
                  <input type="text" name="date" class="date" value="<?php echo $creditdebit->date; ?>" placeholder="Date">
                </div>
              </div>
              <input type="hidden" name="filter_action" class="filter_action" value="creditdebit_filter">
              
            </div>
        </div>
        <div class="creditdebit_filter">
        <?php
            include( get_template_directory().'/admin/creditdebit/ajax_loading/creditdebit_list.php' );
        ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    
jQuery(document).ready(function () {
    jQuery('.ppage').focus();


    jQuery(".ppage").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (event.shiftKey && event.keyCode == 9) { 
            e.preventDefault(); 
        // call custom function here
            jQuery('.last_list_view').focus();
        } else if(event.keyCode == 9){
            e.preventDefault(); 
            jQuery('.date').focus();
        } else {
         jQuery('.ppage').focus();
        }
    });


    jQuery(document).live('keydown', function(e){
        if(jQuery(document.activeElement).closest("#wpbody-content").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                jQuery('.ppage').focus();
            }
        }
    });

    
    jQuery('.filter-section input[type="text"]:last').live('keydown', function(e){

        if(jQuery('.jambo_table td a').length == 0 && jQuery(".next.page-numbers").length == 0 ) {

            
            if (event.shiftKey && event.keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                 jQuery('.ppage').focus();
            } 
            else if ( event.keyCode == 9){
                e.preventDefault(); 
                // call custom function here
               jQuery('.ppage').focus();
            }
            else{

              
                jQuery('.filter-section input[type="text"]:last').focus();
            }
        }

    });


    jQuery('.last_list_view').live('keydown', function(e) { 

        if(jQuery(this).parent().parent().next('tr').length == 0 && jQuery(".next.page-numbers").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (event.shiftKey && event.keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                 jQuery(this).parent().parent().find('.list_update').focus();
            } 
            else if ( event.keyCode == 9){
                e.preventDefault(); 
                // call custom function here
               jQuery('.ppage').focus();
            }
            else{

              
                jQuery(this).parent().parent().find('.last_list_view').focus();
            }
        }
    });

    jQuery(".next.page-numbers").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (keyCode == 9) { 
        e.preventDefault(); 
        // call custom function here
        jQuery('.ppage').focus()
      } 
    });

    
})    

</script>