<?php 
/*
Template Name : Pdf Generate Code
Ref. Url : --
Compitible Browser : IE-8, Google Crome, Mozillafirefox-15.0.1
*/
$count = 0;
$number = 1;
$other_bank_account = '';
foreach ($dx_company_bank_ac_number_other as $key => $bank_number) {
	$other_bank_account .= '<br/>'.$bank_number	;			
}
?>
<style>
table { /* Will apply to all tables */
	/* padding:0; */
/* OR border-collapse: collapse; */
}
</style>

<table width="100%" data-post-id="<?php echo $post->ID; ?>">
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td style="font-size:54px;"><?php echo $dx_setting_company_name?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_company_address; ?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_setting_company_email; ?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_company_number; ?></td>
				</tr>
			</table>
		</td>
		<td align="right">
			<table width="100%" align="right">
				<tr>
					<td style="font-size:72px; color:#8F8F9B;" align="right">INVOICE</td>
				</tr>
				<tr>
					<td height="15px"></td>
				</tr>
				<tr>
					<td  align="right">
						<table border="1" cellpadding="5" style="font-size:25px;">
							<tr>
								<td><b>INVOICE #</b></td>
								<td><b>DATE</b></td>
							</tr>
							<tr>
								<td><?php echo $dx_invoice_number; ?></td>
								<td><?php echo $dx_date_of_execution?></td>
							</tr>
							<tr>
								<td><b>CUSTOMER ID</b></td>
								<td><b>TERMS</b></td>
							</tr>
							<tr>
								<td><?php echo $dx_client; ?></td>
								<td><?php echo $dx_description; ?></td>
							</tr>
							<tr>
								<td><b>COMPANY UNIQUE NO.</b></td>
								<td><b>COMPANY BANK A/C NO.</b></td>
							</tr>
							<tr>
								<td><?php echo $dx_company_number; ?></td>
								<td><?php echo $dx_setting_company_bank_ac_number.$other_bank_account; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td width="60%">
			<table width="70%" cellpadding="2">
				<tr>
					<td>
					<table border="1" cellpadding="5" bgcolor="#DBDBDB" width="100%">
						<tr><td>BILL TO</td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_customer_name;?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_company_name;?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_company_address; ?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_company_number; ?></td>
				</tr>
			</table>
		</td>
		<td width="40%">
			<table width="100%" cellpadding="2">
				<tr>
					<td>
					<table border="1" cellpadding="5" bgcolor="#DBDBDB" width="100%">
						<tr><td>SHIP TO</td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_setting_company_name;?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_setting_company_email;?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_setting_company_address; ?></td>
				</tr>
				<tr>
					<td style="font-size:34px;"><?php echo $dx_setting_company_unique_number; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="15" colspan="2"></td>
	</tr>
		<tr>
		<td colspan="2">
			<table align="center" cellspacing="5" bgcolor="#DBDBDB" cellpadding="5" style="color:#000; font-size:28px; font-weight:bold;" width="100%">
				<tr>
					<td width="5%"  height="15" valign="middle"><?php echo __('NO','dxinvoice'); ?></td>
					<td width="20%"  height="15" valign="middle"><?php echo __('ITEM DESCRIPTION','dxinvoice'); ?></td>
					<td width="15%"  height="15" valign="middle"><?php echo __('RATE','dxinvoice'); ?></td>
					<td width="15%" ><?php echo __('QUANTITY','dxinvoice'); ?></td>
					<td width="15%" ><?php echo __('PRICE','dxinvoice'); ?></td>
					<td width="15%" ><?php echo __('DISCOUNT','dxinvoice'); ?></td>
					<td width="15%" ><?php echo __('TOTAL','dxinvoice'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table align="center" cellpadding="5" style="color:#000; font-size:28px;" width="100%">
				<tbody class="table-calc">
			<?php foreach ($dx_top_custom_value as $invoice_row){?>
				<tr bgcolor="<?php if($count == 1) {echo '#DEDEDE';} else{  echo '#CECECE';} ?>" class="invoice-body-wrap">
					<td width="5%" class="changable-text"><?php echo  $number; ?></td>
					<td width="20%" align="left" height="15" valign="middle" class="changable-text  dx-invoice-desc"><?php echo  $invoice_row['invoice_description'] ?></td>
					<td width="15%" class="changable-text  dx-rate"><?php echo  $invoice_row['rate'] ?></td>
					<td width="15%" class="changable-text dx-quantity"><?php echo  $invoice_row['quantity'] ?></td>
					<td width="15%" class="dx-net"><?php echo  $invoice_row['net'] ?></td>
					<td width="15%" class="changable-text  dx-discount"><?php echo  $invoice_row['discount'] ?></td>
					<td width="15%"  class="dx-total"><?php echo  $invoice_row['total'] ?></td>
				</tr>
			<?php  $number++;
			if($count == 1)
				$count = 0;
			else 
				$count = 1; 
			} ?>
				</tbody>
				<tbody class="table-calc">
					<tr>
						<td colspan="5" rowspan="3" align="left">
							<?php if(!empty($dx_invoice_signature_img)){ ?> 
							<img align="left" src="<?php echo $dx_invoice_signature_img;  ?>"alt="" width="90" height="40"><br><br><?php echo  __('Signature','dxinvoice'); } ?>
						</td>
						<td colspan="2">
							<table bgcolor="#DBDBDB" align="center" cellspacing="5" style="font-size:25px; text-align:left; font-weight:bold;" width="100%">
								<tr>
									<td align="left" width="40%"><?php echo  __('Subtotal','dxinvoice'); ?></td>
									<td></td>
									<td width="30%" align="right" class="dx-net-amount"><?php echo number_format((float)$invoice_net, 2, '.', ''); ?></td>
								</tr>
								<tr>
									<td align="left" width="40%"><?php echo  __('Discount','dxinvoice'); ?></td>
									<td></td>
									<td width="30%" align="right" class="dx-discount-all"><?php echo number_format((float)$invoice_discount, 2, '.', ''); ?></td>
								</tr>
								<tr>
									<td align="left" width="40%"><?php echo  __('Tax','dxinvoice'); ?></td>
									<td align="right" class="dx-vat changable-text" ><?php echo $dx_vat_text."%"; ?></td>
									<td width="30%" align="right" class="dx-vat-amount"><?php echo number_format((float)$vat_amount, 2, '.', ''); ?></td>
								</tr>
								<tr>
									<td align="left" width="40%"><?php echo  __('Total','dxinvoice'); ?></td>
									<td></td>
									<td width="30%" align="right" class="dx-final-total"><?php echo number_format((float)$dx_final_total, 2, '.', ''); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>

	<tr>
		<td height="25"></td>
	</tr>
	<tr>
		<td><b><?php echo __('Term & Conditions','dxinvoice')?></b></td>
	</tr>
	<tr>
		<td><?php echo __('VAT regulations as per Article 21 (2)','dxinvoice')?></td>
	</tr>
	<tr>
		<td colspan="2">
			<table align="right" width="100%">
				<tr>
					<td><?php if(!empty($dx_invoice_signature_img)){ ?> 
			<img align="left" src="<?php echo $dx_invoice_signature_img;  ?>"alt="" width="90" height="40"><br><?php echo  __('Signature','dxinvoice'); } ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
