<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Interpreter\ScoreInterpretation;

final readonly class InterpreterViewModel
{
    private const float INITIAL_Z = 1.0;

    public ScoreInterpretation $score;

    public function __construct()
    {
        $this->score = new ScoreInterpretation(self::INITIAL_Z);
    }

    public function percentileLabel(): string
    {
        $percentile = $this->score->percentile();

        return ctype_digit($percentile) ? "{$percentile}°" : $percentile;
    }

    public function sentence(): string
    {
        return match ($this->score->percentile()) {
            '<1' => __('ui.interpreter.sentenceLow'),
            '>99' => __('ui.interpreter.sentenceHigh'),
            default => __('ui.interpreter.sentence', ['count' => $this->score->percentile()]),
        };
    }

    public function valueText(): string
    {
        return __('ui.interpreter.valueText', [
            'z' => $this->score->formattedZ(),
            'percentile' => $this->percentileLabel(),
            'band' => $this->score->band->label(),
        ]);
    }

    /** @return array<string, array{label: string, value: string}> */
    public function readouts(): array
    {
        return [
            't' => ['label' => __('ui.interpreter.readouts.t'), 'value' => (string) $this->score->tScore],
            'standard' => ['label' => __('ui.interpreter.readouts.standard'), 'value' => (string) $this->score->standardScore],
            'percentile' => ['label' => __('ui.interpreter.readouts.percentile'), 'value' => $this->percentileLabel()],
        ];
    }

    public function chart(): string
    {
        return (string) file_get_contents(resource_path('svg/interpreter.svg'));
    }
}
