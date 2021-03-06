<?php
$stock = false;
if(isset($_GET['stock_id']) && $stock = get_stock($_GET['stock_id'])) {
	$stock_id = $_GET['stock_id'];

}

?>

<div class="">
	<div class="">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Add New Stock <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left stock_validation" id="add_stock">
						<div class="form-group">
							<input type="hidden" value="off" name="form_submit_prevent" class="form_submit_prevent_stock" id="form_submit_prevent_stock"/>
							<input type="hidden" name="lot_number" class="lot_number" id="lot_number" value="<?php if($stock) { echo $stock->lot_number; }?>"/>
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="pro_number" name="pro_number" required="required" class="form-control col-md-7 col-xs-12 pro_number"  value="<?php echo ($stock) ? $stock->product_name : ''; ?>">
								<input type="hidden" id="product_name" class="form-control col-md-7 col-xs-12 product_name"  value="<?php echo ($stock) ? $stock->product_name : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Count / Unit <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="stock_count" name="stock_count" required="required" autocomplete="off" onkeypress="return isNumberKey(event)" maxlength="10" class="form-control col-md-7 col-xs-12 stock_count"   value="<?php echo ($stock) ? $stock->stock_count : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Brand Name <span class="required"></span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="brand_name" readonly class="form-control col-md-7 col-xs-12" tabindex="-1" value="<?php echo ($stock) ? $stock->brand_name : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Selling Price <span class="required"></span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="selling_price" name="selling_total" tabindex="-1" readonly class="form-control col-md-7 col-xs-12" value="<?php echo ($stock) ? $stock->selling_total : ''; ?>">
								<input type="hidden" id="unit_price" name="selling_price"  readonly class="form-control col-md-7 col-xs-12" value="<?php echo ($stock) ? $stock->selling_price : ''; ?>">
							
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
	                          	<button type="submit" class="btn btn-success submit_form">Submit</button>
	                          	<button class="btn btn-primary stock_cancel" type="button" onclick="window.location = '<?php echo admin_url('admin.php?page=list_stocks'); ?>';">Cancel</button>
							  	

								<?php 
									if(  $stock ) {
										echo '<input type="hidden" name="stock_id" value="'.$stock_id.'">';
										echo '<input type="hidden" name="action" class="stock_action" value="update_stock">';
									} else {
										echo '<input type="hidden" name="action" class="stock_action" value="add_stock">';
									}
								?>


	                        </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</div>

