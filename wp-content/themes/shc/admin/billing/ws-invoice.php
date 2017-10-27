<?php
    $bill_data = false;
    $invoice_id['inv_id'] = '';

    if(isset($_GET['id']) && $_GET['id'] != '' ) {
        if(isValidInvoicews($_GET['id'], 1)){
            $update = true;
            $year = $_GET['year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getBillDataws($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $invoice_id['inv_id'] = $bill_fdata->inv_id;

        }
        else {
             echo "<script>alert('INVOICE NOT FOUND!!! Try another number');</script>";
        }
    }

?>
<style>
.ui-widget-header{
    color:#575f6c;
}
.ui-datepicker-calendar { display: none; }

</style>
<div class="container">
  <div class="row">
    <div class="col-md-12 print-hide">
      <div class="x_panel">
        <div class="x_title">
            <form action="<?php menu_page_url( 'ws_invoice' ); ?>" method="GET">
                  <h2>Invoice 
                      <input type="hidden" name="page" value="ws_invoice">
                      <input type="text" name="id" class="invoice_id" value="<?php echo $invoice_id['inv_id']; ?>" autocomplete="off">
                       Year
                      <select name="year" class="year">
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                      </select>
                      <input type="submit" style="height: 38px;margin-left: 20px;" class="btn btn-success">
                  </h2>
                <button class="btn btn-default ws_print_bill pull-right"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-primary pull-right ws_generate_bill" style="margin-right: 5px;"><i class="fa fa-file-pdf-o" href=""></i> Generate PDF</button>
            </form>
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
                    <h1>
                        <i class="fa fa-globe"></i> Invoice Number.   <?php echo $bill_fdata->id; ?>
                        <small class="pull-right"><?php echo $bill_fdata->created_at; ?></small>
                    </h1>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>Saravana Health Store</strong>
                    <br>7/12,Mg Road,Thiruvanmiyur
                    <br>Chennai,Tamilnadu,
                    <br>Pincode-600041.
                    <br>Cell:9841141648.
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
                <b>Invoice <?php echo $bill_fdata->inv_id; ?></b>
                <br>
                <br>
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
                      <th style="width:90px;">SGST AMONUT</th>
                      <th style="width:120px;">SUB TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                            $i = 1;
                            foreach ($bill_ldata as $d_value) {
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
               <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">TAXABLE VALUE</th>
                        <th colspan="2">CENTRAL SALES TAX</th>
                        <th colspan="2">STATE SALES TAX </th> 
                    </tr>
                    <tr>
                        
                        <th>CGST-RATE</th> 
                        <th>CGST-AMOUNT</th>
                        <th>SGST-RATE</th> 
                        <th>SGST-AMOUNT</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                                $tax_amount_zero            = '0.00';
                                $tax_amount_five            = '0.00';
                                $tax_amount_twelve          = '0.00';
                                $tax_amount_eighteen        = '0.00';
                                $tax_amount_twentyeight     = '0.00';
                                $cgst_amount_zero           = '0.00';
                                $cgst_amount_five           = '0.00';
                                $cgst_amount_twelve         = '0.00';
                                $cgst_amount_eighteen       = '0.00';
                                $cgst_amount_twentyeight    = '0.00';
                            foreach ($bill_ldata as $d_value) {   

                                if($d_value->cgst == '0.00'){
                                    $tax_amount_zero    = $tax_amount_zero + $d_value->sub_total;
                                    $cgst_amount_zero   = $cgst_amount_zero + $d_value->cgst_value;
                                }

                                else if($d_value->cgst == '2.50'){
                                    $tax_amount_five     = $tax_amount_five  + $d_value->sub_total;
                                    $cgst_amount_five    = $cgst_amount_five  + $d_value->cgst_value;
                                    
                                }
                                else if($d_value->cgst == '6.00'){
                                    $tax_amount_twelve    = $tax_amount_twelve + $d_value->sub_total;
                                    $cgst_amount_twelve   = $cgst_amount_twelve + $d_value->cgst_value;
                                }
                                else if($d_value->cgst == '9.00'){
                                    $tax_amount_eighteen    = $tax_amount_eighteen + $d_value->sub_total;
                                    $cgst_amount_eighteen   = $cgst_amount_eighteen + $d_value->cgst_value;
                                }
                                else if($d_value->cgst == '14.00'){
                                    $tax_amount_twentyeight    = $tax_amount_twentyeight + $d_value->sub_total;
                                    $cgst_amount_twentyeight   = $cgst_amount_twentyeight + $d_value->cgst_value;
                                }
                                else {

                                    return false;
                                }

                            }

                            $tax_amount = $tax_amount_zero + $tax_amount_five + $tax_amount_twentyeight + $tax_amount_eighteen + $tax_amount_twelve;
                            $cgst_amount = $cgst_amount_zero + $cgst_amount_five + $cgst_amount_twelve + $cgst_amount_eighteen + $cgst_amount_twentyeight;
                        }


                    ?>
                    <?php  if($tax_amount_zero != '0.00'){ ?>
                    <tr class="zero">
                        <td class="amt_zero">Rs. <?php  echo $tax_amount_zero; ?></td>
                        <td class="cgst_zero">0.00 % </td>
                        <td class="cgst_val_zero"><?php echo $cgst_amount_zero; ?></td>
                        <td class="sgst_zero">0.00 %</td>
                        <td class="sgst_val_zero"><?php echo $cgst_amount_zero; ?></td>
                    </tr>
                    <?php } if($tax_amount_five != '0.00'){ ?>
                    <tr class="five">
                        <td class="amt_five">Rs.<?php  echo $tax_amount_five; ?></td>
                        <td class="cgst_five">2.50 % </td>
                        <td class="cgst_val_five"><?php echo $cgst_amount_five; ?></td>
                        <td class="sgst_five">2.50 %</td>
                        <td class="sgst_val_five"><?php echo $cgst_amount_five; ?></td>
                    </tr>
                    <?php } if($tax_amount_twelve != '0.00'){ ?>
                    <tr class="twelve">
                        <td class="amt_twelve">Rs.<?php  echo $tax_amount_twelve; ?></td>
                        <td class="cgst_twelve">6.00 % </td>
                        <td class="cgst_val_twelve"><?php echo $cgst_amount_twelve; ?></td>
                        <td class="sgst_twelve">6.00 %</td>
                        <td class="sgst_val_twelve"><?php echo $cgst_amount_twelve; ?></td>
                    </tr>
                    <?php } if($tax_amount_eighteen != '0.00'){ ?>
                    <tr class="eighteen">
                        <td class="amt_eighteen">Rs.<?php  echo $tax_amount_eighteen; ?></td>
                        <td class="cgst_eighteen">9.00 % </td>
                        <td class="cgst_val_eighteen"><?php echo $cgst_amount_eighteen; ?></td>
                        <td class="sgst_eighteen">9.00 %</td>
                        <td class="sgst_val_eighteen"><?php echo $cgst_amount_eighteen; ?></td>
                    </tr>
                    <?php } if($tax_amount_twentyeight != '0.00'){ ?>
                    <tr class="twentyeight">
                        <td class="amt_twentyeight">Rs.<?php  echo $tax_amount_twentyeight; ?></td>
                        <td class="cgst_twentyeight">14.00 % </td>
                        <td class="cgst_val_twentyeight"><?php echo $cgst_amount_twentyeight; ?></td>
                        <td class="sgst_twentyeight">14.00 %</td>
                        <td class="sgst_val_twentyeight"><?php echo $cgst_amount_twentyeight; ?></td>
                    </tr> 
                    <?php } ?>
                    <tr><td></td><td></td><td></td><td>Total Tax</td><td><?php echo $cgst_amount + $cgst_amount; ?></td></tr> 
                
            </table>
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

                        <th>Discount:</th>
                        <td><?php if($bill_fdata->discount_type == 'cash') {
                        echo $bill_fdata->discount; }
                        else {
                          echo $bill_fdata->discount + 0;
                          echo '%';
                        }
                        ?></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td><?php echo $bill_fdata->sub_total; ?></td>
                      </tr>
                       <tr>
                        <th>Paid Amount:</th>
                        <td><?php echo $bill_fdata->paid_amount; ?></td>
                      </tr>
                      <tr>
                        <th>Balance Amount:</th>
                        <td><?php echo $bill_fdata->return_amt; ?></td>
                      </tr>
                      <tr>
                        <th>Payment Type:</th>
                        <td><?php echo $bill_fdata->payment_type; ?></td>
                      </tr>
                       <tr>
                        <th>Home Delivery:<br/></th>
                          <td>
                             <?php 
                                
                                   echo $bill_fdata->home_delivery_name;
                                    echo "<br/>";
                                    echo $bill_fdata->home_delivery_mobile;
                                    echo "<br/>";
                                    echo $bill_fdata->home_delivery_address; 
                               

                            ?> 
                          </td> 
                      </tr>
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


        </div>
      </div>
    </div>
  </div>
</div>



  

























































































<style type="text/css">
  @media screen {
    .A4 {
      display: none;
    }

    .A4 .footer {
      bottom: 0px;
      left: 0px;
    }
    .A4 .footer .foot {
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




    .A4 .footer {
      position: fixed;
      bottom: 0px;
      left: 0px;
    }
    .A4 .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    }
  }

  @page { margin: 0;padding: 0; }
  .sheet {
    margin: 0;
  }


      .A4 {
        width: 210mm;
      }
      .inner-container {
        padding-left: 20mm;
        padding-right: 20mm;
        width: 210mm;
      }
      .left-float {
        float: left;
      }


      .company-detail {
        height: 100px;
      }
      .company-detail .company-name h3 {
        font-family: serif;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 3px;
      }
      .company-detail .company-address-txt {
          font-size: 13px;
          font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
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

      .billing-title {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
          text-decoration: underline;
      }
      .A4 h3 {
        margin-top: 0px;
      }








  .company-logo {
    width: 50mm;
  }
  .company-address {
    width: 70mm;
  }
  .invoice-detail {
    width: 50mm;
  }

  .invoice-no {
    margin-bottom: 15px;
    font-size: 18px;
  }
  .buyer-detail, .delivery-detail {
    min-height: 100px;
    padding: 20px 10px 20px 10px;
  }
  .buyer-address, .delivery-address {
    padding-left: 10px;
    min-height: 80px;
  }
  .header-txt {
    font-size: 10px;
  }
  .sale-table-invoice tbody {
    font-size: 13px;
  }


</style>



  <div class="A4">
    <div class="sheet padding-10mm">









      <table> 
        <thead>
          <tr>
            <td>
              <div class="customer-detail inner-container" style="margin-top: 20px;margin-bottom:2px;">
                  <table>
                    <tr>
                      <td>
                        <div class="company-logo">
                          <img style="width:165px" src="<?php echo get_template_directory_uri().'/admin/billing/inc/images/tax.png'; ?>">
                        </div>
                      </td>
                      <td>
                        <div class="company-address company-detail">
                          <div class="company-name">
                            <h3>SARAVANA HEALTH STORE</h3>
                          </div>
                          <div class="company-address-txt">
                            No-12/7, MG Road,
                          </div>
                          <div class="company-address-txt">
                            Thiruvanmiyur,
                          </div>
                          <div class="company-address-txt">
                            Chennai - 600041
                          </div>
                          <div class="company-address-txt">
                            <b>GST No - 33BMDPA4840E1ZP</b>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="invoice-detail company-detail">
                          <div class="company-address-txt">
                            <div class="invoice-no">INVOICE NO - 3</div>
                          </div>
                          <div class="company-address-txt">
                            <b>DATE - 13/10/2017</b>
                          </div>

                          <div class="company-address-txt">
                            <b>STATE : TAMILNADU</b>
                          </div>
                          <div class="company-address-txt">
                            <b>STATE CODE : 33</b>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </table>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="customer-detail inner-container" style="margin-top: 20px;margin-bottom:2px;">
                
              </div>
            </td>
          </tr>
        </thead>
        <tbody>

      <?php
        $pages = false;
        $per_page = 12;
        $pieces = false;
        $tota_row = 0;

        if($bill_data) {
          $pages = ceil(count($bill_ldata)/$per_page);
          $pieces = array_chunk($bill_ldata, $per_page);
          $tota_row = count($bill_ldata);
          $reminder = ($tota_row % $per_page);
        }


        $page_total[-1] = 0;
        for ($i = 0; $i < $pages; $i++) { 
          $tot_tmp = 0;
          foreach ($pieces[$i] as $key => $h_value) {
            $tot_tmp = $tot_tmp + $h_value->hiring_amt;
          }
          $page_total[$i] = $page_total[$i-1] + $tot_tmp;
        }


            for ($i = 0; $i < $pages; $i++) { 
              $page_start = ( $i * $per_page ) + 1;
              $current_page = ($i + 1);
          ?>
            <tr>
              <td>
                <div class="inner-container" style="margin-top: 0px;">
                  <div class="bill-detail">
                    <table class="table table-bordered sale-table-invoice" style="margin-bottom: 2px;">
                      <thead>
                        <tr>
                          <th colspan="7">
                            <div class="buyer-detail">
                              BUYER,<br>
                              <div class="buyer-address">
                                RAIN FOREST RESTAURANT,<br>
                                No: 41-42, 1st Main Road,<br>
                                Adyar, Chennai - 600020,<br>
                                <b>GST NO - 33ALCPP8244Q1ZJ</b>
                              </div>
                            </div>
                          </th>
                          <th colspan="5">
                            <div class="delivery-detail">
                              DELIVERY ADDRESS,<br>
                              <div class="delivery-address">
                                
                              </div>
                            </div>
                          </th>
                        </tr>
                        <tr class="header-txt">
                          <th style="width:25px;padding:0;line-height: 40px;" class="center-th" rowspan="2">
                            <div class="text-center">S.No</div>
                          </th>
                          <th class="center-th" style="width:50px;line-height: 15px;" rowspan="2">
                            <div class="text-center">HSN<br>CODE</div>
                          </th>
                          <th class="center-th" style="line-height: 40px;" rowspan="2">
                            <div class="text-center">PRODUCTS</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 40px;" rowspan="2">
                            <div class="text-center">QTY</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 13px;" rowspan="2">
                            <div class="text-center">MRP<br>Per Piece</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 15px;" rowspan="2">
                            <div class="text-center">DISCOUNTED<br>Price</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 40px;" rowspan="2">
                            <div class="text-center">AMOUNT</div>
                          </th>
                          <th class="center-th" style="padding: 0;" colspan="2">
                            <div class="text-center">CGST</div>
                          </th>
                          <th class="center-th" style="padding: 0;" colspan="2">
                            <div class="text-center">SGST</div>
                          </th>
                          <th class="center-th" style="padding: 0;width: 80px;line-height: 15px;" rowspan="2">
                            <div class="text-center">TOTAL<br>AMOUNT</div>
                          </th>
                        </tr>
                        <tr class="header-txt">
                          <th style="padding: 0;width: 35px;"><div class="text-center">RATE</div></th>
                          <th style="padding: 0;width: 50px;"><div class="text-center">AMOUNT</div></th>
                          <th style="padding: 0;width: 35px;"><div class="text-center">RATE</div></th>
                          <th style="padding: 0;width: 50px;"><div class="text-center">AMOUNT</div></th>
                        </tr>
                      </thead>


                      <?php
                      if($current_page > 1) {
                      ?>
                        <tr>
                          <td></td>
                          <td>
                            <div class="text-center">BF / TOTAL</div>
                          </td>
                          <td></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-right">-</div></td>
                          <td>
                            <div class="text-right">
                              jhgjjhgj
                            </div>
                          </td>
                        </tr>
                      <?php
                      }
                      foreach ($pieces[$i] as $key => $value) {
                      ?>
                        <tr>
                          <td>
                            <div class="text-center">
                              <?php echo $page_start ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-center">
                              <?php echo $value->hsn; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-left">
                              <?php echo $value->product_name; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-center">
                              <?php echo $value->sale_unit; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->unit_price; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->discount;?>
                            </div>
                          </td>
                          <td>
                              <?php echo $value->amt; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-center" style="text-align: right;">
                              <?php echo $value->cgst; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->cgst_value; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-center">
                              <?php echo $value->sgst; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->sgst_value; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->sub_total; ?>
                            </div>
                          </td>
                        </tr>

                      <?php
                        $page_start++;
                      }
                        if($pages == $current_page) {
                      ?>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          
                          <tr>
                            <td colspan="11"><div class="text-center">Total (Hire Charges)</div></td>
                            <td>
                              <div class="text-rigth">
                                200
                              </div>
                            </td>
                          </tr>
                          
                      <?php
                        } else {
                      ?>
                          <tr>
                            <td colspan="11">
                              <div class="text-center">CF / TOTAL</div>
                            </td>
                            <td>
                              <div class="text-right">
                                45435
                              </div>
                            </td>
                          </tr>
                      <?php
                        }

                      ?>
                      
                    </table>
                  </div>
                </div>
              </td>
            </tr>
          <?php
            }
          ?>
        </tbody>
      </table>

      <div class="inner-container" style="margin-top: 0px;">
        <div>Amount Chargable (in words)</div>
        <b>INR <?php echo convert_number_to_words_full('300'); ?></b>


        <table class="table table-bordered" style="margin-top:10px;margin-bottom: 5px;width: 120mm;">
          <thead>
            <tr>
              <th class="center-th" style="width:90px;padding:0;" rowspan="2">
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
                <tr>
                  <td>
                    <div class="text-right">
                      567567
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      454
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      45
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="text-right">
                      567567
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      454
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      45
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="text-right">
                      567567
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      454
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      45
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="text-right">
                      567567
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      454
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      45
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="text-right">
                      567567
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      454
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      9%
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      45
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="4">
                    <div class="text-center">
                      Total Tax
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                      45454
                    </div>
                  </td>
                </tr>
          </tbody>
        </table>
        <table>
          <div>Tax Amount (in words)</div>
          <b>INR fghfghfg</b>
        </table>
      </div>


<style type="text/css">
  .customer-signature, .company-signature {
    width: 85mm;
  }
</style>

      <div class="footer" style="margin-bottom:20px;">
          <div class="inner-container" style="margin-top: 5px;">

            <table>
              <tr>
                <td colspan="2">
                  <b><u>Declaration</u></b>
                  <div style="margin-bottom:20px;">We declare that  this  invoice  shows  the  actual price of the goods described and that all particulars are true and correct</div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="customer-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 16px;">
                      For Saravana Health Store
                    </div>
                    <div style="height: 80px;"></div>
                  </div>
                </td>
                <td>
                  <div class="company-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 16px;text-align:right;">
                      Customer Seal & Signature
                    </div>
                    <div style="margin-top: 60px;text-align:right;">Authorised Signatory</div>
                  </div>
                </td>
              </tr>
            </table>

          </div>
      </div>


    </div>
  </div>








<?php //require get_template_directory().'/admin/billing/inline_invoice/ws-invoice.php';  ?>