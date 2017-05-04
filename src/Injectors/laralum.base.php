<?php

\Laralum\Statistics\Models\View::addView();
\Laralum\Statistics\Models\Record::addRecord();
if (!\Illuminate\Support\Facades\Session::has('laralum_statistics_record')) {
    \Illuminate\Support\Facades\Session::put([
        'laralum_statistics_record' => true,
    ]);
}
