<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Catalog\Catalog;
use App\Catalog\CatalogRepository;
use App\Catalog\Category;

final readonly class CategoryFilterViewModel
{
    private Catalog $catalog;

    public function __construct(CatalogRepository $catalogs)
    {
        $this->catalog = $catalogs->catalog();
    }

    /** @return list<CategoryOption> */
    public function options(): array
    {
        $options = [new CategoryOption('tutti', __('ui.catalog.all'), $this->catalog->count(), isDefault: true)];
        foreach ($this->catalog->categoryLabels() as $slug => $label) {
            $options[] = new CategoryOption($slug, $label, $this->catalog->count(Category::from($slug)));
        }

        return $options;
    }
}
