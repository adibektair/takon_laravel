<?php

$id = $_GET['id'];
$wallet_payment = \App\WalletPayment::where('id', $id)->first();

?>




<form method="post" name="downloadForm" action="https://wl.walletone.com/checkout/checkout/Index">
	  <input name="WMI_MERCHANT_ID"    value="170961124637"/>
  <input name="WMI_PAYMENT_AMOUNT" value="<?=$wallet_payment->prices?>.00"/>
  <input name="WMI_CURRENCY_ID"    value="398"/>
  <input name="WMI_DESCRIPTION"    value="Покупка <?= $wallet_payment->amount ?> Таконов  <?= $wallet_payment->getService()->name?> "/>
  <input name="WMI_SUCCESS_URL"    value="https://takon.org"/>
  <input name="WMI_FAIL_URL"       value="https://takon.org/policy"/>
  <input name="WMI_PAYMENT_NO"       value="<?=$wallet_payment->id?>"/>
  <input type="submit"/>
</form>

<script>
	window.onload = submitForm;
	function submitForm() { downloadForm.submit(); }
</script>


