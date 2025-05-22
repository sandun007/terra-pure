@extends('admin.app')
@push('title')
    Dashboard
@endpush
@section('content')
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget w-100">
                <div class="dash-widgetimg">
                    <span><img src="{{asset('asset/img/theme/dash1.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>RS.<span class="counters" data-count="307144.00">307144</span></h5>
                    <h6>Total Sales</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget dash1 w-100">
                <div class="dash-widgetimg">
                    <span><img src="{{asset('asset/img/theme/dash2.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>RS.<span class="counters" data-count="4385.00">4385</span></h5>
                    <h6>Total Profit</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget dash2 w-100">
                <div class="dash-widgetimg">
                    <span><i class="fas fa-user-tag"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5><span class="counters" data-count="385656.50">385656.5</span></h5>
                    <h6>Total Seller</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget dash3 w-100">
                <div class="dash-widgetimg">
                    <span><img src="{{asset('asset/img/theme/dash4.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>RS.<span class="counters" data-count="40000.00">40000</span></h5>
                    <h6>Total Commission</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count">
                <div class="dash-counts">
                    <h4>100</h4>
                    <h5>Customers</h5>
                </div>
                <div class="dash-imgs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das1">
                <div class="dash-counts">
                    <h4>110</h4>
                    <h5>Suppliers</h5>
                </div>
                <div class="dash-imgs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="feather feather-user-check">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das2">
                <div class="dash-counts">
                    <h4>150</h4>
                    <h5>Purchase Invoice</h5>
                </div>
                <div class="dash-imgs">
                    <img src="{{asset('asset/img/theme/file-text-icon-01.svg')}}" class="img-fluid"
                         alt="icon">
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das3">
                <div class="dash-counts">
                    <h4>170</h4>
                    <h5>Sales Invoice</h5>
                </div>
                <div class="dash-imgs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="feather feather-file">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                        <polyline points="13 2 13 9 20 9"></polyline>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->

    <div class="row">
        <div class="col-xl-7 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Purchase &amp; Sales</h5>
                    <div class="graph-sets">
                        <ul class="mb-0">
                            <li>
                                <span>Sales</span>
                            </li>
                            <li>
                                <span>Purchase</span>
                            </li>
                        </ul>
                        <div class="dropdown dropdown-wraper">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                2023
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2023</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2022</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2021</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="sales_charts" style="min-height: 335px;" class="">
                        <div id="apexchartsm1yapawr"
                             class="apexcharts-canvas apexchartsm1yapawr apexcharts-theme-"
                             style="width: 878px; height: 320px;">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                 xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                 xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="878" height="320">
                                <foreignObject x="0" y="0" width="878" height="320">
                                    <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"
                                         style="max-height: 160px;"></div>
                                    <style type="text/css">
                                        .apexcharts-flip-y {
                                            transform: scaleY(-1) translateY(-100%);
                                            transform-origin: top;
                                            transform-box: fill-box;
                                        }

                                        .apexcharts-flip-x {
                                            transform: scaleX(-1);
                                            transform-origin: center;
                                            transform-box: fill-box;
                                        }

                                        .apexcharts-legend {
                                            display: flex;
                                            overflow: auto;
                                            padding: 0 10px;
                                        }

                                        .apexcharts-legend.apexcharts-legend-group-horizontal {
                                            flex-direction: column;
                                        }

                                        .apexcharts-legend-group {
                                            display: flex;
                                        }

                                        .apexcharts-legend-group-vertical {
                                            flex-direction: column-reverse;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
                                            flex-wrap: wrap
                                        }

                                        .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                            flex-direction: column;
                                            bottom: 0;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                            justify-content: flex-start;
                                            align-items: flex-start;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                            justify-content: center;
                                            align-items: center;
                                        }

                                        .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                            justify-content: flex-end;
                                            align-items: flex-end;
                                        }

                                        .apexcharts-legend-series {
                                            cursor: pointer;
                                            line-height: normal;
                                            display: flex;
                                            align-items: center;
                                        }

                                        .apexcharts-legend-text {
                                            position: relative;
                                            font-size: 14px;
                                        }

                                        .apexcharts-legend-text *, .apexcharts-legend-marker * {
                                            pointer-events: none;
                                        }

                                        .apexcharts-legend-marker {
                                            position: relative;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            cursor: pointer;
                                            margin-right: 1px;
                                        }

                                        .apexcharts-legend-series.apexcharts-no-click {
                                            cursor: auto;
                                        }

                                        .apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
                                            display: none !important;
                                        }

                                        .apexcharts-inactive-legend {
                                            opacity: 0.45;
                                        }

                                    </style>
                                </foreignObject>
                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                <g class="apexcharts-yaxis" rel="0" transform="translate(21.01666259765625, 0)">
                                    <g class="apexcharts-yaxis-texts-g">
                                        <text x="20" y="33.666666666666664" text-anchor="end"
                                              dominant-baseline="auto" font-size="11px"
                                              font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                              fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                              style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>300</tspan>
                                            <title>300</title></text>
                                        <text x="20" y="84.13626666666667" text-anchor="end"
                                              dominant-baseline="auto" font-size="11px"
                                              font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                              fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                              style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>200</tspan>
                                            <title>200</title></text>
                                        <text x="20" y="134.60586666666666" text-anchor="end"
                                              dominant-baseline="auto" font-size="11px"
                                              font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                              fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                              style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>100</tspan>
                                            <title>100</title></text>
                                        <text x="20" y="185.07546666666667" text-anchor="end"
                                              dominant-baseline="auto" font-size="11px"
                                              font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                              fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                              style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>0</tspan>
                                            <title>0</title></text>
                                        <text x="20" y="235.54506666666668" text-anchor="end"
                                              dominant-baseline="auto" font-size="11px"
                                              font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                              fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                              style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>-100</tspan>
                                            <title>-100</title></text>
                                        <text x="20" y="286.0146666666667" text-anchor="end"
                                              dominant-baseline="auto" font-size="11px"
                                              font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                              fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                              style="font-family: Helvetica, Arial, sans-serif;">
                                            <tspan>-200</tspan>
                                            <title>-200</title></text>
                                    </g>
                                </g>
                                <g class="apexcharts-inner apexcharts-graphical"
                                   transform="translate(51.01666259765625, 30)">
                                    <defs>
                                        <linearGradient x1="0" y1="0" x2="0" y2="1"
                                                        id="SvgjsLinearGradient1002">
                                            <stop stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                                  offset="0"></stop>
                                            <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                  offset="1"></stop>
                                            <stop stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                                  offset="1"></stop>
                                        </linearGradient>
                                        <clipPath id="gridRectMaskm1yapawr">
                                            <rect width="804.6500043869019" height="252.348" x="0" y="0" rx="0"
                                                  ry="0" opacity="1" stroke-width="0" stroke="none"
                                                  stroke-dasharray="0" fill="#fff"></rect>
                                        </clipPath>
                                        <clipPath id="gridRectBarMaskm1yapawr">
                                            <rect width="808.6500043869019" height="256.348" x="-2" y="-2"
                                                  rx="0" ry="0" opacity="1" stroke-width="0" stroke="none"
                                                  stroke-dasharray="0" fill="#fff"></rect>
                                        </clipPath>
                                        <clipPath id="gridRectMarkerMaskm1yapawr">
                                            <rect width="804.6500043869019" height="252.348" x="0" y="0" rx="0"
                                                  ry="0" opacity="1" stroke-width="0" stroke="none"
                                                  stroke-dasharray="0" fill="#fff"></rect>
                                        </clipPath>
                                        <clipPath id="forecastMaskm1yapawr"></clipPath>
                                        <clipPath id="nonForecastMaskm1yapawr"></clipPath>
                                        <filter id="SvgjsFilter1004" filterUnits="userSpaceOnUse" width="200%"
                                                height="200%" x="-50%" y="-50%">
                                            <feColorMatrix id="SvgjsFeColorMatrix1003" result="brightness"
                                                           in="SourceGraphic" type="matrix" values="
          2 0 0 0 0
          0 2 0 0 0
          0 0 2 0 0
          0 0 0 1 0
        "></feColorMatrix>
                                        </filter>
                                    </defs>
                                    <rect width="17.88111120859782" height="252.348" x="661.6038853009542" y="0"
                                          rx="0" ry="0" opacity="1" stroke-width="0" stroke="#b6b6b6"
                                          stroke-dasharray="3" fill="url(#SvgjsLinearGradient1002)"
                                          class="apexcharts-xcrosshairs" y2="252.348" filter="none"
                                          fill-opacity="0.9" x1="661.6038853009542"
                                          x2="661.6038853009542"></rect>
                                    <line x1="0" y1="252.348" x2="0" y2="258.348" stroke="#e0e0e0"
                                          stroke-dasharray="0" stroke-linecap="butt"
                                          class="apexcharts-xaxis-tick"></line>
                                    <line x1="89.40555604298909" y1="252.348" x2="89.40555604298909"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="178.81111208597818" y1="252.348" x2="178.81111208597818"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="268.2166681289673" y1="252.348" x2="268.2166681289673"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="357.62222417195636" y1="252.348" x2="357.62222417195636"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="447.02778021494544" y1="252.348" x2="447.02778021494544"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="536.4333362579346" y1="252.348" x2="536.4333362579346"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="625.8388923009237" y1="252.348" x2="625.8388923009237"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="715.2444483439128" y1="252.348" x2="715.2444483439128"
                                          y2="258.348" stroke="#e0e0e0" stroke-dasharray="0"
                                          stroke-linecap="butt" class="apexcharts-xaxis-tick"></line>
                                    <line x1="804.650004386902" y1="252.348" x2="804.650004386902" y2="258.348"
                                          stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                          class="apexcharts-xaxis-tick"></line>
                                    <g class="apexcharts-grid">
                                        <g class="apexcharts-gridlines-horizontal">
                                            <line x1="0" y1="50.4696" x2="804.6500043869019" y2="50.4696"
                                                  stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                                  class="apexcharts-gridline"></line>
                                            <line x1="0" y1="100.9392" x2="804.6500043869019" y2="100.9392"
                                                  stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                                  class="apexcharts-gridline"></line>
                                            <line x1="0" y1="151.40879999999999" x2="804.6500043869019"
                                                  y2="151.40879999999999" stroke="#e0e0e0" stroke-dasharray="0"
                                                  stroke-linecap="butt" class="apexcharts-gridline"></line>
                                            <line x1="0" y1="201.8784" x2="804.6500043869019" y2="201.8784"
                                                  stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                                  class="apexcharts-gridline"></line>
                                        </g>
                                        <g class="apexcharts-gridlines-vertical"></g>
                                        <line x1="0" y1="252.348" x2="804.6500043869019" y2="252.348"
                                              stroke="transparent" stroke-dasharray="0"
                                              stroke-linecap="butt"></line>
                                        <line x1="0" y1="1" x2="0" y2="252.348" stroke="transparent"
                                              stroke-dasharray="0" stroke-linecap="butt"></line>
                                    </g>
                                    <g class="apexcharts-grid-borders">
                                        <line x1="0" y1="0" x2="804.6500043869019" y2="0" stroke="#e0e0e0"
                                              stroke-dasharray="0" stroke-linecap="butt"
                                              class="apexcharts-gridline"></line>
                                        <line x1="0" y1="252.348" x2="804.6500043869019" y2="252.348"
                                              stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                              class="apexcharts-gridline"></line>
                                        <line x1="0" y1="252.348" x2="804.6500043869019" y2="252.348"
                                              stroke="#e0e0e0" stroke-dasharray="0" stroke-width="1"
                                              stroke-linecap="butt"></line>
                                    </g>
                                    <g class="apexcharts-bar-series apexcharts-plot-series">
                                        <g class="apexcharts-series" seriesName="Sales" rel="1"
                                           data:realIndex="0">
                                            <path
                                                d="M 35.76222241719564 151.40980000000002 L 35.76222241719564 89.79932000000002 C 35.76222241719564 87.79932000000002 37.76222241719564 85.79932000000002 39.76222241719564 85.79932000000002 L 49.64333362579346 85.79932000000002 C 51.64333362579346 85.79932000000002 53.64333362579346 87.79932000000002 53.64333362579346 89.79932000000002 L 53.64333362579346 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 35.76222241719564 151.40980000000002 L 35.76222241719564 89.79932000000002 C 35.76222241719564 87.79932000000002 37.76222241719564 85.79932000000002 39.76222241719564 85.79932000000002 L 49.64333362579346 85.79932000000002 C 51.64333362579346 85.79932000000002 53.64333362579346 87.79932000000002 53.64333362579346 89.79932000000002 L 53.64333362579346 151.40980000000002 z "
                                                pathFrom="M 35.76222241719564 151.40980000000002 L 35.76222241719564 151.40980000000002 L 53.64333362579346 151.40980000000002 L 53.64333362579346 151.40980000000002 L 53.64333362579346 151.40980000000002 L 53.64333362579346 151.40980000000002 L 53.64333362579346 151.40980000000002 L 35.76222241719564 151.40980000000002 z"
                                                cy="85.79832000000002" cx="125.16777846018473" j="0" val="130"
                                                barHeight="65.61048" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 125.16777846018473 151.40980000000002 L 125.16777846018473 49.42364000000001 C 125.16777846018473 47.42364000000001 127.16777846018473 45.42364000000001 129.16777846018473 45.42364000000001 L 139.04888966878255 45.42364000000001 C 141.04888966878255 45.42364000000001 143.04888966878255 47.42364000000001 143.04888966878255 49.42364000000001 L 143.04888966878255 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 125.16777846018473 151.40980000000002 L 125.16777846018473 49.42364000000001 C 125.16777846018473 47.42364000000001 127.16777846018473 45.42364000000001 129.16777846018473 45.42364000000001 L 139.04888966878255 45.42364000000001 C 141.04888966878255 45.42364000000001 143.04888966878255 47.42364000000001 143.04888966878255 49.42364000000001 L 143.04888966878255 151.40980000000002 z "
                                                pathFrom="M 125.16777846018473 151.40980000000002 L 125.16777846018473 151.40980000000002 L 143.04888966878255 151.40980000000002 L 143.04888966878255 151.40980000000002 L 143.04888966878255 151.40980000000002 L 143.04888966878255 151.40980000000002 L 143.04888966878255 151.40980000000002 L 125.16777846018473 151.40980000000002 z"
                                                cy="45.422640000000015" cx="214.57333450317384" j="1" val="210"
                                                barHeight="105.98616" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 214.57333450317384 151.40980000000002 L 214.57333450317384 4.0009999999999994 C 214.57333450317384 2.0009999999999994 216.57333450317384 0.001 218.57333450317384 0.001 L 228.45444571177165 0.001 C 230.45444571177165 0.001 232.45444571177165 2.001 232.45444571177165 4.0009999999999994 L 232.45444571177165 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 214.57333450317384 151.40980000000002 L 214.57333450317384 4.0009999999999994 C 214.57333450317384 2.0009999999999994 216.57333450317384 0.001 218.57333450317384 0.001 L 228.45444571177165 0.001 C 230.45444571177165 0.001 232.45444571177165 2.001 232.45444571177165 4.0009999999999994 L 232.45444571177165 151.40980000000002 z "
                                                pathFrom="M 214.57333450317384 151.40980000000002 L 214.57333450317384 151.40980000000002 L 232.45444571177165 151.40980000000002 L 232.45444571177165 151.40980000000002 L 232.45444571177165 151.40980000000002 L 232.45444571177165 151.40980000000002 L 232.45444571177165 151.40980000000002 L 214.57333450317384 151.40980000000002 z"
                                                cy="0" cx="303.9788905461629" j="2" val="300"
                                                barHeight="151.4088" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 303.9788905461629 151.40980000000002 L 303.9788905461629 9.047960000000014 C 303.9788905461629 7.047960000000014 305.9788905461629 5.047960000000013 307.9788905461629 5.047960000000013 L 317.86000175476073 5.047960000000013 C 319.86000175476073 5.047960000000013 321.86000175476073 7.047960000000014 321.86000175476073 9.047960000000014 L 321.86000175476073 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 303.9788905461629 151.40980000000002 L 303.9788905461629 9.047960000000014 C 303.9788905461629 7.047960000000014 305.9788905461629 5.047960000000013 307.9788905461629 5.047960000000013 L 317.86000175476073 5.047960000000013 C 319.86000175476073 5.047960000000013 321.86000175476073 7.047960000000014 321.86000175476073 9.047960000000014 L 321.86000175476073 151.40980000000002 z "
                                                pathFrom="M 303.9788905461629 151.40980000000002 L 303.9788905461629 151.40980000000002 L 321.86000175476073 151.40980000000002 L 321.86000175476073 151.40980000000002 L 321.86000175476073 151.40980000000002 L 321.86000175476073 151.40980000000002 L 321.86000175476073 151.40980000000002 L 303.9788905461629 151.40980000000002 z"
                                                cy="5.046960000000013" cx="393.384446589152" j="3" val="290"
                                                barHeight="146.36184" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 393.384446589152 151.40980000000002 L 393.384446589152 79.70540000000001 C 393.384446589152 77.70540000000001 395.384446589152 75.70540000000001 397.384446589152 75.70540000000001 L 407.2655577977498 75.70540000000001 C 409.2655577977498 75.70540000000001 411.2655577977498 77.70540000000001 411.2655577977498 79.70540000000001 L 411.2655577977498 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 393.384446589152 151.40980000000002 L 393.384446589152 79.70540000000001 C 393.384446589152 77.70540000000001 395.384446589152 75.70540000000001 397.384446589152 75.70540000000001 L 407.2655577977498 75.70540000000001 C 409.2655577977498 75.70540000000001 411.2655577977498 77.70540000000001 411.2655577977498 79.70540000000001 L 411.2655577977498 151.40980000000002 z "
                                                pathFrom="M 393.384446589152 151.40980000000002 L 393.384446589152 151.40980000000002 L 411.2655577977498 151.40980000000002 L 411.2655577977498 151.40980000000002 L 411.2655577977498 151.40980000000002 L 411.2655577977498 151.40980000000002 L 411.2655577977498 151.40980000000002 L 393.384446589152 151.40980000000002 z"
                                                cy="75.7044" cx="482.79000263214107" j="4" val="150"
                                                barHeight="75.7044" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 482.79000263214107 151.40980000000002 L 482.79000263214107 130.175 C 482.79000263214107 128.175 484.79000263214107 126.17500000000001 486.79000263214107 126.17500000000001 L 496.6711138407389 126.17500000000001 C 498.6711138407389 126.17500000000001 500.6711138407389 128.175 500.6711138407389 130.175 L 500.6711138407389 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 482.79000263214107 151.40980000000002 L 482.79000263214107 130.175 C 482.79000263214107 128.175 484.79000263214107 126.17500000000001 486.79000263214107 126.17500000000001 L 496.6711138407389 126.17500000000001 C 498.6711138407389 126.17500000000001 500.6711138407389 128.175 500.6711138407389 130.175 L 500.6711138407389 151.40980000000002 z "
                                                pathFrom="M 482.79000263214107 151.40980000000002 L 482.79000263214107 151.40980000000002 L 500.6711138407389 151.40980000000002 L 500.6711138407389 151.40980000000002 L 500.6711138407389 151.40980000000002 L 500.6711138407389 151.40980000000002 L 500.6711138407389 151.40980000000002 L 482.79000263214107 151.40980000000002 z"
                                                cy="126.174" cx="572.1955586751302" j="5" val="50"
                                                barHeight="25.2348" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 572.1955586751302 151.40980000000002 L 572.1955586751302 49.42364000000001 C 572.1955586751302 47.42364000000001 574.1955586751302 45.42364000000001 576.1955586751302 45.42364000000001 L 586.0766698837281 45.42364000000001 C 588.0766698837281 45.42364000000001 590.0766698837281 47.42364000000001 590.0766698837281 49.42364000000001 L 590.0766698837281 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 572.1955586751302 151.40980000000002 L 572.1955586751302 49.42364000000001 C 572.1955586751302 47.42364000000001 574.1955586751302 45.42364000000001 576.1955586751302 45.42364000000001 L 586.0766698837281 45.42364000000001 C 588.0766698837281 45.42364000000001 590.0766698837281 47.42364000000001 590.0766698837281 49.42364000000001 L 590.0766698837281 151.40980000000002 z "
                                                pathFrom="M 572.1955586751302 151.40980000000002 L 572.1955586751302 151.40980000000002 L 590.0766698837281 151.40980000000002 L 590.0766698837281 151.40980000000002 L 590.0766698837281 151.40980000000002 L 590.0766698837281 151.40980000000002 L 590.0766698837281 151.40980000000002 L 572.1955586751302 151.40980000000002 z"
                                                cy="45.422640000000015" cx="661.6011147181193" j="6" val="210"
                                                barHeight="105.98616" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 661.6011147181193 151.40980000000002 L 661.6011147181193 14.094919999999997 C 661.6011147181193 12.094919999999997 663.6011147181193 10.094919999999997 665.6011147181193 10.094919999999997 L 675.4822259267172 10.094919999999997 C 677.4822259267172 10.094919999999997 679.4822259267172 12.094919999999997 679.4822259267172 14.094919999999997 L 679.4822259267172 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 661.6011147181193 151.40980000000002 L 661.6011147181193 14.094919999999997 C 661.6011147181193 12.094919999999997 663.6011147181193 10.094919999999997 665.6011147181193 10.094919999999997 L 675.4822259267172 10.094919999999997 C 677.4822259267172 10.094919999999997 679.4822259267172 12.094919999999997 679.4822259267172 14.094919999999997 L 679.4822259267172 151.40980000000002 z "
                                                pathFrom="M 661.6011147181193 151.40980000000002 L 661.6011147181193 151.40980000000002 L 679.4822259267172 151.40980000000002 L 679.4822259267172 151.40980000000002 L 679.4822259267172 151.40980000000002 L 679.4822259267172 151.40980000000002 L 679.4822259267172 151.40980000000002 L 661.6011147181193 151.40980000000002 z"
                                                cy="10.093919999999997" cx="751.0066707611085" j="7" val="280"
                                                barHeight="141.31488000000002"
                                                barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 751.0066707611085 151.40980000000002 L 751.0066707611085 102.41672000000003 C 751.0066707611085 100.41672000000003 753.0066707611085 98.41672000000003 755.0066707611085 98.41672000000003 L 764.8877819697063 98.41672000000003 C 766.8877819697063 98.41672000000003 768.8877819697063 100.41672000000003 768.8877819697063 102.41672000000003 L 768.8877819697063 151.40980000000002 z "
                                                fill="rgba(40,199,111,1)" fill-opacity="1" stroke="#28c76f"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="0"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 751.0066707611085 151.40980000000002 L 751.0066707611085 102.41672000000003 C 751.0066707611085 100.41672000000003 753.0066707611085 98.41672000000003 755.0066707611085 98.41672000000003 L 764.8877819697063 98.41672000000003 C 766.8877819697063 98.41672000000003 768.8877819697063 100.41672000000003 768.8877819697063 102.41672000000003 L 768.8877819697063 151.40980000000002 z "
                                                pathFrom="M 751.0066707611085 151.40980000000002 L 751.0066707611085 151.40980000000002 L 768.8877819697063 151.40980000000002 L 768.8877819697063 151.40980000000002 L 768.8877819697063 151.40980000000002 L 768.8877819697063 151.40980000000002 L 768.8877819697063 151.40980000000002 L 751.0066707611085 151.40980000000002 z"
                                                cy="98.41572000000002" cx="840.4122268040976" j="8" val="105"
                                                barHeight="52.99308" barWidth="17.88111120859782"></path>
                                            <g class="apexcharts-bar-goals-markers">
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                            </g>
                                        </g>
                                        <g class="apexcharts-series" seriesName="Purchase" rel="2"
                                           data:realIndex="1">
                                            <path
                                                d="M 35.76222241719564 151.41080000000002 L 35.76222241719564 223.11520000000004 C 35.76222241719564 225.11520000000004 37.76222241719564 227.11520000000004 39.76222241719564 227.11520000000004 L 49.64333362579346 227.11520000000004 C 51.64333362579346 227.11520000000004 53.64333362579346 225.11520000000004 53.64333362579346 223.11520000000004 L 53.64333362579346 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 35.76222241719564 151.41080000000002 L 35.76222241719564 223.11520000000004 C 35.76222241719564 225.11520000000004 37.76222241719564 227.11520000000004 39.76222241719564 227.11520000000004 L 49.64333362579346 227.11520000000004 C 51.64333362579346 227.11520000000004 53.64333362579346 225.11520000000004 53.64333362579346 223.11520000000004 L 53.64333362579346 151.41080000000002 z "
                                                pathFrom="M 35.76222241719564 151.41080000000002 L 35.76222241719564 151.41080000000002 L 53.64333362579346 151.41080000000002 L 53.64333362579346 151.41080000000002 L 53.64333362579346 151.41080000000002 L 53.64333362579346 151.41080000000002 L 53.64333362579346 151.41080000000002 L 35.76222241719564 151.41080000000002 z"
                                                cy="227.11420000000004" cx="125.16777846018473" j="0" val="-150"
                                                barHeight="-75.7044" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 125.16777846018473 151.41080000000002 L 125.16777846018473 192.83344000000002 C 125.16777846018473 194.83344000000002 127.16777846018473 196.83344000000002 129.16777846018473 196.83344000000002 L 139.04888966878255 196.83344000000002 C 141.04888966878255 196.83344000000002 143.04888966878255 194.83344000000002 143.04888966878255 192.83344000000002 L 143.04888966878255 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 125.16777846018473 151.41080000000002 L 125.16777846018473 192.83344000000002 C 125.16777846018473 194.83344000000002 127.16777846018473 196.83344000000002 129.16777846018473 196.83344000000002 L 139.04888966878255 196.83344000000002 C 141.04888966878255 196.83344000000002 143.04888966878255 194.83344000000002 143.04888966878255 192.83344000000002 L 143.04888966878255 151.41080000000002 z "
                                                pathFrom="M 125.16777846018473 151.41080000000002 L 125.16777846018473 151.41080000000002 L 143.04888966878255 151.41080000000002 L 143.04888966878255 151.41080000000002 L 143.04888966878255 151.41080000000002 L 143.04888966878255 151.41080000000002 L 143.04888966878255 151.41080000000002 L 125.16777846018473 151.41080000000002 z"
                                                cy="196.83244000000002" cx="214.57333450317384" j="1" val="-90"
                                                barHeight="-45.42264" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 214.57333450317384 151.41080000000002 L 214.57333450317384 172.64560000000003 C 214.57333450317384 174.64560000000003 216.57333450317384 176.64560000000003 218.57333450317384 176.64560000000003 L 228.45444571177165 176.64560000000003 C 230.45444571177165 176.64560000000003 232.45444571177165 174.64560000000003 232.45444571177165 172.64560000000003 L 232.45444571177165 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 214.57333450317384 151.41080000000002 L 214.57333450317384 172.64560000000003 C 214.57333450317384 174.64560000000003 216.57333450317384 176.64560000000003 218.57333450317384 176.64560000000003 L 228.45444571177165 176.64560000000003 C 230.45444571177165 176.64560000000003 232.45444571177165 174.64560000000003 232.45444571177165 172.64560000000003 L 232.45444571177165 151.41080000000002 z "
                                                pathFrom="M 214.57333450317384 151.41080000000002 L 214.57333450317384 151.41080000000002 L 232.45444571177165 151.41080000000002 L 232.45444571177165 151.41080000000002 L 232.45444571177165 151.41080000000002 L 232.45444571177165 151.41080000000002 L 232.45444571177165 151.41080000000002 L 214.57333450317384 151.41080000000002 z"
                                                cy="176.64460000000003" cx="303.9788905461629" j="2" val="-50"
                                                barHeight="-25.2348" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 303.9788905461629 151.41080000000002 L 303.9788905461629 238.25608000000003 C 303.9788905461629 240.25608000000003 305.9788905461629 242.25608000000003 307.9788905461629 242.25608000000003 L 317.86000175476073 242.25608000000003 C 319.86000175476073 242.25608000000003 321.86000175476073 240.25608000000003 321.86000175476073 238.25608000000003 L 321.86000175476073 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 303.9788905461629 151.41080000000002 L 303.9788905461629 238.25608000000003 C 303.9788905461629 240.25608000000003 305.9788905461629 242.25608000000003 307.9788905461629 242.25608000000003 L 317.86000175476073 242.25608000000003 C 319.86000175476073 242.25608000000003 321.86000175476073 240.25608000000003 321.86000175476073 238.25608000000003 L 321.86000175476073 151.41080000000002 z "
                                                pathFrom="M 303.9788905461629 151.41080000000002 L 303.9788905461629 151.41080000000002 L 321.86000175476073 151.41080000000002 L 321.86000175476073 151.41080000000002 L 321.86000175476073 151.41080000000002 L 321.86000175476073 151.41080000000002 L 321.86000175476073 151.41080000000002 L 303.9788905461629 151.41080000000002 z"
                                                cy="242.25508000000002" cx="393.384446589152" j="3" val="-180"
                                                barHeight="-90.84528" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 393.384446589152 151.41080000000002 L 393.384446589152 172.64560000000003 C 393.384446589152 174.64560000000003 395.384446589152 176.64560000000003 397.384446589152 176.64560000000003 L 407.2655577977498 176.64560000000003 C 409.2655577977498 176.64560000000003 411.2655577977498 174.64560000000003 411.2655577977498 172.64560000000003 L 411.2655577977498 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 393.384446589152 151.41080000000002 L 393.384446589152 172.64560000000003 C 393.384446589152 174.64560000000003 395.384446589152 176.64560000000003 397.384446589152 176.64560000000003 L 407.2655577977498 176.64560000000003 C 409.2655577977498 176.64560000000003 411.2655577977498 174.64560000000003 411.2655577977498 172.64560000000003 L 411.2655577977498 151.41080000000002 z "
                                                pathFrom="M 393.384446589152 151.41080000000002 L 393.384446589152 151.41080000000002 L 411.2655577977498 151.41080000000002 L 411.2655577977498 151.41080000000002 L 411.2655577977498 151.41080000000002 L 411.2655577977498 151.41080000000002 L 411.2655577977498 151.41080000000002 L 393.384446589152 151.41080000000002 z"
                                                cy="176.64460000000003" cx="482.79000263214107" j="4" val="-50"
                                                barHeight="-25.2348" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 482.79000263214107 151.41080000000002 L 482.79000263214107 182.73952000000003 C 482.79000263214107 184.73952000000003 484.79000263214107 186.73952000000003 486.79000263214107 186.73952000000003 L 496.6711138407389 186.73952000000003 C 498.6711138407389 186.73952000000003 500.6711138407389 184.73952000000003 500.6711138407389 182.73952000000003 L 500.6711138407389 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 482.79000263214107 151.41080000000002 L 482.79000263214107 182.73952000000003 C 482.79000263214107 184.73952000000003 484.79000263214107 186.73952000000003 486.79000263214107 186.73952000000003 L 496.6711138407389 186.73952000000003 C 498.6711138407389 186.73952000000003 500.6711138407389 184.73952000000003 500.6711138407389 182.73952000000003 L 500.6711138407389 151.41080000000002 z "
                                                pathFrom="M 482.79000263214107 151.41080000000002 L 482.79000263214107 151.41080000000002 L 500.6711138407389 151.41080000000002 L 500.6711138407389 151.41080000000002 L 500.6711138407389 151.41080000000002 L 500.6711138407389 151.41080000000002 L 500.6711138407389 151.41080000000002 L 482.79000263214107 151.41080000000002 z"
                                                cy="186.73852000000002" cx="572.1955586751302" j="5" val="-70"
                                                barHeight="-35.328720000000004"
                                                barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 572.1955586751302 151.41080000000002 L 572.1955586751302 197.88040000000004 C 572.1955586751302 199.88040000000004 574.1955586751302 201.88040000000004 576.1955586751302 201.88040000000004 L 586.0766698837281 201.88040000000004 C 588.0766698837281 201.88040000000004 590.0766698837281 199.88040000000004 590.0766698837281 197.88040000000004 L 590.0766698837281 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 572.1955586751302 151.41080000000002 L 572.1955586751302 197.88040000000004 C 572.1955586751302 199.88040000000004 574.1955586751302 201.88040000000004 576.1955586751302 201.88040000000004 L 586.0766698837281 201.88040000000004 C 588.0766698837281 201.88040000000004 590.0766698837281 199.88040000000004 590.0766698837281 197.88040000000004 L 590.0766698837281 151.41080000000002 z "
                                                pathFrom="M 572.1955586751302 151.41080000000002 L 572.1955586751302 151.41080000000002 L 590.0766698837281 151.41080000000002 L 590.0766698837281 151.41080000000002 L 590.0766698837281 151.41080000000002 L 590.0766698837281 151.41080000000002 L 590.0766698837281 151.41080000000002 L 572.1955586751302 151.41080000000002 z"
                                                cy="201.87940000000003" cx="661.6011147181193" j="6" val="-100"
                                                barHeight="-50.4696" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 661.6011147181193 151.41080000000002 L 661.6011147181193 192.83344000000002 C 661.6011147181193 194.83344000000002 663.6011147181193 196.83344000000002 665.6011147181193 196.83344000000002 L 675.4822259267172 196.83344000000002 C 677.4822259267172 196.83344000000002 679.4822259267172 194.83344000000002 679.4822259267172 192.83344000000002 L 679.4822259267172 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 661.6011147181193 151.41080000000002 L 661.6011147181193 192.83344000000002 C 661.6011147181193 194.83344000000002 663.6011147181193 196.83344000000002 665.6011147181193 196.83344000000002 L 675.4822259267172 196.83344000000002 C 677.4822259267172 196.83344000000002 679.4822259267172 194.83344000000002 679.4822259267172 192.83344000000002 L 679.4822259267172 151.41080000000002 z "
                                                pathFrom="M 661.6011147181193 151.41080000000002 L 661.6011147181193 151.41080000000002 L 679.4822259267172 151.41080000000002 L 679.4822259267172 151.41080000000002 L 679.4822259267172 151.41080000000002 L 679.4822259267172 151.41080000000002 L 679.4822259267172 151.41080000000002 L 661.6011147181193 151.41080000000002 z"
                                                cy="196.83244000000002" cx="751.0066707611085" j="7" val="-90"
                                                barHeight="-45.42264" barWidth="17.88111120859782"></path>
                                            <path
                                                d="M 751.0066707611085 151.41080000000002 L 751.0066707611085 200.40388000000002 C 751.0066707611085 202.40388000000002 753.0066707611085 204.40388000000002 755.0066707611085 204.40388000000002 L 764.8877819697063 204.40388000000002 C 766.8877819697063 204.40388000000002 768.8877819697063 202.40388000000002 768.8877819697063 200.40388000000002 L 768.8877819697063 151.41080000000002 z "
                                                fill="rgba(234,84,85,1)" fill-opacity="1" stroke="#ea5455"
                                                stroke-opacity="1" stroke-linecap="round" stroke-width="0"
                                                stroke-dasharray="0" class="apexcharts-bar-area " index="1"
                                                clip-path="url(#gridRectBarMaskm1yapawr)"
                                                pathTo="M 751.0066707611085 151.41080000000002 L 751.0066707611085 200.40388000000002 C 751.0066707611085 202.40388000000002 753.0066707611085 204.40388000000002 755.0066707611085 204.40388000000002 L 764.8877819697063 204.40388000000002 C 766.8877819697063 204.40388000000002 768.8877819697063 202.40388000000002 768.8877819697063 200.40388000000002 L 768.8877819697063 151.41080000000002 z "
                                                pathFrom="M 751.0066707611085 151.41080000000002 L 751.0066707611085 151.41080000000002 L 768.8877819697063 151.41080000000002 L 768.8877819697063 151.41080000000002 L 768.8877819697063 151.41080000000002 L 768.8877819697063 151.41080000000002 L 768.8877819697063 151.41080000000002 L 751.0066707611085 151.41080000000002 z"
                                                cy="204.40288" cx="840.4122268040976" j="8" val="-105"
                                                barHeight="-52.99308" barWidth="17.88111120859782"></path>
                                            <g class="apexcharts-bar-goals-markers">
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                                <g className="apexcharts-bar-goals-groups"
                                                   class="apexcharts-hidden-element-shown"
                                                   clip-path="url(#gridRectMarkerMaskm1yapawr)"></g>
                                            </g>
                                        </g>
                                        <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                        <g class="apexcharts-datalabels" data:realIndex="1"></g>
                                    </g>
                                    <line x1="0" y1="0" x2="804.6500043869019" y2="0" stroke="#b6b6b6"
                                          stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                          class="apexcharts-ycrosshairs"></line>
                                    <line x1="0" y1="0" x2="804.6500043869019" y2="0" stroke="#b6b6b6"
                                          stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                          class="apexcharts-ycrosshairs-hidden"></line>
                                    <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                        <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)">
                                            <text x="44.702778021494545" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan> Jan</tspan>
                                                <title> Jan </title></text>
                                            <text x="134.10833406448364" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Feb</tspan>
                                                <title>Feb</title></text>
                                            <text x="223.51389010747275" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Mar</tspan>
                                                <title>Mar</title></text>
                                            <text x="312.9194461504618" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Apr</tspan>
                                                <title>Apr</title></text>
                                            <text x="402.32500219345087" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>May</tspan>
                                                <title>May</title></text>
                                            <text x="491.73055823644" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Jun</tspan>
                                                <title>Jun</title></text>
                                            <text x="581.1361142794292" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Jul</tspan>
                                                <title>Jul</title></text>
                                            <text x="670.5416703224183" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Aug</tspan>
                                                <title>Aug</title></text>
                                            <text x="759.9472263654075" y="280.348" text-anchor="middle"
                                                  dominant-baseline="auto" font-size="12px"
                                                  font-family="Helvetica, Arial, sans-serif" font-weight="400"
                                                  fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                                  style="font-family: Helvetica, Arial, sans-serif;">
                                                <tspan>Sep</tspan>
                                                <title>Sep</title></text>
                                        </g>
                                    </g>
                                    <g class="apexcharts-yaxis-annotations"></g>
                                    <g class="apexcharts-xaxis-annotations"></g>
                                    <g class="apexcharts-point-annotations"></g>
                                </g>
                            </svg>
                            <div class="apexcharts-tooltip apexcharts-theme-light"
                                 style="left: 721.561px; top: 5px;">
                                <div class="apexcharts-tooltip-title"
                                     style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">Aug
                                </div>
                                <div
                                    class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0 apexcharts-active"
                                    style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker"
                                                                           style="background-color: rgb(40, 199, 111);"></span>
                                    <div class="apexcharts-tooltip-text"
                                         style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group"><span
                                                class="apexcharts-tooltip-text-y-label">Sales: </span><span
                                                class="apexcharts-tooltip-text-y-value">280</span></div>
                                        <div class="apexcharts-tooltip-goals-group"><span
                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                        <div class="apexcharts-tooltip-z-group"><span
                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                    </div>
                                </div>
                                <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                     style="order: 2; display: none;"><span class="apexcharts-tooltip-marker"
                                                                            style="background-color: rgb(40, 199, 111);"></span>
                                    <div class="apexcharts-tooltip-text"
                                         style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group"><span
                                                class="apexcharts-tooltip-text-y-label">Sales: </span><span
                                                class="apexcharts-tooltip-text-y-value">280</span></div>
                                        <div class="apexcharts-tooltip-goals-group"><span
                                                class="apexcharts-tooltip-text-goals-label"></span><span
                                                class="apexcharts-tooltip-text-goals-value"></span></div>
                                        <div class="apexcharts-tooltip-z-group"><span
                                                class="apexcharts-tooltip-text-z-label"></span><span
                                                class="apexcharts-tooltip-text-z-value"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                <div class="apexcharts-yaxistooltip-text"></div>
                            </div>
                            <div class="apexcharts-toolbar" style="top: 0px; right: 3px;">
                                <div class="apexcharts-menu-icon" title="Menu">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24">
                                        <path fill="none" d="M0 0h24v24H0V0z"></path>
                                        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
                                    </svg>
                                </div>
                                <div class="apexcharts-menu">
                                    <div class="apexcharts-menu-item exportSVG" title="Download SVG">Download
                                        SVG
                                    </div>
                                    <div class="apexcharts-menu-item exportPNG" title="Download PNG">Download
                                        PNG
                                    </div>
                                    <div class="apexcharts-menu-item exportCSV" title="Download CSV">Download
                                        CSV
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-sm-12 col-12 d-flex">
            <div class="card flex-fill default-cover mb-4">
                <div class="bg-danger card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 text-white">Low Stock Product</h4>
                    <div class="view-all-link">
                        <a href="javascript:void(0);" class="view-all d-flex align-items-center text-white">
                            View All<span class="ps-2 d-flex align-items-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-arrow-right feather-16">
                                    <line x1="5" y1="12" x2="19" y2="12"></line><polyline
                                        points="12 5 19 12 12 19"></polyline></svg></span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table dashboard-recent-products">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Products</th>
                                <th>Price</th>
                                <th>Current Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td >
                                    Lenevo 3rd Generation</a>
                                </td>
                                <td>$12500</td>
                                <td>10</td>
                            </tr><tr>
                                <td>1</td>
                                <td >
                                    Lenevo 3rd Generation</a>
                                </td>
                                <td>$12500</td>
                                <td>10</td>
                            </tr><tr>
                                <td>1</td>
                                <td >
                                    Lenevo 3rd Generation</a>
                                </td>
                                <td>$12500</td>
                                <td>10</td>
                            </tr><tr>
                                <td>1</td>
                                <td >
                                    Lenevo 3rd Generation</a>
                                </td>
                                <td>$12500</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>
                                    Lenevo 3rd Generation</a>
                                </td>
                                <td>$12500</td>
                                <td>10</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Return Order</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive dataview">
                <table class="table dashboard-expired-products">
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Customer Name</th>
                        <th>Seller Name</th>
                        <th>Delivery Date</th>
                        <th>Return Date</th>
                        <th>Price</th>
                        <th class="no-sort">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                           ORD/00001
                        </td>
                        <td>Suranga</td>
                        <td>Sudarshana</td>
                        <td>29 Mar 2023</td>
                        <td>29 Mar 2023</td>
                        <td>Rs.1500.00</td>
                        <td class="action-table-data">
                            <div class="edit-delete-action">
                                <a class="me-2 p-2" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-edit">
                                        <path
                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path
                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <a class=" confirm-text p-2" href="javascript:void(0);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-trash-2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr><tr>
                        <td>
                           ORD/00001
                        </td>
                        <td>Suranga</td>
                        <td>Sudarshana</td>
                        <td>29 Mar 2023</td>
                        <td>29 Mar 2023</td>
                        <td>Rs.1500.00</td>
                        <td class="action-table-data">
                            <div class="edit-delete-action">
                                <a class="me-2 p-2" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-edit">
                                        <path
                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path
                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <a class=" confirm-text p-2" href="javascript:void(0);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-trash-2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr><tr>
                        <td>
                           ORD/00001
                        </td>
                        <td>Suranga</td>
                        <td>Sudarshana</td>
                        <td>29 Mar 2023</td>
                        <td>29 Mar 2023</td>
                        <td>Rs.1500.00</td>
                        <td class="action-table-data">
                            <div class="edit-delete-action">
                                <a class="me-2 p-2" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-edit">
                                        <path
                                            d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path
                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <a class=" confirm-text p-2" href="javascript:void(0);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-trash-2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


