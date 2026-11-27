<?php
// app/Controllers/BookingController.php — контроллер броней.

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AreaRepository;
use App\Models\BookingRepository;

final class BookingController
{
    private BookingRepository $bookings;
    private AreaRepository $areas;

    public function __construct()
    {
        $this->bookings = new BookingRepository();
        $this->areas = new AreaRepository();
    }

    public function index(): void
    {
        $status = $_GET['status'] ?? '';
        $bookings = $this->bookings->all();

        if ($status !== '') {
            $bookings = array_values(array_filter($bookings, fn(array $b): bool => $b['status'] === $status));
        }

        view('bookings/index', [
            'title'    => 'Брони',
            'bookings' => $bookings,
            'status'   => $status,
        ]);
    }

    public function show(string $id): void
    {
        $booking = $this->bookings->find((int) $id);
        if ($booking === null) {
            notFound();
        }

        view('bookings/show', [
            'title'   => $booking['title'],
            'booking' => $booking,
            'area'    => $this->areas->find($booking['area_id']),
        ]);
    }

    public function create(): void
    {
        view('bookings/create', [
            'title'  => 'Новая бронь',
            'areas'  => $this->areas->all(),
            'errors' => [],
            'old'    => [],
        ]);
    }

    public function store(): void
    {
        $title = trim($_POST['title'] ?? '');
        $areaId = (int) ($_POST['area_id'] ?? 0);
        $priority = $_POST['priority'] ?? 'medium';
        $dueDate = $_POST['due_date'] ?? '';

        $errors = [];
        if (mb_strlen($title) < 3) {
            $errors['title'] = 'Название — не короче 3 символов';
        }
        if ($this->areas->find($areaId) === null) {
            $errors['area_id'] = 'Выберите площадку из списка';
        }
        if (!in_array($priority, ['low', 'medium', 'high'], true)) {
            $errors['priority'] = 'Недопустимый приоритет';
        }

        if ($errors) {
            http_response_code(422);
            view('bookings/create', [
                'title'  => 'Новая бронь',
                'areas'  => $this->areas->all(),
                'errors' => $errors,
                'old'    => $_POST,
            ]);
            return;
        }

        $this->bookings->add([
            'area_id'  => $areaId,
            'title'    => $title,
            'status'   => 'new',
            'priority' => $priority,
            'due_date' => $dueDate !== '' ? $dueDate : null,
        ]);

        redirect('/bookings');
    }
}