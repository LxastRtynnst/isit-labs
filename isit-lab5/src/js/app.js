const loginScreen = document.getElementById('login-screen');
const mainScreen = document.getElementById('main-screen');
const loginForm = document.getElementById('login-form');
const loginEmail = document.getElementById('login-email');
const loginPassword = document.getElementById('login-password');
const loginButton = document.getElementById('login-button');
const errorEmail = document.getElementById('error-email');
const errorPassword = document.getElementById('error-password');
const errorLogin = document.getElementById('error-login');
const userName = document.getElementById('user-name');
const logoutButton = document.getElementById('logout-button');

const bookingForm = document.getElementById('booking-form');
const bookingTitle = document.getElementById('booking-title');
const bookingArea = document.getElementById('booking-area');
const bookingDate = document.getElementById('booking-date');
const errorTitle = document.getElementById('error-title');
const errorDate = document.getElementById('error-date');
const errorForm = document.getElementById('error-form');
const bookingBody = document.getElementById('booking-body');
const statusFilter = document.getElementById('status-filter');
const searchInput = document.getElementById('search-input');
const refreshButton = document.getElementById('refresh-button');
const message = document.getElementById('message');
const bookingTable = document.getElementById('booking-table');
const counter = document.getElementById('counter');

const STATUS_NAMES = {
  new: 'Новая',
  confirmed: 'Подтверждена',
  in_progress: 'В работе',
  cancelled: 'Отменена',
};

const PRIORITY_NAMES = {
  low: 'Низкий',
  medium: 'Средний',
  high: 'Высокий',
};

//  Вспомогательные функции 

function showInfo(text, isError = false) {
  message.textContent = text;
  message.classList.toggle('error-message', isError);
  message.hidden = !text;
}

function updateCounter() {
  const rows = bookingBody.querySelectorAll('tr');
  counter.textContent = rows.length;
  bookingTable.hidden = rows.length === 0;
}

function addCell(row, text) {
  const cell = document.createElement('td');
  cell.textContent = text;
  row.appendChild(cell);
  return cell;
}

function createBookingRow(booking) {
  const row = document.createElement('tr');
  row.setAttribute('data-id', booking.id);

  if (booking.status === 'confirmed') {
    row.classList.add('done');
  }

  addCell(row, booking.id ?? '—');
  addCell(row, booking.title);
  addCell(row, STATUS_NAMES[booking.status] || booking.status);
  addCell(row, PRIORITY_NAMES[booking.priority] || booking.priority);
  addCell(row, booking.dueDate || '');

  const actionsCell = document.createElement('td');

  const doneButton = document.createElement('button');
  doneButton.textContent =
    booking.status === 'confirmed' ? 'Отменить' : 'Подтвердить';
  doneButton.setAttribute(
    'title',
    booking.status === 'confirmed' ? 'Вернуть в работу' : 'Отметить подтверждённой'
  );
  doneButton.addEventListener('click', onDoneClick);
  actionsCell.appendChild(doneButton);

  const deleteButton = document.createElement('button');
  deleteButton.textContent = 'Удалить';
  deleteButton.classList.add('danger');
  deleteButton.addEventListener('click', onDeleteClick);
  actionsCell.appendChild(deleteButton);

  row.appendChild(actionsCell);

  return row;
}


function onFormSubmit(event) {
  event.preventDefault();
  errorTitle.textContent = '';
  errorDate.textContent = '';
  errorForm.textContent = '';

  const title = bookingTitle.value.trim();
  if (title.length < 3) {
    errorTitle.textContent = 'Название должно содержать минимум 3 символа';
    return;
  }

  const newBooking = {
    id: null,
    title: title,
    area: bookingArea.options[bookingArea.selectedIndex].text,
    status: 'new',
    priority: 'medium',
    dueDate: bookingDate.value || '—',
  };

  const row = createBookingRow(newBooking);
  bookingBody.insertBefore(row, bookingBody.firstChild);

  bookingForm.reset();
  bookingArea.selectedIndex = 0;
  updateCounter();
}

function onDoneClick(event) {
  const row = event.target.closest('tr');
  const isDone = row.classList.toggle('done');
  const statusCell = row.children[2];
  const button = event.target;

  if (isDone) {
    statusCell.textContent = STATUS_NAMES.confirmed;
    button.textContent = 'Отменить';
    button.setAttribute('title', 'Вернуть в работу');
  } else {
    statusCell.textContent = STATUS_NAMES.new;
    button.textContent = 'Подтвердить';
    button.setAttribute('title', 'Отметить подтверждённой');
  }
}

function onDeleteClick(event) {
  const row = event.target.closest('tr');
  if (confirm('Удалить эту бронь?')) {
    row.remove();
    updateCounter();
  }
}


async function loadBookings() {
  const status = statusFilter.value;
  const q = searchInput.value.trim();
  const url = `/api/bookings?status=${encodeURIComponent(status)}&q=${encodeURIComponent(q)}`;

  const response = await fetch(url);
  if (!response.ok) {
    throw new Error('Ошибка сервера: ' + response.status);
  }
  return await response.json();
}

async function refreshList() {
  showInfo('Загрузка…');
  refreshButton.disabled = true;

  try {
    const bookings = await loadBookings();
    bookingBody.innerHTML = '';

    if (bookings.length === 0) {
      showInfo('Брони не найдены. Измените фильтр или текст поиска.');
    } else {
      bookings.forEach((booking) => {
        bookingBody.appendChild(createBookingRow(booking));
      });
      showInfo(`Загружено броней: ${bookings.length}`);
    }
    updateCounter();
  } catch (error) {
    showInfo('Нет связи с сервером. Проверьте подключение и повторите попытку.', true);
    bookingBody.innerHTML = '';
    updateCounter();
  } finally {
    refreshButton.disabled = false;
  }
}

// Авторизация (упрощённая) 

function onLoginSubmit(event) {
  event.preventDefault();
  errorEmail.textContent = '';
  errorPassword.textContent = '';
  errorLogin.textContent = '';

  const email = loginEmail.value.trim();
  const password = loginPassword.value.trim();

  if (!email || !email.includes('@')) {
    errorEmail.textContent = 'Введите корректный email';
    return;
  }
  if (password.length < 3) {
    errorPassword.textContent = 'Пароль слишком короткий';
    return;
  }

  loginScreen.hidden = true;
  mainScreen.hidden = false;
  userName.textContent = email;

  refreshList();
}

function onLogout() {
  mainScreen.hidden = true;
  loginScreen.hidden = false;
  loginForm.reset();
}

loginForm.addEventListener('submit', onLoginSubmit);
logoutButton.addEventListener('click', onLogout);

bookingForm.addEventListener('submit', onFormSubmit);
refreshButton.addEventListener('click', refreshList);
statusFilter.addEventListener('change', refreshList);
searchInput.addEventListener('input', refreshList);