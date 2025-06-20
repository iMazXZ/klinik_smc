<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('patient', 'name')
                    ->label('Nama Pasien')
                    ->required(),
                Forms\Components\Select::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->label('Nama Dokter')
                    ->required(),
                Forms\Components\DateTimePicker::make('reservation_time')
                    ->label('Waktu Reservasi')
                    ->required(),
                Forms\Components\Textarea::make('complaint')
                    ->label('Keluhan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'canceled' => 'Canceled',
                        'completed' => 'Completed',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan nama pasien dari relasi
                TextColumn::make('patient.name')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),
                // Menampilkan nama dokter dari relasi
                TextColumn::make('doctor.name')
                    ->label('Nama Dokter')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reservation_time')
                    ->label('Waktu Reservasi')
                    ->dateTime('d M Y, H:i') // Format tanggal agar mudah dibaca
                    ->sortable(),
                BadgeColumn::make('status') // Menggunakan badge agar status lebih menarik
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'canceled',
                        'primary' => 'completed',
                    ])
                    ->sortable(),
            ])
            ->filters([
                // Filter berdasarkan status
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'canceled' => 'Canceled',
                        'completed' => 'Completed',
                    ])
            ])
            ->actions([
                ViewAction::make()
                ->label('Lihat & Chat') // Ubah label tombol
                ->url(fn (Reservation $record): string => static::getUrl('view', ['record' => $record])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
            'view' => Pages\ViewReservation::route('/{record}/view'), // Daftarkan halaman baru kita
        ];
    }
}
