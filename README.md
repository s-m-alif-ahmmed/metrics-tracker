# 📊 MetricsTracker

A Laravel package to **track impressions, clicks, and CTR** (Click-Through Rate) for any model like pages, posts, banners, or other content. Inspired by platforms like Fiverr and Upwork.

---

## ✨ Features

- ✅ Track impressions (when content is viewed)
- ✅ Track clicks (when user interacts)
- ✅ Automatically generates CTR (Click-Through Rate)
- ✅ Supports polymorphic relationships
- ✅ Artisan command to clean old records

---

## 📦 Installation

```bash

composer require alifahmmed/metrics-tracker
```
Then publish the migration:

```bash
php artisan vendor:publish --tag=public
php artisan migrate
```

🔧 Setup

🎮 1. How to Use in Controller
You can easily integrate metrics tracking (impressions and clicks) directly in your controllers.

👁️ Store Impressions (e.g., in index() method)
To track impressions when showing a list of items:

```bash

use AlifAhmmed\MetricsTracker\Helpers\MetricsTracker;

$data = YourModel::all(); // Replace with your model
MetricsTracker::trackImpressionsForCollection($data);
```
This will:

Store an impression only once per user/IP per day

Automatically attach a metric_token to each model for tracking clicks

🖱️ Store Clicks (e.g., in show() method)
To track clicks when a user views or interacts with a single item:

```bash

use AlifAhmmed\MetricsTracker\Helpers\MetricsTracker;

$data = YourModel::findOrFail($id); // Replace with your model
MetricsTracker::trackClickAndGenerateToken($data);
```

This will:

Store a click only if an impression exists for today

Attach the metric_token to the model for reference

🔍 2. Filter Metrics by Days
You can filter impressions and clicks by a specific range of days using this snippet:
```bash

$request->validate([
    'days' => 'required|in:1,7,14,30,60,90,180,365',
]);

$days = $request->days;

$metrics = MetricsTracker::getMetricsByDays($days);
```
Accepted values for days:
1, 7, 14, 30, 60, 90, 180, 365

This will return all metrics grouped by:

trackable_type

type (impression or click)

date

📈 3. Calculate CTR (Click-Through Rate)
To calculate CTR for a specific model, ID, and day range, use:
```bash

use App\Models\YourModel;

$ctr = MetricsTracker::calculateCTR(YourModel::class, $trackableId, 30);
```
Replace YourModel::class with your actual model class.

Replace $trackableId with the model ID.

Replace 30 with any day value (1, 7, 14, 30, 60, 90, 180, 365).

If there are no impressions, the CTR will return 0.

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
