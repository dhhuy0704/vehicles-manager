<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
    </form>

    @if($selectedVehicleId)
        <div class="mt-4">
            <h2 class="text-xl font-bold mb-4">
                Refuel Records
                @if($selectedTimeRange == '1_month')
                    - Last Month
                @elseif($selectedTimeRange == '3_months')
                    - Last 3 Months
                @elseif($selectedTimeRange == '6_months')
                    - Last 6 Months
                @elseif($selectedTimeRange == '12_months')
                    - Last 12 Months
                @else
                    - All Time
                @endif
            </h2>
            {{ $this->table }}
        </div>
    @else
        <div class="mt-4 p-4 bg-gray-50 rounded-lg text-gray-500">
            <p>Please select an owner and vehicle to view refuel records.</p>
        </div>
    @endif
</x-filament-panels::page>