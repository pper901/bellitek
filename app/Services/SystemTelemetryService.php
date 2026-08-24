<?php

namespace App\Services;

class SystemTelemetryService
{
    public function metrics(): array
    {
        return [
            'host' => [
                'cpu' => $this->cpuUsage(),
                'memory' => $this->memoryUsage(),
            ],

            'docker' => [
                'laravel' => $this->dockerContainerMetrics(
                    config('services.generalclass.laravel_container')
                ),

                'java' => $this->dockerContainerMetrics(
                    config('services.generalclass.java_container')
                ),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HOST CPU
    |--------------------------------------------------------------------------
    */

    private function cpuUsage(): ?float
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return $this->windowsCpuUsage();
        }

        return $this->linuxCpuUsage();
    }

    /*
    |--------------------------------------------------------------------------
    | HOST MEMORY
    |--------------------------------------------------------------------------
    */

    private function memoryUsage(): array
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return $this->windowsMemoryUsage();
        }

        return $this->linuxMemoryUsage();
    }

    /*
    |--------------------------------------------------------------------------
    | WINDOWS CPU
    |--------------------------------------------------------------------------
    */

    private function windowsCpuUsage(): ?float
    {
        $output = shell_exec(
            'powershell -NoProfile -Command "(Get-Counter \'\\Processor(_Total)\\% Processor Time\').CounterSamples.CookedValue"'
        );

        if (!$output) {
            return null;
        }

        return round((float) trim($output), 2);
    }

    /*
    |--------------------------------------------------------------------------
    | WINDOWS MEMORY
    |--------------------------------------------------------------------------
    */

    private function windowsMemoryUsage(): array
    {
        $output = shell_exec(
            'powershell -NoProfile -Command "Get-CimInstance Win32_OperatingSystem | Select-Object TotalVisibleMemorySize,FreePhysicalMemory | ConvertTo-Json"'
        );

        if (!$output) {
            return $this->emptyMemory();
        }

        $data = json_decode($output, true);

        $total = (float) ($data['TotalVisibleMemorySize'] ?? 0);
        $free = (float) ($data['FreePhysicalMemory'] ?? 0);

        if ($total <= 0) {
            return $this->emptyMemory();
        }

        $used = $total - $free;

        return [
            'used_percent' =>
                round(($used / $total) * 100, 2),

            'total_mb' =>
                round($total / 1024, 2),

            'used_mb' =>
                round($used / 1024, 2),

            'free_mb' =>
                round($free / 1024, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LINUX CPU
    |--------------------------------------------------------------------------
    |
    | Uses /proc/stat instead of sys_getloadavg().
    |
    | /proc/stat provides cumulative CPU time:
    |
    | user
    | nice
    | system
    | idle
    | iowait
    | irq
    | softirq
    | steal
    |
    | CPU utilization is calculated from the difference between
    | two samples.
    |
    */

    private function linuxCpuUsage(): ?float
    {
        $first = $this->readLinuxCpuStats();

        if (!$first) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Wait briefly before taking the second sample.
        |--------------------------------------------------------------------------
        |
        | CPU statistics in /proc/stat are cumulative counters.
        | We need two samples to determine how much CPU time was
        | actually used during the interval.
        |
        */

        usleep(100000); // 100ms

        $second = $this->readLinuxCpuStats();

        if (!$second) {
            return null;
        }

        $totalDelta =
            $second['total'] -
            $first['total'];

        $idleDelta =
            $second['idle'] -
            $first['idle'];

        if ($totalDelta <= 0) {
            return null;
        }

        $usage =
            (($totalDelta - $idleDelta) / $totalDelta) * 100;

        return round(
            max(0, min($usage, 100)),
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | READ /proc/stat
    |--------------------------------------------------------------------------
    */

    private function readLinuxCpuStats(): ?array
    {
        $contents = @file_get_contents('/proc/stat');

        if (!$contents) {
            return null;
        }

        foreach (explode("\n", $contents) as $line) {

            /*
            |--------------------------------------------------------------------------
            | We only want the aggregate "cpu" line.
            |
            | Example:
            |
            | cpu  12345 100 2345 56789 123 45 67 0 0 0
            |--------------------------------------------------------------------------
            */

            if (!preg_match(
                '/^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/',
                trim($line),
                $matches
            )) {
                continue;
            }

            $user =
                (int) $matches[1];

            $nice =
                (int) $matches[2];

            $system =
                (int) $matches[3];

            $idle =
                (int) $matches[4];

            $iowait =
                (int) $matches[5];

            $irq =
                (int) $matches[6];

            $softirq =
                (int) $matches[7];

            $steal =
                (int) $matches[8];

            /*
            |--------------------------------------------------------------------------
            | Total CPU time
            |--------------------------------------------------------------------------
            */

            $total =
                $user +
                $nice +
                $system +
                $idle +
                $iowait +
                $irq +
                $softirq +
                $steal;

            /*
            |--------------------------------------------------------------------------
            | Idle CPU time
            |--------------------------------------------------------------------------
            |
            | iowait is treated as idle because the CPU is not actively
            | executing application work while waiting for I/O.
            |
            */

            $idleTotal =
                $idle +
                $iowait;

            return [
                'total' => $total,
                'idle' => $idleTotal,
            ];
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | LINUX MEMORY
    |--------------------------------------------------------------------------
    */

    private function linuxMemoryUsage(): array
    {
        $memInfo = @file('/proc/meminfo');

        if (!$memInfo) {
            return $this->emptyMemory();
        }

        $memory = [];

        foreach ($memInfo as $line) {

            if (
                preg_match(
                    '/^(\w+):\s+(\d+)/',
                    $line,
                    $matches
                )
            ) {
                $memory[$matches[1]] =
                    (int) $matches[2];
            }
        }

        $total =
            $memory['MemTotal'] ?? 0;

        $available =
            $memory['MemAvailable'] ?? 0;

        if ($total <= 0) {
            return $this->emptyMemory();
        }

        $used =
            $total - $available;

        return [
            'used_percent' =>
                round(($used / $total) * 100, 2),

            'total_mb' =>
                round($total / 1024, 2),

            'used_mb' =>
                round($used / 1024, 2),

            'free_mb' =>
                round($available / 1024, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DOCKER CONTAINER METRICS
    |--------------------------------------------------------------------------
    */

    private function dockerContainerMetrics(
        ?string $container
    ): array {

        if (!$container) {
            return $this->emptyDockerMetrics(
                'Container not configured'
            );
        }

        $command =
            'docker stats '
            . escapeshellarg($container)
            . ' --no-stream '
            . '--format "{{json .}}"';

        $output = shell_exec($command);

        if (!$output) {
            return $this->emptyDockerMetrics(
                'Docker metrics unavailable'
            );
        }

        $data = json_decode(
            trim($output),
            true
        );

        if (!is_array($data)) {
            return $this->emptyDockerMetrics(
                'Invalid Docker response'
            );
        }

        return [
            'available' => true,

            'container' =>
                $data['Name'] ?? $container,

            'cpu_percent' =>
                $this->parsePercent(
                    $data['CPUPerc'] ?? null
                ),

            'memory' => [
                'usage_mb' =>
                    $this->parseMemory(
                        $data['MemUsage'] ?? null
                    ),

                'usage_percent' =>
                    $this->parsePercent(
                        $data['MemPerc'] ?? null
                    ),

                'limit_mb' =>
                    $this->parseMemoryLimit(
                        $data['MemUsage'] ?? null
                    ),
            ],

            'pids' =>
                isset($data['PIDs'])
                    ? (int) $data['PIDs']
                    : null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE CPU %
    |--------------------------------------------------------------------------
    */

    private function parsePercent(
        ?string $value
    ): ?float {

        if (!$value) {
            return null;
        }

        $value =
            str_replace('%', '', trim($value));

        return is_numeric($value)
            ? round((float) $value, 2)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE MEMORY
    |--------------------------------------------------------------------------
    */

    private function parseMemory(
        ?string $value
    ): ?float {

        if (!$value) {
            return null;
        }

        $parts =
            explode('/', $value);

        if (!isset($parts[0])) {
            return null;
        }

        return $this->memoryToMb(
            trim($parts[0])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE MEMORY LIMIT
    |--------------------------------------------------------------------------
    */

    private function parseMemoryLimit(
        ?string $value
    ): ?float {

        if (!$value) {
            return null;
        }

        $parts =
            explode('/', $value);

        if (!isset($parts[1])) {
            return null;
        }

        return $this->memoryToMb(
            trim($parts[1])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MEMORY UNIT CONVERSION
    |--------------------------------------------------------------------------
    */

    private function memoryToMb(
        string $value
    ): ?float {

        if (!preg_match(
            '/^([\d.]+)\s*(B|KiB|MiB|GiB|TiB)$/i',
            $value,
            $matches
        )) {
            return null;
        }

        $number =
            (float) $matches[1];

        $unit =
            strtoupper($matches[2]);

        return match ($unit) {

            'B' =>
                round($number / 1024 / 1024, 2),

            'KIB' =>
                round($number / 1024, 2),

            'MIB' =>
                round($number, 2),

            'GIB' =>
                round($number * 1024, 2),

            'TIB' =>
                round($number * 1024 * 1024, 2),

            default =>
                null,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY RESPONSES
    |--------------------------------------------------------------------------
    */

    private function emptyMemory(): array
    {
        return [
            'used_percent' => null,
            'total_mb' => null,
            'used_mb' => null,
            'free_mb' => null,
        ];
    }

    private function emptyDockerMetrics(
        string $reason
    ): array {

        return [
            'available' => false,
            'container' => null,
            'reason' => $reason,
            'cpu_percent' => null,

            'memory' => [
                'usage_mb' => null,
                'usage_percent' => null,
                'limit_mb' => null,
            ],

            'pids' => null,
        ];
    }
}