<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('doctor_id')
                    ->relationship('doctor', 'name') // Ambil relasi dari model Schedule ke Doctor
                    ->required()
                    ->label('Dokter'),
                Select::make('day_of_week')
                    ->options([
                        '1' => 'Senin',
                        '2' => 'Selasa',
                        '3' => 'Rabu',
                        '4' => 'Kamis',
                        '5' => 'Jumat',
                        '6' => 'Sabtu',
                        '7' => 'Minggu',
                    ])
                    ->required()
                    ->label('Hari Praktek'),
                TimePicker::make('start_time')
                    ->required()
                    ->label('Jam Mulai'),
                TimePicker::make('end_time')
                    ->required()
                    ->label('Jam Selesai'),
                TextInput::make('duration_minutes')
                    ->required()
                    ->numeric()
                    ->default(30)
                    ->label('Durasi per Sesi (menit)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctor.name')->label('Dokter')->searchable()->sortable(),
                TextColumn::make('day_of_week')
                    ->label('Hari Praktek')
                    ->formatStateUsing(fn (string $state): string => [
                        '1' => 'Senin',
                        '2' => 'Selasa',
                        '3' => 'Rabu',
                        '4' => 'Kamis',
                        '5' => 'Jumat',
                        '6' => 'Sabtu',
                        '7' => 'Minggu',
                    ][$state] ?? 'Tidak Diketahui')
                    ->sortable(),
                TextColumn::make('start_time')->label('Mulai')->sortable(),
                TextColumn::make('end_time')->label('Selesai')->sortable(),
                TextColumn::make('duration_minutes')->label('Durasi (menit)')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
