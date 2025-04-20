function trackMetricEvent(type, token) {
    if (!token) return Promise.resolve();

    fetch('/track-metric', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            type: type,
            metric_token: token,
            url: window.location.href
        })
    }).then(response => {
        if (!response.ok) {
            console.error('Error tracking metric:', type, token);
        }
    }).catch(error => {
        console.error('Error in network request:', error);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll('.track-item[data-track-token]');
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const token = entry.target.dataset.trackToken;
                trackMetricEvent('impression', token);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    items.forEach(item => observer.observe(item));
});

document.addEventListener("click", function (e) {
    let el = e.target.closest('.track-click[data-track-token]');
    if (el) {
        e.preventDefault();
        const token = el.dataset.trackToken;
        const url = el.getAttribute('href');

        // Track the click event
        trackMetricEvent('click', token).finally(() => {
            window.location.href = url;
        });
    }
});
