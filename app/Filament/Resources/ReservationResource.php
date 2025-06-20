<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action; // Gunakan Action biasa untuk kontrol lebih baik

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->relationship('patient', 'name')->label('Nama Pasien')->required(),
                Forms\Components\Select::make('doctor_id')->relationship('doctor', 'name')->label('Nama Dokter')->required(),
                Forms\Components\DateTimePicker::make('reservation_time')->label('Waktu Reservasi')->required(),
                Forms\Components\Textarea::make('complaint')->label('Keluhan')->required()->columnSpanFull(),
                Forms\Components\Select::make('status')->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'canceled' => 'Canceled', 'completed' => 'Completed'])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.name')->label('Nama Pasien')->searchable()->sortable(),
                TextColumn::make('doctor.name')->label('Nama Dokter')->searchable()->sortable(),
                TextColumn::make('reservation_time')->label('Waktu Reservasi')->dateTime('d M Y, H:i')->sortable(),
                BadgeColumn::make('status')->colors(['warning' => 'pending','success' => 'confirmed','danger' => 'canceled','primary' => 'completed'])->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(['pending' => 'Pending','confirmed' => 'Confirmed','canceled' => 'Canceled','completed' => 'Completed',])
            ])
            ->actions([
                Action::make('view_chat')
                    ->label('Lihat & Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn (Reservation $record): string => static::getUrl('view', ['record' => $record])),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
            'view' => Pages\ViewReservation::route('/{record}/view'),
        ];
    }
}
