# 📊 MetricsTracker

A Laravel package to **track impressions, clicks, and CTR** (Click-Through Rate) for any model like pages, posts, banners, or other content. Inspired by platforms like Fiverr and Upwork.

---

## ✨ Features

- ✅ Track impressions (when content is viewed)
- ✅ Track clicks (when user interacts)
- ✅ Automatically generates CTR (Click-Through Rate)
- ✅ Supports polymorphic relationships
- ✅ Includes JavaScript snippet for easy frontend integration
- ✅ Artisan command to clean old records

---

## 📦 Installation

```bash
composer require alifahmmed/metrics-tracker
```
Then publish the migration and public JS assets:

```bash
php artisan vendor:publish --tag=public
php artisan migrate
```
ℹ️ The JavaScript tracking file will be published to public/vendor/alifahmmed/metrics-tracker.js.

🔧 Setup
1. Add Trait to Your Models
   Add the HasMetrics trait to any model you want to track:

```bash
use AlifAhmmed\MetricsTracker\Traits\HasMetrics;

class Post extends Model
{
    use HasMetrics;
}

```

2. Expose Token in Blade or API
   Use the provided helper to generate a metric token and attach it to your rendered elements:

```bash
@php
    $metricToken = AlifAhmmed\MetricsTracker\Helpers\MetricsTracker::generateMetricToken($post);
@endphp

<div class="track-item" data-track-token="{{ $metricToken }}" data-id="{{ $data->id }}">
    {{ $post->title }}
</div>

<a href="{{ route('posts.show', $post) }}" class="track-click" data-track-token="{{ $metricToken }}">
    Read More
</a>

```

3. Include the JS Script
   Add the script to your Blade layout (usually in <head> or at the end of <body>):

```bash
<script src="{{ asset('vendor/alifahmmed/metrics-tracker.js') }}"></script>

```

📊 How It Works
👁️ Impression is tracked when an element becomes visible in the viewport.

🖱️ Click is tracked when an element is clicked.

📈 CTR (Click-Through Rate) is calculated based on total clicks / total impressions * 100.

🛠 Artisan Commands
Clean up metrics older than 90 days (adjustable):

```bash
php artisan metrics:clean
```

🧪 Example Usage (Controller or API)
If you're building a SPA or headless app, you can also generate tokens server-side:

```bash
use AlifAhmmed\MetricsTracker\Helpers\MetricsTracker;

$token = MetricsTracker::generateMetricToken($post);
```
🧑‍💻 Author
S M Alif Ahmmed

⚖ License
MIT License. Use it freely in personal and commercial projects.

⭐ Like it?
Give it a ⭐ on GitHub and share it with your Laravel friends!
