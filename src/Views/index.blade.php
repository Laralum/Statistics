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
        <div class="uk-section-small">
             <div class="uk-container uk-container-large">
                 <div uk-grid class="uk-child-width-1-1@s uk-child-width-1-2@m uk-child-width-1-4@xl">
                     <div>
                         <div class="uk-card uk-card-default uk-card-body">
                             <span class="statistics-text">@lang('laralum_statistics::general.views')</span><br />
                             <span class="statistics-number">
                                 {{ $views->count() }}
                             </span>
                         </div>
                     </div>
                     <div>
                         <div class="uk-card uk-card-default uk-card-body">
                             <span class="statistics-text">@lang('laralum_statistics::general.unique_visitors')</span><br />
                             <span class="statistics-number">
                                 {{ $views->unique('ip')->count() }}
                             </span>
                         </div>
                     </div>
                     <div>
                         <div class="uk-card uk-card-default uk-card-body">
                             <span class="statistics-text">@lang('laralum_statistics::general.most_used_browser')</span><br />
                             <span class="statistics-number">
                                 <?php
                                     $sortedViews = $views->unique('browser')->sortByDesc(function ($product, $key) {
                                         return $product->countSameBrowser();
                                     });
                                 ?>
                                 {{ $sortedViews->first()->browser }}
                             </span>
                         </div>
                     </div>
                     <div>
                         <div class="uk-card uk-card-default uk-card-body">
                             <span class="statistics-text">@lang('laralum_statistics::general.most_used_os')</span><br />
                             <span class="statistics-number">
                                 <?php
                                     $sortedViews = $views->unique('os')->sortByDesc(function ($product, $key) {
                                         return $product->countSameOs();
                                     });
                                 ?>
                                 {{ $sortedViews->first()->os }}
                             </span>
                         </div>
                     </div>
                 </div>
                 <div uk-grid class="uk-child-width-1-1@s uk-child-width-1-2@l">
                     <div>
                         <div class="uk-card uk-card-default">
                             <div class="uk-card-header">
                                 @lang('laralum_statistics::general.views_last_week')
                             </div>
                             <div class="uk-card-body">
                                 {!! $viewsLastWeek->render() !!}
                             </div>
                         </div>
                     </div>
                     <div>
                         <div class="uk-card uk-card-default">
                             <div class="uk-card-header">
                                 @lang('laralum_statistics::general.unique_visitors_last_week')
                             </div>
                             <div class="uk-card-body">
                                 {!! $uniqueVisitorsLastWeek->render() !!}
                             </div>
                         </div>
                     </div>
                     <div>
                         <div class="uk-card uk-card-default">
                             <div class="uk-card-header">
                                 @lang('laralum_statistics::general.most_used_browsers')
                             </div>
                             <div class="uk-card-body">
                                 {!! $mostUsedBrowsers->render() !!}
                             </div>
                         </div>
                     </div>
                     <div>
                         <div class="uk-card uk-card-default">
                             <div class="uk-card-header">
                                 @lang('laralum_statistics::general.most_used_os')
                             </div>
                             <div class="uk-card-body">
                                 {!! $mostUsedOs->render() !!}
                             </div>
                         </div>
                     </div>
                     <div>
                         <div class="uk-card uk-card-default">
                             <div class="uk-card-header">
                                 @lang('laralum_statistics::general.most_visited_pages')
                             </div>
                             <div class="uk-card-body">
                                 <div class="uk-overflow-auto">
                                    <table class="uk-table uk-table-small">
                                        <thead>
                                            <tr>
                                                <th>@lang('laralum_statistics::general.views')</th>
                                                <th>@lang('laralum_statistics::general.unique_visitors')</th>
                                                <th>@lang('laralum_statistics::general.url')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                $sortedViews = $views->unique('next_url')->sortByDesc(function ($product, $key) {
                                                    return $product->countSameUrl();
                                                });
                                            ?>

                                            @foreach( $sortedViews as $view )
                                                <tr>
                                                    <td>{{$view->countSameUrl()}}</td>
                                                    <td>{{$view->countSameUrl(true)}}</td>
                                                    <td><a href="{{ $view->next_url }}">{{ $view->next_url }}</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

    </div>
@endsection
