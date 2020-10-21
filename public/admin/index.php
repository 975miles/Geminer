<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
restrict_to("admin");
gen_top("Admin dashboard");
?>
<h1>Admin actions</h1>
<hr>
<a class="btn btn-primary" href="dbm/">Manage the database</a>
<hr>
<a class="btn btn-primary" href="announce">Make an announcement</a>
<hr>
<form class="form-inline" action="generate-codes" method="post">
    <button class="btn btn-primary" type="submit">Generate</button>
    <input class="form-control" id="codeAmount" type="number" name="number" min="1" max="1000" value="1">
    <span>code(s) for</span>
    <input class="form-control" id="productAmount" type="number" name="amount" value="1">
    <select class="form-control" id="gemSelect" name="gemId"></select>
    <select class="form-control" id="crateTypeSelect" name="crateType"></select>
    <select class="form-control" id="productType" onchange="changeType()" name="product" required>
        <option value="none">(choose)</option>
        <option value="premium">premium</option>
        <option value="energy">energy</option>
        <option value="money"><?=$currency_symbol?></option>
        <option value="gem">gem(s)</option>
        <option value="crate">crate(s)</option>
    </select>
</form>

<script src="/a/js/admin/code.js"></script>
<?php gen_bottom(); ?>