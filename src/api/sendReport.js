import axios from 'axios';
import { API_URL } from './constants';

const sendReport = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('reason', 'dislike');

  const response = await axios(API_URL + '/sendReport', {
    method: 'POST',
    data: data,
  });

  if ('umami' in window) {
    window.umami.track('send-report', {'item': item});
  }

  return response.data.result;
}

export default sendReport;