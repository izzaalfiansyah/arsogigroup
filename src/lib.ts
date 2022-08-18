import axios from "axios";

export const http = axios.create({
  baseURL: (window as any).apiURL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': '1234567890',
  },
});

export const readFile = (file, callback) => {
  var reader = new FileReader();
  reader.readAsDataURL(file);

  reader.onload = () => {
    callback(reader.result);
  }
}

export function numeric(el: HTMLInputElement) {
  el.addEventListener('keyup', (e) => {
    el.value = el.value.replace(/[^,\d]/gi, '');
  });
}

export const notif = (message) => {
  const div = document.createElement('div');
  div.innerHTML = `
  <div class="notif animate-left">
    <i class="fa fa-info-circle"></i>
    <div class="flex-1">${message}</div>
  </div>
  `;
  
  document.documentElement.appendChild(div);

  setTimeout(() => {
    div.remove();
  }, 3000);
}

export const auth = {
  id: localStorage.getItem('id'),
  jabatan: localStorage.getItem('jabatan'),
};

export const en = {
  days:        ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
  daysShort:   ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
  daysMin:     ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
  months:      ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
  monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  meridiem:    ['am', 'pm'],
  suffix:      ['st', 'nd', 'rd', 'th'],
  todayBtn:    'Today',
  clearBtn:    'Clear',
  timeView:    'Show time view',
  backToDate:  'Back to calendar view'
}