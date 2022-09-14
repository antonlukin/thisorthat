import axios from 'axios';
import { API_URL } from './constants';

const setAudit = async (token, item, result) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('vote', result);

  const response = await axios.post(API_URL + '/setAudit', data);

  return response.data.result;
}

export default setAudit;