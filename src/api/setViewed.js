import axios from 'axios';
import { API_URL } from './constants';

const setViewed = async (token, item, result) => {
  const data = new FormData();
  data.append('token', token);
  data.append('views[' + item + ']', result);

  const response = await axios.post(API_URL + '/setViewed', data);

  return response.data.result;
}

export default setViewed;
