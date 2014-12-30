<?php 
/*
Template Name : Pdf Generate Code
Ref. Url : --
Compitible Browser : IE-8, Google Crome, Mozillafirefox-15.0.1
*/
// Transparancy Image Stamp

$dx_invoice_detail = '<table width="100%" border="0" style="padding:5px; ">
          <tbody><tr><td style="font-size:54px; font-weight:bold;" class="invoice-label" colspan="2">'. __('Invoice','dxinvoice').'</td></tr>
          <tr style="font-size:28px;">
            <td style="text-align:right;">'. __('Date of financial event:','dxinvoice').'</td><td style="text-align:right; margin-right:20px;">25.12.2014  </td>
          </tr>
          <tr style="font-size:28px;">
            <td style="text-align:right;">'. __('Issued at:','dxinvoice').'</td><td style="text-align:right; margin-right:20px;">-</td>
          </tr>
          <tr style="font-size:28px;">
            <td style="text-align:right;">'. __('No.','dxinvoice').'</td><td style="text-align:right; margin-right:20px;">'.$dx_invoice_number.'</td>
          </tr>
      	</tbody></table>';

$client_detail = '<table style="padding:5px;">
				    <tbody><tr class="client-title"><td>'. $dx_customer_name.'</td></tr>
				    <tr style="font-size:38px; color:#7F7F7F;"><td ><b>'.$dx_company_name.'</b></td></tr>
				    <tr style="font-size:28px;"><td bgcolor="#CCCCFF"><b>'. __('Address:','dxinvoice').'</b>
				'.$dx_company_address.'</td></tr>
				    <tr style="font-size:28px;"><td bgcolor="#CCCCFF">'. __('ID No.:','dxinvoice'). $dx_company_number.'</td></tr>
				    <tr style="font-size:28px;"><td bgcolor="#CCCCFF">'. __('Contant Person','dxinvoice').' : '.$dx_client_name.'</td></tr>
				  </tbody>
				</table>';

$customer_detail = '<table style="padding:5px;">
				    <tbody><tr style="padding:10px 0;" class="client-title">
				    <td>'. __('Supplier','dxinvoice').'</td></tr>
				    <tr style="font-size:38px;"><td style="padding:10px 0; color:#7F7F7F;"><b>WP Valet LLC</b></td></tr>
				    <tr style="font-size:28px; background-color:#CCCCFF;"><td><b>'. __('Address:','dxinvoice').'</b>
				4659 56th Ter East</td></tr>
				    <tr style="font-size:28px; background-color:#CCCCFF;"><td>'. __('ID No.','dxinvoice').'</td></tr>
				    <tr style="font-size:28px; background-color:#CCCCFF;"><td>'. __('Contant Person','dxinvoice').'</td></tr>
				  </tbody>
				</table>';
?>


<table border="1" width="100%">
  <tbody>
  <tr>
    <td style="border-width:0; border-right:none;" colspan="3" ></td>
    <td colspan="4" align="right">
      	<?php echo $dx_invoice_detail; ?>    </td>
  </tr>
	<tr>
		<td colspan="3" height="150px">
			<?php echo $client_detail; ?>		</td>
		<td colspan="4" height="150px">
			<?php echo $customer_detail; ?>		</td>
	</tr>
	<tr style="font-size:30px; text-align:center;" valign="baseline" bgcolor="#CCCCFF">
		<td width="6%"><?php echo  __('No','dxinvoice'); ?> </td> 
		<td width="29%"><?php echo  __('Description','dxinvoice'); ?></td>
		<td width="15%"><?php echo  __('Rate','dxinvoice'); ?></td>
		<td width="9%"><?php echo  __('Qty','dxinvoice'); ?></td>
		<td width="15%"><?php echo  __('Price Per Unit','dxinvoice'); ?></td>
		<td width="14%"><?php echo  __('Discount','dxinvoice'); ?></td>
		<td width="12%"><?php echo  __('Total ( USD )','dxinvoice'); ?></td>
	</tr>
	<tr>
		<td colspan="7" height="420px">
			<table border="1">
				<tbody>
				<?php foreach ($dx_top_custom_value as $invoice_row){?>
				  <tr style="font-size:28px; text-align:center; border:none;">
				  	<td width="6%"><?php echo  $invoice_row['number'] ?> </td> 
					<td width="29%"><?php echo  $invoice_row['invoice_description'] ?></td>
					<td width="15%"><?php echo  $invoice_row['rate'] ?></td>
					<td width="9%"><?php echo  $invoice_row['quantity'] ?></td>
					<td width="15%"><?php echo  $invoice_row['rate'] ?></td>
					<td width="14%"><?php //echo  $invoice_row[''] ?></td>
					<td width="12%"><?php echo  $invoice_row['total'] ?></td>
				  </tr>
				<?php $invoice_total += $invoice_row['total'];} ?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr style="font-size:30px; text-align:left;"> 
		<td colspan="4"></td>
		<td bgcolor="#CCCCFF" valign="bottom"><?php echo  __('Net Amount','dxinvoice'); ?></td>
		<td></td>
		<td><?php echo $invoice_total; ?></td>
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="4"></td>
		<td bgcolor="#CCCCFF"><?php echo  __('Tax Base','dxinvoice'); ?></td>
		<td></td>
		<td>-</td>
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="2" bgcolor="#CCCCFF"><?php echo $dx_amount; ?></td>
		<td colspan="2"></td>
		<td bgcolor="#CCCCFF"><?php echo  __('VAT','dxinvoice'); ?></td>
		<td>0%</td>
		<td>-</td>
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="2"><?php echo $dx_amount_text; ?></td>
		<td colspan="2"></td>
		<td bgcolor="#CCCCFF"><?php echo  __('Total','dxinvoice'); ?></td>
		<td>0%</td>
		<td><?php echo $invoice_total; ?></td>
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="4"></td>
		<td colspan="2"><?php echo  __('VAT regulations as per Article 21 (2)','dxinvoice'); ?></td>
		<td></td>
	</tr>
	<tr style="font-size:30px; text-align:center;" valign="middle">
		<td colspan="2" align="left"><?php echo  __('Client :','dxinvoice'); ?></td>
		<td colspan="2" rowspan="2"></td>
		<td colspan="2"><?php echo  __('Created by:','dxinvoice'); ?></td>
		<td rowspan="2" align="center"><?php if(!empty($dx_invoice_signature_img)){ ?> 
			<img align="left" src="<?php echo $dx_invoice_signature_img;  ?>"alt="" width="90" height="40"><?php echo  __('Signature','dxinvoice'); } ?> </td>
	</tr>
	<tr style="font-size:30px; text-align:center;">
		
		<td colspan="2"><?php echo  $dx_customer_name; ?></td>
		<td colspan="2"><?php echo  $current_user_firstname; ?></td>
	</tr>
	<tr style="font-size:30px; text-align:center;">
		<td>&nbsp;</td>
		<td bgcolor="#CCCCFF"><?php echo  __('Additional Info','dxinvoice'); ?></td>
		<td colspan="2"></td>
		<td colspan="2"><?php echo  __('Payment Type: Bank transfer','dxinvoice'); ?></td>
		<td></td>
	</tr>
	<tr style="font-size:30px; text-align:center;">
		<td>&nbsp;</td>
		<td><?php echo  __('First Investment Bank','dxinvoice'); ?></td>
		<td colspan="2" align="left">BIC: - </td>
		<td colspan="2"><?php echo  __('IBAN:'.$dx_bank_account,'dxinvoice'); ?></td>
		<td></td>
	</tr>
	</tbody>
</table>