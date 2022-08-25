import axios from 'axios';

const register = async (token) => {
  const data = new FormData();
  data.append('client', 'test');
  data.append('uniqid', '0');

  const response = await axios.get('/register', {
    data: data,
  });

  return response.data.result;
}

export default register;