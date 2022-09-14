import axios from 'axios';
import { nanoid } from 'nanoid'

const register = async () => {
  const data = new FormData();
  data.append('client', 'web-react');
  data.append('uniqid', nanoid());

  const response = await axios.post('https://api.thisorthat.ru/register', data);

  return response.data.result?.token;
}

export default register;