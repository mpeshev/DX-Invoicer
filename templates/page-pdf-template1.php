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
            <td style="text-align:right;">'. __('Date of financial event:','dxinvoice').'</td><td style="text-align:right; margin-right:20px;">'.date('d.m.Y',strtotime($dx_date_of_execution)).'</td>
          </tr>
          <tr style="font-size:28px;">
            <td style="text-align:right;">'. __('Issued at:','dxinvoice').'</td><td style="text-align:right; margin-right:20px;">-</td>
          </tr>
          <tr style="font-size:28px;">
            <td style="text-align:right;">'. __('No.','dxinvoice').'</td><td style="text-align:right; margin-right:20px;">'.$dx_invoice_number.'</td>
          </tr>
      	</tbody></table>';

$client_detail = '<table style="padding:5px;">
				    <tbody><tr class="client-title"><td class="" data-clientname="'.$dx_client.'">'. $dx_customer_name.'</td></tr>
				    <tr style="font-size:38px; color:#7F7F7F;"><td style="padding:10px 0;"><b class="" data-clientcompany >'.$dx_company_name.'</b></td></tr>
				    <tr style="font-size:28px;"><td bgcolor="#CCCCFF"><b>'. __('Address:','dxinvoice').'</b><span class="" data-clientcomaddr> 
				'.$dx_company_address.'</span></td></tr>
				    <tr style="font-size:28px;"><td bgcolor="#CCCCFF">'. __('ID No.:','dxinvoice').'<span class="" data-clientcomnum>'. $dx_company_number.'</span></td></tr>
				    <tr style="font-size:28px;"><td bgcolor="#CCCCFF">'. __('Contant Person','dxinvoice').' :<span class="" data-contactperson > '.$dx_client_name.'</span></td></tr>
				  </tbody>
				</table>';
$other_bank_account = '';
if($dx_company_bank_ac_number_other)
{
	foreach ($dx_company_bank_ac_number_other as $key => $bank_number) {
		$other_bank_account .= '<tr style="font-size:28px; background-color:#CCCCFF;" ><td><span class="" data-setting-account>'.$bank_number.'</span></td></tr>'	;			
	}	
}

$customer_detail = '<table style="padding:5px;">
				    <tbody><tr style="padding:10px 0;" class="client-title">
				    <td class="" data-customername>'.$dx_setting_person_name.'</td></tr>
				    <tr style="font-size:38px;"><td style="padding:10px 0; color:#7F7F7F;"><b data-customercomname class="">'.$dx_setting_company_name.'</b></td></tr>
				    <tr style="font-size:28px; background-color:#CCCCFF;"><td><b>'. __('Address:','dxinvoice').'</b>
				<span class="" data-customercomaddr>'.$dx_setting_company_address.'</span></td></tr>
				    <tr style="font-size:28px; background-color:#CCCCFF;" ><td>'. __('ID No.','dxinvoice').'<span class="" data-customercomidno>'.$dx_setting_company_unique_number.'</span></td></tr>
				    <tr style="font-size:28px; background-color:#CCCCFF;" ><td>'. __('Contant Person : ','dxinvoice').'<span class="" data-customercomcontactp>'.$dx_setting_company_responsible_person.'</span></td></tr>
				    <tr style="font-size:28px; background-color:#CCCCFF;" ><td>'. __('Bank Account : ','dxinvoice').'<span class="" data-setting-account>'.$dx_setting_company_bank_ac_number.'</span></td></tr>
				  	'.$other_bank_account.'
				  </tbody>
				</table>';
?>


