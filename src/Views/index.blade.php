@extends('laralum::layouts.master')
@section('icon', 'ion-stats-bars')
@section('title', __('laralum_statistics::general.statistics'))
@section('subtitle', __('laralum_statistics::general.statistics_desc'))
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_statistics::general.home')</a></li>
        <li><span>@lang('laralum_statistics::general.statistics')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div class="uk-container uk-container-large">
            <div uk-grid>
                <div class="uk-width-1-1@m uk-width-1-2@l">
                    <div class="uk-card uk-card-default uk-card-body">
                        <span class="statistics-text">@lang('laralum_statistics::general.views')</span><br />
                        <span class="statistics-number" id="views">
                            <div uk-spinner></div>
                        </span>
                    </div>
                </div>
                <div class="uk-width-1-1@m uk-width-1-2@l">
                    <div class="uk-card uk-card-default uk-card-body">
                        <span class="statistics-text">@lang('laralum_statistics::general.unique_visitors')</span><br />
                        <span class="statistics-number" id="unique_views">
                            <div uk-spinner></div>
                        </span>
                    </div>
                </div>
                <div class="uk-width-1-1@m uk-width-1-3@l">
                    <div class="uk-card uk-card-default uk-card-body">
                        <span class="statistics-text">@lang('laralum_statistics::general.most_used_browser')</span><br />
                        <span class="statistics-number" id="most_used_browser">
                            <div uk-spinner></div>
                        </span>
                    </div>
                </div>
                <div class="uk-width-1-1@m uk-width-1-3@l">
                    <div class="uk-card uk-card-default uk-card-body">
                        <span class="statistics-text">@lang('laralum_statistics::general.most_used_os')</span><br />
                        <span class="statistics-number" id="most_used_os">
                            <div uk-spinner></div>
                        </span>
                    </div>
                </div>
                <div class="uk-width-1-1@m uk-width-1-3@l">
                    <div class="uk-card uk-card-default uk-card-body">
                        <span class="statistics-text">@lang('laralum_statistics::general.most_used_language')</span><br />
                        <span class="statistics-number" id="most_used_language">
                            <div uk-spinner></div>
                        </span>
                    </div>
                </div>
                <div class="uk-width-1-1@m uk-width-1-2@l">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header">
                            @lang('laralum_statistics::general.most_used_browsers')
                        </div>
                        <div class="uk-card-body">
                            {!! $browsers_chart->render() !!}
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1@m uk-width-1-2@l">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header">
                            @lang('laralum_statistics::general.most_used_os')
                        </div>
                        <div class="uk-card-body">
                            {!! $oss_chart->render() !!}
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header">
                            @lang('laralum_statistics::general.views_last_week')
                        </div>
                        <div class="uk-card-body">
                            {!! $latest_views_chart->render() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-margin-large uk-text-center">
                <a href="{{ route('laralum::statistics.restart.confirm') }}" class="uk-button uk-button-danger uk-width-1-2@s uk-width-1-3@m uk-width-1-4@l">@lang('laralum_statistics::general.restart')</a>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("{{ route('laralum_api::statistics.views') }}", function(data) {
            $('#views').animate({opacity:0}, function() {
                $('#views').text(data['data']).animate({opacity:1});
            });
        });
        $.post("{{ route('laralum_api::statistics.uniqueViews') }}", function(data) {
            $('#unique_views').animate({opacity:0}, function() {
                $('#unique_views').text(data['data']).animate({opacity:1});
            });
        });
        $.post("{{ route('laralum_api::statistics.mostUsedBrowser') }}", function(data) {
            $('#most_used_browser').animate({opacity:0}, function() {
                $('#most_used_browser').text(data['data']).animate({opacity:1});
            });
        });
        $.post("{{ route('laralum_api::statistics.mostUsedOs') }}", function(data) {
            $('#most_used_os').animate({opacity:0}, function() {
                $('#most_used_os').text(data['data']).animate({opacity:1});
            });
        });
        $.post("{{ route('laralum_api::statistics.mostUsedLanguage') }}", function(data) {
            $('#most_used_language').animate({opacity:0}, function() {
                $('#most_used_language').text(data['data']).animate({opacity:1});
            });
        });
        
    });
</script>
@endsection
