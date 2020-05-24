<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("GEMiner Premium", "Details about GEMiner premium");
?>

<h1>GEMiner Premium</h1>
<p>
    Do you crave virtual colours, but you're not getting enough of them?<br>
    Are you somewhat insane?<br>
    Do gradually increasing numbers turn you on?<br>
    Do said numbers need to have little to no effect on anything for them to turn you on <i>effectively</i>?<br>
    You're a Gemini, right?<br>
    <br>
    Well, have I got the solution for you!<br>
    Get GEMiner premium to quench all your virtual number-craving needs!<br>
    <br>
    To get GEMiner premium, <a class="btn btn-sm btn-primary" href="<?=$premium_purchase_url?>">buy</a> a code then <a class="btn btn-sm btn-primary" href="redeem.php">redeem</a> it.<br>
    <br>
    Oh? You want to know what it is you're buying before you buy what you're buying?<br>
    Fine, here's a handy table.
</p>

<style>
td, th {
  border: 1px solid black;
}
</style>

<table>
    <thead>
        <tr>
            <th></th>
            <th>Free</th>
            <th>Premium</th>
        </tr>
    </thead>


    <tbody>
        <tr>
            <td>Shift storage (how many shifts you can save while idle)</td>
            <td>25 (storage for just over a day)</td>
            <td>9000 (storage for just over a year)</td>
        </tr>

        <tr>
            <td>Collections (how many you can create)</td>
            <td>10</td>
            <td>250</td>
        </tr>

        <tr>
            <td>Coolness level</td>
            <td>Kinda</td>
            <td>Very</td>
        </tr>
    </tbody>
</table>

<?php gen_bottom(); ?>