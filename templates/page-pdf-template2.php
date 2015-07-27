<?php 
/*
Template Name : Pdf Generate Code
Ref. Url : --
Compitible Browser : IE-8, Google Crome, Mozillafirefox-15.0.1
*/
$count = 0;
$number = 1;
?>

<table width="100%" class="table table-hover" data-post-id="<?php echo $post->ID; ?>">
	<tr>
		<td colspan="2">
			<table width="100%" cellpadding="10" bgcolor="red" style="color:#fff;" frame="void">
				<tbody>
					<tr>
					<td width="70%">
						<table cellpadding="5" >
							<tr>
								<td width="15%"></td>
								<td width="85%" style="font-size: 54px; color: rgb(255, 255, 255);"><?php echo $dx_setting_company_name?></td>
							</tr>
						</table>					
					</td>
					<td width="30%">
						<table align="left" width="100%" style="font-size: 54px; color: rgb(255, 255, 255);">
							<tbody>
								<tr><td><?php echo $dx_setting_company_website; ?></td></tr>
								<tr><td><?php echo $dx_setting_company_email; ?></td></tr>
								<tr><td><?php echo $dx_company_number; ?></td></tr>
							</tbody>
						</table>
					</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#F5F5F5" height="25px" colspan="2" ></td>
	</tr>
	<tr>
		<td width="50%"></td>
		<td width="50%" align="right" style="float:right;">
			<table bgcolor="Black" style="color:#fff; font-size:54px; border-spacing:0;" align="right" width="100%">
				<tr>
					<td width="50%" align="center">
						INVOICE	
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#DEDEDE" width="50%">
			<table cellpadding="10" bgcolor="">
				<tr>
					<td>
						<table style="font-size:28px;padding:5px;">
							<tr><td style="font-size:34px;">
								<b><?php echo __('INVOICE TO :','dxinvoice'); ?> 
									<BR><span class="changable-text" data-clientname="<?php echo $dx_client; ?>"><?php echo $dx_customer_name;?></span>
									</b>
									<BR><b class="changable-text" data-clientcompany><?php echo $dx_company_name;?></b></td></tr>
							<tr><td><?php echo __('ADDRESS :','dxinvoice'); ?> <br><span data-clientcomaddr><?php echo $dx_company_address; ?></span></td></tr>
							<tr><td><?php echo __('PHONE :','dxinvoice'); ?> <span  data-clientcomnum><?php echo $dx_company_number; ?> </span></td></tr>
							<tr><td><?php echo __('EMAIL :','dxinvoice'); ?> <span  data-clientcomnum><?php echo $dx_setting_company_email; ?> </span></td></tr>
						</table>
					</td>
					
				</tr>
			</table>
		</td>
		<td bgcolor="#DEDEDE" width="50%">
			<table border="0" cellspacing="20" >
				<tr>
					<td>
						<table style="font-size:28px;padding:5px;">
							<tr><td><?php echo __('DATE :','dxinvoice'); ?> 13/5/2014</td></tr>
							<tr><td><?php echo __('INVOICE NUMBER :','dxinvoice'); ?> <?php echo  $dx_invoice_number; ?></td></tr>
							<tr><td><?php echo __('ACCOUNT NUMBER :','dxinvoice'); ?><span> <?php echo $dx_bank_account; ?></span> </td></tr>
							<tr><td bgcolor="#F83E3F" style="font-size:48px; background:#F83E3F;"><?php echo __('TOTAL  :','dxinvoice'); ?> <span class="dx-final-total"><?php echo number_format((float)$dx_final_total, 2, '.', ''); ?></span> </td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table align="center" cellspacing="5" bgcolor="#F4F4F4" cellpadding="5" style="color:#fff; font-size:28px; font-weight:bold;" width="100%">
				<tr>
					<td width="5%" bgcolor="Red" height="15" valign="middle"><?php echo __('NO','dxinvoice'); ?></td>
					<td width="20%" bgcolor="Red" height="15" valign="middle"><?php echo __('ITEM DESCRIPTION','dxinvoice'); ?></td>
					<td width="15%" bgcolor="Red" height="15" valign="middle"><?php echo __('RATE','dxinvoice'); ?></td>
					<td width="15%" bgcolor="Red"><?php echo __('QUANTITY','dxinvoice'); ?></td>
					<td width="15%" bgcolor="Red"><?php echo __('PRICE','dxinvoice'); ?></td>
					<td width="15%" bgcolor="Red"><?php echo __('DISCOUNT','dxinvoice'); ?></td>
					<td width="15%" bgcolor="Red"><?php echo __('TOTAL','dxinvoice'); ?></td>
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
					<td width="20%" height="15" valign="middle" class="changable-text  dx-invoice-desc"><?php echo  $invoice_row['invoice_description'] ?></td>
					<td width="15%" class="changable-text  dx-rate"><?php echo  $invoice_row['rate'] ?></td>
					<td width="15%" class="changable-text dx-quantity"><?php echo  $invoice_row['quantity'] ?></td>
					<td width="15%" class="dx-net"><?php echo  $invoice_row['net'] ?></td>
					<td width="15%" class="changable-text  dx-discount"><?php echo  $invoice_row['discount'] ?></td>
					<td width="15%" class="dx-total"><?php echo  $invoice_row['total'] ?></td>
				</tr>
			<?php  $number++;
			if($count == 1)
				$count = 0;
			else 
				$count = 1; 
			} ?>
				</tbody>
				<tr>
					<td colspan="5" rowspan="3" bgcolor="#F4F4F4" align="left">
						<?php if(!empty($dx_invoice_signature_img)){ ?> 
						<img align="left" src="<?php echo $dx_invoice_signature_img;  ?>"alt="" width="90" height="40"><br><br><?php echo  __('Signature','dxinvoice'); } ?>
					</td>
					<td colspan="2">
						<table bgcolor="Red" align="center" cellspacing="5" style="color:#fff; font-size:34px; font-weight:bold;" width="100%">
							<tr>
								<td><?php echo  __('Subtotal','dxinvoice'); ?></td>
								<td></td>
								<td align="right" class="dx-net-amount"><?php echo number_format((float)$invoice_net, 2, '.', ''); ?></td>
							</tr>
							<tr>
								<td><?php echo  __('Discount','dxinvoice'); ?></td>
								<td></td>
								<td align="right" class="dx-discount-all"><?php echo number_format((float)$invoice_discount, 2, '.', ''); ?></td>
							</tr>
							<tr>
								<td><?php echo  __('Tax','dxinvoice'); ?></td>
								<td align="right" class="dx-vat changable-text" ><?php echo $dx_vat_text."%"; ?></td>
								<td align="right" class="dx-vat-amount"><?php echo number_format((float)$vat_amount, 2, '.', ''); ?></td>
							</tr>
							<tr>
								<td><?php echo  __('Total','dxinvoice'); ?></td>
								<td></td>
								<td align="right" class="dx-final-total"><?php echo number_format((float)$dx_final_total, 2, '.', ''); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#F4F4F4">
		<td colspan="2">
			<table cellpadding="5" border="0">
				<tr>
					<td colspan="2" style="font-size:48px;"><?php echo __('Term & Conditions','dxinvoice')?> </td>
				</tr>
				<tr>
					<td colspan="2" style="font-size:34px;"><?php echo __('VAT regulations as per Article 21 (2)','dxinvoice')?> </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
