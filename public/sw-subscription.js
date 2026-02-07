navigator.serviceWorker.register("/sw.js");

async function subscribeUser() {
    const registration = await navigator.serviceWorker.ready;

    // Public VAPID key dari .env
    const vapidKey = "{{ config('webpush.vapid.public_key') }}";

    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: vapidKey,
    });

    await fetch("/api/save-subscription", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(subscription),
    });
}

subscribeUser();
