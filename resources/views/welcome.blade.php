<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway Kuliner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
</head>
<body>
    <script>
        window.__WELCOME_ROUTES__ = {
            cart: @json(route('customer.cart')),
            login: @json(route('login')),
            customerDashboard: @json(route('customer.dashboard')),
        };
    </script>
    <div id="welcome-dashboard-root"></div>
</body>
</html>
