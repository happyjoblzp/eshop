<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title><?php echo $this->_siteConfig->name;?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-width=1,user-scalable=0">
    <link type="image/x-icon" href="<?php echo IUrl::creatUrl("")."favicon.ico";?>" rel="icon">
    <script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/jquery/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/bootstrap/js/bootstrap.min.js"></script><link rel="stylesheet" type="text/css" href="/eshop/runtime/_systemjs/bootstrap/css/bootstrap.min.css" />
    <script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/form/form.js"></script>
    <script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/autovalidate/validate.js?v=20190606"></script><link rel="stylesheet" type="text/css" href="/eshop/runtime/_systemjs/autovalidate/style.css" />
    <script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/artdialog/artDialog.js?v=20190606"></script><script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/artdialog/plugins/iframeTools.js"></script><link rel="stylesheet" type="text/css" href="/eshop/runtime/_systemjs/artdialog/skins/black.css" />
    <script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/artTemplate/artTemplate.js"></script><script type="text/javascript" charset="UTF-8" src="/eshop/runtime/_systemjs/artTemplate/artTemplate-plugin.js"></script>
    <script src='<?php echo $this->getWebViewPath()."javascript/jquery.i18n.min.js";?>'></script>
    <link rel="stylesheet" type="text/css" href="/eshop/runtime/_systemjs/bootstrap/css/bootstrap.min.css" />
    <!--[if IE]><script src="<?php echo $this->getWebViewPath()."javascript/html5.js";?>"></script><![endif]-->
    <script src='<?php echo $this->getWebViewPath()."javascript/site.js";?>'></script>
    <script type='text/javascript' src='<?php echo IUrl::creatUrl("")."public/javascript/public.js";?>'></script>
    <!-- <link rel="stylesheet" href="<?php echo $this->getWebSkinPath()."style/style.css";?>"> -->
    <link rel="stylesheet" href="<?php echo $this->getWebSkinPath()."style/site.css";?>">
    <link rel="stylesheet" href="<?php echo $this->getWebSkinPath()."style/base-custom.css";?>">
    <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">
    <script src="https://unpkg.com/swiper/js/swiper.min.js"> </script>
</head>

