<?php

    $md = $_GET['TransactionId'];
    $pareq = $_GET['PaReq'];
    $url = $_GET['url'];
    const API_KEY = 'f1bfe6d357926dca0b37913171d258af';
    const ID = 'pk_0ad5acde2f593df7c5a63c9c27807';

?>

<form name="downloadForm" action="<?=$url?>" method="POST">
    <input type="hidden" name="PaReq" value="<?=$pareq?>">
    <input type="hidden" name="MD" value="<?=$md?>">
    <input type="hidden" name="TermUrl" value="http://takon.org/api/paymentcomplete">
</form>
<script>
    window.onload = submitForm;
    function submitForm() { downloadForm.submit(); }
</script>

