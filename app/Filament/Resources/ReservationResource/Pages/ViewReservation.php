<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation; // <-- Import model Reservation
use Filament\Resources\Pages\Page;

class ViewReservation extends Page
{
    protected static string $resource = ReservationResource::class;

    protected static string $view = 'filament.resources.reservation-resource.pages.view-reservation';

    // Properti untuk menyimpan data reservasi yang akan ditampilkan
    public Reservation $record;

    // Method `mount` akan mengambil data berdasarkan ID dari URL
    public function mount(int | string $record): void
    {
        $this->record = Reservation::findOrFail($record);
    }
}
