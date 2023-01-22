<!DOCTYPE html>
<html lang="en">
<head>
    <title>Design listing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="jumbotron text-center">
    <h1><a href="/" target="_self" style="all: unset; cursor: pointer;">List of Designs</a></h1>
</div>

<div class="container">
    <div class="row">
    <?php foreach ($this->_['designs'] as $index => $design) : ?>
        <div class="col-xs-12 col-sm-4 p-3 <?= $index==3 ? 'bg-info' : '' ?>">
            <a href="/createPdf?id=<?= $design['id'] ?>" target="_blank">
                <img src="<?= $design['thumb_local'] ?>" alt=""/>
            </a>

            <h5><?= $design['title'] ?></h5>

            <p>
                <span id="price_<?= $design['id'] ?>"><?= $design['price_local'] ?></span>

                <?php if ($design['is_postcard'] === true && count($design['productOptionsPrice'])) : ?>
                    <label for="optionPrice_<?= $design['id'] ?>"></label>
                    <select name="optionPrice" id="optionPrice_<?= $design['id'] ?>" onchange="setPrice(this)">
                    <?php foreach ($design['productOptionsPrice'] as $quantity => $prices) : ?>
                    <option value="<?=$prices['price_formatted']?>">
                        <?=$quantity . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $prices['price_formatted'] . ' (' . $prices['price_per_card'] . ' ea.)' ?>
                    </option>
                    <?php endforeach ?>
                </select>
                <?php endif ?>
            </p>
        </div>
    <?php endforeach ?>
    </div>

    <?php $currentPage = $this->_['currentPage'] ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $currentPage==1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $currentPage!=1 ? '?page=' . ($currentPage-1) : '#' ?>" tabindex="-1">Previous</a>
            </li>
            <?php for ($page = 1; $page <= $this->_['pages']; $page++) : ?>
            <li class="page-item <?= $this->_['currentPage']==$page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
            </li>
            <?php endfor ?>
            <li class="page-item <?= $this->_['currentPage']==$this->_['pages'] ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $currentPage!=$this->_['pages'] ? '?page=' . ($currentPage+1) : '#' ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<script type="text/javascript">
    function setPrice(selectObject) {
        let newPrice = selectObject.value;
        let priceId = selectObject.id.replace("optionPrice_", "price_");

        jQuery('#' + priceId).text(newPrice);
    }

</script>

</body>
</html>
