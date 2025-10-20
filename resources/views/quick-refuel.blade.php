<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quick Refuel - Vehicles Manager</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Vehicle Selector -->
    <div class="fixed top-0 left-0 right-0 bg-white shadow-md p-4 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <label for="global_vehicle_id" class="block text-sm font-medium text-gray-700 mr-4">Selected Vehicle:</label>
                <select id="global_vehicle_id" class="flex-1 max-w-xs py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Select a vehicle</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ ucfirst($vehicle->manufacturer) }} {{ ucfirst($vehicle->model) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="min-h-screen pt-20 flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Quick Refuel Record
                </h2>
                @if (session('success'))
                    <div class="mt-2 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <form id="refuelForm" class="mt-8 flex flex-col h-full" action="{{ route('quick-refuel.store') }}" method="POST">
                @csrf
                <input type="hidden" id="vehicle_id" name="vehicle_id" required>
                
                <div class="flex-grow space-y-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="date" name="date" required 
                            value="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="gas_station_id" class="block text-sm font-medium text-gray-700">Gas Station</label>
                        <select id="gas_station_id" name="gas_station_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Select a gas station</option>
                            @foreach ($gasStations as $station)
                                <option value="{{ $station->id }}" {{ $defaultValues['gas_station_id'] == $station->id ? 'selected' : '' }}>
                                    {{ $station->name }} - {{ $station->location }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="odometer" class="block text-sm font-medium text-gray-700">Odometer Reading</label>
                        <input type="number" id="odometer" name="odometer" required 
                            value="{{ $defaultValues['odometer'] }}"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="Current odometer reading">
                    </div>

                    <div>
                        <label for="price_per_unit" class="block text-sm font-medium text-gray-700">Price/Unit</label>
                        <input type="number" id="price_per_unit" name="price_per_unit" required 
                            value="{{ $defaultValues['price_per_unit'] }}"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="0.00">
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (liters)</label>
                        <input type="number" step="0.01" id="amount" name="amount" required 
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="0.00">
                    </div>

                    <div>
                        <label for="total_cost" class="block text-sm font-medium text-gray-700">Total Cost</label>
                        <input type="number" step="0.01" id="total_cost" name="total_cost"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                            placeholder="0.00">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400 transition-colors duration-200">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Cookie management functions
        function setCookie(name, value, days) {
            const d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = "expires=" + d.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }

        function getCookie(name) {
            const cookieName = name + "=";
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i].trim();
                if (cookie.indexOf(cookieName) === 0) {
                    return cookie.substring(cookieName.length, cookie.length);
                }
            }
            return "";
        }

        // Format number to 2 decimal places
        function formatNumber(num) {
            return parseFloat(num).toFixed(2);
        }

        // Calculate functions
        function calculateTotal(amount, pricePerUnit) {
            return amount * pricePerUnit;
        }

        function calculateAmount(total, pricePerUnit) {
            return pricePerUnit ? total / pricePerUnit : 0;
        }

        function calculatePricePerUnit(total, amount) {
            return amount ? total / amount : 0;
        }

        // Update form values
        function updateFormValues(changedField) {
            const amountInput = document.getElementById('amount');
            const pricePerUnitInput = document.getElementById('price_per_unit');
            const totalCostInput = document.getElementById('total_cost');

            const amount = parseFloat(amountInput.value) || 0;
            const pricePerUnit = parseFloat(pricePerUnitInput.value);
            const totalCost = parseFloat(totalCostInput.value) || 0;

            switch(changedField) {
                case 'amount':
                    if (amount >= 0 && !isNaN(pricePerUnit)) {
                        totalCostInput.value = formatNumber(calculateTotal(amount, pricePerUnit));
                    }
                    break;
                    
                case 'price_per_unit':
                    if (!isNaN(pricePerUnit) && amount > 0) {
                        totalCostInput.value = formatNumber(calculateTotal(amount, pricePerUnit));
                    }
                    break;
                    
                case 'total_cost':
                    if (totalCost >= 0) {
                        if (!isNaN(pricePerUnit)) {
                            amountInput.value = formatNumber(calculateAmount(totalCost, pricePerUnit));
                        } 
                    }
                    break;
            }
        }

        // Initialize vehicle selector and form
        document.addEventListener('DOMContentLoaded', function() {
            const globalVehicleSelect = document.getElementById('global_vehicle_id');
            const vehicleIdInput = document.getElementById('vehicle_id');
            const savedVehicleId = getCookie('selected_vehicle_id');

            // Set initial values from cookie
            if (savedVehicleId) {
                globalVehicleSelect.value = savedVehicleId;
                vehicleIdInput.value = savedVehicleId;
            }

            // Handle vehicle selection changes
            globalVehicleSelect.addEventListener('change', function() {
                const selectedVehicleId = this.value;
                vehicleIdInput.value = selectedVehicleId;
                setCookie('selected_vehicle_id', selectedVehicleId, 365); // Store for 1 year

                // Fetch last record for selected vehicle
                if (selectedVehicleId) {
                    fetch(`/api/last-refuel-record?vehicle_id=${selectedVehicleId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Update form fields with last record data
                            if (data.gas_station_id) {
                                document.getElementById('gas_station_id').value = data.gas_station_id;
                            }
                            if (data.price_per_unit) {
                                document.getElementById('price_per_unit').value = formatNumber(data.price_per_unit);
                                updateFormValues('price_per_unit');
                            }
                            if (data.odometer) {
                                document.getElementById('odometer').value = data.odometer;
                            }
                        })
                        .catch(error => console.error('Error fetching last record:', error));
                }
            });

            // Ensure form has vehicle ID before submission
            document.getElementById('refuelForm').addEventListener('submit', function(e) {
                if (!vehicleIdInput.value) {
                    e.preventDefault();
                    alert('Please select a vehicle first.');
                }
            });

            // Add input event listeners for real-time calculations
            ['amount', 'price_per_unit', 'total_cost'].forEach(fieldId => {
                document.getElementById(fieldId).addEventListener('input', function() {
                    updateFormValues(fieldId);
                });
            });

            // Initial calculation if default values exist
            const defaultPricePerUnit = document.getElementById('price_per_unit').value;
            const defaultAmount = document.getElementById('amount').value;
            if (defaultPricePerUnit && defaultAmount) {
                updateFormValues('amount');
            }
        });
    </script>
</body>
</html>