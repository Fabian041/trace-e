@extends('layouts.root.main')

@section('main')
<div class="row mt-2">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-stats">
                <div class="card-stats-title">Line - 
                    <a class="font-weight-600" data-toggle="dropdown" href="#" id="orders-month">Antenna</a>
                </div>
                <div class="card-stats-items">
                    <div class="card-stats-item">
                        <div class="card-stats-item-count">24</div>
                        <div class="card-stats-item-label">OK</div>
                    </div>
                    <div class="card-stats-item">
                        <div class="card-stats-item-count">12</div>
                        <div class="card-stats-item-label">NG</div>
                    </div>
                </div>
            </div>
            <div class="card-icon shadow-primary bg-primary">
                <i class="fa-solid fa-satellite-dish" style="color: #ffffff;"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Parts</h4>
                </div>
                <div class="card-body">
                    59
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-stats">
                <div class="card-stats-title">Line - 
                    <a class="font-weight-600" data-toggle="dropdown" href="#" id="orders-month">ECU</a>
                </div>
                <div class="card-stats-items">
                    <div class="card-stats-item">
                        <div class="card-stats-item-count">24</div>
                        <div class="card-stats-item-label">OK</div>
                    </div>
                    <div class="card-stats-item">
                        <div class="card-stats-item-count">12</div>
                        <div class="card-stats-item-label">NG</div>
                    </div>
                </div>
            </div>
            <div class="card-icon shadow-primary bg-primary">
                <i class="fa-solid fa-microchip" style="color: #ffffff;"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Parts</h4>
                </div>
                <div class="card-body">
                    59
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-chart">
                <canvas id="sales-chart" height="80"></canvas>
            </div>
            <div class="card-icon shadow-primary bg-primary">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Line</h4>
                </div>
                <div class="card-body">
                    4,732
                </div>
            </div>
        </div>
    </div>
</div>
@endsection