import axios from 'axios';

const register = async () => {
  const data = new FormData();
  data.append('client', 'test');
  data.append('uniqid', '0');

  const response = await axios.post('/register', data);

  return response.data.result?.token;
}

export default register;