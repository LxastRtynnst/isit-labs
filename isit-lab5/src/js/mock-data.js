function filterList(list, query) {
  return list.filter((item) => {
    for (const key of Object.keys(query)) {
      const q = query[key];
      if (q === '' || q === undefined) continue;
      const value = item[key];
      if (value === undefined) continue;
      if (String(value).toLowerCase().includes(String(q).toLowerCase())) {
        return true;
      }
    }
    return Object.keys(query).every(
      (key) => query[key] === '' || query[key] === undefined
    );
  });
}

const areas = [
  { id: 1, name: 'Футбольное поле №1', sport: 'Футбол', price: 2000 },
  { id: 2, name: 'Теннисный корт №2', sport: 'Теннис', price: 1200 },
  { id: 3, name: 'Баскетбольная площадка', sport: 'Баскетбол', price: 1500 },
];

const bookings = [
  {
    id: 1,
    title: 'Утренняя игра',
    area: 'Футбольное поле №1',
    status: 'confirmed',
    priority: 'high',
    dueDate: '2026-10-15',
  },
  {
    id: 2,
    title: 'Тренировка с тренером',
    area: 'Теннисный корт №2',
    status: 'in_progress',
    priority: 'medium',
    dueDate: '2026-10-16',
  },
  {
    id: 3,
    title: 'Матч выходного дня',
    area: 'Футбольное поле №1',
    status: 'new',
    priority: 'low',
    dueDate: '2026-10-18',
  },
  {
    id: 4,
    title: 'Игра в баскетбол 3x3',
    area: 'Баскетбольная площадка',
    status: 'confirmed',
    priority: 'high',
    dueDate: '2026-10-14',
  },
  {
    id: 5,
    title: 'Корпоративный турнир',
    area: 'Футбольное поле №1',
    status: 'in_progress',
    priority: 'high',
    dueDate: '2026-10-20',
  },
  {
    id: 6,
    title: 'Открытая тренировка',
    area: 'Баскетбольная площадка',
    status: 'new',
    priority: 'medium',
    dueDate: '2026-10-22',
  },
  {
    id: 7,
    title: 'Утренний забег',
    area: 'Теннисный корт №2',
    status: 'new',
    priority: 'medium',
    dueDate: '2026-10-25',
  },
];

window.ROUTES = {
  'GET /api/bookings': function (query) {
    return filterList(bookings, query);
  },
  'GET /api/areas': function () {
    return areas;
  },
};