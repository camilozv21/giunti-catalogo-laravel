<?php

declare(strict_types=1);

namespace App\Catalog;

enum Category: string
{
    case Clinica = 'clinica';
    case Scuola = 'scuola';
    case Lavoro = 'lavoro';
    case Formazione = 'formazione';
}
