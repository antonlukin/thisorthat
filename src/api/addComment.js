import axios from 'axios';
import { API_URL } from './constants';

const addComment = async (token, item, message) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('message', message);

  const response = await axios(API_URL + '/addComment', {
    method: 'POST',
    data: data,
  });

  if ('umami' in window) {
    window.umami.track('add-comment', {'item': item});
  }

  return response.data.result;
}

export default addComment;