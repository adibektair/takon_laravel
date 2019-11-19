<?php

$serviceId = $_GET['service_id'];
$amount = $_GET['amount'];
$user_id = $_GET['user_id'];

$service = \App\Service::where('id', $serviceId)->first();

?>




<form method="post" name="downloadForm" action="https://wl.walletone.com/checkout/checkout/Index">
	  <input name="WMI_MERCHANT_ID"    value="170961124637"/>
  <input name="WMI_PAYMENT_AMOUNT" value="<?=$amount?>.00"/>
  <input name="WMI_CURRENCY_ID"    value="398"/>
  <input name="WMI_DESCRIPTION"    value="Покупка <?= $amount ?> Таконов  <?= $service->name?> "/>
  <input name="WMI_SUCCESS_URL"    value="https://takon.org"/>
  <input name="WMI_FAIL_URL"       value="https://takon.org/policy"/>
  <input name="WMI_PAYMENT_NO"       value="1"/>
  <input type="submit"/>
</form>

<script>
	window.onload = submitForm;
	function submitForm() { downloadForm.submit(); }
</script>


