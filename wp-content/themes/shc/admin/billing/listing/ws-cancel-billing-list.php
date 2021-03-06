<?php
    $billing = new Billing();
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Table design <small>Custom design</small></h2>
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
                    <div class="col-md-1 form-group">
                      <select name="ppage" class="ppage ">
                        <option value="5" <?php echo ($billing->ppage == 5) ? 'selected' : '' ?>>5</option>
                        <option value="10" <?php echo ($billing->ppage == 10) ? 'selected' : '' ?>>10</option>
                        <option value="20" <?php echo ($billing->ppage == 20) ? 'selected' : '' ?>>20</option>
                        <option value="50" <?php echo ($billing->ppage == 50) ? 'selected' : '' ?>>50</option>
                      </select>
                    </div>


                    <div class="col-md-1 form-group has-feedback">
                        <input type="text" class="form-control inv_id" name="inv_id" value="<?php echo $billing->inv_id; ?>" placeholder="Invoice" style="padding-right: 5px;">
                        <span class="form-control-feedback" aria-hidden="true" style="margin-top: 6px;"></span>
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="order_id" class="order_id form-control" value="<?php echo $billing->order_id; ?>" placeholder="Order ID">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="name" class="name form-control" value="<?php echo $billing->name; ?>" placeholder="Customer Name">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="mobile" class="mobile form-control" value="<?php echo $billing->mobile; ?>" placeholder="Customer Mobile">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="bill_from" class="bill_from form-control" value="<?php echo $billing->bill_from; ?>" placeholder="Bill From">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="bill_to" class="bill_to form-control" value="<?php echo $billing->bill_to; ?>" placeholder="Bill From">
                    </div>
                </div>
              <input type="hidden" name="filter_action" class="filter_action" value="ws_cancel_billing_filter">
              
            </div>
        </div>
        <div class="ws_cancel_billing_filter">
        <?php
            include( get_template_directory().'/admin/billing/ajax_loading/ws-cancel-billing-list.php' );
        ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    
jQuery(document).ready(function () {
    jQuery('.ppage').focus();

    jQuery(document).live('keydown', function(e){
        if(jQuery(document.activeElement).closest("#wpbody-content").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                jQuery('.ppage').focus()
            }
        }
    });
      jQuery(".ppage").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 
      if (event.shiftKey && event.keyCode == 9) { 
            e.preventDefault(); 
        // call custom function here
            jQuery('.last_list_view').focus();
        } else if(event.keyCode == 9){
            e.preventDefault(); 
            jQuery('.inv_id').focus();
        } else {
         jQuery('.ppage').focus();
        }
    });
    
    jQuery('.filter-section input[type="text"]:last').live('keydown', function(e){

        if(jQuery('.jambo_table td a').length == 0 && jQuery(".next.page-numbers").length == 0 ) {

            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                jQuery('.ppage').focus()
            }
        }

    });


   
    jQuery('.last_list_view').live('keydown', function(e) { 

        if(jQuery(this).parent().parent().next('tr').length == 0 && jQuery(".next.page-numbers").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (event.shiftKey && event.keyCode == 9) { 

                e.preventDefault();
                jQuery(this).closest('tr').prev('tr').find('.last_list_view').focus();

            } 
           
            else if ( event.keyCode == 9){

                e.preventDefault(); 
                // call custom function here
               jQuery('.ppage').focus();
            } 
            else{
                jQuery(this).parent().parent().find('last_list_view').focus();
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