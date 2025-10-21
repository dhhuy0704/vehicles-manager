<?php

namespace App\Filament\Pages;

use App\Models\Owner;
use App\Models\RefuelRecord;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class Dashboard extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public ?string $selectedOwnerId = null;
    public ?string $selectedVehicleId = null;
    public ?string $selectedTimeRange = null;
    
    public function mount()
    {
        // Retrieve values from session if they exist
        $this->selectedOwnerId = Session::get('dashboard_selected_owner_id', null);
        $this->selectedVehicleId = Session::get('dashboard_selected_vehicle_id', null);
        $this->selectedTimeRange = Session::get('dashboard_selected_time_range', '3_months');
        
        // Validate that the vehicle still belongs to the owner
        if ($this->selectedOwnerId && $this->selectedVehicleId) {
            $vehicleBelongsToOwner = Vehicle::where('id', $this->selectedVehicleId)
                ->where('owner_id', $this->selectedOwnerId)
                ->exists();
                
            if (!$vehicleBelongsToOwner) {
                $this->selectedVehicleId = null;
                Session::forget('dashboard_selected_vehicle_id');
            }
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('selectedOwnerId')
                            ->label('Select Owner')
                            ->options(
                                Owner::all()->pluck('full_name', 'id')
                            )
                            ->live()
                            ->default($this->selectedOwnerId)
                            ->afterStateUpdated(function ($state) {
                                $this->selectedOwnerId = $state;
                                Session::put('dashboard_selected_owner_id', $state);
                                
                                // Only reset vehicle if owner changed
                                if ($this->selectedVehicleId) {
                                    $vehicleBelongsToOwner = Vehicle::where('id', $this->selectedVehicleId)
                                        ->where('owner_id', $state)
                                        ->exists();
                                        
                                    if (!$vehicleBelongsToOwner) {
                                        $this->selectedVehicleId = null;
                                        Session::forget('dashboard_selected_vehicle_id');
                                    }
                                }
                            }),
                            
                        Select::make('selectedVehicleId')
                            ->label('Select Vehicle')
                            ->options(function (callable $get) {
                                $ownerId = $get('selectedOwnerId');
                                if (!$ownerId) {
                                    return [];
                                }
                                return Vehicle::where('owner_id', $ownerId)
                                    ->get()
                                    ->pluck('vehicle_label', 'id');
                            })
                            ->live()
                            ->default($this->selectedVehicleId)
                            ->afterStateUpdated(function ($state) {
                                $this->selectedVehicleId = $state;
                                Session::put('dashboard_selected_vehicle_id', $state);
                            })
                            ->disabled(fn (callable $get) => !$get('selectedOwnerId'))
                            ->placeholder(fn (callable $get) => $get('selectedOwnerId') 
                                ? 'Select a vehicle' 
                                : 'Please select an owner first'),
                                
                        Select::make('selectedTimeRange')
                            ->label('Time Range')
                            ->options([
                                '1_month'   => 'Last Month',
                                '3_months'  => 'Last 3 Months',
                                '6_months'  => 'Last 6 Months',
                                '12_months' => 'Last 12 Months',
                                'all'       => 'All Time'
                            ])
                            ->live()
                            ->default($this->selectedTimeRange)
                            ->afterStateUpdated(function ($state) {
                                $this->selectedTimeRange = $state;
                                Session::put('dashboard_selected_time_range', $state);
                            }),
                    ]),
            ]);
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->selectedVehicleId) {
                    return RefuelRecord::query()->whereNull('id'); // Empty query if no vehicle selected
                }
                
                $query = RefuelRecord::query()
                    ->where('vehicle_id', $this->selectedVehicleId)
                    ->with('gasStation'); // Eager load the gas station relation
                
                // Apply time range filter
                if ($this->selectedTimeRange && $this->selectedTimeRange !== 'all') {
                    $now = Carbon::now();
                    $months = (int) str_replace('_months', '', $this->selectedTimeRange);
                    $startDate = $now->copy()->subMonths($months)->startOfDay();
                    
                    $query->where('date', '>=', $startDate);
                }
                
                return $query->latest('date')->latest('time');
            })
            ->columns([
                TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('time')
                    ->label('Time')
                    ->formatStateUsing(fn (RefuelRecord $record) => $record->formatted_time)
                    ->sortable(),
                TextColumn::make('gasStation.name')
                    ->label('Gas Station')
                    ->searchable(),
                TextColumn::make('odometer')
                    ->label('Odometer')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_per_unit')
                    ->label('Unit Price')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: ','
                    )
                    ->prefix('$')
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ','
                    )
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Total Cost')
                    ->money('USD')
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->emptyStateHeading('No refuel records found')
            ->emptyStateDescription('Select a vehicle to view its refuel records.');
    }
}
