<!DOCTYPE html>
<html lang="en">
<?php include '../lib/common.php';
include "includes/sonance_header.php";
$currencies = Settings::sessionCurrency();
$currency1 = $currencies['currency'];
$c_currency1 = $currencies['c_currency'];
$usd_field = 'usd_ask';
API::add('Transactions','get',array(false,false,1,$c_currency1,$currency1));
API::add('Stats','getCurrent',array($currencies['c_currency'],$currencies['currency']));
//27-USD, 28-BTC, 42-LTC, 43-ZEC, 44-BCH, 45-ETH
API::add('Transactions','get24hData',array(28,27));
API::add('Transactions','get24hData',array(42,27));
API::add('Transactions','get24hData',array(42,28));
API::add('Transactions','get24hData',array(43,27));
API::add('Transactions','get24hData',array(43,28));
API::add('Transactions','get24hData',array(45,27));
API::add('Transactions','get24hData',array(45,28));
API::add('Transactions','get24hData',array(42,45));
API::add('Transactions','get24hData',array(43,45));
API::add('Transactions','get24hData',array(44,27));
API::add('Transactions','get24hData',array(44,28));
$query = API::send();
// echo "<pre>"; print_r($query['Stats']['getCurrent']['results']); exit;
$transactions_24hrs_btc_usd = $query['Transactions']['get24hData']['results'][0] ;
$transactions_24hrs_ltc_usd = $query['Transactions']['get24hData']['results'][1] ;
$transactions_24hrs_ltc_btc = $query['Transactions']['get24hData']['results'][2] ;
$transactions_24hrs_zec_usd = $query['Transactions']['get24hData']['results'][3] ;
$transactions_24hrs_zec_btc = $query['Transactions']['get24hData']['results'][4] ;
$transactions_24hrs_eth_usd = $query['Transactions']['get24hData']['results'][5] ;
$transactions_24hrs_eth_btc = $query['Transactions']['get24hData']['results'][6] ;
$transactions_24hrs_ltc_eth = $query['Transactions']['get24hData']['results'][7] ;
$transactions_24hrs_zec_eth = $query['Transactions']['get24hData']['results'][8] ;
$transactions_24hrs_bch_usd = $query['Transactions']['get24hData']['results'][9] ;
$transactions_24hrs_bch_btc = $query['Transactions']['get24hData']['results'][10] ;
$currency_info = $CFG->currencies[$currencies['currency']];
$c_currency_info = $CFG->currencies[$currencies['c_currency']];
$currency_majors = array('USD','EUR','CNY','RUB','CHF','JPY','GBP','CAD','AUD');
$c_majors = count($currency_majors);
$curr_list = $CFG->currencies;
$curr_list1 = array();
foreach ($currency_majors as $currency) {
    if (empty($curr_list[$currency]))
        continue;

    $curr_list1[$currency] = $curr_list[$currency];
    unset($curr_list[$currency]);
}
$curr_list = array_merge($curr_list1,$curr_list);
// echo "<pre>"; print_r($curr_list); exit;
$stats = $query['Stats']['getCurrent']['results'][0];
if ($stats['daily_change'] > 0)
    $arrow = '<i id="up_or_down" class="fa fa-caret-up price-green"></i> ';
elseif ($stats['daily_change'] < 0)
    $arrow = '<i id="up_or_down" class="fa fa-caret-down price-red"></i> ';
else
    $arrow = '<i id="up_or_down" class="fa fa-minus"></i> ';
?>

