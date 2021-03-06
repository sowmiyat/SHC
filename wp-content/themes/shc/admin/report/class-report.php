<?php
	class report {
 
		function __construct() {

		    if( isset($_POST['action'])) {
		    	$params = array();
				parse_str($_POST['data'], $params);

		        $this->cpage  = 1;
		        $this->ppage  = isset($params['ppage']) ? $params['ppage'] : 5;
		        $this->bill_from = isset($params['bill_from']) ? $params['bill_from'] : date('Y-m-01');
		        $this->bill_to = isset($params['bill_to']) ? $params['bill_to'] : date('Y-m-t');
		        $this->slap    = isset($params['slap']) ? $params['slap'] :'' ;

		    }  
		    else {
		        $this->cpage 		= isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage 		= isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 5;
		        $this->bill_from 	= isset( $_GET['bill_from'] ) ? $_GET['bill_from']  : date('Y-m-d');
		        $this->bill_to 		= isset( $_GET['bill_to'] ) ? $_GET['bill_to']  : date('Y-m-d');
		        $this->slap   = isset( $_GET['slap'] ) ? $_GET['slap']  : '';
		    }
		}



		function stock_report_pagination( $args ) {
		    global $wpdb;
		    $sale = $wpdb->prefix.'shc_sale';
		    $sale_details =  $wpdb->prefix.'shc_sale_detail';
		    $return_table = $wpdb->prefix.'shc_return_items_details';
		    $ws_sale = $wpdb->prefix.'shc_ws_sale';
		    $ws_sale_details = $wpdb->prefix.'shc_ws_sale_detail';
		    $ws_return_table = $wpdb->prefix.'shc_ws_return_items_details';
		    $lot_table = $wpdb->prefix.'shc_lots';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	    	$page_arg['product_name'] = $this->product_name;
	    	$page_arg['bill_from'] = $this->bill_from;
	    	$page_arg['bill_to'] = $this->bill_to;
	    	$page_arg['slap'] = $this->slap;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';  


		    $bill_from = $this->bill_from;
		    $bill_to   = $this->bill_to;
   			if($this->slap != '') {
		    	$condition .= "AND report.gst = ".$this->slap;
		    }
		    

		  
			$query = "SELECT report.*,lot.brand_name,lot.product_name,lot.hsn from (
    SELECT (sum(final_ws_sale.bal_cgst)) as cgst_value,
    (sum(final_ws_sale.bal_igst)) as igst_value, 
    (sum(final_ws_sale.cess)) as cess,
    (sum(final_ws_sale.bal_total)) as total, 
    (sum(final_ws_sale.bal_unit)) as total_unit, 
    (sum(final_ws_sale.bal_amt)) as amt, 
    final_ws_sale.gst as gst, 
    final_ws_sale.lot_id from 
    (
        SELECT 
        (case when return_table.return_cgst is null then sale_table.sale_cgst else sale_table.sale_cgst - return_table.return_cgst end ) as bal_cgst, 
        (case when return_table.return_igst is null then sale_table.sale_igst else sale_table.sale_igst - return_table.return_igst end ) as bal_igst, 
        (case when return_table.cess is null then sale_table.cess else sale_table.cess - return_table.cess end ) as cess,
        (case when return_table.return_total is null then sale_table.sale_total else sale_table.sale_total - return_table.return_total end ) as bal_total, 
        (case when return_table.return_unit is null then sale_table.sale_unit else sale_table.sale_unit - return_table.return_unit end) as bal_unit, 
        (case when return_table.return_amt is null then sale_table.sale_amt else sale_table.sale_amt - return_table.return_amt end ) as bal_amt, sale_table.cgst as gst, 
        sale_table.lot_id FROM ( 
            SELECT sale_details.cgst,sale_details.lot_id, 
            sum(case when sale.gst_type ='cgst' then  sale_details.cgst_value else 0.00 end) as sale_cgst, 
            sum(case when sale.gst_type ='cgst' then  sale_details.sgst_value else 0.00 end) sale_sgst, 
            sum(case when sale.gst_type ='igst' then  sale_details.igst_value else 0.00 end) sale_igst,
			sum(sale_details.cess_value) as cess,
           	sum(sale_details.sub_total) as sale_total, 
            sum(sale_details.sale_unit) as sale_unit, 
            sum(sale_details.amt) as sale_amt 
            FROM ${sale} as sale 
            left join 
            ${sale_details} as sale_details 
            on sale.`id`= sale_details.sale_id 
            WHERE sale.active = 1 and sale_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by sale_details.lot_id ) 
        as sale_table left join ( 
           SELECT ret_tab.* FROM  (
       SELECT return_details.cgst,
       return_details.lot_id, 
       sum(case when rt.gst_type = 'cgst' then return_details.cgst_value else 0.00 end) as return_cgst, 
       sum(case when rt.gst_type = 'cgst' then return_details.sgst_value else 0.00 end) as return_sgst, 
	   sum(case when rt.gst_type = 'igst' then return_details.igst_value else 0.00 end) as return_igst, 
	   return_details.cess_value as cess,
       sum(return_details.sub_total) as return_total , 
       sum(return_details.return_unit) as return_unit, 
       sum(return_details.amt) as return_amt,
       return_details.sale_id from ${return_table} as return_details left join wp_shc_return_items as  rt on rt.id = return_details.return_id  
       WHERE return_details.active = 1 AND DATE(return_details.modified_at) >= date('$bill_from') AND DATE(return_details.modified_at) <= date('$bill_to')  
       group by return_details.lot_id) as ret_tab 
    left join ${sale} as sale 
    on sale.id = ret_tab.sale_id ) as return_table on sale_table.lot_id = return_table.lot_id union ALL
SELECT 
(case when ws_return_table.return_cgst is null then ws_sale_table.sale_cgst else ws_sale_table.sale_cgst - ws_return_table.return_cgst end ) as bal_cgst, 
(case when ws_return_table.return_igst is null then ws_sale_table.sale_igst else ws_sale_table.sale_igst - ws_return_table.return_igst end ) as bal_igst,
(case when ws_return_table.cess is null then ws_sale_table.cess else ws_sale_table.cess - ws_return_table.cess end ) as cess, 
(case when ws_return_table.return_total is null then ws_sale_table.sale_total else ws_sale_table.sale_total - ws_return_table.return_total end ) as bal_total, 
(case when ws_return_table.return_unit is null then ws_sale_table.sale_unit else ws_sale_table.sale_unit - ws_return_table.return_unit end) as bal_unit, 
(case when ws_return_table.return_amt is null then ws_sale_table.sale_amt else ws_sale_table.sale_amt - ws_return_table.return_amt end ) as bal_amt, 
ws_sale_table.cgst as gst, ws_sale_table.lot_id 
FROM ( 
    SELECT ws_sale_details.cgst,ws_sale_details.lot_id, 
 	sum(case when sale.gst_type ='cgst' then  ws_sale_details.cgst_value else 0.00 end) as sale_cgst, 
    sum(case when sale.gst_type ='cgst' then  ws_sale_details.sgst_value else 0.00 end) sale_sgst, 
    sum(case when sale.gst_type ='igst' then  ws_sale_details.igst_value else 0.00 end) sale_igst,
	SUM(ws_sale_details.cess_value) as cess,
    sum(ws_sale_details.sub_total) as sale_total, 
    sum(ws_sale_details.sale_unit) as sale_unit, 
    sum(ws_sale_details.amt) as sale_amt FROM 
    ${ws_sale} as sale 
    left join 
    ${ws_sale_details} as ws_sale_details
    on  sale.`id`= ws_sale_details.sale_id 
    WHERE sale.active = 1 and ws_sale_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by ws_sale_details.lot_id
) as ws_sale_table left join ( 
   SELECT ws_ret_tab.* FROM  (
       SELECT ws_return_details.cgst,
       ws_return_details.lot_id, 
       sum(case when wrt.gst_type = 'cgst' then ws_return_details.cgst_value else 0.00 end) as return_cgst, 
       sum(case when wrt.gst_type = 'cgst' then ws_return_details.sgst_value else 0.00 end) as return_sgst, 
	   sum(case when wrt.gst_type = 'igst' then ws_return_details.igst_value else 0.00 end) as return_igst, 
       ws_return_details.cess_value as cess,
       sum(ws_return_details.sub_total) as return_total , 
       sum(ws_return_details.return_unit) as return_unit, 
       sum(ws_return_details.amt) as return_amt,
       ws_return_details.sale_id from ${ws_return_table} as ws_return_details left join wp_shc_ws_return_items as wrt on wrt.id = ws_return_details.return_id   
       WHERE ws_return_details.active = 1 AND DATE(ws_return_details.modified_at) >= date('$bill_from') AND DATE(ws_return_details.modified_at) <= date('$bill_to')  
       group by ws_return_details.lot_id) as ws_ret_tab 
    left join ${ws_sale} as sale 
    on sale.id = ws_ret_tab.sale_id
) as ws_return_table
on ws_sale_table.lot_id = ws_return_table.lot_id ) 
as final_ws_sale group by final_ws_sale.lot_id )
as report 
left join ${lot_table} as 
lot on report.lot_id=lot.id WHERE report.total_unit > 0 ${condition}";

		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

	        $status_query       = "SELECT SUM(cgst_value) as total_cgst,SUM(igst_value) as total_igst,sum(total_unit) as sold_qty,sum(total) as sub_tot,sum(amt) as tot_amt FROM (${query}) AS combined_table";
			$data['s_result']   = $wpdb->get_row( $status_query );
		    $data['total']      = $wpdb->get_var( $total_query );

		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage         = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=list_report')),
		                'format' => '',
		                'type' => 'array',
		                'prev_text' => __('prev'),
		                'next_text' => __('next'),
		                'total' => $totalPage,
		                'current' => $page
		                )
		            );
		        if ( ! empty( $pagination ) ) : 
		            $customPagHTML .= '<ul class="paginate pag3 clearfix"><li class="single">Page '.$page.' of '.$totalPage.'</li>';
		            foreach ($pagination as $key => $page_link ) {
		                if( strpos( $page_link, 'current' ) !== false ) {
		                    $customPagHTML .=  '<li class="current">'.$page_link.'</li>';
		                } else {
		                    $customPagHTML .=  '<li>'.$page_link.'</li>';
		                }
		            }
		            $customPagHTML .=  '</ul>';
		        endif;
		    }

		    $data['pagination'] = $customPagHTML;
		    $end_count = $data['start_count'] + count($data['result']);

		    if( $end_count == 0){
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;

	}
	
	function return_report_pagination( $args ) {
		    global $wpdb;
		  
		    $return_table = $wpdb->prefix.'shc_return_items_details';
			$ws_return_table = $wpdb->prefix.'shc_ws_return_items_details';
		    
		    
		    $lot_table = $wpdb->prefix.'shc_lots';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	    	$page_arg['product_name'] = $this->product_name;
	    	$page_arg['bill_from'] = $this->bill_from;
	    	$page_arg['bill_to'] = $this->bill_to;
	    	$page_arg['slap'] = $this->slap;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';  


		    $bill_from = $this->bill_from;
		    $bill_to   = $this->bill_to;
   			if($this->slap != '') {
		    	$condition .= " WHERE cgst = ".$this->slap;
		    }
		    

		  
			$query = "SELECT * from 
(select 
 full_return_tab.lot_id,
 sum(full_return_tab.return_cgst) as cgst_value,
  sum(full_return_tab.return_igst) as igst_value,
  sum(full_return_tab.cess) as cess,
 sum(full_return_tab.return_amt) as amt,
 sum(full_return_tab.return_unit) as return_unit,
 sum(full_return_tab.return_total) as subtotal from 
 (SELECT return_details.cgst,return_details.lot_id, 
 	sum(case when rt.gst_type = 'cgst' then return_details.cgst_value else 0.00 end) as return_cgst, 
  	sum(case when rt.gst_type = 'cgst' then return_details.sgst_value else 0.00 end) as return_sgst, 
	sum(case when rt.gst_type = 'igst' then return_details.igst_value else 0.00 end) as return_igst, 
	return_details.cess_value as cess,
  sum(return_details.sub_total) as return_total , 
  sum(return_details.return_unit) as return_unit, 
  sum(return_details.amt) as return_amt 
  FROM  ${return_table} as return_details left join wp_shc_return_items as rt on rt.id = return_details.return_id
  WHERE return_details.active = 1 AND DATE(return_details.modified_at) >= date('$bill_from') AND DATE(return_details.modified_at) <= date('$bill_to') group by return_details.lot_id
union all
SELECT 
  ws_return_details.cgst,
  ws_return_details.lot_id, 
	sum(case when wrt.gst_type = 'cgst' then ws_return_details.cgst_value else 0.00 end) as return_cgst, 
  	sum(case when wrt.gst_type = 'cgst' then ws_return_details.sgst_value else 0.00 end) as return_sgst, 
	sum(case when wrt.gst_type = 'igst' then ws_return_details.igst_value else 0.00 end) as return_igst, 
	ws_return_details.cess_value as cess, 
  sum(ws_return_details.sub_total) as return_total , 
  sum(ws_return_details.return_unit) as return_unit, 
  sum(ws_return_details.amt) as return_amt 
  FROM  ${ws_return_table} as ws_return_details left join wp_shc_ws_return_items as wrt on wrt.id = ws_return_details.return_id 
  WHERE ws_return_details.active = 1 AND DATE(ws_return_details.modified_at) >= date('$bill_from') AND DATE(ws_return_details.modified_at) <= date('$bill_to') group by ws_return_details.lot_id
 ) as full_return_tab group by full_return_tab.lot_id) as r_table 
left join 
(select id,cgst,sgst,product_name,brand_name from ${lot_table} WHERE active=1) as lot_tab on lot_tab.id =r_table.lot_id  ${condition}";


		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

	        $status_query       = "SELECT SUM(cgst_value) as total_cgst,SUM(igst_value) as total_igst,sum(return_unit) as sold_qty,sum(subtotal) as sub_tot,sum(amt) as tot_amt FROM (${query}) AS combined_table";
			$data['s_result']   = $wpdb->get_row( $status_query );
		    $data['total']      = $wpdb->get_var( $total_query );

		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );
		    $totalPage         = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=list_return')),
		                'format' => '',
		                'type' => 'array',
		                'prev_text' => __('prev'),
		                'next_text' => __('next'),
		                'total' => $totalPage,
		                'current' => $page
		                )
		            );
		        if ( ! empty( $pagination ) ) : 
		            $customPagHTML .= '<ul class="paginate pag3 clearfix"><li class="single">Page '.$page.' of '.$totalPage.'</li>';
		            foreach ($pagination as $key => $page_link ) {
		                if( strpos( $page_link, 'current' ) !== false ) {
		                    $customPagHTML .=  '<li class="current">'.$page_link.'</li>';
		                } else {
		                    $customPagHTML .=  '<li>'.$page_link.'</li>';
		                }
		            }
		            $customPagHTML .=  '</ul>';
		        endif;
		    }

		    $data['pagination'] = $customPagHTML;
		    $end_count = $data['start_count'] + count($data['result']);

		    if( $end_count == 0){
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;

	}


			function stock_report_pagination_accountant( $args ) {
		    global $wpdb;
		    $sale = $wpdb->prefix.'shc_sale';
		    $sale_details =  $wpdb->prefix.'shc_sale_detail';
		    $return_table = $wpdb->prefix.'shc_return_items_details';
		    $ws_sale = $wpdb->prefix.'shc_ws_sale';
		    $ws_sale_details = $wpdb->prefix.'shc_ws_sale_detail';
		    $ws_return_table = $wpdb->prefix.'shc_ws_return_items_details';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	    	$page_arg['bill_from'] = $this->bill_from;
	    	$page_arg['bill_to'] = $this->bill_to;
	    	$page_arg['slap'] = $this->slap;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';  
		    
		    if($this->bill_from != '' && $this->bill_to != '') {
		    	$condition .= " AND DATE(sale.modified_at) >= DATE('".$this->bill_from."') AND DATE(sale.modified_at) <= DATE('".$this->bill_to."')";
		    } else if($this->bill_from != '' || $this->bill_to != '') {
		    	if($this->bill_from != '') {
		    		$condition .= " AND DATE(sale.modified_at) >= DATE('".$this->bill_from."') AND DATE(sale.modified_at) <= DATE('".$this->bill_from."')";
		    	} else {
		    		$condition .= " AND DATE(sale.modified_at) >= DATE('".$this->bill_to."') AND DATE(sale.modified_at) <= DATE('".$this->bill_to."')";
		    	}
		    }

		    if($this->slap != '') {
		    	$condition_final .= "AND report.gst = ".$this->slap;
		    }
		    $query 				= "SELECT * from (
		    		SELECT 
					(sum(fin_tab.bal_cgst)) as cgst_value,
					(sum(fin_tab.bal_igst)) as igst_value, 
					(sum(fin_tab.cess)) as cess, 
					(sum(fin_tab.bal_total)) as total, 
					(sum(fin_tab.bal_unit)) as total_unit, 
					(sum(fin_tab.bal_amt)) as amt,fin_tab.gst as gst 
				      
					from (SELECT 
							(case when return_table.return_cgst is null then sale_table.sale_cgst else sale_table.sale_cgst - return_table.return_cgst end ) as bal_cgst,
							(case when return_table.return_igst is null then sale_table.sale_igst else sale_table.sale_igst - return_table.return_igst end ) as bal_igst, 
							(case when return_table.cess is null then sale_table.cess else sale_table.cess - return_table.cess end ) as cess,
							(case when return_table.return_total is null then sale_table.sale_total else sale_table.sale_total - return_table.return_total end ) as bal_total,
							(case when return_table.return_unit is null then sale_table.sale_unit else  sale_table.sale_unit - return_table.return_unit end) as bal_unit,
							(case when return_table.return_amt is null then sale_table.sale_amt else sale_table.sale_amt - return_table.return_amt end ) as bal_amt,
							sale_table.cgst as gst
							FROM 
							(
							SELECT sale_details.cgst,
							   sum(case when sale.gst_type ='cgst' then  sale_details.cgst_value else 0.00 end) as sale_cgst, 
            					sum(case when sale.gst_type ='cgst' then  sale_details.sgst_value else 0.00 end) sale_sgst, 
            					sum(case when sale.gst_type ='igst' then  sale_details.igst_value else 0.00 end) sale_igst,
								sum(sale_details.cess_value) as cess, 
							    sum(sale_details.sub_total) as sale_total, 
							    sum(sale_details.sale_unit) as sale_unit,
							    sum(sale_details.amt) as sale_amt FROM ${sale} as sale left join ${sale_details} as sale_details on sale.`id`= sale_details.sale_id WHERE sale.active = 1 and sale_details.active = 1 ${condition} group by sale_details.cgst
							) as sale_table 
							left join
							(
							 SELECT return_details.cgst,
								sum(case when sale.gst_type = 'cgst' then return_details.cgst_value else 0.00 end) as return_cgst, 
       							sum(case when sale.gst_type = 'cgst' then return_details.sgst_value else 0.00 end) as return_sgst, 
	   							sum(case when sale.gst_type = 'igst' then return_details.igst_value else 0.00 end) as return_igst, 
	   							
							    sum(return_details.cess_value) as cess,
							    sum(return_details.sub_total) as return_total,
							    sum(return_details.return_unit) as return_unit,
							    sum(return_details.amt) as return_amt FROM ${sale} as sale left join ${return_table} as return_details on sale.`id`= return_details.sale_id WHERE sale.active = 1 and return_details.active = 1 ${condition} group by return_details.cgst
							) as return_table 
							on sale_table.cgst = return_table.cgst
				union all 
				                	SELECT 
		(case when ws_return_table.return_cgst is null then ws_sale_table.sale_cgst else ws_sale_table.sale_cgst - ws_return_table.return_cgst end ) as bal_cgst,
		(case when ws_return_table.return_igst is null then ws_sale_table.sale_igst else ws_sale_table.sale_igst - ws_return_table.return_igst end ) as bal_igst,
		(case when ws_return_table.cess is null then ws_sale_table.cess else ws_sale_table.cess - ws_return_table.cess end ) as cess, 
		(case when ws_return_table.return_total is null then ws_sale_table.sale_total else ws_sale_table.sale_total - ws_return_table.return_total end ) as bal_total,
		(case when ws_return_table.return_unit is null then ws_sale_table.sale_unit else  ws_sale_table.sale_unit - ws_return_table.return_unit end) as bal_unit,
		(case when ws_return_table.return_amt is null then ws_sale_table.sale_amt else ws_sale_table.sale_amt - ws_return_table.return_amt end ) as bal_amt,
							ws_sale_table.cgst as gst
							FROM 
							(
							SELECT ws_sale_details.cgst,
 								sum(case when sale.gst_type ='cgst' then  ws_sale_details.cgst_value else 0.00 end) as sale_cgst, 
    							sum(case when sale.gst_type ='cgst' then  ws_sale_details.sgst_value else 0.00 end) sale_sgst, 
    							sum(case when sale.gst_type ='igst' then  ws_sale_details.igst_value else 0.00 end) sale_igst,
								SUM(ws_sale_details.cess_value) as cess, 
							    sum(ws_sale_details.sub_total) as sale_total, 
							    sum(ws_sale_details.sale_unit) as sale_unit,
							    sum(ws_sale_details.amt) as sale_amt FROM ${ws_sale} as sale left join ${ws_sale_details} as ws_sale_details on sale.`id`= ws_sale_details.sale_id WHERE sale.active = 1 and ws_sale_details.active = 1 ${condition} group by ws_sale_details.cgst
							) as ws_sale_table 
							left join
							(
							 SELECT ws_return_details.cgst,
							    sum(case when sale.gst_type = 'cgst' then ws_return_details.cgst_value else 0.00 end) as return_cgst, 
       							sum(case when sale.gst_type = 'cgst' then ws_return_details.sgst_value else 0.00 end) as return_sgst, 
	   							sum(case when sale.gst_type = 'igst' then ws_return_details.igst_value else 0.00 end) as return_igst, 
       							sum(ws_return_details.cess_value) as cess,
							    sum(ws_return_details.sub_total) as return_total ,
							    sum(ws_return_details.return_unit) as return_unit,
							    sum(ws_return_details.amt) as return_amt FROM ${ws_sale} as sale left join ${ws_return_table} as ws_return_details on sale.`id`= ws_return_details.sale_id WHERE sale.active = 1 and ws_return_details.active = 1 ${condition} group by ws_return_details.cgst
							) as ws_return_table 
							on ws_sale_table.cgst = ws_return_table.cgst ) as fin_tab GROUP by fin_tab.gst ) as report WHERE report.total_unit > 0 ${condition_final}";
		            


		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

	        $status_query       = "SELECT SUM(cgst_value) as total_cgst,SUM(igst_value) as total_igst,sum(total_unit) as sold_qty,sum(total) as sub_tot,sum(amt) as tot_amt FROM (${query}) AS combined_table";
			$data['s_result']   = $wpdb->get_row( $status_query );

		    $data['total']      = $wpdb->get_var( $total_query );

		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;
 
		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage         = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=list_report_account')),
		                'format' => '',
		                'type' => 'array',
		                'prev_text' => __('prev'),
		                'next_text' => __('next'),
		                'total' => $totalPage,
		                'current' => $page
		                )
		            );
		        if ( ! empty( $pagination ) ) : 
		            $customPagHTML .= '<ul class="paginate pag3 clearfix"><li class="single">Page '.$page.' of '.$totalPage.'</li>';
		            foreach ($pagination as $key => $page_link ) {
		                if( strpos( $page_link, 'current' ) !== false ) {
		                    $customPagHTML .=  '<li class="current">'.$page_link.'</li>';
		                } else {
		                    $customPagHTML .=  '<li>'.$page_link.'</li>';
		                }
		            }
		            $customPagHTML .=  '</ul>';
		        endif;
		    }

		    $data['pagination'] = $customPagHTML;
		    $end_count = $data['start_count'] + count($data['result']);

		    if( $end_count == 0){
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;

	}
}


//SELECT sale.created_at, sum(sale_details.cgst_value) as cgst_val, sum(sale_details.sgst_value) as sgst_val, sum(sale_details.sale_unit) as sale_unit, sum(sale_details.sub_total) as total FROM `wp_shc_sale` as sale left join wp_shc_sale_detail as sale_details on sale.`id`= sale_details.sale_id WHERE sale.active = 1 and sale_details.active = 1 and sale_details.cgst='9.00' and sale.created_at between '2017-09-09' and '2017-09-31' GROUP by DATE(sale.created_at)
//SELECT return_sale.created_at, sum(return_sale_details.cgst_value) as return_cgst, sum(return_sale_details.sgst_value) as return_sgst, sum(return_sale_details.return_unit) as return_unit, sum(return_sale_details.sub_total) as return_total FROM `wp_shc_return_items` as return_sale left join wp_shc_return_items_details as return_sale_details on return_sale.`id`= return_sale_details.sale_id WHERE return_sale.active = 1 and return_sale_details.active = 1 and return_sale_details.cgst='9.00' and return_sale.created_at between '2017-09-09' and '2017-09-31' GROUP by DATE(return_sale.created_at)
?>
