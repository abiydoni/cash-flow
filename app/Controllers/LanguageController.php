<?php

namespace App\Controllers;

class LanguageController extends BaseController
{
    public function index($locale)
    {
        $supported = config('App')->supportedLocales;
        if (in_array($locale, $supported)) {
            session()->set('locale', $locale);
        }
        return redirect()->back();
    }
}
