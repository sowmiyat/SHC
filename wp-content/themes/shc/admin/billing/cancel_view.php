<?php
    $bill_data = false;
    $invoice_id['inv_id'] = '';

    if(isset($_GET['id']) && $_GET['id'] != ''  ) {
            $update = true;
            $year = $_GET['year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getCancelBillData($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $invoice_id['inv_id'] = $bill_fdata->inv_id;
            $bill_id = $bill_fdata->id;
            $gst_slab = gst_group_cancel_retail($bill_id);
            $gst_data = $gst_slab['gst_data'];

       
    }
$profile = get_profile1();
$netbank = get_netbank1();
$payment_type = paymenttypeGroupByType($_GET['id'],$_GET['year']);
$tot_paid_amt = 0;
foreach ($payment_type['WithOutCredit'] as $p_value) {
  $tot_paid_amt = $p_value->amount + $tot_paid_amt;
 }
?>

<script>
  function print_current_page()
  {
  // window.print();
  var printPage = window.open(document.URL, '_blank');
  setTimeout(printPage.print(), 5);
  }
</script>
<div class="container">
  <div class="row">
    <div class="col-md-12 print-hide">
      <div class="x_panel">
        <div class="x_title">
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

        <?php
            if($bill_data) {
        ?>
          <section class="content invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12 invoice-header">
                    <h4>
                        <i class="fa fa-globe"></i> Invoice Number.   <?php echo 'Inv '.$bill_fdata->id; ?>
                        <small class="pull-right"><?php echo $bill_fdata->created_at; ?></small>
                    </h4>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
                    <br/><?php echo $profile ? $profile->address : '';  ?>
                    <br/><?php echo $profile ? $profile->address2 : '';  ?>
                    <br/>Cell : <?php echo $profile ? $profile->phone_number : '';  ?>
                    <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?>
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong><?php echo $bill_fdata->customer_name; ?></strong>
                    <br><?php echo $bill_fdata->address; ?>
                    <br><?php echo $bill_fdata->mobile; ?>
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                <b>Order ID:</b> <?php echo $bill_fdata->order_id; ?>
                <br>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
              <div class="col-xs-12 table">
                <h2>Billed Items</h2>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width:50px;">S.No</th>
                      <th style="width:300px;">PRODUCT</th>
                      <th style="width:300px;">HSN CODE</th>
                      <th style="width:80px;">QTY</th>
                      <th style="width:120px;">MRP</th>
                      <th style="width:140px;">DISCOUNT</th>
                      <th style="width:140px;">AMOUNT</th>
                      <th style="width:90px;">CGST</th>
                      <th style="width:90px;">CGST AMOUNT</th>
                      <th style="width:90px;">SGST</th>
                      <th style="width:90px;">SGST AMOUNT</th>
                      <th style="width:120px;">SUB TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                            $i = 1;
                            foreach ($bill_ldata as $d_value) {
                              $is_delivery =$d_value->is_delivery;
                                if($is_delivery == 1){
                                  $checked = 'checked';
                                }
                                else {
                                  $checked = '';
                                }
                    ?>
                        <tr>
                          <td>
                            <div class="rowno"><?php echo $i; ?></div>
                          </td>
                          <td>
                            <span class="span_product_name"><?php echo $d_value->product_name; ?></span>
                          </td>
                          <td>
                            <span class="span_hsn"><?php echo $d_value->hsn; ?></span>
                          </td>
                          <td>
                            <span class="span_unit_count"><?php echo $d_value->sale_unit; ?><span>
                              <input type="hidden" value="<?php echo $d_value->sale_unit; ?>" name="unit_count" class="unit_count"/> 
                          </td>
                          <td>
                            <span class="span_unit_price"><?php echo $d_value->unit_price; ?><span>
                          </td> 
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->discount; ?><span>
                          </td>
                          <td>
                            <span class="span_sub_total"><?php echo $d_value->amt; ?></span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->cgst; ?><span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->cgst_value; ?><span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->sgst; ?><span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->sgst_value; ?><span>
                          </td>                               
                          <td>
                            <span class="span_sale_tax"><?php echo $d_value->sub_total; ?></span>
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
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
              <!-- accepted payments column -->
              <div class="col-xs-6">

              </div>
              <div class="col-xs-2">
              </div>

              <!-- /.col -->
              <div class="col-xs-4">
                <p class="lead"></p>
                <div class="table-responsive">
                  <table class="table">
                                       <tbody>
                      <tr>
                        <th style="width:50%">Total:</th>
                        <td><?php echo $bill_fdata->before_total; ?></td>
                      </tr>
                     <!--  <tr>

                        <th>Discount:</th>
                        <td><?php if($bill_fdata->discount_type == 'cash') {
                        echo $bill_fdata->discount; }
                        else {
                          echo $bill_fdata->discount + 0;
                          echo '%';
                        }
                        ?></td>
                      </tr> -->
                      <?php if( $bill_fdata->before_total != $bill_fdata->sub_total) { ?>
                       <tr>

                        <th>Discount Amount(In Rs):</th>
                        <td> <?php 
                              $before_total = $bill_fdata->before_total;  
                              $after_total  = $bill_fdata->sub_total;
                              $discount = $before_total - $after_total;
                              echo $discount .'.00';
                            ?>
                        </td>
                      </tr>
                      
                      <tr>
                        <th style="width:50%">Amount Payable:</th>
                        <td><?php echo $bill_fdata->sub_total; ?></td>
                      </tr>
                      <?php } ?>
                       <!-- <tr>
                        <th>Amount Paid:</th>
                        <td><?php echo $bill_fdata->sub_total; ?></td>
                      </tr> -->
                    <!--   <tr>
                        <th>Amount Return:</th>
                        <td><?php echo $bill_fdata->pay_to_bal; ?></td>
                      </tr> -->
                     <!--  <tr>
                        <th>Due Amount:</th>
                        <td><?php echo $bill_fdata->sub_total - $tot_paid_amt; ?></td>
                      </tr> -->
                     <!--  <tr>
                        <th>Payment Type:</th><td>


                        <?php $internet_check = '';
                            $total_paid = 0;
                          foreach ($payment_type['WithOutCredit'] as $p_value) {
                            if($p_value->payment_type == 'cash'){
                              echo 'Cash : ';
                              echo  $p_value->amount.'</br>';
                            } 
                            if($p_value->payment_type == 'card'){
                              echo 'Card : ';
                              echo $p_value->amount.'</br>';
                            }
                            if($p_value->payment_type == 'cheque'){

                              echo 'Cheque : ';
                              echo  $p_value->amount.'</br>';
                            } 
                            if($p_value->payment_type == 'internet'){
                              $internet_check = $p_value->payment_type; 
                              echo 'Netbanking : ';
                              echo $p_value->amount.'</br>';
                              ?>
                              <table>
                                <tr> <td><b>Banking Details,</b></td><td></td></tr>
                                <tr> <td>Name</td><td> : <?php echo $netbank ? $netbank->shop_name : ''; ?></td></tr>
                                <tr> <td>Bank Name</td><td> : <?php echo $netbank ? $netbank->bank : ''; ?></td></tr>
                                <tr> <td>Account Number</td><td> : <?php echo $netbank ? $netbank->account : ''; ?></td></tr>
                                <tr> <td>IFSC Code</td><td> : <?php echo $netbank ? $netbank->ifsc : ''; ?></td></tr>
                                <tr> <td>Account Type</td><td> : <?php echo $netbank ? $netbank->account_type : ''; ?></td></tr>
                                <tr> <td>Branch</td><td> : <?php echo $netbank ? $netbank->branch : ''; ?></td></tr>
                              </table>
                              <?php 
                             
                            } 
                            $total_paid = $p_value->amount + $total_paid;

                          }
                          foreach ($payment_type['WithCredit'] as $p_value) {
                              if($p_value->payment_type == 'credit'){
                                  echo  'Credit : ';
                                  echo $bill_fdata->sub_total - $total_paid.'</br>';
                              } 
                          }
                        ?>
                        
                      </td>
                    </tr> -->
                     <!--  <tr>
                        <th>COD</th>
                        <td>

                          <?php if($bill_fdata->cod_check == '1') {
                                  echo $bill_fdata->sub_total - $total_paid;
                                } 
                          ?>
                        </td>
                      </tr> -->
                      
                     <!--  <tr>
                        <th>Delivery: <br/></th>
                          <td>
                             <?php  $is_delivery = $bill_fdata->is_delivery;
                                    if($is_delivery == '1'){     
                                      echo $bill_fdata->home_delivery_name;
                                      echo "<br/>";
                                      echo $bill_fdata->home_delivery_mobile;
                                      echo "<br/>";
                                      echo $bill_fdata->home_delivery_address; 
                                    }
                            ?> 
                          </td> 
                      </tr> -->
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            
          </section>


          <?php
            }
          ?>

            <a class="btn btn-default pull-right bill_retail_print" href="javascript:void(0)" target="_blank" onclick="print_current_page();"><i class="fa fa-print"></i> Print</a>

        </div>
      </div>
    </div>
  </div>
</div>



 <div class="clearfix"></div>

<script type="text/javascript">
    jQuery('.pull-right').focus();
</script>

























<style type="text/css" >

  
    @media screen {
    .A4_HALF .footer {
      bottom: 0px;
      left: 0px;
    }
    .A4_HALF{
      display: none;
    }
    .A4_HALF .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    }

  }
  /** Fix for Chrome issue #273306 **/
  @media print {
    #adminmenumain, #wpfooter, .print-hide {
      display: none;
    }
    body, html {
      height: auto;
      padding:0px;
      font-family: normal;
      font-size: 13px;
      
    }
    html.wp-toolbar {
      padding:0;
    }
    #wpcontent {
      background: white;
      box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
      margin: 1mm;
      display: block;
      padding: 0;
    }




    .A4_HALF .footer {
      position: fixed;
      bottom: 0px;
      left: 0px;
    }
    .A4_HALF .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    }
  }

      @page { margin: 0;padding: 0; }
      .sheet {
        margin: 0;
        font-size: 14px
      }


      .A4_HALF {
        width: 100mm;
        position: relative;
      }
      .inner-container {
        padding-left: 20mm;
        padding-right: 20mm;
        width: 100mm;

      }

      .left-float {
        float: left;
      }

      .text-center {
        text-align: center;
      }
      .text-rigth {
        text-align: right;
      }
      .table td, .table th {
        background-color: transparent !important;
      }


      .table>tbody>tr>td {
        padding: 0 3px;
        height: 20px;
      }
      .table-bordered>tbody>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
      }

      .A4_HALF h3 {
        margin-top: 10px;
      }

