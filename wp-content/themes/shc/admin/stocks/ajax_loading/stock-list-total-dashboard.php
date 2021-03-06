<?php

    if(!$stocks) {
       $stocks = new Stocks();
        $ppage = 5;
    }

   
    $result_args = array(
        'orderby_field' => 'is_alert',
        'page' => $stocks->cpage,
        'order_by' => 'DESC',
        'items_per_page' => 5 ,
        'condition' => '',
    );
    $stock_list = $stocks->stock_list_pagination_dashboard($result_args);

?>  
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">Brand Name </th>
                            <th class="column-title">Product Name </th>
                            <th class="column-title">Available Stock </th>
                           <!--  <th class="column-title ">Is Alert </th> -->
                        </tr>
                    </thead>

                    <tbody>
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
                                    <td class="brand" <?php if($s_value->is_alert == 1){ echo'style="color:red"'; } ?>><?php echo $s_value->brand_name; ?></td>
                                    <td class="product" <?php if($s_value->is_alert == 1){ echo'style="color:red"'; } ?>><?php echo $s_value->product_name; ?></td>
                                    <td class="bal_stock" <?php if($s_value->is_alert == 1){ echo'style="color:red"'; } ?>><?php echo $s_value->balance_stock; ?></td>
                                   <!--  <td class="alert"><?php echo $s_value->is_alert; ?></td> -->
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