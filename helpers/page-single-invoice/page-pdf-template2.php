<?php 
/*
Template Name : Pdf Generate Code
Ref. Url : --
Compitible Browser : IE-8, Google Crome, Mozillafirefox-15.0.1
*/
$count = 0;

$dx_invoice_detail = '<table width="100%" border="0" style="padding:5px;" cellpadding="5">
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
<style>
table { /* Will apply to all tables */
	/* padding:0; */
/* OR border-collapse: collapse; */
}
</style>
<table width="100%">
	<tr>
		<td colspan="2">
			<table width="100%" cellpadding="10" bgcolor="red" style="color:#fff;" frame="void">
				<tbody>
					<tr>
					<td width="70%">
						<table cellpadding="5">
							<tr>
								<td width="15%"></td>
								<td width="85%" style="font-size:54px;">DESIGN FIRM<BR>SLOGAN HERE</td>
							</tr>
						</table>					
					</td>
					<td width="30%">
						<table align="left" width="100%">
							<tbody>
								<tr><td>WWW.google.com</td></tr>
								<tr><td>admin@admin.com</td></tr>
								<tr><td>123 456 789</td></tr>
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
		<td width="80%"></td>
		<td width="20%">
			<table bgcolor="Black" valign="middle" style="color:#fff; font-size:54px; border-spacing:0;" valign="middle" align="center">
				<tr>
					<td>
						INVOICE
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#DEDEDE" width="50%">
			<table cellpadding="10" bgcolor="#F5F5F5">
				<tr>
					<td>
						<table style="font-size:28px;padding:5px;">
							<tr><td style="font-size:34px;"><b>INVOICE TO : <BR><?php echo $dx_customer_name;?></b><BR><b><?php echo $dx_company_name;?></b></td></tr>
							<tr><td>ADDRESS : <br><?php echo $dx_company_address; ?></td></tr>
							<tr><td>PHONE : <?php echo $dx_company_number; ?> </td></tr>
							<tr><td>EMAIL : ADMIN @ ADMIN.COM</td></tr>
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
							<tr><td>DATE : 13/5/2014</td></tr>
							<tr><td>INVOICE NUMBER : <?php echo  $dx_invoice_number; ?></td></tr>
							<tr><td>ACCOUNT NUMBER : <?php echo $dx_bank_account; ?> </td></tr>
							<tr><td bgcolor="#F83E3F" style="font-size:48px; background:#F83E3F;">TOTAL  : <?php echo $invoice_total;?> </td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table align="center" cellspacing="5" bgcolor="#F4F4F4" cellpadding="5" style="color:#fff;" width="100%">
				<tr>
					<td width="40%" bgcolor="Red" height="15" valign="middle">ITEM DESCRIPTION</td>
					<td width="20%" bgcolor="Red">QUANTITY</td>
					<td width="20%" bgcolor="Red">PRICE</td>
					<td width="20%" bgcolor="Red">TOTAL</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table align="center" cellpadding="5" style="color:#000; font-size:34px;" width="100%">
			<?php foreach ($dx_top_custom_value as $invoice_row){?>
				<tr bgcolor="<?php if($count == 1) {echo '#DEDEDE';} else{  echo '#CECECE';} ?>">
					<td width="40%" height="15" valign="middle"><?php echo  $invoice_row['invoice_description'] ?></td>
					<td width="20%"><?php echo  $invoice_row['quantity'] ?></td>
					<td width="20%"><?php echo  $invoice_row['rate'] ?></td>
					<td width="20%"><?php echo  $invoice_row['total'] ?></td>
				</tr>
			<?php  
			if($count == 1)
				$count = 0;
			else 
				$count = 1; 
			} ?>
				<tr>
					<td colspan="2" rowspan="3" bgcolor="#F4F4F4" align="left">
						<?php if(!empty($dx_invoice_signature_img)){ ?> 
			<img align="left" src="<?php echo $dx_invoice_signature_img;  ?>"alt="" width="90" height="40"><br><?php echo  __('Signature','dxinvoice'); } ?>
					</td>
					<td colspan="2">
						<table bgcolor="Red" align="center" cellspacing="5" style="color:#fff; font-size:34px; font-weight:bold;">
							<tr>
								<td><?php echo  __('Subtotal','dxinvoice'); ?></td>
								<td><?php echo $invoice_total; ?></td>
							</tr>
							<tr>
								<td><?php echo  __('Tax','dxinvoice'); ?></td>
								<td>-</td>
							</tr>
							<tr>
								<td><?php echo  __('Total','dxinvoice'); ?></td>
								<td><?php echo $invoice_total; ?></td>
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