<body id="wrapper">
    <!-- <div id="colorPanel" class="colorPanel">
        <a id="cpToggle" href="#"></a>
        <ul></ul>
    </div> -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <?php if (!User::isLoggedIn()): ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="index.html">
                <!-- <img class="logo" src="sonance/img/logo.png" alt=""> -->
                <!-- <h3 class="text-white"><?= $CFG->exchange_name; ?></h3> -->
                <img src="images/star.png" alt="img" class="logo-star">
                    <img src="images/logo1.png" alt="img" class="main-logo" style="filter: invert(100%" />
            </a>
            <div class="collapse navbar-collapse justify-content-md-center" id="navbarToggler">
                <!-- <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="sonance/img/icons/exchange-icon.png"><span class="name">Exchange</span></a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="trade.html">
                                Basic
                            </a>
                            <a class="dropdown-item" href="advanced.html">
                                Advanced
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""><img src="sonance/img/icons/lab-icon.png"><span class="name">Labs</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""><img src="sonance/img/icons/rocket-icon.png"><span class="name">LaunchPad</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link last" href="info.html"><img src="sonance/img/icons/info-icon.png"><span class="name">Info</span></a>
                    </li>
                </ul> -->
                <ul class="navbar-nav ml-auto">
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="">Join Us</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.messenger.com/t/194868894428597" target="_blank" rel="nofollow">Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://bitcoinscript.bitexchange.systems/" rel="nofollow" target="_blank">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register">Register</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">English</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="">
                                Deutsch
                            </a>
                            <a class="dropdown-item" href="">
                                Francais
                            </a>
                        </div>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link language-btn" href=""><img src="sonance/img/icons/translate-icon.png"></a>
                    </li> -->
                </ul>
            </div>
             <?php else: ?>
             <? include 'includes/topheader.php'; ?>
             <?php endif; ?>
        </div>
    </nav>
    <header>
        <div class="banner row no-margin">
            <div class="container content">
                <h1><?= $CFG->exchange_name; ?> - Exchange The World</h1>
                <div class="links">
                    <p><a href="register">Create Account</a><span class="line"></span>Already Registered? <a href="login">Login</a></p>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="banner-images">
                            <a href="https://bitexchange.systems/cryptocurrency-wallet-script/" target="_blank" rel="nofollow"><img src="sonance/img/banner/1.png"></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="banner-images">
                            <a href="https://bitexchange.systems/bitcoin-exchange-android-app-theme/" target="_blank" rel="nofollow"><img src="sonance/img/banner/2.png"></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="banner-images">
                            <a href="https://bitexchange.live/api-docs.php" target="_blank" rel="nofollow"><img src="sonance/img/banner/3.png"></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="banner-images">
                            <a href="http://bitcoinscript.bitexchange.systems/2018/01/cryptocurrency-exchange-software-security.html" target="_blank" rel="nofollow"><img src="sonance/img/banner/4.png"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky row no-margin">
             <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
            <div class="container">
                <div class="row">
                    
                    <?
                if ($curr_list) {
                    foreach ($curr_list as $key => $currency) {
                        if (is_numeric($key) || $currency['id'] == $c_currency_info['id'])
                            continue;
                
                        $last_price = Stringz::currency($stats['last_price'] * ((empty($currency_info) || $currency_info['currency'] == 'USD') ? 1/$currency[$usd_field] : $currency_info[$usd_field] / $currency[$usd_field]),2,4);
                        echo '<div style="padding:0 3em;"><span><a href="#"><span><b>'.$currency['currency'].'</b></span>&nbsp; <i>'.$last_price.'</i></a></span></div>';
                    }
                }
                ?>
                    
                   <!--  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <p><a href="#"><span><?= $CFG->exchange_name; ?> Lists Red Pulse (RPX)</span><i>(01-12)</i></a></p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <p><a href="#"><span><?= $CFG->exchange_name; ?> Lists Red Pulse (RPX)</span><i>(01-12)</i></a></p>
                    </div> -->
               
                </div>
            </div>
             </marquee>
        </div>
    </header>
    <div class="page-container">
        <div class="container">
            <div class="row statistics-widget">
                <div class="col">
                    <a href="#" class="statistics-widget-link">
                        <div class="statistics-widget-grid">
                            <div class="content">
                                <h5>BTC/USD</h5>
                                <h6><strong class="green-color"><? echo $transactions_24hrs_btc_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_btc_usd['lastPrice'],2,4) : '0.00' ; ?></strong><!--  $0.05 --></h6>
                                <p>Volume : <? echo $transactions_24hrs_btc_usd['transactions_24hrs'] ? $transactions_24hrs_btc_usd['transactions_24hrs'] : '0.00'; ?> BTC</p>
                            </div>
                            <span class="status green-color"><? echo $transactions_24hrs_btc_usd['change_24hrs'] ? $transactions_24hrs_btc_usd['change_24hrs'] : '0.00'; ?></span>
                            <div class="chart-bar">
                                <svg version="1.1" class="highcharts-root" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="translate(0.5,0.5)">
                                        <path id="BNBBTC" stroke="rgba(244,220,174,1)" fill="none" stroke-width="1" d="M0 0 L10 8 L20 2 L30 1 L40 4 L50 9 L60 23 L70 24 L80 23 L90 26 L100 35 L110 40 L120 40 L130 37 L140 38 L150 30 L160 26 L170 23 L180 28 L190 24 L200 17 L210 21 L220 24 L230 24"></path>
                                        <path id="BNBBTCfill" fill="rgba(254,251,245,1)" stroke="none" d="M0 40 L0 0 L10 8 L20 2 L30 1 L40 4 L50 9 L60 23 L70 24 L80 23 L90 26 L100 35 L110 40 L120 40 L130 37 L140 38 L150 30 L160 26 L170 23 L180 28 L190 24 L200 17 L210 21 L220 24 L230 24 L230 40"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="statistics-widget-link">
                        <div class="statistics-widget-grid">
                            <div class="content">
                                <h5>LTC/USD</h5>
                                <h6><strong class="green-color"><? echo $transactions_24hrs_ltc_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_ltc_usd['lastPrice'],2,4) : '0.00' ; ?></strong><!--  $0.05 --></h6>
                                <p>Volume : <? echo $transactions_24hrs_ltc_usd['transactions_24hrs'] ? $transactions_24hrs_ltc_usd['transactions_24hrs'] : '0.00'; ?> BTC</p>
                            </div>
                            <span class="status green-color"><? echo $transactions_24hrs_ltc_usd['change_24hrs'] ? $transactions_24hrs_ltc_usd['change_24hrs'] : '0.00'; ?></span>
                            <div class="chart-bar">
                                <svg version="1.1" class="highcharts-root" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="translate(0.5,0.5)">
                                        <path id="TRXBTC" stroke="rgba(244,220,174,1)" fill="none" stroke-width="1" d="M0 30 L10 27 L20 35 L30 38 L40 22 L50 38 L60 38 L70 35 L80 30 L90 30 L100 35 L110 40 L120 35 L130 38 L140 32 L150 30 L160 35 L170 30 L180 25 L190 13 L200 0 L210 13 L220 10 L230 13"></path>
                                        <path id="TRXBTCfill" fill="rgba(254,251,245,1)" stroke="none" d="M0 40 L0 30 L10 27 L20 35 L30 38 L40 22 L50 38 L60 38 L70 35 L80 30 L90 30 L100 35 L110 40 L120 35 L130 38 L140 32 L150 30 L160 35 L170 30 L180 25 L190 13 L200 0 L210 13 L220 10 L230 13 L230 40"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="statistics-widget-link">
                        <div class="statistics-widget-grid">
                            <div class="content">
                                <h5>BCH/USD</h5>
                                <h6><strong class="green-color"><? echo $transactions_24hrs_bch_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_bch_usd['lastPrice'],2,4) : '0.00' ; ?></strong><!--  $0.05 --></h6>
                                <p>Volume : <? echo $transactions_24hrs_bch_usd['transactions_24hrs'] ? $transactions_24hrs_bch_usd['transactions_24hrs'] : '0.00'; ?> BTC</p>
                            </div>
                            <span class="status green-color"><? echo $transactions_24hrs_bch_usd['transactions_24hrs'] ? $transactions_24hrs_bch_usd['change_24hrs'] : '0.00'; ?></span>
                            <div class="chart-bar">
                                <svg version="1.1" class="highcharts-root" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="translate(0.5,0.5)">
                                        <path id="RPXBTC" stroke="rgba(244,220,174,1)" fill="none" stroke-width="1" d="M0 13 L10 0 L20 15 L30 19 L40 20 L50 24 L60 24 L70 25 L80 22 L90 26 L100 22 L110 22 L120 32 L130 33 L140 36 L150 30 L160 37 L170 40 L180 35 L190 33 L200 34 L210 32 L220 34 L230 29"></path>
                                        <path id="RPXBTCfill" fill="rgba(254,251,245,1)" stroke="none" d="M0 40 L0 13 L10 0 L20 15 L30 19 L40 20 L50 24 L60 24 L70 25 L80 22 L90 26 L100 22 L110 22 L120 32 L130 33 L140 36 L150 30 L160 37 L170 40 L180 35 L190 33 L200 34 L210 32 L220 34 L230 29 L230 40"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="statistics-widget-link">
                        <div class="statistics-widget-grid">
                            <div class="content">
                                <h5>ZEC/USD</h5>
                                <h6><strong class="green-color"><? echo $transactions_24hrs_zec_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_zec_usd['lastPrice'],2,4) : '0.00' ; ?></strong><!--  $0.05 --></h6>
                                <p>Volume : <? echo $transactions_24hrs_zec_usd['transactions_24hrs'] ? $transactions_24hrs_zec_usd['transactions_24hrs'] : '0.00'; ?> BTC</p>
                            </div>
                            <span class="status green-color"><? echo $transactions_24hrs_zec_usd['transactions_24hrs'] ? $transactions_24hrs_zec_usd['change_24hrs'] : '0.00'; ?></span>
                            <div class="chart-bar">
                                <svg version="1.1" class="highcharts-root" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="translate(0.5,0.5)">
                                        <path id="GTOBTC" stroke="rgba(244,220,174,1)" fill="none" stroke-width="1" d="M0 7 L10 6 L20 8 L30 9 L40 3 L50 0 L60 6 L70 3 L80 8 L90 3 L100 3 L110 14 L120 12 L130 32 L140 38 L150 36 L160 35 L170 40 L180 37 L190 27 L200 32 L210 37 L220 39 L230 39"></path>
                                        <path id="GTOBTCfill" fill="rgba(254,251,245,1)" stroke="none" d="M0 40 L0 7 L10 6 L20 8 L30 9 L40 3 L50 0 L60 6 L70 3 L80 8 L90 3 L100 3 L110 14 L120 12 L130 32 L140 38 L150 36 L160 35 L170 40 L180 37 L190 27 L200 32 L210 37 L220 39 L230 39 L230 40"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="graph-outer">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container">
              <div id="tv-medium-widget"></div>
              <div class="tradingview-widget-copyright"></div>
              <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
              <script type="text/javascript">
              new TradingView.MediumWidget(
              {
              "container_id": "tv-medium-widget",
              "symbols": [
                "COINBASE:LTCUSD|1y",
                "COINBASE:BTCUSD|1y",
                "BITFINEX:ZECUSD|1y",
                "COINBASE:ETHUSD|1y",
                "COINBASE:BCHUSD|1y"
              ],
              "greyText": "Quotes by",
              "gridLineColor": "#e9e9ea",
              "fontColor": "#83888D",
              "underLineColor": "#dbeffb",
              "trendLineColor": "rgba(109, 158, 235, 1)",
              "width": "100%",
              "height": "400px",
              "locale": "in"
            }
              );
              </script>
            </div>
            <!-- TradingView Widget END -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="home-data-table">
                        <nav class="nav-justified">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-btc-tab" data-toggle="tab" href="#nav-btc" role="tab" aria-controls="nav-btc" aria-selected="true">USD Markets</a>
                                <!-- <a class="nav-item nav-link" id="nav-ltc-tab" data-toggle="tab" href="#nav-ltc" role="tab" aria-controls="nav-btc" aria-selected="false">LTC Markets</a> -->
                                <a class="nav-item nav-link" id="nav-eth-tab" data-toggle="tab" href="#nav-eth" role="tab" aria-controls="nav-eth" aria-selected="false">ETH Markets</a>
                                <!-- <a class="nav-item nav-link" id="nav-usdt-tab" data-toggle="tab" href="#nav-usdt" role="tab" aria-controls="nav-usdt" aria-selected="false">BCH Markets</a> -->
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-btc" role="tabpanel" aria-labelledby="nav-btc-tab">
                                <table id="hm-data-table" class="table row-border hm-data-table table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Pair</th>
                                            <th>Last Price</th>
                                            <th>24h Change</th>
                                            <th>24h High</th>
                                            <th>24h Low</th>
                                            <th>24h Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="clickable-row" data-href="#">
                                            <td>
                                                <div class="star-inner">
                                                    <input id="star1" type="checkbox" name="time" />
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                    BTC/USD
                                                </div>
                                            </td>
                                            <td><span class="green-color"><? echo $transactions_24hrs_btc_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_btc_usd['lastPrice'],2,4) : '0.00' ; ?></span> <!-- <span class="gray-color">/ $944.16</span> --></td>
                                            <td><span class="red-color"><? echo $transactions_24hrs_btc_usd['change_24hrs'] ? $transactions_24hrs_btc_usd['change_24hrs'] : '0.00' ; ?></span></td>
                                            <td>0.0232</td>
                                            <td>0.086131</td>
                                            <td><? echo $transactions_24hrs_btc_usd['transactions_24hrs'] ? $transactions_24hrs_btc_usd['transactions_24hrs'] : '0.00' ; ?></td>
                                        </tr>
                                        <tr class="clickable-row" data-href="#">
                                            <td>
                                                <div class="star-inner">
                                                    <input id="star1" type="checkbox" name="time" />
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                    LTC/USD
                                                </div>
                                            </td>
                                            <td><span class="green-color"><? echo $transactions_24hrs_ltc_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_ltc_usd['lastPrice'],2,4) : '0.00' ; ?></span> <!-- <span class="gray-color">/ $944.16</span> --></td>
                                            <td><span class="red-color"><? echo $transactions_24hrs_ltc_usd['change_24hrs'] ? $transactions_24hrs_ltc_usd['change_24hrs'] : '0.00' ; ?></span></td>
                                            <td>0.0232</td>
                                            <td>0.086131</td>
                                            <td><? echo $transactions_24hrs_ltc_usd['transactions_24hrs'] ? $transactions_24hrs_ltc_usd['transactions_24hrs'] : '0.00' ; ?></td>
                                        </tr>
                                        <tr class="clickable-row" data-href="#">
                                            <td>
                                                <div class="star-inner">
                                                    <input id="star1" type="checkbox" name="time" />
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                    BCH/USD
                                                </div>
                                            </td>
                                            <td><span class="green-color"><? echo $transactions_24hrs_bch_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_bch_usd['lastPrice'],2,4) : '0.00' ; ?></span> <!-- <span class="gray-color">/ $944.16</span> --></td>
                                            <td><span class="red-color"><? echo $transactions_24hrs_bch_usd['change_24hrs'] ? $transactions_24hrs_bch_usd['change_24hrs'] : '0.00' ; ?></span></td>
                                            <td>0.0232</td>
                                            <td>0.086131</td>
                                            <td><? echo $transactions_24hrs_bch_usd['transactions_24hrs'] ? $transactions_24hrs_bch_usd['transactions_24hrs'] : '0.00' ; ?></td>
                                        </tr>
                                        <tr class="clickable-row" data-href="#">
                                            <td>
                                                <div class="star-inner">
                                                    <input id="star1" type="checkbox" name="time" />
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                    ZEC/USD
                                                </div>
                                            </td>
                                            <td><span class="green-color"><? echo $transactions_24hrs_zec_usd['lastPrice'] ? Stringz::currency($transactions_24hrs_zec_usd['lastPrice'],2,4) : '0.00' ; ?></span> <!-- <span class="gray-color">/ $944.16</span> --></td>
                                            <td><span class="red-color"><? echo $transactions_24hrs_zec_usd['change_24hrs'] ? $transactions_24hrs_zec_usd['change_24hrs'] : '0.00' ; ?></span></td>
                                            <td>0.0232</td>
                                            <td>0.086131</td>
                                            <td><? echo $transactions_24hrs_zec_usd['transactions_24hrs'] ? $transactions_24hrs_zec_usd['transactions_24hrs'] : '0.00' ; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="nav-eth" role="tabpanel" aria-labelledby="nav-ltc-tab">
                                <table id="hm-data-table" class="table row-border hm-data-table table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Pair</th>
                                            <th>Last Price</th>
                                            <th>24h Change</th>
                                            <th>24h High</th>
                                            <th>24h Low</th>
                                            <th>24h Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="clickable-row" data-href="#">
                                            <td>
                                                <div class="star-inner">
                                                    <input id="star1" type="checkbox" name="time" />
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                    LTC/ETH
                                                </div>
                                            </td>
                                            <td><span class="green-color"><? echo $transactions_24hrs_ltc_eth['lastPrice'] ? $transactions_24hrs_ltc_eth['lastPrice'] : '0.00' ; ?></span> <!-- <span class="gray-color">/ $944.16</span> --></td>
                                            <td><span class="red-color"><? echo $transactions_24hrs_ltc_eth['change_24hrs'] ? $transactions_24hrs_ltc_eth['change_24hrs'] : '0.00' ; ?></span></td>
                                            <td>0.0232</td>
                                            <td>0.086131</td>
                                            <td><? echo $transactions_24hrs_ltc_eth['transactions_24hrs'] ? $transactions_24hrs_ltc_eth['transactions_24hrs'] : '0.00' ; ?></td>
                                        </tr>
                                        <tr class="clickable-row" data-href="#">
                                            <td>
                                                <div class="star-inner">
                                                    <input id="star1" type="checkbox" name="time" />
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                    ZEC/ETH
                                                </div>
                                            </td>
                                            <td><span class="green-color"><? echo $transactions_24hrs_zec_eth['lastPrice'] ? $transactions_24hrs_zec_eth['lastPrice'] : '0.00' ; ?></span> <!-- <span class="gray-color">/ $944.16</span> --></td>
                                            <td><span class="red-color"><? echo $transactions_24hrs_zec_eth['change_24hrs'] ? $transactions_24hrs_zec_eth['change_24hrs'] : '0.00' ; ?></span></td>
                                            <td>0.0232</td>
                                            <td>0.086131</td>
                                            <td><? echo $transactions_24hrs_zec_eth['transactions_24hrs'] ? $transactions_24hrs_zec_eth['transactions_24hrs'] : '0.00' ; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <div class="tab-pane fade" id="nav-usdt" role="tabpanel" aria-labelledby="nav-usdt-tab">
                                <table id="hm-data-table" class="table row-border hm-data-table table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Pair</th>
                                            <th>Last Price</th>
                                            <th>24h Change</th>
                                            <th>24h High</th>
                                            <th>24h Low</th>
                                            <th>24h Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="clickable-row" data-href="trade.html">
                                            <td>
                                                <div class="star-inner">
                                                    <input id="star1" type="checkbox" name="time" />
                                                    <label for="star1"><i class="fas fa-star"></i></label>
                                                    ETH/BTC
                                                </div>
                                            </td>
                                            <td><span class="green-color">0.086583</span> <span class="gray-color">/ $944.16</span></td>
                                            <td><span class="red-color">-1.87</span></td>
                                            <td>0.088899</td>
                                            <td>0.086131</td>
                                            <td>9,349.24283190</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="footer-links">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p><strong>Links:</strong></p>
                    <p class="links">
                        <a href="">NEO</a>
                        <a href="">Bitkan</a>
                        <a href="">BTC123</a>
                        <a href="">8BTC</a>
                    </p>
                </div>
            </div>
        </div>
    </div> -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xs-12 links">
                    <ul>
                        <li><a href="https://bitexchange.live/about.php" rel="nofollow" target="_blank">About</a></li>
                        <li><a href="https://bitexchange.live/terms.php" rel="nofollow" target="_blank">Terms</a></li>
                        <li><a href="https://bitexchange.live/api-docs.php" rel="nofollow" target="_blank">API</a></li>
                        <li><a href="https://bitexchange.live/fee-schedule.php" rel="nofollow" target="_blank">Fees</a></li>
                        <li><a href="https://bitexchange.live/contact.php" target="_blank" rel="nofollow">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-xs-12 social">
                    <ul>
                        <li><a href=""><i class=""><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href=""><i class="fab fa-twitter"></i></a></li>
                        <li><a href=""><i class="fab fa-reddit-alien"></i></li>
                        <li><a href=""><i class="fab fa-google-plus-g"></i></a></li>
                        <li><a href=""><i class="fab fa-medium-m"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row copy-bar">
                <div class="col-md-6 col-xs-12 copy">
                    <p>&copy; 2018 <?= $CFG->exchange_name; ?> All Rights Reserved</p>
                </div>
                <!-- <div class="col-md-6 col-xs-12 statistics">
                    <p><span class="gray-color">24h Volume：</span> 1,211,621.18 <span class="gray-color">LTC/</span> 81,420.07 <span class="gray-color">BTC/</span> 238,606.22 <span class="gray-color">ETH/674,419,885.28 <span class="gray-color">USDT</span> </p>
                </div> -->
            </div>
        </div>
    </footer>
    <div class="fb_chat" style=""> 
        <a href="https://www.messenger.com/t/194868894428597" class="fb-msg-btn-chat" target="_blank" rel="nofollow"> Contact us on Facebook</a> 
    </div>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js">
    </script>
    </script>
    <!-- Color Switcher -->
    <script type="text/javascript" src="sonance/js/jquery.colorpanel.js"></script>
    <!-- Custom Scripts -->
    <script type="text/javascript" src="sonance/js/script.js"></script>
</body>
<script type="text/javascript">
$(document).ready(function() {
    $('.hm-data-table').DataTable();
});
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#colorPanel').ColorPanel({
        styleSheet: '#cpswitch',
        animateContainer: '#wrapper',
        colors: {
            '#2f3340': 'css/skins/default.css',
            '#16a085': 'css/skins/seagreen.css',
            '#000000': 'css/skins/black.css',
            '#4b77be': 'css/skins/blue.css',
            '#c0392c': 'css/skins/red.css',
        }
    });
});
</script>

</html>