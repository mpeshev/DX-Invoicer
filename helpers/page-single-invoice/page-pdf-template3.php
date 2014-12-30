<?php 
/*
Template Name : Pdf Generate Code
Ref. Url : --
Compitible Browser : IE-8, Google Crome, Mozillafirefox-15.0.1
*/
$count = 0;

?>
<style>
table { /* Will apply to all tables */
	/* padding:0; */
/* OR border-collapse: collapse; */
}
</style>

<table width="100%">
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td style="font-size:54px;">[Company Name]</td>
				</tr>
				<tr>
					<td style="font-size:34px;">[Street Address:]</td>
				</tr>
				<tr>
					<td style="font-size:34px;">[City:]</td>
				</tr>
				<tr>
					<td style="font-size:34px;">[Phone:]</td>
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
						<table border="1" cellpadding="5" style="font-size:34px;">
							<tr>
								<td><b>INVOICE #</b></td>
								<td><b>DATE</b></td>
							</tr>
							<tr>
								<td><?php echo $dx_invoice_number; ?></td>
								<td>12/05/2014</td>
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
								<td><?php echo $dx_bank_account; ?></td>
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
	</tr>
	<tr>
		<td height="15" colspan="2"></td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%" cellpadding="5" style="font-size:34px;">
				<tr>
					<td>
						<table bgcolor="#DBDBDB" width="100%" border="1" cellpadding="5" style="font-size:38px;">
							<tr>
								<td width="55%"><?php echo __('DESCRIPTION'); ?></td>
								<td width="15%"><?php echo __('QTY'); ?></td>
								<td width="15%"><?php echo __('UNIT PRICE'); ?></td>
								<td width="15%"><?php echo __('AMOUNT'); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<?php foreach ($dx_top_custom_value as $invoice_row){?>
					<tr bgcolor="<?php if($count == 1) {echo '#DEDEDE';} else{  echo '#CECECE';} ?>">
						<td width="55%" height="15" valign="middle"><?php echo  $invoice_row['invoice_description'] ?></td>
						<td width="15%"><?php echo  $invoice_row['quantity'] ?></td>
						<td width="15%"><?php echo  $invoice_row['rate'] ?></td>
						<td width="15%"><?php echo  $invoice_row['total'] ?></td>
					</tr>
				<?php  
				if($count == 1)
					$count = 0;
				else 
					$count = 1; 
				} ?>
				<tr style="border:1px solid #333;">
					<td width="55%"><?php echo __('Thank you for your business'); ?></td>
					<td colspan="2"><?php echo __('TOTAL'); ?></td>
					<td><?php echo $invoice_total;?></td>
				</tr>
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
<!--<table width="100%">
	<tr>
		<td bgcolor="#F5F5F5" height="25px" colspan="2" ></td>
	</tr>
	<tr>
		<td width="70%"></td>
		<td width="30%">
			<table bgcolor="Black" valign="middle" style="color:#fff; font-size:94px; border-spacing:0;" valign="middle" align="center">
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
</table>-->
