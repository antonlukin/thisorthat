export default function logout() {
  window.localStorage.removeItem('token');
  window.location.reload();
}