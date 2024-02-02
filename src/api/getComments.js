import axios from 'axios';
import { API_URL } from './constants';

const getComments = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('limit', 100);

  const response = await axios(API_URL + '/getComments', {
    method: 'POST',
    data: data,
  });

  if ('umami' in window) {
    window.umami.track('get-comments', {'item': item});
  }

  return response.data.result?.comments;
}

export default getComments;