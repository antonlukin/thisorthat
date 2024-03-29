import axios from 'axios';
import { API_URL } from './constants';

const getAudit = async (token) => {
  const data = new FormData();
  data.append('token', token);
  data.append('status', 'new');

  const response = await axios(API_URL + '/getAudit', {
    method: 'POST',
    data: data,
  });

  if ('umami' in window) {
    window.umami.track('get-audit');
  }

  return response.data.result?.items;
}

export default getAudit;