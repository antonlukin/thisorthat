import axios from 'axios';
import { nanoid } from 'nanoid'
import { API_URL } from './constants';

const register = async () => {
  const data = new FormData();
  data.append('client', 'web-react');
  data.append('uniqid', nanoid());

  const response = await axios.post(API_URL + '/register', data);

  return response.data.result?.token;
}

export default register;