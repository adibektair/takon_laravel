<?php
$md = $_GET['TransactionId'];
$pareq = $_GET['PaReq'];
$url = $_GET['url'];

const API_KEY = 'f1bfe6d357926dca0b37913171d258af';
const ID = 'pk_0ad5acde2f593df7c5a63c9c27807';
//
//$data = array("PaReq" => $pareq, "MD" => $md, 'TermUrl' => 'http://takon.org/api/paymenthandle');
//
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//    'Content-type: application/x-www-form-urlencoded',
//    'Authorization: Basic '. ID . ":". API_KEY
//));
//curl_setopt($ch, CURLOPT_URL,$url);
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS,
//    http_build_query($data));
//
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//$server_output = curl_exec ($ch);
//curl_close ($ch);
//
//dd($server_output);

//?>

<form name="downloadForm" action="<?=$url?>" method="POST">
    <input name="PaReq" value="<?=$pareq?>">
    <input name="MD" value="<?=$md?>">
    <input name="TermUrl" value="http://takon.org/paycom">
</form>
<script>
    window.onload = submitForm;
    function submitForm() { downloadForm.submit(); }
</script>

