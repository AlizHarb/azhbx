<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Lightweight Profiler for AzHbx
 */
class Profiler
{
    private float $startTime;
    /**
     * @var array<int, array{label: string, time: float, memory: int}>
     */
    private array $events = [];
    private int $startMemory;

    /**
 * __construct method.
 *
 * @return mixed
 */
    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
    }

    /**
 * mark method.
 *
 * @return mixed
 */
    public function mark(string $label): void
    {
        $this->events[] = [
            'label' => $label,
            'time' => microtime(true) - $this->startTime,
            'memory' => memory_get_usage() - $this->startMemory,
        ];
    }

    /**
     * @return array{total_time: float, peak_memory: int, events: array<int, array{label: string, time: float, memory: int}>}
     */
    public function getStats(): array
    {
        return [
            'total_time' => (microtime(true) - $this->startTime) * 1000, // ms
            'peak_memory' => memory_get_peak_usage(),
            'events' => $this->events,
        ];
    }

    /**
 * renderToolbar method.
 *
 * @return mixed
 */
    public function renderToolbar(): string
    {
        $stats = $this->getStats();
        $time = number_format($stats['total_time'], 2);
        $mem = number_format($stats['peak_memory'] / 1024 / 1024, 2);

        return <<<HTML
        <div style="position:fixed;bottom:0;right:0;background:#1e293b;color:#e2e8f0;padding:8px 12px;font-family:monospace;font-size:12px;border-top-left-radius:8px;z-index:9999;box-shadow:0 -2px 10px rgba(0,0,0,0.2);">
            <span style="color:#818cf8;font-weight:bold;">AzHbx</span>
            <span style="margin:0 8px;color:#475569;">|</span>
            <span>{$time} ms</span>
            <span style="margin:0 8px;color:#475569;">|</span>
            <span>{$mem} MB</span>
        </div>
        HTML;
    }
}