<table border="1" width="100%" class="table table-hover" data-post-id="<?php echo $post->ID; ?>">
  <tbody>
  <tr>
    <td style="border-width:0; border-right:none;" colspan="3" ></td>
    <td colspan="4" align="right">
      	<?php echo $dx_invoice_detail; ?>    </td>
  </tr>
	<tr>
		<td colspan="3" height="150px" valign="top">
			<?php echo $client_detail; ?>		</td>
		<td colspan="4" height="150px">
			<?php echo $customer_detail; ?>		</td>
	</tr>
	<tr style="font-size:30px; text-align:center;" valign="baseline" bgcolor="#CCCCFF">
		<td width="6%"><?php echo  __('No','dxinvoice'); ?> </td> 
		<td width="29%"><?php echo  __('Description','dxinvoice'); ?></td>
		<td width="15%"><?php echo  __('Rate','dxinvoice'); ?></td>
		<td width="9%"><?php echo  __('Qty','dxinvoice'); ?></td>
		<td width="15%"><?php echo  __('Net','dxinvoice'); ?></td>
		<td width="14%"><?php echo  __('Discount','dxinvoice'); ?></td>
		<td width="12%"><?php echo  __('Total ( '.strtoupper($dx_currency).' )','dxinvoice'); ?></td>
	</tr>
	<tr>
		<td colspan="7" valign="top">
			<table border="1" width="100%" class="table table-hover">
				<tbody class="table-calc">
				<?php foreach ($dx_top_custom_value as $invoice_row){
					 if(count($invoice_row)){
					?>
				  <tr style="font-size:28px; text-align:center; border:none;" class="invoice-body-wrap">
				  	<td width="6%"><?php echo  $invoice_row['number']; ?> </td> 
					<td width="29%" class="changable-text dx-invoice-desc" ><?php echo  $invoice_row['invoice_description']; ?></td>
					<td width="15%" class="changable-text dx-rate" ><?php echo  $invoice_row['rate']; ?></td>
					<td width="9%" class="changable-text dx-quantity" ><?php echo  $invoice_row['quantity']; ?></td>
					<td width="15%" class="dx-net"><?php echo  $invoice_row['net']; ?></td>
					<td width="14%" class="changable-text dx-discount" ><?php echo  $invoice_row['discount'] ?></td>
					<td width="12%" class="dx-total"><?php echo  $invoice_row['total']; ?></td>
				  </tr>
				<?php }
				} ?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr style="font-size:30px; text-align:left;"> 
		<td colspan="4"></td>
		<td bgcolor="#CCCCFF" valign="bottom"><?php echo  __('Net Amount','dxinvoice'); ?></td>
		<td></td>
		<td align="right" class="dx-net-amount"><?php echo number_format((float)$invoice_net, 2, '.', ''); ?></td>
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="4"></td>
		<td bgcolor="#CCCCFF"><?php echo  __('Discount','dxinvoice'); ?></td>
		<td></td>
		<td align="right" class="dx-discount-all"><?php echo number_format((float)$invoice_discount, 2, '.', ''); ?></td>
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="2" bgcolor="#CCCCFF" class="dx-amount-all"><?php echo number_format((float)$dx_amount, 2, '.', ''); ?></td>
		<td colspan="2"></td>
		<td bgcolor="#CCCCFF"><?php echo  __('VAT','dxinvoice'); ?></td>
		<td align="right" class="dx-vat changable-text" ><?php echo $dx_vat_text."%"; ?></td>
		<td align="right" class="dx-vat-amount"><?php echo number_format((float)$vat_amount, 2, '.', ''); ?></td>
		
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="2"><?php echo $dx_amount_text; ?></td>
		<td colspan="2"></td>
		<td bgcolor="#CCCCFF"><?php echo  __('Total','dxinvoice'); ?></td>
		<td>0%</td>
		<td align="right" class="dx-final-total"><?php echo number_format((float)$dx_final_total, 2, '.', ''); ?></td>
	</tr>
	<tr style="font-size:30px; text-align:left;">
		<td colspan="4"></td>
		<td colspan="2"><?php echo  __('VAT regulations as per Article 21 (2)','dxinvoice'); ?></td>
		<td></td>
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
		<td colspan="2"><?php echo  __('IBAN:','dxinvoice')."<span class='' data-bankacc>".$dx_bank_account; ?></span></td>
		<td></td>
	</tr>
	</tbody>
</table>
<table>
<tbody>
	<tr style="font-size:30px; text-align:center;" valign="middle">
		<td colspan="2" align="left"><?php echo  __('Client :','dxinvoice'); ?></td>
		<td colspan="2" rowspan="2"></td>
		<td colspan="2"><?php echo  __('Created by:','dxinvoice'); ?></td>
		<td rowspan="2" align="center"><?php if(!empty($dx_invoice_signature_img)){ ?> 
			<img align="left" src="<?php echo $dx_invoice_signature_img;  ?>"alt="" width="90" height="40"><?php echo  __('Signature','dxinvoice'); } ?> </td>
	</tr>
	</tbody>
</table>