<body>

    <div class="top_menu">
        <nav class="navbar navbar-default top_menu_navbar" role="navigation">
            <div id="main" class="container">

                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">切换导航</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="https://www.ajvs.com/sklep.new/index.php"><span class="glyphicon glyphicon-home"></span></a>
                </div>
                <div class="collapse navbar-collapse" id="top-navbar-collapse">
                    <ul class="nav navbar-nav text-center" id="header_navbar">

                        <li><a href="" class="underline">关于我们</a></li>
                        <li><a href="" class="underline">维修 & 服务</a></li>
                        <li><a href="" class="underline">帮助 & 支持</a></li>
                        <li><a href="" class="underline">联系方式</a></li>
                        <li><a href="" class="underline">我的账户</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right text-center">
                        <li>
                            <a href="https://www.ajvs.com/new/create_account.php">中英文切换</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div id="main2" class="container">

        <div class="row top_menu_items">
            <div class="row margin-15">
                <div class="col-item col-sm-4 block_display logo-block">
                    <a>
                        <img class="logo" src="static/img/logo.png" />
                    </a>
                    <a><span class="glyphicon glyphicon-shopping-cart shop-icon hidden-md hidden-lg pull-right"></span></a>
                </div>

                <div class="col-item col-sm-3 hidden-xs hidden-sm">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-item col-sm-2">
                                <span class="glyphicon glyphicon-earphone phone-icon"></span>
                            </div>
                            <div class="col-item col-sm-10 contact-number
                            ">
                                <div class="display-inline-block">
                                    <p>销售&服务热线</p>
                                    <p>021-88888888</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-item col-sm-1 hidden-xs hidden-sm">
                    <span class="glyphicon glyphicon-shopping-cart shop-icon"></span>
                </div>
                <div class="col-item col-sm-6 col-md-4">
                    <div class="search-input-wrap clearfix">
                        <input type="text" class="form-control search-input" placeholder="keyword" aria-describedby="sizing-addon1">
                        <button type="button" class="btn btn-default search-btn btn-lg">
                            <span class="glyphicon glyphicon-search "></span>
                        </button>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div id="main3">
        <div class="container">
            <div class="row top_menu_items">
                <div class="row margin-15">
                    <div class="col-xs-12 col-sm-7 block_display abort-us">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="nowrap col-xs-6 col-sm-2 ">
                                    <a>
                                        关于我们
                                    </a>
                                </div>
                                <div class="nowrap col-xs-6 col-sm-2 ">
                                    <span>邮箱:</span>
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4 clearfix repair-btns">
                        <div class="btn-wrap">
                            <span class="hidden-xs hidden-sm ">报修服务:</span>
                            <button id="rma" type="button" class="btn btn-default color-main color-white">
                                <span class="
                                        glyphicon glyphicon-list-alt"></span>
                                RMA</button>
                        </div>
                        <div class="btn-wrap">
                            <button id="repair" type="button" class="btn btn-default color-main">
                                <span class="glyphicon glyphicon-wrench color-white"></span>
                                维修评估</button>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="main-banner">

    </div>
    <!-- 
    <div class="container">
        <img src="static/img/cat1" height="120px" />
    </div> -->

    <div id="category" class="container catlist none">
        <div class="head clearfix">
            <h2>产品分类</h2>
            <div class="swiper-pagination"></div>
        </div>
        <div id="categorylist" class="container-fluid  catlist none">
            <div class="swiper-container-catlist swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目1</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat1" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目2</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat2" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目3</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat3" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目4</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat4" />
                                </div>
                                <a></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目1</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat1" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目2</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat2" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目3</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat3" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目4</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat4" />
                                </div>
                                <a></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目1</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat1" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目2</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat2" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目3</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat3" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目4</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat4" />
                                </div>
                                <a></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目1</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat1" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目2</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat2" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目3</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat3" />
                                </div>
                                <a></a>
                            </div>
                            <div class="col-item col-sm-3">
                                <div class="cat-item-title">类目4</div>
                                <div class="cat-item-content">
                                    <img class="" src="static/img/cat4" />
                                </div>
                                <a></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 如果需要分页器 -->


            </div>

        </div>
    </div>



    <div id="recommend" class="container itemlist none">

        <div class="head">
            <h2>推荐商品</h2>
        </div>

        <div id="recommendList" class="container-fluid itemlist none">
            <div class="swiper-container swiper-container-itemlist">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-item col-sm-4">
                                <div class="item-item-title">产品1</div>
                                <div class="item-item-content">
                                    <img class="" width="171px" height="195px" src="static/img/item1" />
                                    <div class="price-wrap">
                                        <span>价格:</span>
                                        <span>$123</span>
                                    </div>
                                    <div class="btns-wrap">
                                        <a>详情</a>
                                        <button type="button" class="btn btn-danger btn-sm">购买</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-item col-sm-4">
                                <div class="item-item-title">产品1</div>
                                <div class="item-item-content">
                                    <img class="" width="171px" height="195px" src="static/img/item1" />
                                    <div class="price-wrap">
                                        <span>价格:</span>
                                        <span>$123</span>
                                    </div>
                                    <div class="btns-wrap">
                                        <a>详情</a>
                                        <button type="button" class="btn btn-danger btn-sm">购买</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-item col-sm-4">
                                <div class="item-item-title">产品1</div>
                                <div class="item-item-content">
                                    <img class="" width="171px" height="195px" src="static/img/item1" />
                                    <div class="price-wrap">
                                        <span>价格:</span>
                                        <span>$123</span>
                                    </div>
                                    <div class="btns-wrap">
                                        <a>详情</a>
                                        <button type="button" class="btn btn-danger btn-sm">购买</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-item col-sm-4">
                                <div class="item-item-title">产品1</div>
                                <div class="item-item-content">
                                    <img class="" width="171px" height="195px" src="static/img/item1" />
                                    <div class="price-wrap">
                                        <span>价格:</span>
                                        <span>$123</span>
                                    </div>
                                    <div class="btns-wrap">
                                        <a>详情</a>
                                        <button type="button" class="btn btn-danger btn-sm">购买</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-item col-sm-4">
                                <div class="item-item-title">产品1</div>
                                <div class="item-item-content">
                                    <img class="" width="171px" height="195px" src="static/img/item1" />
                                    <div class="price-wrap">
                                        <span>价格:</span>
                                        <span>$123</span>
                                    </div>
                                    <div class="btns-wrap">
                                        <a>详情</a>
                                        <button type="button" class="btn btn-danger btn-sm">购买</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-item col-sm-4">
                                <div class="item-item-title">产品1</div>
                                <div class="item-item-content">
                                    <img class="" width="171px" height="195px" src="static/img/item1" />
                                    <div class="price-wrap">
                                        <span>价格:</span>
                                        <span>$123</span>
                                    </div>
                                    <div class="btns-wrap">
                                        <a>详情</a>
                                        <button type="button" class="btn btn-danger btn-sm">购买</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 如果需要导航按钮 -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

            </div>

        </div>
    </div>


    <div class="footer">
        <ul class="list-inline container">

            <li><a>关于我们</a><span class="delimiter-line">|</span></li>
            <li><a>联系我们</a><span class="delimiter-line">|</span></li>
            <li><a>联系客服</a><span class="delimiter-line">|</span></li>
            <li><a>合作招商</a><span class="delimiter-line">|</span></li>
            <li><a>维修单</a></li>
        </ul>
    </div>

    <script>
        var mySwiperCat = new Swiper('.swiper-container-catlist', {
            loop: true, // 循环模式选项
            ///autoplay: true,
            //delay: 5000,
            // 如果需要分页器
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },


        })
        var mySwiperItem = new Swiper('.swiper-container-itemlist', {
            loop: true, // 循环模式选项
            //autoplay: true,
            //delay: 5000,

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        })
    </script>
</body>
<script>
    //当首页时显示分类
    {
        if: IWeb::$app - > getController() - > getId() == 'site' && IWeb::$app - > getController() - > getAction() - > getId() == 'index'
    }
    $('.catlist').removeClass('none');
    $('.itemlist').removeClass('none'); {
        /if}
        $(function() {
            $('input:text[name="word"]').val("<?php echo $this->word;?>");
        });
</script>

</html>