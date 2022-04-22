
<div class="col-xl-3 col-lg-4 col-md-12 order-lg-0 order-md-1 order-sm-1 order-1">
    <aside class="sidebar product--sidebar">
        <div class="sidebar-card card--category">
            <a class="card-title" href="#collapse1" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">
                <h5>Categories
                    <span class="icon-arrow-down"></span>
                </h5>
            </a>
            <div class="collapse show collapsible-content" id="collapse1">
                <ul class="card-content">
                    <?php
                    $data_cat = mysqli_query($db, "SELECT * FROM categories ORDER BY category ASC");
                    while ($data_cat_s = mysqli_fetch_assoc($data_cat)){
                    ?>
                    <li>
                        <a href="#">
                            <?=ucfirst($data_cat_s['category'])?>
                        </a>
                    </li>
                    <?
                    }
                    ?>
                </ul>
            </div><!-- end .collapsible_content -->
        </div><!-- end .sidebar-card -->
        <!--<div class="sidebar-card card--slider">-->
        <!--    <a class="card-title" href="#collapse3" data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">-->
        <!--        <h5>Filter Products-->
        <!--            <span class="icon-arrow-down"></span>-->
        <!--        </h5>-->
        <!--    </a>-->
        <!--    <div class="collapse show collapsible-content" id="collapse3">-->
        <!--        <div class="card-content">-->
        <!--            <div class="range-slider price-range"></div>-->
        <!--            <div class="price-ranges">-->
        <!--                <span class="from rounded">$30</span>-->
        <!--                <span class="to rounded">$300</span>-->
        <!--            </div>-->
        <!--            <div class="search-update">-->
        <!--                <a href="" class="btn btn-sm btn-primary">Search Update</a>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div><!-- end .sidebar-card -->
    </aside><!-- end aside -->
</div><!-- end .col-md-3 -->