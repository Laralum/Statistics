@extends('laralum::layouts.master')
@section('icon', 'ion-stats-bars')
@section('title', __('laralum_statistics::general.statistics'))
@section('subtitle', __('laralum_statistics::general.statistics_desc'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_statistics::general.home')</a></li>
        <li><span>@lang('laralum_statistics::general.statistics')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid class="uk-child-width-1-1">
            <div>
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_statistics::general.visits_last_week')
                    </div>
                    <div class="uk-card-body">
                        {!! $charts['visits_last_week']->render() !!}
                    </div>
                </div>
                <br>
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_statistics::general.most_used_browsers')
                    </div>
                    <div class="uk-card-body">
                        {!! $charts['most_used_browsers']->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
