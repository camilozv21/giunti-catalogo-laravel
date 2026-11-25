<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Catalog\CatalogFacts;
use App\Catalog\CatalogRepository;
use Illuminate\Contracts\View\View;

final readonly class LandingController
{
    public function __construct(private CatalogRepository $catalogs) {}

    public function __invoke(): View
    {
        $catalog = $this->catalogs->catalog();

        return view('pages.home', [
            'catalog' => $catalog,
            'facts' => CatalogFacts::of($catalog),
        ]);
    }
}
