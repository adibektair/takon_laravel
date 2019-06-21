<?php
$md = $_GET['TransactionId'];
$pareq = $_GET['PaReq'];
$url = $_GET['url'];
?>
<form name="downloadForm" action="<?=$url?>" method="POST">
    <input name="PaReq" value="<?=$pareq?>">
    <input name="MD" value="<?=$md?>">
    <input type="hidden" name="TermUrl" value="http://takon.org/payment">
</form>
<script>
    window.onload = submitForm;
    function submitForm() { downloadForm.submit(); }
</script>

