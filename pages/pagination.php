<p>
    <?=$paginator->getTotalItems()?> found.
    
    Showing 
    <?=$paginator->getCurrentPageFirstItem()?> 
    - 
    <?=$paginator->getCurrentPageLastItem()?>.
</p>

<nav>
    <ul class="pagination">
        <?php if ($paginator->getPrevUrl()) { ?>
        <li class="page-item"><button class="page-link" onclick="goToPage(<?=$paginator->getPrevUrl()?>)">&laquo; Previous</button></li>
        <?php } ?>

        <?php foreach ($paginator->getPages() as $page) { ?>
            <?php if ($page['url']) { ?>
            <li class="page-item<?=$page['isCurrent'] ? " active" : ""?>">
                <button class="page-link" onclick="goToPage(<?=$page['url']?>)"><?=$page['num']?></button>
            </li>
            <?php } else { ?>
            <li class="page-item disabled">
                <span class="page-link"><?=$page['num']?></span>
            </li>
            <?php } ?>
        <?php } ?>

        <?php if ($paginator->getNextUrl()) { ?>
        <li class="page-item"><button class="page-link" onclick="goToPage(<?=$paginator->getNextUrl()?>)">Next &raquo;</button></li>
        <?php } ?>
    </ul>
</nav>

<script>
function goToPage(pageNum) {
    let query = $.getQueryParameters();
    query.page = pageNum;
    window.location = window.location.pathname+"?"+$.param(query);
}
</script>