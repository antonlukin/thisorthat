import axios from 'axios';
import { API_URL } from './constants';

const addFavorite = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);

  const response = await axios(API_URL + '/addFavorite', {
    method: 'POST',
    data: data,
  });

  if ('umami' in window) {
    window.umami.track('add-favorite', {'item': item});
  }

  return response.data.result;
}

export default addFavorite;