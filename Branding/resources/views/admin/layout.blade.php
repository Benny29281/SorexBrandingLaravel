<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Sorex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="bar2.png" type="image/png">
</head>

<body class="bg-gray-100">
<div class="flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md h-screen p-5">
        <h1 class="text-xl font-bold mb-5">SOREX</h1>

        <nav class="space-y-3">
            <a class="block px-3 py-2 rounded hover:bg-gray-200" href="#">Dashboard</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-200" href="#">Orders</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-200" href="#">Customers</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-200" href="#">Reports</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-200" href="#">Settings</a>
        </nav>
    </aside>

    <!-- MAIN -->
    <div class="flex-1">

        <!-- TOPBAR -->
        <div class="w-full bg-white shadow-sm p-4 flex justify-between items-center">
            <input type="text" placeholder="Search..."
                class="border px-3 py-2 rounded w-1/3">
            <div class="flex items-center space-x-4">
                <span>Hi, Admin</span>
                <img src="https://i.pravatar.cc/40" class="rounded-full w-10"/>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="p-6 space-y-8">

            <!-- CARDS -->
            <div class="grid grid-cols-4 gap-6">
                <div class="p-5 bg-gradient-to-r from-pink-500 to-red-500 text-white rounded-lg shadow">
                    <p class="text-sm">Users</p>
                    <h2 class="text-3xl font-bold">10,368</h2>
                </div>
                <div class="p-5 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-lg shadow">
                    <p class="text-sm">Sales</p>
                    <h2 class="text-3xl font-bold">388,888</h2>
                </div>
                <div class="p-5 bg-gradient-to-r from-orange-400 to-yellow-500 text-white rounded-lg shadow">
                    <p class="text-sm">Orders</p>
                    <h2 class="text-3xl font-bold">1,089</h2>
                </div>
                <div class="p-5 bg-gradient-to-r from-teal-400 to-green-500 text-white rounded-lg shadow">
                    <p class="text-sm">Earnings</p>
                    <h2 class="text-3xl font-bold">$90,863</h2>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-2 bg-white p-5 rounded shadow">
                    <h3 class="font-semibold mb-2">Recent Reports</h3>
                    <canvas id="lineChart"></canvas>
                </div>

                <div class="bg-white p-5 rounded shadow">
                    <h3 class="font-semibold mb-2">Chart By Items</h3>
                    <canvas id="donutChart"></canvas>
                </div>
            </div>

            <!-- TABLE -->
            <div class="grid grid-cols-2 gap-6">

                <!-- Earnings -->
                <div class="bg-white p-5 shadow rounded">
                    <h3 class="font-semibold mb-3">Earnings By Items</h3>

                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="p-2 text-left">Item</th>
                                <th class="p-2 text-left">Price</th>
                                <th class="p-2 text-left">Quantity</th>
                                <th class="p-2 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="p-2">Shoes</td>
                                <td class="p-2">$120</td>
                                <td class="p-2">22</td>
                                <td class="p-2">$2640</td>
                            </tr>
                            <tr class="border-b">
                                <td class="p-2">Hoodie</td>
                                <td class="p-2">$45</td>
                                <td class="p-2">65</td>
                                <td class="p-2">$2925</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Top Countries -->
                <div class="bg-white p-5 shadow rounded">
                    <h3 class="font-semibold mb-3">Top Countries</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Indonesia</span><span class="font-bold">75%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>USA</span><span class="font-bold">50%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Japan</span><span class="font-bold">42%</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- CHARTJS SCRIPTS -->
<script>
const lineCtx = document.getElementById('lineChart');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun"],
        datasets: [{
            label: 'Sales',
            data: [30, 50, 40, 60, 80, 70],
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    }
});

const donutCtx = document.getElementById('donutChart');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
            data: [300, 120, 150],
        }]
    }
});
</script>

@include('components.keep-alive')
</body>
</html>
