import axios from 'axios';
import { API_URL } from './constants';

const cancelReport = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);

  const response = await axios(API_URL + '/cancelReport', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default cancelReport;