.strikethrough {
  position: relative;
}
.strikethrough:before {
  position: absolute;
  content: "";
  left: 0;
  top: 50%;
  right: 0;
  border-top: 1px solid;
  border-color: inherit;

  -webkit-transform:rotate(-70deg);
  -moz-transform:rotate(-70deg);
  -ms-transform:rotate(-70deg);
  -o-transform:rotate(-70deg);
  transform:rotate(-70deg);
}
      
</style>   
 <div class="A4_HALF strikethrough" >
  <div class="sheet padding-10mm">
    <?php
      if($bill_data) {
    ?>
    <b><h1>CANCELLED BILL</h1></b>
    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr class="text-center" >
        <td valign='top' WIDTH='50%'>
            <strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
            <span style=" font-size: 13px;line-height:12px; " ><br/><?php echo $profile ? $profile->address : '';  ?>
            <br/><?php echo $profile ? $profile->address2 : '';  ?>    
            <br/>PH : <?php echo $profile ? $profile->phone_number : '';  ?>
            <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?></span>
        </td>
      </tr>
    </table>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
        <td valign='top' WIDTH='50%'>Customer : <?php echo $bill_fdata->customer_name; ?> </td>         
        <td valign='top' WIDTH='50%'>Address : <?php echo $bill_fdata->address; ?></td>         
      </tr>
      <tr>        
        <td valign='top' WIDTH='50%'>Phone No :<?php echo $bill_fdata->mobile; ?> </td>     
      </tr>
      
    </table>
    <div class="text-center" >CASH BILL</div>
    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
        <td valign='top' WIDTH='70%'>Inv No : <b><?php echo $bill_fdata->inv_id; ?></b></td>
        <td valign='top' WIDTH='70%'>Time : </td>     
      </tr>
      <tr>
        <td valign='top' WIDTH='30%'>Date : <?php echo date("d/m/Y"); ?></td>
        <td valign='top' WIDTH='30%'> </td>
      </tr>
    </table>
    <style>
      .dotted_border_top  {
        border-top: 1px dashed #000;        

      }
      .dotted_border_bottom  {        
        border-bottom: 1px dashed #000;
      }
    </style>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" >
      <tr>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>SNO</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>PRODUCT</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>HSN</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>QTY</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>MRP</th>
       <!--  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>Dis.Price</th> -->
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>TOTAL</th>
      </tr>
      <tr>

      </tr>
      <?php
        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
          $i = 1;
          foreach ($bill_ldata as $d_value) {
      ?>
                
      <tr>
      <td valign='top'  align='center'><?php echo $i; ?></td>
      <td valign='top'  align='center'><?php echo $d_value->product_name; ?></td>
      <td valign='top'  align='center'><?php echo $d_value->hsn; ?></td>
      <td valign='top'  align='center'><?php echo $d_value->sale_unit; ?></td>
      <td valign='top'  align='center'><?php echo $d_value->unit_price; ?></td>
     <!--  <td valign='top' align='left'><?php echo $d_value->discount; ?></td> -->
      <td valign='top' align='center'><?php echo $d_value->total; ?>&nbsp;&nbsp;&nbsp;</td></tr>

      <?php
        $i++;
          }
        } 
        ?> 

     
    </table>


      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
      <!-- <tr>
        <td valign='top' colspan="3" >No.Of.Items : 4    </td>
        <td valign='top'  align='right' >Total Qty : 10&nbsp;&nbsp;&nbsp;</td>
      </tr> -->
      
       <tr> 
         <td class="dotted_border_top " colspan="6" valign='bottom' align='right'><b>AMOUNT</b></td>
         <td  class="dotted_border_top " valign='bottom' align='right'><span class="amount"><?php echo ''.$bill_fdata->before_total.''; ?>&nbsp;&nbsp;&nbsp;</span></td>
      </tr>
      <tr> 
         <td class="dotted_border_top " colspan="6" valign='bottom' align='right'><b>DISCOUNT</b></td>
         <td  class="dotted_border_top " valign='bottom' align='right'><span class="amount">           
          <?php 
            
          $before_total = $bill_fdata->before_total;  
          $after_total  = $bill_fdata->sub_total;
          $discount = $before_total - $after_total;
          echo $discount .'.00';
          ?>&nbsp;&nbsp;&nbsp;</span></td>
      </tr>
      <tr> 
         <td class="dotted_border_top dotted_border_bottom" colspan="6" valign='bottom' align='right'><b>TOTAL AMOUNT</b></td>
         <td  class="dotted_border_top dotted_border_bottom" valign='bottom' align='right'><span class="amount"><?php echo '<b>'.$bill_fdata->sub_total.'</b>'; ?>&nbsp;&nbsp;&nbsp;</span></td>
      </tr>
    </table>

          <?php
            }
          ?>
                <!-- <table class="table table-bordered" style="margin-top:10px;margin-bottom: 5px;width: 120mm;"> -->
    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style="font-size:15px;" >
      <thead>
        <tr>
          <th colspan="5" class="dotted_border_bottom"  align="center" >GST Details</th>
        </tr>     
        <tr>
          <th valign='top' class="center-th" style="width:90px;padding:0;" rowspan="2">
            <div class="text-center">Taxable Value</div>
          </th>
          <th class="center-th" style="padding: 0;" colspan="2">
            <div class="text-center">CGST</div>
          </th>
          <th class="center-th" style="padding: 0;" colspan="2">
            <div class="text-center">SGST</div>
          </th>
        </tr>
        <tr>
          <th style="padding: 0;width: 70px;"><div class="text-center">Rate</div></th>
          <th style="padding: 0;width: 70px;"><div class="text-center">Amount</div></th>
          <th style="padding: 0;width: 70px;"><div class="text-center">Rate</div></th>
          <th style="padding: 0;width: 70px;"><div class="text-center">Amount</div></th>
        </tr>
      </thead>
      <tbody>


      <?php  
      if(isset($gst_data)) { 
          $total_tax=0;
          $gst_tot=0;
        foreach( $gst_data as $g_data) {
      ?>
          <tr class="">
            <td class=""><div class="text-right">Rs. <?php  echo $g_data->sale_amt; ?></div></td>
            <td class=""><div class="text-right"><?php echo $g_data->cgst; ?> % </div></td>
            <td class=""><div class="text-right"><?php echo $g_data->sale_cgst; ?></div></td>
            <td class=""><div class="text-right"><?php echo $g_data->cgst; ?> % </div></td>
            <td class=""><div class="text-right"><?php echo $g_data->sale_sgst; ?></div></td>
          </tr>
           <?php 
           $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
           $gst_tot = $g_data->sale_sgst + $gst_tot;

        }
      } ?>
      <tr class="">
        <td class=""><div class="text-right"></div></td>
        <td class=""><div class="text-right"></div></td>
        <td class=""><div class="text-right"><?php echo $gst_tot; ?></div></td>
        <td class=""><div class="text-right"></div></td>
        <td class=""><div class="text-right"><?php echo $gst_tot; ?></div></td>
      </tr>
      <tr>
        <td  class="dotted_border_bottom" colspan="4">
          <div class="text-center">
            <b>Total Tax</b>
          </div>
        </td>
        <td class="dotted_border_bottom" >
          <div class="text-right">
           <b><?php echo $total_tax; ?></b>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
    <div style="text-align: center;" >Thank You !!!. Visit Again !!!.</div>
  <?php 
    $is_delivery = $bill_fdata->is_delivery; 
    if($is_delivery == '1') { 
      ?>
  <table>
    <tr><td>Delivery To</td><td>  </td></tr>
    <tr><td>Name</td><td>  : <?php echo $bill_fdata->home_delivery_name; ?></td></tr>
    <tr><td>Address</td><td>  : <?php echo $bill_fdata->home_delivery_mobile; ?></td></tr>
    <tr><td>Phone No</td><td>  : <?php echo $bill_fdata->home_delivery_address ; ?></td></tr>
  </table> 
  <?php } ?>


  </div>
  <div>
 
    <br/>
  </div>
</div>

