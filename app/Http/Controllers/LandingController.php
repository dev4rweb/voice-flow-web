<?php

namespace App\Http\Controllers;

use App\Services\LocaleResolver;
use App\Services\RecognitionLanguageCatalog;
use App\Services\SeoMetaBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

final class LandingController extends Controller
{
    public function redirectByBrowser(Request $request, LocaleResolver $resolver): RedirectResponse
    {
        return redirect()->route('landing', [
            'locale' => $resolver->resolve($request->header('Accept-Language')),
        ]);
    }

    public function show(string $locale, LocaleResolver $resolver, SeoMetaBuilder $seo, RecognitionLanguageCatalog $recognitionLanguages): View
    {
        abort_unless($resolver->isSupported($locale), 404);

        App::setLocale($locale);

        return view('landing', [
            'locale' => $locale,
            'locales' => config('voice_flow.supported_locales'),
            'product' => config('voice_flow'),
            'content' => __('landing'),
            'seo' => $seo->landing($locale),
            'recognitionLanguages' => $recognitionLanguages->names(),
            'recognitionLanguageCount' => $recognitionLanguages->count(),
        ]);
    }
}
