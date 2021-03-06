<?php
    if(!$stocks) {
        $stocks = new Stocks();
    }
   global $wpdb;
    $stock_table              = $wpdb->prefix.'shc_stock';

    if($_GET['action']=='delete'){
        $id = $_GET['delete_id'];
        $data_delete = $wpdb->update( $stock_table ,array( 'active' =>'0' ),array( 'id' => $id ));
    }
  


    $result_args = array(
        'orderby_field' => 's.modified_at',
        'page' => $stocks->cpage,
        'order_by' => 'DESC',
        'items_per_page' => $stocks->ppage ,
        'condition' => '',
    );
    $stock_list = $stocks->stock_list_pagination($result_args);

?>
<style>
.pointer td{
    text-align: center;
}
.headings th {
    text-align: center;
}
</style>     <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">Brand Name </th>
                            <th class="column-title">Product Name </th>
                            <th class="column-title">Stock Qty </th>
                           <!--  <th class="column-title">Selling Price </th> -->
                            <th class="column-title">Stock Added </th>
                            <th class="column-title">Action </th>
                        </tr>
                    </thead>

                    <tbody style="text-align: center;">
                    <?php

                        if( isset($stock_list['result']) && $stock_list['result'] ) {
                            $i = $stock_list['start_count']+1;

                            foreach ($stock_list['result'] as $s_value) {
                               

                                $stock_id = $s_value->stock_id;
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo $s_value->brand_name; ?></td>
                                    <td class=""><?php echo $s_value->product_name; ?></td>
                                    <td class=""><?php echo $s_value->stock_count; ?></td>
                                    <!-- <td class=""><?php //echo $s_value->selling_total; ?></td> -->
                                    <td class=""><?php echo $s_value->modified; ?>  </td>
                                    <td class="">
                                        <a href="<?php echo admin_url('admin.php?page=add_stocks')."&stock_id=${stock_id}"; ?>"  class="list_update">Update</a> / 
                                        <?php if(is_super_admin()) { ?>  <a href = "#" class="list_delete delete-stock last_list_view" data-id="<?php echo $s_value->stock_id; ?>">Delete</a> <?php } ?>
                                    </td>
                                    
                                </tr>
                    <?php
                                $i++;
                                                 
                            }
                        }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>



        <div class="row">
            <div class="col-sm-7">
                <div class="paging_simple_numbers" id="datatable-fixed-header_paginate">
                    <?php
                    echo $stock_list['pagination'];
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <?php  echo $stock_list['status_txt']; ?>
            </div>
    </div>
 