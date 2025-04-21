<?php

namespace AlifAhmmed\MetricsTracker\Helpers;

use AlifAhmmed\MetricsTracker\Models\Metric;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class MetricsTracker
{
    public static function generateMetricToken($model)
    {
        if (!$model || !method_exists($model, 'getMorphClass')) return null;

        $type = $model->getMorphClass();
        $id = $model->id;

        return base64_encode("{$type}|{$id}");
    }

    public static function decodeMetricToken($token)
    {
        try {
            $decoded = base64_decode($token);
            [$type, $id] = explode('|', $decoded);

            if (class_exists($type) && is_subclass_of($type, \Illuminate\Database\Eloquent\Model::class)) {
                return [$type, $id];
            }
        } catch (\Throwable $e) {
            return [null, null];
        }

        return [null, null];
    }

    public static function track($type, $trackable = null, $url = null)
    {
        $ip = Request::ip();

        $impressionExists = Metric::where('trackable_type', get_class($trackable))
            ->where('trackable_id', $trackable->id)
            ->where('user_id', auth()->id() ?? null)
            ->where('device_ip', $ip)
            ->whereDate('created_at', now()->toDateString())
            ->where('type', 'impression')
            ->exists();

        $clickExists = Metric::where('trackable_type', get_class($trackable))
            ->where('trackable_id', $trackable->id)
            ->where('user_id', auth()->id() ?? null)
            ->where('device_ip', $ip)
            ->whereDate('created_at', now()->toDateString())
            ->where('type', 'click')
            ->exists();

        if ($type === 'impression' && !$impressionExists) {
            Metric::create([
                'trackable_id'   => $trackable?->id,
                'trackable_type' => get_class($trackable),
                'type'           => 'impression',
                'user_id'        => auth()->id(),
                'url'            => $url ?? Request::url(),
                'device_ip'      => $ip,
            ]);
            return;
        }

        if ($type === 'click' && $impressionExists && !$clickExists) {
            Metric::create([
                'trackable_id'   => $trackable?->id,
                'trackable_type' => get_class($trackable),
                'type'           => 'click',
                'user_id'        => auth()->id(),
                'url'            => $url ?? Request::url(),
                'device_ip'      => $ip,
            ]);
            return;
        }

        if ($type === 'click' && !$clickExists) {
            Metric::create([
                'trackable_id'   => $trackable?->id,
                'trackable_type' => get_class($trackable),
                'type'           => 'click',
                'user_id'        => auth()->id(),
                'url'            => $url ?? Request::url(),
                'device_ip'      => $ip,
            ]);
            return;
        }

        return;
    }

    public static function trackImpressionsForCollection($models, $url = null)
    {
        foreach ($models as $model) {
            self::track('impression', $model, $url);
            $model->metric_token = self::generateMetricToken($model);
        }
    }

    public static function trackClickAndGenerateToken($trackable, $url = null)
    {
        self::track('click', $trackable, $url);
        $trackable->metric_token = self::generateMetricToken($trackable);
    }

    public static function getMetricsByDays($days)
    {
        return Metric::query()
            ->whereDate('created_at', '>=', now()->subDays($days))
            ->get()
            ->groupBy([
                'trackable_type',
                'type',
                function ($item) {
                    return $item->created_at->format('Y-m-d');
                }
            ]);
    }

    public static function calculateCTR($trackableType = null, $trackableId = null, $days = null)
    {
        $query = Metric::query();

        if ($trackableType) {
            $query->where('trackable_type', $trackableType);
        }

        if ($trackableId) {
            $query->where('trackable_id', $trackableId);
        }

        if ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $impressions = (clone $query)->where('type', 'impression')->count();
        $clicks = (clone $query)->where('type', 'click')->count();

        if ($impressions === 0) {
            return 0;
        }

        return round(($clicks / $impressions) * 100, 2);
    }

}
