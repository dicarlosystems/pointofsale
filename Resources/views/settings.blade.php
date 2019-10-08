@extends('header')

@section('content')
@parent

@include('accounts.nav', ['selected' => 'PointOfSale'])

<div class="row">
    <div class="col-md-12">
        {!! Former::open('settings/PointOfSale') !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">PointOfSale Settings</h3>
            </div>
            <div class="panel-group">
                <div class="form-group"></div>

                <div class="form-group">
                    <label class="control-label col-lg-4 col-sm-4"></label>
                    <div class="col-lg-8 col-sm-8">
                        {!! Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')) !!}
                    </div>
                </div>
            </div>
        </div>

        {!! Former::close() !!}
    </div>
</div>
@stop